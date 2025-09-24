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
        return $this->pagination ? $query->paginate($this->perPage)->appends(request()->query()) : $query->get();
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
        })
            ->select('id', 'name', 'stage', 'code')
            ->get();
    }

    public function store($input)
    {
        DB::beginTransaction();
        $student = $this->model->create($input);
        DB::commit();

        return $student;
    }

    public function update($input, $id)
    {
        $student = $this->findById($id);
        $student->update([
            'name' => $input->name,
            'stage' => $input->stage ?? $student->stage,
            'phone' => $input->phone ?? $student->phone,
            'parent_phone' => $input->parent_phone ?? $student->parent_phone,
            'parent_phone_2' => $input->parent_phone_2 ?? $student->parent_phone_2,
            'note' => $input->note ?? null,
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

    public function profilePic($image, $id)
    {
        $model = $this->findById($id);

        return $this->updateProfilePic($model, $image, 'students_images');
    }

    public function dropdown()
    {
        return $this->model->get(['id', 'name', 'stage', 'code', 'phone']);
    }

    public function parent($input)
    {
        return $this->model->where('parent_phone', $input['phone'])->where('code', $input['code'])->first();
    }

    public function createSpecial($input)
    {
        $student = $this->findById($input->student_id);
        DB::beginTransaction();
        foreach ($input['cases'] as $case) {
            $student->specialCases()->attach($case['professor_id'], [
                'professor_price' => $case['professor_price'] ?? null,
                'center_price' => $case['center_price'] ?? null,
            ]);
        }
        DB::commit();

    }

    public function updateSpecialCase($input)
    {
        DB::table('student_special_cases')
            ->where('id', $input['case_id'])
            ->update([
                $input['field'] => $input['value'] ?? 0,
                'updated_at' => now(),
            ]);

        return back()->with('success', __('trans.updated_successfully'));
    }
}
