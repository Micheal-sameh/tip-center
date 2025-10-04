<?php

namespace App\Repositories;

use App\Enums\ChargeType;
use App\Models\Charge;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
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
        return $this->pagination ? $query->paginate($this->perPage)->appends(request()->query()) : $query->get();
    }

    public function index($input)
    {
        $user = auth()->user();
        $query = $this->chargesFilter($input)
            ->when($user->can('charges_salary'), fn ($q) => $q->whereNot('type', ChargeType::GAP))
            ->when(! $user->can('charges_salary'), fn ($q) => $q->whereNotIn('type', [ChargeType::GAP, ChargeType::SALARY, ChargeType::RENT]))
            ->whereNotIn('type', [ChargeType::STUDENT_SETTLE_CENTER, ChargeType::STUDENT_SETTLE_PRINT, ChargeType::STUDENT_PRINT])
            ->latest();

        return $this->execute($query);
    }

    public function gap($input)
    {
        $query = $this->chargesFilter($input)
            ->where('type', ChargeType::GAP)
            ->latest();

        return $this->execute($query);
    }

    public function studentPrint($input)
    {
        $query = $this->chargesFilter($input)
            ->where('type', ChargeType::STUDENT_PRINT)
            ->latest();

        return $this->execute($query);
    }

    protected function chargesFilter($input)
    {
        if (! isset($input['date_from']) && ! isset($input['date_to'])) {
            $input['date_from'] = today();
            $input['date_to'] = today();
        }

        return $this->model->query()
            ->when(isset($input['name']), fn ($q) => $q->where('title', 'like', '%'.$input['name'].'%'))
            ->when(isset($input['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $input['date_from']))
            ->when(isset($input['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $input['date_to']))
            ->when(isset($input['type']), fn ($q) => $q->where('type', $input['type']));
    }

    public function store($input)
    {
        $createdAt = isset($input['created_at']) ? $input['created_at'] : now();
        DB::beginTransaction();
        $charge = $this->model->create([
            'title' => $input['title'],
            'amount' => $input['amount'],
            'type' => $input['type'],
            'reverse' => $input['reverse'] ?? 0,
            'created_by' => Auth::id(),
            'created_at' => $createdAt,
        ]);
        DB::commit();

        return $charge;
    }

    public function reverseGap()
    {
        $charges = $this->model->where('reverse', 1)->get();
        DB::beginTransaction();
        foreach ($charges as $charge) {
            $this->store([
                'title' => 'reversed '.$charge->title,
                'amount' => -$charge->amount,
                'type' => $charge->type,
                'created_by' => $charge->created_by,
                'created_at' => $charge->created_at,
                'reverse' => 0,
            ]);
            $charge->update([
                'reverse' => 0,
            ]);
        }
        DB::commit();
    }

    public function income($input)
    {
        return $this->incomeQuery($input)
            ->whereNotIn('type', [ChargeType::GAP, ChargeType::STUDENT_SETTLE_CENTER, ChargeType::STUDENT_SETTLE_PRINT, ChargeType::STUDENT_PRINT])
            ->sum('amount');
    }

    public function incomeGap($input)
    {
        return $this->incomeQuery($input)
            ->where('type', ChargeType::GAP)
            ->sum('amount');
    }

    public function incomeSettle($input)
    {
        return $this->incomeQuery($input)
            ->whereIn('type', [ChargeType::STUDENT_SETTLE_CENTER, ChargeType::STUDENT_SETTLE_PRINT])
            ->sum('amount');
    }

    public function incomeStudentPrint($input)
    {
        return $this->incomeQuery($input)
            ->where('type', ChargeType::STUDENT_PRINT)
            ->sum('amount');
    }

    public function specialRoomsIncome($input)
    {
        return $this->incomeQuery($input)
            ->where('type', ChargeType::STUDENT_SETTLE_CENTER_ROOM)
            ->sum('amount');
    }

    public function specialRoomsCharge($input)
    {
        return $this->incomeQuery($input)
            ->where('type', ChargeType::ROOM_10_11)
            ->sum('amount');
    }

    public function delete($id)
    {
        $charge = $this->findById($id);
        $type = $charge->type;
        $charge->delete();

        return $type;
    }

    private function incomeQuery($input)
    {
        if (! isset($input['date_from']) && ! isset($input['date_to'])) {
            $input['date_from'] = today();
            $input['date_to'] = today();
        }

        return $this->model
            ->when(isset($input['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $input['date_from']))
            ->when(isset($input['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $input['date_to']));
    }

    public function chargesReport($input)
    {
        $user = auth()->user();
        $query = $this->chargesFilter($input)
            ->when($user->can('charges_salary'), fn ($q) => $q->whereNot('type', ChargeType::GAP))
            ->when(! $user->can('charges_salary'), fn ($q) => $q->whereNotIn('type', [ChargeType::GAP, ChargeType::SALARY, ChargeType::RENT]))
            ->whereNotIn('type', [ChargeType::STUDENT_SETTLE_CENTER, ChargeType::STUDENT_SETTLE_PRINT, ChargeType::STUDENT_PRINT, ChargeType::STUDENT_SETTLE_CENTER_ROOM, ChargeType::ROOM_10_11])
            ->orderByDesc('id');

        $charges = $query->get();
        $total = $query->sum('amount');

        return compact('charges', 'total');
    }
}
