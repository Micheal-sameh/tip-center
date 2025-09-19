<?php

namespace App\Http\Controllers;

use App\DTOs\SessionStudentDTO;
use App\Http\Requests\AttendanceCreateRequest;
use App\Http\Requests\StoreSessionStudentRequest;
use App\Http\Requests\UpdateSessionStudentRequest;
use App\Models\SessionStudent;
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
        $all_students = $this->studentService->dropdown($request->code);
        if ($request->code) {
            $students = $this->studentService->search($request->code);

            return view('session_students.index', compact('students'));

        }
        if ($request->student_id) {
            return $this->selectStudent($request);
        }

        return view('session_students.index', compact('all_students'));
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
        $to_pay = $student->toPay()->get();
        $attend = SessionStudent::where('session_id', $request->session_id)
            ->where('student_id', $request->student_id)->exists();

        $message = null;
        if ($attend) {
            $message = 'Student already attended this session.';
        }

        return view('session_students.create', compact('student', 'session', 'to_pay', 'message'));
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
            'session_id', 'student_id', 'total_paid', 'professor_price', 'center_price', 'printables', 'materials', 'to_pay', 'to_pay_center', 'to_pay_print', 'to_pay_materials'
        ));
        $student = $this->studentService->show($input->student_id);
        $reminder = $this->sessionStudentService->store($input);
        if ($reminder > 0) {
            return redirect()->route('attendances.index')->with('success', " $student->name: Reminder $reminder EGP");
        }

        return to_route('attendances.index');
    }

    public function update(UpdateSessionStudentRequest $request, $id)
    {
        $input = new SessionStudentDTO(...$request->only(
            'professor_price', 'center_price', 'printables', 'materials', 'to_pay', 'to_pay_center', 'to_pay_print', 'to_pay_materials'
        ));
        $attendence = $this->sessionStudentService->update($input, $id);

        return redirect()->route('sessions.students', $attendence->session_id)->with('success', "prices updated to {$attendence->student->name}");
    }

    public function pay($id)
    {
        $pay = $this->sessionStudentService->pay($id);

        return redirect()->back()->with('success', "settle pay to {$pay->student->name} on session {$pay->session->professor->name}");
    }

    public function delete($id)
    {
        $this->sessionStudentService->delete($id);

        return redirect()->back()->With('success', 'removed successful');
    }
}
