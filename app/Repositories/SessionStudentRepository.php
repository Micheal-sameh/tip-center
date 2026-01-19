<?php

namespace App\Repositories;

use App\Enums\AttendenceType;
use App\Enums\ChargeType;
use App\Enums\ReportType;
use App\Enums\SessionStatus;
use App\Models\ProfessorStageBalance;
use App\Models\Session;
use App\Models\SessionStudent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionStudentRepository extends BaseRepository
{
    public function __construct(
        SessionStudent $model,
        protected ChargeRepository $chargeRepository,
        protected StudentSettlementRepository $studentSettlementRepository,
    ) {
        $this->model = $model;
    }

    protected function model(): string
    {
        return SessionStudent::class;
    }

    public bool $pagination = true;

    public int $perPage = 10;

    protected function execute(Builder $query): Collection|LengthAwarePaginator
    {
        return $this->pagination ? $query->paginate($this->perPage)->appends(request()->query()) : $query->get();
    }

    public function index($input)
    {
        $query = $this->model->query()
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
            });

        return $this->execute($query);
    }

    public function show($id)
    {
        return $this->findById($id);
    }

    public function simplePay($input, $session)
    {
        DB::beginTransaction();
        $attendence = $this->model->create([
            'session_id' => $input->session_id,
            'student_id' => $input->student_id,
            'professor_price' => $session->professor_price,
            'center_price' => $session->center_price,
            'printables' => $session->printables ?? 0,
            'materials' => $session->materials ?? 0,
            'is_attend' => AttendenceType::ATTEND,
            'created_by' => Auth::id(),
        ]);
        $required_price = $session->professor_price + $session->center_price + $session->printables + $session->materials;
        $reminder = $input->total_paid - $required_price;
        DB::commit();

        return $reminder;
    }

    public function advancedPay($input)
    {
        DB::beginTransaction();
        $attendence = $this->model->create([
            'session_id' => $input->session_id,
            'student_id' => $input->student_id,
            'professor_price' => $input->professor_price,
            'center_price' => $input->center_price,
            'printables' => $input->printables ?? 0,
            'materials' => $input->materials ?? 0,
            'to_pay' => $input->to_pay ?? 0,
            'to_pay_center' => $input->to_pay_center ?? 0,
            'to_pay_print' => $input->to_pay_print ?? 0,
            'to_pay_materials' => $input->to_pay_materials ?? 0,
            'is_attend' => AttendenceType::ATTEND,
            'created_by' => Auth::id(),
        ]);
        DB::commit();

        return 0;
    }

    public function update($input, $id)
    {
        $attendance = $this->findById($id);
        $attendance->update([
            'center_price' => $input->center_price ?? 0,
            'professor_price' => $input->professor_price ?? 0,
            'printables' => $input->printables ?? 0,
            'materials' => $input->materials ?? 0,
            'to_pay' => $input->to_pay ?? 0,
            'to_pay_center' => $input->to_pay_center ?? 0,
            'to_pay_print' => $input->to_pay_print ?? 0,
            'to_pay_materials' => $input->to_pay_materials ?? 0,
            'is_attend' => AttendenceType::ATTEND,
            'updated_by' => Auth::id(),
        ]);

        return $attendance;
    }

    public function updateToPay($input, $id)
    {
        $attendance = $this->findById($id);
        $attendance->update([
            'to_pay' => $input->to_pay ?? 0,
            'to_pay_center' => $input->to_pay_center ?? 0,
            'to_pay_print' => $input->to_pay_print ?? 0,
            'to_pay_materials' => $input->to_pay_materials ?? 0,
            'updated_by' => Auth::id(),
        ]);

        return $attendance;
    }

    public function pay($id)
    {
        $pay = $this->findById($id);
        info('before pay '.$pay);
        $sessionDate = $pay->session->created_at->startOfDay();
        $isPast = $sessionDate->lt(today());

        DB::beginTransaction();
        $professor_amount = $pay->to_pay;
        $materials = $pay->to_pay_materials;
        $center = $pay->to_pay_center;
        $printables = $pay->to_pay_print;
        if ($pay->to_pay || $pay->to_pay_materials) {
            $professor = $pay->session->professor;
            if ($isPast) {
                ProfessorStageBalance::updateOrCreate(
                    ['professor_id' => $professor->id, 'stage' => $pay->session->stage],
                    [
                        'balance' => DB::raw('balance + '.$pay->to_pay),
                        'materials_balance' => DB::raw('materials_balance + '.$pay->to_pay_materials),
                    ]
                );
            }
            $pay->update([
                'professor_price' => $pay->professor_price + $pay->to_pay,
                'materials' => $pay->materials + $pay->to_pay_materials,
                'to_pay' => 0,
                'to_pay_materials' => 0,
            ]);
        }
        if ($pay->to_pay_center > 0 || $pay->to_pay_print > 0) {
            $title = $pay->student->name.' session '.$pay->session->professor->name.' '.$pay->session->created_at->format('d-m');
            if ($isPast) {
                if ($pay->to_pay_center > 0) {
                    $this->chargeRepository->store([
                        'title' => $title,
                        'amount' => $pay->to_pay_center,
                        'type' => ($pay->session_id == 10 || $pay->session_id == 11) ? ChargeType::STUDENT_SETTLE_CENTER_ROOM : ChargeType::STUDENT_SETTLE_CENTER,
                        'reverse' => 1,
                    ]);
                }
                if ($pay->to_pay_print > 0) {
                    $this->chargeRepository->store([
                        'title' => $title,
                        'amount' => $pay->to_pay_print,
                        'type' => ChargeType::STUDENT_SETTLE_PRINT,
                        'reverse' => 1,
                    ]);
                }
            }

            $pay->update([
                'center_price' => $pay->center_price + $pay->to_pay_center,
                'printables' => $pay->printables + $pay->to_pay_print,
                'to_pay_center' => 0,
                'to_pay_print' => 0,
            ]);
        }
        info('after pay '.$pay);
        info('professor '.$professor);
        $totalPaid = $professor_amount + $materials + $center + $printables;
        if ($totalPaid > 0) {
            $this->studentSettlementRepository->store([
                'student_id' => $pay->student_id,
                'session_id' => $pay->session_id,
                'professor_id' => $pay->session->professor_id,
                'amount' => $totalPaid,
                'description' => 'Payment settlement for session',
                'session_student_ids' => [$pay->id],
                'settled_at' => now(),
                'center' => $center,
                'professor_amount' => $professor_amount,
                'materials' => $materials,
                'printables' => $printables,
            ]);
        }

        DB::commit();

        return $pay;
    }

    public function absentStudents($session_id, $studentsIds)
    {
        foreach ($studentsIds as $student_id) {
            $this->model->create([
                'session_id' => $session_id,
                'student_id' => $student_id,
                'professor_price' => 0,
                'center_price' => 0,
                'printables' => 0,
                'materials' => 0,
                'to_pay' => 0,
                'is_attend' => AttendenceType::ABSENT,
            ]);
        }
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
            'status' => $session->status == SessionStatus::ACTIVE ? SessionStatus::PENDING : SessionStatus::ACTIVE,
        ]);

        return $session;
    }

    public function session($input)
    {
        $session = $input['session'];

        $query = $this->model
            ->with([
                'student' => function ($q) use ($input, $session) {
                    if (isset($input['with_phones'])) {
                        $q->select('id', 'name', 'phone', 'parent_phone');
                    } else {
                        $q->select('id', 'name');
                    }

                    // eager-load toPay filtered by professor
                    $q->with(['toPay' => function ($sub) use ($session) {
                        $sub->whereHas('session', function ($sq) use ($session) {
                            $sq->where('professor_id', $session->professor_id);
                        });
                    }]);
                },
            ])
            ->where('session_id', $input['session_id']);
        if (isset($input['type'])) {
            $query = match ((int) $input['type']) {
                ReportType::PROFESSOR => $query->select('id', 'created_at', 'professor_price', 'student_id', 'to_pay', 'materials', 'is_attend'),
                ReportType::CENTER => $query->select('id', 'created_at', 'center_price', 'printables', 'student_id', 'to_pay_center', 'to_pay_print', 'is_attend'),
                default => $query,
            };
        }

        return $query->orderBy('is_attend', 'desc')->get();
    }

    public function student($input)
    {
        $query = $this->model->where('student_id', $input['student_id'])
            ->when(! is_null($input['professor_id']), function ($query) use ($input) {
                $query->whereHas('session', function ($query) use ($input) {
                    $query->where('professor_id', $input['professor_id']);
                });
            })->latest();
        if (isset($input['type'])) {
            match ((int) $input['type']) {
                ReportType::PROFESSOR => $query->select('session_id', 'created_at', 'professor_price', 'student_id', 'to_pay', 'materials'),
                ReportType::CENTER => $query->select('session_id', 'created_at', 'center_price', 'printables', 'student_id', 'to_pay'),
                default => $query,
            };
        }

        return $query->get();
    }

    public function settleDue($paid, $student_id)
    {
        $attendances = $this->model
            ->where('student_id', $student_id)
            ->where('to_pay', '>', 0)
            ->orderBy('created_at') // or 'id' to go oldest first
            ->get();

        foreach ($attendances as $attendance) {
            if ($paid <= 0) {
                break;
            }

            $session = Session::find($attendance->session_id);

            if ($session) {
                if ($attendance->center_price != $session->center_price) {
                    $attendance->center_price = $session->center_price;
                }
                if ($attendance->professor_price != $session->professor_price) {
                    $attendance->professor_price = $session->professor_price;
                }
            }

            $due = $attendance->to_pay;

            if ($paid >= $due) {
                $paid -= $due;
                $attendance->to_pay = 0;
            } else {
                $attendance->to_pay -= $paid;
                $paid = 0;
            }

            $attendance->save();
            $attendance['due'] = $due;
        }

        return $attendances;
    }

    public function parent($student)
    {
        return $this->model->where('student_id', $student->id)->get();
    }

    public function alreadyAttend($session_id, $student_id)
    {
        return SessionStudent::where('session_id', $session_id)
            ->where('student_id', $student_id)->first();
    }

    public function toPay($input)
    {
        $query = $this->model->query()
            ->with(['student:id,name,stage,phone,parent_phone', 'session.professor:id,name'])
            ->where(function ($q) {
                $q->where('to_pay', '>', 0)
                    ->orWhere('to_pay_center', '>', 0)
                    ->orWhere('to_pay_materials', '>', 0)
                    ->orWhere('to_pay_print', '>', 0);
            })
            ->when(isset($input['stage']), fn ($q) => $q->whereHas('student', fn ($sq) => $sq->where('stage', $input['stage'])))
            ->when(isset($input['name']), fn ($q) => $q->whereHas('student', fn ($sq) => $sq->where('name', 'like', '%'.$input['name'].'%')))
            ->when(isset($input['professor_id']), fn ($q) => $q->whereHas('session', fn ($sq) => $sq->where('professor_id', $input['professor_id'])))
            ->orderBy('created_at');

        return $this->execute($query);
    }
}
