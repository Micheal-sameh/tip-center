<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    abstract protected function model(): string;

    public function updateProfilePic($model, $image, string $collection)
    {
        $model->clearMediaCollection($collection);
        $model->addMedia($image)->toMediaCollection($collection);

        return $model;
    }
}
