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
        $user = auth()->user();
        $query = $this->chargesFilter($input)
            ->when($user->can('charges_salary'), fn ($q) => $q->whereNot('type', ChargeType::GAP))
            ->when(! $user->can('charges_salary'), fn ($q) => $q->whereNotIn('type', [ChargeType::GAP, ChargeType::SALARY, ChargeType::RENT]));

        return $this->execute($query);
    }

    public function gap($input)
    {
        $query = $this->chargesFilter($input)
            ->where('type', ChargeType::GAP);

        return $this->execute($query);
    }

    protected function chargesFilter($input)
    {
        if(!isset($input['date_from']) && !isset($input['date_to'])){
            $input['date_from'] = today();
            $input['date_to'] = today();
        }

        return $this->model->query()
            ->when(isset($input['name']), fn ($q) => $q->where('title', 'like', '%'.$input['name'].'%'))
            ->when(isset($input['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $input['date_from']))
            ->when(isset($input['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $input['date_to']));
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
            ->when(isset($input['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $input['date_to']))
            ->when(! isset($input['date_from']) && ! isset($input['date_to']), fn ($q) => $q->whereDate('created_at', today()));
    }
}
