<?php

namespace App\Repositories;

use App\Enums\UserStatus;
use App\Models\Professor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProfessorRepository extends BaseRepository
{
    public function __construct(Professor $model)
    {
        $this->model = $model;
    }

    protected function model(): string
    {
        return Professor::class;
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
            ->when($input->has('name'), function ($q) use ($input) {
                $q->where(function ($subQuery) use ($input) {
                    $subQuery->where('name', 'like', '%'.$input->name.'%')
                        ->orWhere('phone', 'like', '%'.$input->name.'%');
                });
            })->when($input->has('stages'), function ($query) use ($input) {
                $query->whereHas('stages', fn ($q) => $q->whereIn('stage', $input->stages));
            });

        return $this->execute($query);
    }

    public function show($id)
    {
        return $this->findById($id);
    }

    public function store($input)
    {
        DB::beginTransaction();
        $professor = $this->model->create([
            'name' => $input->name,
            'optional_phone' => $input->optional_phone,
            'phone' => $input->phone,
            'school' => $input->school,
            'subject' => $input->subject,
            'status' => UserStatus::ACTIVE,
            'birth_date' => $input->birth_date,
        ]);

        $professor->professorStages()->sync($input->stage_schedules);
        DB::commit();

        return $professor;
    }

    public function update($input, $id)
    {
        $professor = $this->findById($id);
        $professor->update([
            'name' => $input->name ?? $professor->name,
            'optional_phone' => $input->optional_phone ?? $professor->optional_phone,
            'phone' => $input->phone ?? $professor->phone,
            'school' => $input->school ?? $professor->school,
            'subject' => $input->subject ?? $professor->subject,
        ]);
        $professor->professorStages()->sync($input->stage_schedules);

        return $professor;
    }

    public function delete($id)
    {
        $professor = $this->findById($id);
        $professor->delete();
    }

    public function dropdown($input)
    {
        return $this->model
            ->when(isset($input['stage']), function ($query) use ($input) {
                $query->whereHas('stages', fn ($q) => $q->where('stage', $input['stage']));
            })->select('id', 'name')->get();
    }

    public function changeStatus($id)
    {
        $professor = $this->findById($id);
        $professor->update([
            'status' => $professor->status == UserStatus::ACTIVE ? UserStatus::INACTIVE : UserStatus::ACTIVE,
        ]);

        return $professor;
    }

    public function profilePic($image, $id)
    {
        $model = $this->findById($id);

        return $this->updateProfilePic($model, $image, 'professors_images');
    }
}
