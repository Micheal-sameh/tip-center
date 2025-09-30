<?php

namespace App\Repositories;

use App\Models\Audit;
use Illuminate\Pagination\LengthAwarePaginator;

class AuditRepository extends BaseRepository
{
    public function __construct(Audit $model)
    {
        $this->model = $model;
    }

    protected function model(): string
    {
        return Audit::class;
    }

    public function cleanOldAudits()
    {
        return $this->model->where('created_at', '<', now()->subDays(60))->delete();
    }

    public function index($request): LengthAwarePaginator
    {
        $from = $request->input('from_date') ?: today();
        $to = $request->input('to_date') ?: today();
        $timeFrom = $request->input('time_from');
        $timeTo = $request->input('time_to');
        $withData = $request->input('with_data');
        $query = $this->model->with('user');
        $query->where('table_name', '!=', 'charges');

        // Filter by table name
        if ($request->filled('table')) {
            $query->where('table_name', $request->table);
        }

        // Filter by user
        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        // Filter by date range
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Filter by time range
        if ($timeFrom) {
            $query->whereTime('created_at', '>=', $timeFrom);
        }
        if ($timeTo) {
            $query->whereTime('created_at', '<=', $timeTo);
        }

        if (! $withData) {
            $query->select('id', 'user_id', 'record_id', 'table_name', 'created_at');
        }

        return $query->orderByDesc('id')->paginate(20)->appends($request->query());
    }

    public function show($id)
    {
        return $this->model->with('user')->findOrFail($id);
    }
}
