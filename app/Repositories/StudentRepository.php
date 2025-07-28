<?php

namespace App\Repositories;

use App\Enums\UserStatus;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StudentRepository extends BaseRepository
{
    public function __construct(Student $model)
    {
        $this->model = $model;
    }

    protected function model(): string
    {
        return Student::class;
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
            ->when(isset($input['stage']), fn ($q) => $q->where('stage', $input['stage']))
            ->when(isset($input['from']), fn ($q) => $q->whereDate('created_at', '>=', $input['from']))
            ->when(isset($input['to']), fn ($q) => $q->whereDate('created_at', '<=', $input['to']))
            ->when(isset($input['search']), function ($query) use ($input) {
                $query->where(function ($q) use ($input) {
                    $search = $input['search'];
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('parent_phone', 'like', "%{$search}%")
                        ->orWhere('parent_phone_2', 'like', "%{$search}%");
                });
            });

        $sortMap = [
            'name_asc' => ['name', 'asc'],
            'name_desc' => ['name', 'desc'],
            'date_asc' => ['created_at', 'asc'],
            'date_desc' => ['created_at', 'desc'],
        ];

        [$column, $direction] = $sortMap[$input['sort_by'] ?? ''] ?? ['code', 'desc'];

        $query->orderBy($column, $direction);

        return $this->execute($query);
    }

    public function show($id)
    {
        return $this->findById($id);
    }

    public function search($search)
    {
        return $this->model->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('parent_phone', 'like', "%{$search}%")
                ->orWhere('parent_phone_2', 'like', "%{$search}%");
        })->get();
    }

    public function store($input)
    {
        DB::beginTransaction();
        $student = $this->model->create([
            'name' => $input->name,
            'stage' => $input->stage,
            'phone' => $input->phone,
            'parent_phone' => $input->parent_phone,
            'parent_phone_2' => $input->parent_phone_2,
            'birth_date' => $input->birth_date,
            'note' => $input->note,
        ]);
        DB::commit();

        return $student;
    }

    public function update($input, $id)
    {
        $student = $this->findById($id);
        $student->update([
            'stage' => $input->stage ?? $student->stage,
            'phone' => $input->phone ?? $student->phone,
            'parent_phone' => $input->parent_phone ?? $student->parent_phone,
            'parent_phone_2' => $input->parent_phone_2 ?? $student->parent_phone_2,
            'birth_date' => $input->birth_date ?? $student->birth_date,
            'note' => $input->note ?? $student->note,
        ]);

        return $student;
    }

    public function delete($id)
    {
        $student = $this->findById($id);
        $student->delete();
    }

    public function changeStatus($id)
    {
        $student = $this->findById($id);
        $student->update([
            'status' => $student->status == UserStatus::ACTIVE ? UserStatus::INACTIVE : UserStatus::ACTIVE,
        ]);

        return $student;
    }
}
