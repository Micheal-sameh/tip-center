<?php

namespace App\Http\Controllers;

use App\Enums\StagesEnum;
use App\Http\Requests\ParentReportRequest;
use App\Http\Requests\ReportIndexRequest;
use App\Http\Requests\SessionReportRequest;
use App\Http\Requests\StudentReportRequest;
use App\Services\ProfessorService;
use App\Services\ReportService;
use App\Services\SessionService;
use App\Services\SessionStudentService;
use App\Services\StudentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function __construct(
        protected SessionService $sessionservice,
        protected StudentService $studentService,
        protected ProfessorService $professorService,
        protected ReportService $reportService,
        protected SessionStudentService $sessionStudentService,
    ) {
        //     $this->middleware('permission:sessions_view')->only(['index', 'show']);
        //     $this->middleware('permission:sessions_create')->only(['create', 'store']);
        //     $this->middleware('permission:sessions_update')->only(['edit', 'update']);
        //     $this->middleware('permission:sessions_delete')->only('destroy');
        //     $this->middleware('permission:sessions_resetPassword')->only('resetPassword');
    }

    public function index(ReportIndexRequest $request)
    {
        $sessions = $this->reportService->index($request->validated());

        return view('reports.index', compact('sessions'));
    }

    public function session(SessionReportRequest $request)
    {
        $session = $this->sessionservice->report($request->validated());
        $reports = $this->reportService->session($request->validated());
        $selected_type = $request->type;

        return view('reports.session', compact('reports', 'session', 'selected_type'));
    }

    public function student(StudentReportRequest $request)
    {
        if ($request['student_id']) {
            $reports = $this->reportService->student($request->validated());

            return view('reports.student', compact('reports'));
        } elseif ($request['search']) {
            $students = $this->studentService->index($request->validated());

            return view('reports.student', compact('students'));
        }

        return view('reports.student');
    }

    public function parent(ParentReportRequest $request)
    {
        $reports = $this->reportService->parent($request->validated());
        if ($reports == false) {
            return redirect()->back()->with('error', 'No student found');
        }

        return view('parents.student', compact('reports'));
    }

    public function downloadStudentReport(StudentReportRequest $request)
    {
        $reports = $this->reportService->student($request->validated());

        $pdf = Pdf::loadView('reports.student-pdf', compact('reports'));
        $student = $reports?->first()?->student?->name;

        return $pdf->download("$student.pdf");
    }

    public function downloadSessionReport(SessionReportRequest $request)
    {
        $session = $this->sessionservice->report($request->validated());
        $reports = $this->reportService->session($request->validated());
        $selected_type = $request->type;

        $pdf = Pdf::loadView('reports.session-pdf', compact('reports', 'session', 'selected_type'));
        $filename = Str::slug($session->professor->name).' - '.StagesEnum::getStringValue($session->stage).'.pdf';

        return $pdf->download($filename);
    }

    public function income(Request $request)
    {
        $reports = $this->reportService->income($request);

        return $reports;
    }
}
