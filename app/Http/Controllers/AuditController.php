<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from_date') ?: today();
        $to = $request->input('to_date') ?: today();
        $query = Audit::with('user');
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

        $audits = $query->latest()->paginate(20)->appends($request->query());

        return view('audits.index', compact('audits'));
    }

    public function show($id)
    {
        $audit = Audit::with('user')->findOrFail($id);

        return view('audits.show', compact('audit'));
    }
}
