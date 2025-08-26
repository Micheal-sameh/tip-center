<?php

namespace App\Repositories;

use App\Enums\ChargeType;
use App\Models\Charge;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ChargeRepository extends BaseRepository
{
    public function __construct(Charge $model)
    {
        $this->model = $model;
    }

    protected function model(): string
    {
        return Charge::class;
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
            ->when(isset($input['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $input['date_from']))
            ->when(isset($input['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $input['date_to']));

        return $this->execute($query);
    }

    public function store($input)
    {
        DB::beginTransaction();
        $charge = $this->model->create([
            'title' => $input['title'],
            'amount' => $input['amount'],
            'type' => $input['type'],
        ]);
        DB::commit();

        return $charge;
    }

    public function income($input)
    {
        return $this->incomeQuery($input)
            ->where('type', '!=', ChargeType::GAP)
            ->sum('amount');
    }

    public function incomeGap($input)
    {
        return $this->incomeQuery($input)
            ->where('type', ChargeType::GAP)
            ->sum('amount');
    }

    public function delete($id)
    {
        $charge = $this->findById($id);
        $charge->delete();
    }

    private function incomeQuery($input)
    {
        return $this->model
            ->when(isset($input['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $input['date_from']))
            ->when(isset($input['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $input['date_to']));
    }
}
