<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportIndexRequest;
use App\Services\ProfessorService;
use App\Services\ReportService;
use App\Services\SessionService;
use App\Services\SessionStudentService;
use App\Services\StudentService;
use Illuminate\Http\Request;

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

    public function session($session_id)
    {
        $report = $this->reportService->session($session_id);

        return $report;
        // return view('session_students.create', compact('student', 'session'));
    }

    public function student(Request $request)
    {
        if ($request['professor_id'] && $request['student_id']) {
            $reports = $this->reportService->student($request);

            return view('reports.student', compact('reports'));
        } elseif ($request['search']) {
            $students = $this->studentService->index($request);

            return view('reports.student', compact('students'));
        }

        // $professors = $this->professorService->dropdown();
        // return $report;
        return view('reports.student');
    }
}
