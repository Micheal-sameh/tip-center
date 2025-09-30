<?php

namespace App\Http\Controllers;

use App\Repositories\AuditRepository;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    protected AuditRepository $auditRepository;

    public function __construct(AuditRepository $auditRepository)
    {
        $this->auditRepository = $auditRepository;
    }

    public function index(Request $request)
    {
        $audits = $this->auditRepository->index($request);

        return view('audits.index', compact('audits'));
    }

    public function show($id)
    {
        $audit = $this->auditRepository->show($id);

        return view('audits.show', compact('audit'));
    }
}
