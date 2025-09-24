<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SettingRepository extends BaseRepository
{
    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    protected function model(): string
    {
        return Setting::class;
    }

    public bool $pagination = true;

    public int $perPage = 10;

    protected function execute(Builder $query): Collection|LengthAwarePaginator
    {
        return $this->pagination ? $query->paginate($this->perPage)->appends(request()->query()) : $query->get();
    }

    public function index()
    {
        $query = $this->model->query();

        return $this->execute($query);
    }

    public function update($settings, $files)
    {
        foreach ($settings as $key => $setting) {
            $mainSetting = $this->findById($key);

            if ($mainSetting->type === 'file' && isset($files[$key]['value'])) {
                $newFile = $files[$key]['value'];

                // Delete existing media (if any)
                $mainSetting->clearMediaCollection('app_logo');

                // Add the new file
                $mainSetting->addMedia($newFile)
                    ->toMediaCollection('app_logo');
            } else {
                // Update non-file setting
                $mainSetting->update([
                    'value' => $setting['value'] ?? null,
                ]);
            }
        }
    }
}
