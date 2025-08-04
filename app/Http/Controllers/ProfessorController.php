<?php

namespace App\Http\Controllers;

use App\DTOs\ProfessorDTO;
use App\Http\Requests\ProfessorCreateRequest;
use App\Http\Requests\ProfessorIndexRequest;
use App\Http\Requests\ProfessorUpdateRequest;
use App\Http\Requests\ProfilePicRequest;
use App\Services\ProfessorService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ProfessorController extends Controller
{
    public function __construct(protected ProfessorService $professorService)
    {
        $this->middleware('permission:professors_view')->only(['index', 'show']);
        $this->middleware('permission:professors_create')->only(['create', 'store']);
        $this->middleware('permission:professors_update')->only(['edit', 'update']);
        $this->middleware('permission:professors_delete')->only('delete');
        $this->middleware('permission:professors_changeStatus')->only('changeStatus');
    }

    public function index(ProfessorIndexRequest $request)
    {
        $input = new ProfessorDTO(...$request->validated());
        $professors = $this->professorService->index($input);

        if ($request->ajax()) {
            return view('professors.partials.table', compact('professors'))->render();
        }

        return view('professors.index', compact('professors'));
    }

    public function show($id)
    {
        $professor = $this->professorService->show($id);

        return view('professors.show', compact('professor'));
    }

    public function create()
    {
        return view('professors.create');
    }

    public function store(ProfessorCreateRequest $request)
    {
        $input = new ProfessorDTO(...$request->only(
            'name', 'phone', 'optional_phone', 'birth_date', 'school', 'subject', 'stage_schedules', 'type'
        ));
        $this->professorService->store($input);

        return to_route('professors.index');
    }

    public function edit($id)
    {
        $professor = $this->professorService->show($id);
        $roles = Role::where('name', '!=', 'student')->get();

        return view('professors.edit', compact('professor', 'roles'));
    }

    public function update(ProfessorUpdateRequest $request, $id)
    {
        $input = new professorDTO(...$request->only(
            'phone', 'optional_phone', 'birth_date', 'school', 'subject', 'stage_schedules', 'type'
        ));

        $this->professorService->update($input, $id);

        return to_route('professors.show', $id);
    }

    public function delete($id)
    {
        $this->professorService->delete($id);

        return to_route('professors.index');
    }

    public function changeStatus($id)
    {
        $professor = $this->professorService->changeStatus($id);

        return response()->json([
            'success' => true,
            'message' => __('messages.Status updated'),
        ]);
    }

    public function profilePic(ProfilePicRequest $request, $id)
    {
        $professor = $this->professorService->profilePic($request->image, $id);

        return to_route('professors.show', $professor->id)->with('success', 'Profile picture updated successfully');
    }

    public function dropdown(Request $request)
    {
        return response()->json($this->professorService->dropdown($request));
    }
}
