<?php

namespace App\Repositories;

use App\Enums\ChargeType;
use App\Enums\ReportType;
use App\Enums\SessionStatus;
use App\Enums\SessionType;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SessionRepository extends BaseRepository
{
    public function __construct(
        Session $model,
        protected SessionStudentRepository $sessionStudentRepository,
    ) {
        $this->model = $model;
    }

    protected function model(): string
    {
        return Session::class;
    }

    public bool $pagination = true;

    public int $perPage = 10;

    protected function execute(Builder $query): Collection|LengthAwarePaginator
    {
        return $this->pagination ? $query->paginate($this->perPage) : $query->get();
    }

    public function index($input)
    {
        $this->checkActive();
        $query = $this->model->query()
            ->withCount('sessionStudents')
            ->when(isset($input['professor_id']), fn ($q) => $q->where('professor_id', $input['professor_id']))
            ->when(isset($input['stage']), fn ($q) => $q->where('stage', $input['stage']))
            ->when(isset($input['status']), fn ($q) => $q->where('status', $input['status']))
            ->when(isset($input['type']), fn ($q) => $q->where('type', $input['type']))
            ->when(isset($input['search']), function ($query) use ($input) {
                $query->whereHas('professor', function ($q) use ($input) {
                    $q->where(function ($q) use ($input) {
                        $q->where('name', 'like', '%'.$input['search'].'%')
                            ->orWhere('phone', 'like', '%'.$input['search'].'%');
                    });
                });
            })
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('type', SessionType::OFFLINE)
                        ->whereDate('created_at', today());
                })
                    ->orWhere(function ($q2) {
                        $q2->where('type', SessionType::ONLINE)
                            ->where('status', SessionStatus::ACTIVE);
                    });
            })
            ->orderBy('type')
            ->orderBy('status');

        return $this->execute($query);
    }

    public function onlineSessions()
    {
        $query = $this->model->query()
            ->where('status', SessionStatus::ACTIVE)
            ->where('type', SessionType::ONLINE);

        return $this->execute($query);

    }

    public function show($id)
    {
        return $this->findById($id);
    }

    public function report($input)
    {
        $query = $this->model->where('id', $input['session_id']);
        if (isset($input['type'])) {
            match ((int) $input['type']) {
                ReportType::PROFESSOR => $query->select('created_at', 'id', 'professor_id', 'stage', 'professor_price', 'materials'),
                ReportType::CENTER => $query->select('created_at', 'id', 'professor_id', 'stage', 'printables', 'center_price'),
                default => $query,
            };
        }

        return $query->first();
    }

    public function store($input)
    {
        DB::beginTransaction();
        $session = $this->model->create([
            'professor_id' => $input->professor_id,
            'stage' => $input->stage,
            'professor_price' => $input->professor_price,
            'center_price' => $input->center_price,
            'status' => $input->type == SessionType::ONLINE ? SessionStatus::ACTIVE : SessionStatus::PENDING,
            'printables' => $input->printables,
            'materials' => $input->materials,
            'start_at' => $input->start_at,
            'end_at' => $input->end_at,
            'room' => $input->room,
            'type' => $input->type,
        ]);
        DB::commit();

        return $session;
    }

    public function update($input, $id)
    {
        $session = $this->findById($id);
        $session->update([
            'stage' => $input->stage ?? $session->stage,
            'professor_price' => $input->professor_price ?? $session->professor_price,
            'center_price' => $input->center_price ?? $session->center_price,
            'printables' => $input->printables ?? $session->printables,
            'materials' => $input->materials ?? $session->materials,
            'start_at' => $input->start_at ?? $session->start_at,
            'end_at' => $input->end_at ?? $session->end_at,
            'room' => $input->room ?? $session->room,
            'note' => $input->note ?? $session->note,
        ]);

        return $session;
    }

    public function delete($id)
    {
        $session = $this->findById($id);
        $session->delete();
    }

    public function close($input, $id)
    {
        DB::beginTransaction();
        $session = $this->findById($id);
        $session->update([
            'status' => SessionStatus::FINISHED,
        ]);
        $session->sessionExtra()->create([
            'copies' => $input['copies'] ?? 0,
            'markers' => $input['markers'] ?? 0,
            'cafeterea' => $input['cafeterea'] ?? 0,
            'other' => $input['other'] ?? 0,
            'notes' => $input['notes'],
        ]);
        $this->absence($session);
        DB::commit();

        return $session;
    }

    public function absence($session)
    {
        $absentStudents = $this->absentStudents($session);
        $this->sessionStudentRepository->absentStudents($session->id, $absentStudents);
    }

    public function absentStudents($currentSession)
    {
        $lastSession = $this->model->where('id', '<', $currentSession->id)
            ->where('professor_id', $currentSession->professor_id)
            ->where('stage', $currentSession->stage)
            ->where('type', $currentSession->type)
            ->orderBy('id', 'desc')
            ->first();

        $lastSessionStudents = $lastSession?->sessionStudents->pluck('student_id') ?? collect();
        $currentStudents = $currentSession->sessionStudents->pluck('student_id');

        return $lastSessionStudents->diff($currentStudents);
    }

    public function status($status, $id)
    {
        $session = $this->findById($id);
        $session->update([
            'status' => $status,
        ]);

        return $session;
    }

    public function mySessions(array $input)
    {
        $professorIds = $this->model
            ->where('stage', $input['stage'])
            // ->whereHas('sessionStudents', fn ($q) => $q->where('student_id', $input['student_id'])
            // )
            ->where('status', SessionStatus::ACTIVE)
            ->latest()
            ->get()
            ->unique('professor_id')
            ->pluck('professor_id');

        return $this->model
            ->where('stage', $input['stage'])
            ->where('status', SessionStatus::ACTIVE)
            ->whereIn('professor_id', $professorIds)
            // ->whereHas('sessionStudents', fn($q) => $q->where('student_id', '!=', $input['student_id']))
            ->get();
    }

    public function lastSession($session, $student)
    {

        return $this->model->whereHas('sessionStudents',
            fn ($q) => $q->where('student_id', $student->id)
        )->where('stage', $session->stage)->where('professor_id', $session->professor_id)
            ->where('status', SessionStatus::PENDING)->latest()->first();
    }

    public function reports($input)
    {
        if(!isset($input['from']) && !isset($input['to'])){
            $input['from'] = today();
            $input['to'] = today();
        }
        $query = $this->model->when(isset($input['stage']), fn ($q) => $q->where('stage', $input['stage']))
            ->when(isset($input['professor']), function ($query) use ($input) {
                $query->whereHas('professor', fn ($q) => $q->where('name', 'like', '%'.$input['professor'].'%'));
            })
            ->when(isset($input['from']), fn ($q) => $q->whereDate('created_at', '>=', $input['from']))
            ->when(isset($input['to']), fn ($q) => $q->whereDate('created_at', '<=', $input['to']))
            ->latest();

        return $this->execute($query);
    }

    public function checkActive(): void
    {
        // Sessions that started and not yet ended → make ACTIVE
        $this->model->where('status', SessionStatus::PENDING)
            ->where('type', SessionType::OFFLINE)
            ->whereDate('created_at', Carbon::today())
            ->where('start_at', '<=', now())
            ->where('end_at', '>', now())
            ->update(['status' => SessionStatus::ACTIVE]);

        // Sessions already ended → make PENDING
        $this->model->whereIn('status', [SessionStatus::ACTIVE, SessionStatus::PENDING])
            ->where('type', SessionType::OFFLINE)
            ->whereDate('created_at', Carbon::today())
            ->where('end_at', '<=', now())
            ->update(['status' => SessionStatus::WARNING]);
    }

    public function checkYesterday(): void
    {
        $sessions = $this->model->whereIn('status', [
            SessionStatus::ACTIVE,
            SessionStatus::PENDING,
            SessionStatus::WARNING,
        ])
            ->where('type', SessionType::OFFLINE)
            ->whereDate('created_at', '<', Carbon::today())
            ->get();

        foreach ($sessions as $session) {
            $session->update(['status' => SessionStatus::FINISHED]);
            $this->absence($session);
        }
    }

    public function automaticCreateSessions($professors): void
    {
        $this->checkYesterday();

        foreach ($professors as $professor) {
            foreach ($professor->stages as $stage) {

                $lastSession = $stage->getLastForProfessorAndStage(
                    $stage->professor_id,
                    $stage->stage
                );

                if (! $lastSession) {
                    continue; // skip if no previous session
                }

                $this->model->create([
                    'professor_id' => $stage->professor_id,
                    'stage' => $stage->stage,
                    'start_at' => $stage->from,
                    'end_at' => $stage->to,
                    'type' => SessionType::OFFLINE,
                    'professor_price' => $lastSession->professor_price,
                    'center_price' => $lastSession->center_price,
                    'status' => SessionStatus::PENDING,
                    'room' => $lastSession->room,
                ]);
            }
        }
    }

    public function income($input)
    {
        return $this->model->query()
            ->when(isset($input['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $input['date_from']))
            ->when(isset($input['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $input['date_to']))
            ->when(! isset($input['date_from']) && ! isset($input['date_to']), fn ($q) => $q->whereDate('created_at', today()))
            ->with([
                'professor' => fn ($q) => $q->select('id', 'name'),
                'sessionExtra',
            ])
            ->withCount(['sessionStudents',
                'sessionStudents as attended_count' => function ($query) {
                    $query->where('is_attend', 1);
                },
            ])->when(true, function ($query) {
                $query->withSum([
                    'sessionStudents as total_center_price' => function ($q) {
                        $q->whereHas('session', function ($session) {
                            $session->whereNotIn('room', [10, 11]);
                        });
                    },
                ], 'center_price');
            })
            ->withCount(['sessionStudents as total_paid_students' => function ($q) {
                $q->whereColumn('center_price', 'sessions.center_price');
            }], 'center_price')
            ->withSum('sessionStudents as total_center_price', 'center_price')
            ->withSum('sessionStudents as total_materials', 'materials')
            ->withSum('sessionStudents as total_printables', 'printables')
            ->get();
    }

    public function monthlyIncome($month)
    {
        return DB::table('sessions as s')
            ->selectRaw('
                DATE(s.created_at) as day,

                -- exclude center price for rooms 10 and 11
                COALESCE(SUM(CASE WHEN s.room NOT IN (10, 11) THEN ss.center_price ELSE 0 END), 0) as center,

                COALESCE(SUM(ss.printables), 0) as print,
                COALESCE(SUM(e.markers), 0) as markers,
                COALESCE(SUM(e.copies), 0) as copies,

                COALESCE(MAX(c.charges_gap), 0) as charges_gap,
                COALESCE(MAX(c.charges_center), 0) as charges_center,
                COALESCE(MAX(c.charges_markers), 0) as charges_markers,
                COALESCE(MAX(c.charges_others), 0) as charges_others,
                COALESCE(MAX(c.charges_copies), 0) as charges_copies,

                -- income
                (
                    COALESCE(SUM(CASE WHEN s.room NOT IN (10, 11) THEN ss.center_price ELSE 0 END), 0) +
                    COALESCE(SUM(ss.printables), 0) +
                    COALESCE(SUM(e.copies), 0) +
                    COALESCE(MAX(c.charges_gap), 0)
                ) as income_total,

                -- charges total
                (
                    COALESCE(MAX(c.charges_center), 0) +
                    COALESCE(MAX(c.charges_copies), 0) +
                    COALESCE(MAX(c.charges_markers), 0) +
                    COALESCE(MAX(c.charges_others), 0)
                ) as charges_total,

                -- difference
                (
                    (
                        COALESCE(SUM(CASE WHEN s.room NOT IN (10, 11) THEN ss.center_price ELSE 0 END), 0) +
                        COALESCE(SUM(ss.printables), 0) +
                        COALESCE(SUM(e.copies), 0) +
                        COALESCE(MAX(c.charges_gap), 0)
                    ) -
                    (
                        COALESCE(MAX(c.charges_center), 0) +
                        COALESCE(MAX(c.charges_copies), 0) +
                        COALESCE(MAX(c.charges_markers), 0) +
                        COALESCE(MAX(c.charges_others), 0)
                    )
                ) as difference_total,

                -- net values
                (COALESCE(SUM(CASE WHEN s.room NOT IN (10, 11) THEN ss.center_price ELSE 0 END), 0) - COALESCE(MAX(c.charges_center), 0)) as net_center,
                ((COALESCE(SUM(e.copies), 0) + COALESCE(SUM(ss.printables), 0)) - COALESCE(MAX(c.charges_copies), 0)) as net_copies,
                (COALESCE(SUM(e.markers), 0) - COALESCE(MAX(c.charges_markers), 0)) as net_markers,
                (0 - COALESCE(MAX(c.charges_others), 0)) as net_others
            ')
            ->leftJoin('session_students as ss', 's.id', '=', 'ss.session_id')
            ->leftJoin('session_extras as e', 's.id', '=', 'e.session_id')
            ->leftJoin(DB::raw('(
        SELECT
            DATE(created_at) as charge_day,
            SUM(CASE WHEN type = '.(int) ChargeType::CENTER.' THEN amount ELSE 0 END) as charges_center,
            SUM(CASE WHEN type = '.(int) ChargeType::COPIES.' THEN amount ELSE 0 END) as charges_copies,
            SUM(CASE WHEN type = '.(int) ChargeType::MARKERS.' THEN amount ELSE 0 END) as charges_markers,
            SUM(CASE WHEN type = '.(int) ChargeType::OTHERS.' THEN amount ELSE 0 END) as charges_others,
            SUM(CASE WHEN type = '.(int) ChargeType::GAP.' THEN amount ELSE 0 END) as charges_gap
        FROM charges
        GROUP BY charge_day
    ) c'), DB::raw('DATE(s.created_at)'), '=', 'c.charge_day')
            ->whereMonth('s.created_at', carbon::parse($month)->month)
            ->whereYear('s.created_at', carbon::parse($month)->year)
            ->groupBy(DB::raw('DATE(s.created_at)'))
            ->orderBy('day')
            ->get();
    }

    public function specialRooms($input)
    {
        return $this->model->query()
            ->when(isset($input['date_from']), fn ($q) => $q->where('created_at', '>=', $input['date_from']))
            ->when(isset($input['date_to']), fn ($q) => $q->where('created_at', '<=', $input['date_to']))
            ->whereIn('room', [10, 11])
            ->with([
                'professor' => fn ($q) => $q->select('id', 'name'),
            ])
            ->withSum('sessionStudents as center', 'center_price')
            ->withCount(['sessionStudents',
                'sessionStudents as attended_count' => function ($query) {
                    $query->where('is_attend', 1);
                },
            ])
            ->withCount(['sessionStudents as total_paid_students' => function ($q) {
                $q->whereColumn('center_price', 'sessions.center_price');
            }], 'center_price')
            ->get();
    }
}
