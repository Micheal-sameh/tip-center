<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilePicRequest;
use App\Http\Requests\SettleDueRequest;
use App\Http\Requests\SpecialCaseUpdateRequest;
use App\Repositories\ProfessorRepository;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentSpecialCaseController extends Controller
{
    public function __construct(
        protected StudentService $studentservice,
        protected ProfessorRepository $professorRepository,
    ) {
        $this->middleware('permission:students_view')->only(['index', 'show']);
        $this->middleware('permission:students_create')->only(['create', 'store']);
        $this->middleware('permission:students_update')->only(['edit', 'update']);
        $this->middleware('permission:students_delete')->only('delete');
        $this->middleware('permission:students_changeStatus')->only('changeStatus');
    }

    public function show($id)
    {
        $student = $this->studentservice->show($id);

        return view('students.show', compact('student'));
    }

    public function create(Request $request)
    {
        $student = $this->studentservice->show($request->student_id);
        $input = ['stage' => $student->stage];
        $professors = $this->professorRepository->dropdown($input);

        return view('student_special.create', compact('student', 'professors'));
    }

    public function store(Request $request)
    {
        $this->studentservice->createSpecial($request);

        return to_route('students.index');
    }

    public function edit($id)
    {
        $student = $this->studentservice->show($id);

        return view('students.edit', compact('student'));
    }

    public function update(SpecialCaseUpdateRequest $request, $id)
    {
        $this->studentservice->updateSpecialCase($request->validated(), $id);

        return redirect()->back();
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
        $this->studentservice->settleDue($request->paid, $request->student_id);

        return redirect()->back()->with('success', 'paid money successful');
    }
}
