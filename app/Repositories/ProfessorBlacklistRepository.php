<?php

namespace App\Repositories;

use App\Models\ProfessorBlacklist;

class ProfessorBlacklistRepository extends BaseRepository
{
    public function __construct(ProfessorBlacklist $model)
    {
        $this->model = $model;
    }

    protected function model(): string
    {
        return ProfessorBlacklist::class;
    }

    public function index($input)
    {
        $query = $this->model->with(['professor', 'student']);

        if (! empty($input['professor_id'])) {
            $query->where('professor_id', $input['professor_id']);
        }

        if (! empty($input['professor_name'])) {
            $query->whereHas('professor', function ($q) use ($input) {
                $q->where('name', 'like', '%'.$input['professor_name'].'%');
            });
        }

        if (! empty($input['student_id'])) {
            $query->where('student_id', $input['student_id']);
        }

        if (! empty($input['student_name'])) {
            $query->whereHas('student', function ($q) use ($input) {
                $q->where('name', 'like', '%'.$input['student_name'].'%');
            });
        }

        return $query->paginate(15);
    }

    public function create(array $data): ProfessorBlacklist
    {
        return $this->model->create($data);
    }

    public function delete($id): ?bool
    {
        return $this->findById($id)->delete();
    }
}
