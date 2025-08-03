<?php

namespace App\Http\Controllers;

use App\DTOs\SessionDTO;
use App\Enums\SessionStatus;
use App\Enums\StagesEnum;
use App\Http\Requests\CloseSessionRequesst;
use App\Http\Requests\SessionCreateRequest;
use App\Http\Requests\SessionIndexRequest;
use App\Http\Requests\SessionUpdateRequest;
use App\Services\ProfessorService;
use App\Services\SessionService;

class SessionController extends Controller
{
    public function __construct(
        protected SessionService $sessionservice,
        protected ProfessorService $professorService,
    ) {
        $this->middleware('permission:sessions_view')->only(['index', 'show']);
        $this->middleware('permission:sessions_create')->only(['create', 'store']);
        $this->middleware('permission:sessions_update')->only(['edit', 'update']);
        $this->middleware('permission:sessions_delete')->only('destroy');
        $this->middleware('permission:sessions_resetPassword')->only('resetPassword');
    }

    public function index(SessionIndexRequest $request)
    {
        $sessions = $this->sessionservice->index($request->validated());
        $totalsessions = $sessions->total();
        $professors = $this->professorService->dropdown();
        if ($request->ajax()) {
            return view('sessions.partials.session_cards', ['sessions' => $sessions]);
        }

        return view('sessions.index', compact('sessions', 'totalsessions', 'professors'));
    }

    public function show($id)
    {
        $session = $this->sessionservice->show($id);

        return view('sessions.show', compact('session'));
    }

    public function create($professor_id)
    {
        $professor = $this->professorService->show($professor_id);
        $stages = $professor->stages;

        return view('sessions.create', compact('professor', 'stages'));
    }

    public function store(SessionCreateRequest $request)
    {
        $input = new SessionDTO(...$request->only(
            'professor_id', 'stage', 'professor_price', 'center_price', 'printables', 'start_at', 'end_at', 'materials'
        ));

        $this->sessionservice->store($input);

        return to_route('sessions.index');
    }

    public function edit($id)
    {
        $session = $this->sessionservice->show($id);

        if ($session->status == SessionStatus::FINISHED) {
            return redirect()->back()
                ->with('error', 'You cannot edit an inactive session.');
        }

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

    public function close(CloseSessionRequesst $request, $id)
    {
        $session = $this->sessionservice->close($request->validated(), $id);

        return redirect()->back()->with('success', $session->professor->name.' stage '.StagesEnum::getStringValue($session->stage).' '.'Status changed successfully');
    }

    public function active($id)
    {
        $session = $this->sessionservice->status(SessionStatus::ACTIVE, $id);

        return redirect()->back()->with('success', $session->professor->name.' stage '.StagesEnum::getStringValue($session->stage).' '.'Status changed successfully');
    }

    public function students($id)
    {
        $session = $this->sessionservice->students($id);

        return view('sessions.students', compact('session'));
    }
}
