<?php

namespace App\Repositories;

use App\Enums\ReportType;
use App\Enums\SessionStatus;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SessionRepository extends BaseRepository
{
    public function __construct(Session $model)
    {
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
            ->whereDate('created_at', today())
            ->when(isset($input['professor_id']), fn ($q) => $q->where('professor_id', $input['professor_id']))
            ->when(isset($input['stage']), fn ($q) => $q->where('stage', $input['stage']))
            ->when(isset($input['status']), fn ($q) => $q->where('status', $input['status']))
            ->when(isset($input['search']), function ($query) use ($input) {
                $query->whereHas('professor', function ($q) use ($input) {
                    $q->where(function ($q) use ($input) {
                        $q->where('name', 'like', '%'.$input['search'].'%')
                            ->orWhere('phone', 'like', '%'.$input['search'].'%');
                    });
                });
            })
            ->orderBy('status');

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
                ReportType::PROFESSOR => $query->select('created_at', 'id', 'professor_id', 'stage', 'professor_price'),
                ReportType::CENTER => $query->select('created_at', 'id', 'professor_id', 'stage', 'printables', 'center_price'),
                default => $query,
            };
        }

        return $query->first();
    }

    public function store($input)
    {
        $status = Carbon::parse($input->start_at) >= now() ? SessionStatus::ACTIVE : SessionStatus::PENDING;
        DB::beginTransaction();
        $session = $this->model->create([
            'professor_id' => $input->professor_id,
            'stage' => $input->stage,
            'professor_price' => $input->professor_price,
            'center_price' => $input->center_price,
            'status' => $status,
            'printables' => $input->printables,
            'start_at' => $input->start_at,
            'end_at' => $input->end_at,
        ]);
        DB::commit();

        return $session;
    }

    public function update($input, $id)
    {
        $session = $this->findById($id);
        $session->update([
            'stage' => $input->stage ?? $session->stage,
            'phone' => $input->phone ?? $session->phone,
            'parent_phone' => $input->parent_phone ?? $session->parent_phone,
            'parent_phone_2' => $input->parent_phone_2 ?? $session->parent_phone_2,
            'birth_date' => $input->birth_date ?? $session->birth_date,
            'note' => $input->note ?? $session->note,
        ]);

        return $session;
    }

    public function delete($id)
    {
        $session = $this->findById($id);
        $session->delete();
    }

    public function changeStatus($id)
    {
        $session = $this->findById($id);
        $session->update([
            'status' => $session->status == SessionStatus::ACTIVE ? SessionStatus::INACTIVE : SessionStatus::ACTIVE,
        ]);

        return $session;
    }

    public function mySessions(array $input)
    {
        $professorIds = $this->model
            ->where('stage', $input['stage'])
            ->whereHas('sessionStudents', fn ($q) => $q->where('student_id', $input['student_id'])
            )
            ->where('status', SessionStatus::INACTIVE)
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
        $this->checkActive();

        return $this->model->whereHas('sessionStudents',
            fn ($q) => $q->where('student_id', $student->id)
        )->where('stage', $session->stage)->where('professor_id', $session->professor_id)
            ->where('status', SessionStatus::INACTIVE)->latest()->first();
    }

    public function reports($input)
    {
        $query = $this->model->when(isset($input['stage']), fn ($q) => $q->where('stage', $input['stage']))
            ->when(isset($input['professor']), function ($query) use ($input) {
                $query->whereHas('professor', fn ($q) => $q->where('name', 'like', '%'.$input['professor'].'%'));
            })
            ->when(isset($input['from']), fn ($q) => $q->whereDate('created_at', '>=', $input['from']))
            ->when(isset($input['to']), fn ($q) => $q->whereDate('created_at', '<=', $input['to']))
            ->latest();

        return $this->execute($query);
    }

    public function checkActive()
    {
        $sessions = $this->model->where('status', SessionStatus::ACTIVE)
            ->whereDate('created_at', '<', Carbon::today())
            ->get();
        $sessions->each(function ($session) {
            $session->update(['status' => SessionStatus::INACTIVE]);
        });
    }
}
