<?php

namespace App\Repositories;

use App\Enums\ReportType;
use App\Enums\SessionStatus;
use App\Models\SessionStudent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SessionStudentRepository extends BaseRepository
{
    public function __construct(SessionStudent $model)
    {
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
        return $this->pagination ? $query->paginate($this->perPage) : $query->get();
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
        if ($input->total_paid >= $session->center_price) {
            $input->center_price = $session->center_price;
            $input->total_paid -= $input->center_price;
        }
        if ($input->total_paid >= $session->printables) {
            $input->printables = $session->printables;
            $input->total_paid -= $input->printables;
        }
        if ($input->total_paid >= $session->professor_price) {
            $input->professor_price = $session->professor_price;
        } else {
            $input->professor_price = $input->total_paid;
            $reminder = $session->center_price + $session->professor_price + $session->printables - $input->total_paid;
        }
        DB::beginTransaction();
        $attendence = $this->model->create([
            'session_id' => $input->session_id,
            'student_id' => $input->student_id,
            'professor_price' => $input->professor_price,
            'center_price' => $input->center_price,
            'printables' => $input->printables,
            'to_pay' => $reminder ?? 0,
        ]);
        DB::commit();

        return $attendence;
    }

    public function advancedPay($input)
    {
        DB::beginTransaction();
        $attendence = $this->model->create([
            'session_id' => $input->session_id,
            'student_id' => $input->student_id,
            'professor_price' => $input->professor_price,
            'center_price' => $input->center_price,
            'printables' => $input->printables,
        ]);
        DB::commit();

        return $attendence;
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

    public function session($session_id)
    {
        return $this->model->where('session_id', $session_id)->get();
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
                ReportType::PROFESSOR => $query->select('session_id', 'created_at', 'professor_price', 'student_id'),
                ReportType::CENTER => $query->select('session_id', 'created_at', 'center_price', 'printables', 'student_id'),
                default => $query,
            };
        }

        return $query->get();
    }
}
