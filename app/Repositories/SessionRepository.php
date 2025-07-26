<?php

namespace App\Repositories;

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
        $query = $this->model->query()
            ->when(isset($input['professor_id']), fn ($q) => $q->where('professor_id', $input['professor_id']))
            ->when(isset($input['stage']), fn ($q) => $q->where('stage', $input['stage']))
            ->when(isset($input['status']), fn ($q) => $q->where('status', $input['status']))
            ->when(isset($input['search']), function ($query) use ($input) {
                $query->whereHas('professor', function ($q) use ($input) {
                    $q->where(function ($q) use ($input) {
                        $q->where('name', 'like', '%'.$input['search'].'%')
                            ->orWhere('email', 'like', '%'.$input['search'].'%');
                    });
                });
            });

        return $this->execute($query);
    }

    public function show($id)
    {
        return $this->findById($id);
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
}
