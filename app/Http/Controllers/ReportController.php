<?php

namespace App\Http\Controllers;

use App\DTOs\SessionDTO;
use App\DTOs\SessionStudentDTO;
use App\Enums\StagesEnum;
use App\Http\Requests\ReportIndexRequest;
use App\Http\Requests\SessionUpdateRequest;
use App\Http\Requests\StoreSessionStudentRequest;
use App\Services\ReportService;
use App\Services\SessionService;
use App\Services\SessionStudentService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        protected SessionService $sessionservice,
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

    public function show($id)
    {
        $session = $this->sessionservice->show($id);

        return view('sessions.show', compact('session'));
    }

    public function session($session_id)
    {
        $report = $this->reportService->session($session_id);

        return $report;
        // return view('session_students.create', compact('student', 'session'));
    }

    public function selectStudent(Request $request)
    {
        $selected_student = $this->reportService->show($request->student_id);
        $input['stage'] = $selected_student->stage;
        $sessions = $this->sessionservice->index($input);

        return view('session_students.index', compact('selected_student', 'sessions'));
    }

    public function store(StoreSessionStudentRequest $request)
    {
        $input = new SessionStudentDTO(...$request->only(
            'session_id', 'student_id', 'total_paid', 'professor_price', 'center_price', 'printables'
        ));
        $this->sessionStudentService->store($input);

        return to_route('attendances.index');
    }

    public function edit($id)
    {
        $session = $this->sessionservice->show($id);

        return view('sessions.edit', compact('session'));
    }

    public function update(SessionUpdateRequest $request, $id)
    {
        $input = new sessionDTO(...$request->only(
            'stage', 'phone', 'parent_phone', 'parent_phone_2', 'birth_date', 'note',
        ));

        $this->sessionservice->update($input, $id);

        return to_route('sessions.show', $id);
    }

    public function delete($id)
    {
        $this->sessionservice->delete($id);

        return to_route('sessions.index');
    }

    public function changeStatus($id)
    {
        $session = $this->sessionservice->changeStatus($id);

        return redirect()->back()->with('success', $session->professor->name.' stage '.StagesEnum::getStringValue($session->stage).' '.'Status changed successfully');
    }
}
