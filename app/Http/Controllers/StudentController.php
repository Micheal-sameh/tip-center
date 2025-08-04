<?php

namespace App\Http\Controllers;

use App\DTOs\StudentDTO;
use App\Http\Requests\ProfilePicRequest;
use App\Http\Requests\SettleDueRequest;
use App\Http\Requests\StudentCreateRequest;
use App\Http\Requests\StudentIndexRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Services\StudentService;

class StudentController extends Controller
{
    public function __construct(protected StudentService $studentservice)
    {
        $this->middleware('permission:students_view')->only(['index', 'show']);
        $this->middleware('permission:students_create')->only(['create', 'store']);
        $this->middleware('permission:students_update')->only(['edit', 'update']);
        $this->middleware('permission:students_delete')->only('delete');
        $this->middleware('permission:students_changeStatus')->only('changeStatus');
    }

    public function index(StudentIndexRequest $request)
    {
        $students = $this->studentservice->index($request->validated());
        $totalStudents = $students->total();

        return view('students.index', compact('students', 'totalStudents'));
    }

    public function show($id)
    {
        $student = $this->studentservice->show($id);

        return view('students.show', compact('student'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(StudentCreateRequest $request)
    {
        $input = new StudentDTO(...$request->only(
            'name', 'stage', 'phone', 'parent_phone', 'parent_phone_2', 'birth_date', 'note',
        ));

        $this->studentservice->store($input);

        return to_route('students.index');
    }

    public function edit($id)
    {
        $student = $this->studentservice->show($id);

        return view('students.edit', compact('student'));
    }

    public function update(StudentUpdateRequest $request, $id)
    {
        $input = new StudentDTO(...$request->only(
            'stage', 'phone', 'parent_phone', 'parent_phone_2', 'birth_date', 'note',
        ));

        $this->studentservice->update($input, $id);

        return to_route('students.show', $id);
    }

    public function delete($id)
    {
        $this->studentservice->delete($id);

        return to_route('students.index');
    }

    public function profilePic(ProfilePicRequest $request, $id)
    {
        $this->studentservice->profilePic($request->image, $id);

        return to_route('students.show', $id)->with('success', 'Profile picture updated successfully');
    }

    public function settleDue(SettleDueRequest $request, $id)
    {
        $this->studentservice->settleDue($request->paid, $id);

        return redirect()->back()->with('success', 'paid money successful');
    }
}
