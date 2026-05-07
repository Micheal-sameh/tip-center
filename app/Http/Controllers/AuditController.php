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

    public function showAuth()
    {
        return view('audits.auth');
    }

    public function postAuth(Request $request)
    {
        $request->validate(['password' => 'required']);

        if ($request->password !== 'Misho$1234') {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        session(['audit_authenticated' => true]);

        return redirect()->intended(route('audits.index'));
    }

    public function index(Request $request)
    {
        // Expire audit session after single page view (require re-auth next visit)
        session()->forget('audit_authenticated');

        $audits = $this->auditRepository->index($request);

        return view('audits.index', compact('audits'));
    }

    public function show($id)
    {
        $audit = $this->auditRepository->show($id);

        return view('audits.show', compact('audit'));
    }
}
