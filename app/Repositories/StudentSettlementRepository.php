<?php

namespace App\Repositories;

use App\Enums\ReportType;
use App\Models\StudentSettlement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class StudentSettlementRepository extends BaseRepository
{
    public function __construct(StudentSettlement $model)
    {
        $this->model = $model;
    }

    protected function model(): string
    {
        return StudentSettlement::class;
    }

    public bool $pagination = true;

    public int $perPage = 10;

    protected function execute(Builder $query): Collection|LengthAwarePaginator
    {
        return $this->pagination ? $query->paginate($this->perPage)->appends(request()->query()) : $query->get();
    }

    public function index($input)
    {
        $query = $this->settlementsFilter($input)->latest();

        return $this->execute($query);
    }

    public function store($input)
    {
        return $this->model->create([
            'student_id' => $input['student_id'],
            'session_id' => $input['session_id'],
            'professor_id' => $input['professor_id'],
            'amount' => $input['amount'],
            'description' => $input['description'],
            'session_student_ids' => $input['session_student_ids'],
            'settled_at' => $input['settled_at'],
            'created_by' => Auth::id(),
            'center' => $input['center'] ?? 0,
            'professor_amount' => $input['professor_amount'] ?? 0,
            'materials' => $input['materials'] ?? 0,
            'printables' => $input['printables'] ?? 0,
        ]);
    }

    public function session(array $input)
    {
        $session = $input['session'];

        $startDateTime = $session->created_at->clone()->setTimeFrom($session->start_at);
        $endDateTime = $session->created_at->clone()->setTimeFrom($session->end_at);

        return $this->model
            ->where('professor_id', $session->professor_id)
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->when(isset($input['type']), function ($query) use ($input) {
                $type = (int) $input['type'];

                $query->where(function ($q) use ($type) {
                    if ($type === ReportType::PROFESSOR) {
                        $q->where('professor_amount', '>', 0)
                            ->orWhere('materials', '>', 0);
                    } elseif ($type === ReportType::CENTER) {
                        $q->where('center', '>', 0)
                            ->orWhere('printables', '>', 0);
                    }
                });
            })
            ->with([
                'student' => fn ($q) => $q->where('stage', $session->stage)
                    ->select('id', 'name', 'stage'),
                'professor:id,name',
            ])
            ->get();
    }

    public function settlementsFilter($input)
    {
        if (! isset($input['date_from']) && ! isset($input['date_to'])) {
            $input['date_from'] = today();
            $input['date_to'] = today();
        }

        return $this->model->query()
            ->when(isset($input['date_from']), fn ($q) => $q->whereDate('settled_at', '>=', $input['date_from']))
            ->when(isset($input['date_to']), fn ($q) => $q->whereDate('settled_at', '<=', $input['date_to']))
            ->when(isset($input['student_id']), fn ($q) => $q->where('student_id', $input['student_id']))
            ->when(isset($input['professor_id']), fn ($q) => $q->where('professor_id', $input['professor_id']))
            ->when(isset($input['session_id']), fn ($q) => $q->where('session_id', $input['session_id']))
            ->when(isset($input['name']), fn ($q) => $q->where(function ($sub) use ($input) {
                $sub->whereHas('student', fn ($s) => $s->where('name', 'like', '%'.$input['name'].'%'))
                    ->orWhereHas('professor', fn ($p) => $p->where('name', 'like', '%'.$input['name'].'%'));
            }));
    }

    public function incomeCenter($input)
    {
        return $this->model->query()
            ->when(isset($input['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $input['date_from']))
            ->when(isset($input['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $input['date_to']))
            ->when(! isset($input['date_from']) && ! isset($input['date_to']), fn ($q) => $q->whereDate('created_at', today()))
            ->whereHas('session', fn ($q) => $q->whereNotIn('room', [10, 11]))
            ->sum('center');
    }

    public function incomePrint($input)
    {
        return $this->model->query()
            ->when(isset($input['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $input['date_from']))
            ->when(isset($input['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $input['date_to']))
            ->when(! isset($input['date_from']) && ! isset($input['date_to']), fn ($q) => $q->whereDate('created_at', today()))
            ->whereHas('session', fn ($q) => $q->whereNotIn('room', [10, 11]))
            ->sum('printables');
    }

    public function specialRoomsIncome($input)
    {
        return $this->model->query()
            ->when(isset($input['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $input['date_from']))
            ->when(isset($input['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $input['date_to']))
            ->when(! isset($input['date_from']) && ! isset($input['date_to']), fn ($q) => $q->whereDate('created_at', today()))
            ->whereHas('session', fn ($q) => $q->whereIn('room', [10, 11]))
            ->sum('center');
    }
}
