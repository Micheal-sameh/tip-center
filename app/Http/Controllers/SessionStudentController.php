<?php

namespace App\Http\Controllers;

use App\DTOs\SessionStudentDTO;
use App\Http\Requests\AttendanceCreateRequest;
use App\Http\Requests\StoreSessionStudentRequest;
use App\Services\SessionService;
use App\Services\SessionStudentService;
use App\Services\StudentService;
use Illuminate\Http\Request;

class SessionStudentController extends Controller
{
    public function __construct(
        protected SessionService $sessionservice,
        protected StudentService $studentService,
        protected SessionStudentService $sessionStudentService,
    ) {
        //     $this->middleware('permission:sessions_view')->only(['index', 'show']);
        //     $this->middleware('permission:sessions_create')->only(['create', 'store']);
        //     $this->middleware('permission:sessions_update')->only(['edit', 'update']);
        //     $this->middleware('permission:sessions_delete')->only('destroy');
        //     $this->middleware('permission:sessions_resetPassword')->only('resetPassword');
    }

    public function index(Request $request)
    {
        if ($request->code) {
            $students = $this->studentService->search($request->code);

            return view('session_students.index', compact('students'));

        }
        if ($request->student_id) {
            return $this->selectStudent($request);
        }

        return view('session_students.index');
    }

    public function show($id)
    {
        $session = $this->sessionservice->show($id);

        return view('sessions.show', compact('session'));
    }

    public function create(AttendanceCreateRequest $request)
    {
        $student = $this->studentService->show($request->student_id);
        $session = $this->sessionservice->show($request->session_id);
        $to_pay = $student->toPay()->sum('to_pay');

        return view('session_students.create', compact('student', 'session', 'to_pay'));
    }

    public function selectStudent(Request $request)
    {
        $selected_student = $this->studentService->show($request->student_id);
        $input['stage'] = $selected_student->stage;
        $input['student_id'] = $selected_student->id;
        $sessions = $this->sessionservice->index($input);
        $my_sessions = $this->sessionservice->mySessions($input);

        return view('session_students.index', compact('selected_student', 'sessions', 'my_sessions'));
    }

    public function store(StoreSessionStudentRequest $request)
    {
        $input = new SessionStudentDTO(...$request->only(
            'session_id', 'student_id', 'total_paid', 'professor_price', 'center_price', 'printables', 'materials', 'to_pay'
        ));
        $this->sessionStudentService->store($input);

        return to_route('attendances.index');
    }

    public function delete($id)
    {
        $this->sessionservice->delete($id);

        return to_route('sessions.index');
    }
}
