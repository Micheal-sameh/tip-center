<?php

namespace App\Repositories;

use App\Models\StudentBlacklist;

class StudentBlacklistRepository extends BaseRepository
{
    public function __construct(StudentBlacklist $model)
    {
        $this->model = $model;
    }

    protected function model(): string
    {
        return StudentBlacklist::class;
    }

    public function index()
    {
        return $this->model->with('student')->paginate(15);
    }

    public function create(array $data): StudentBlacklist
    {
        return $this->model->create($data);
    }

    public function delete($id): ?bool
    {
        return $this->findById($id)->delete();
    }
}
