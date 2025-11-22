<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfessorBlacklistRequest;
use App\Models\Professor;
use App\Models\Student;
use App\Repositories\ProfessorBlacklistRepository;
use Illuminate\Http\Request;

class ProfessorBlacklistController extends Controller
{
    protected ProfessorBlacklistRepository $blacklistRepository;

    public function __construct(ProfessorBlacklistRepository $blacklistRepository)
    {
        $this->blacklistRepository = $blacklistRepository;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['professor_id', 'professor_name', 'student_id', 'student_name']);
        $blacklists = $this->blacklistRepository->index($filters);

        return view('professor_blacklists.index', compact('blacklists'));
    }

    public function create()
    {
        $professors = Professor::orderBy('name')->get();
        $students = Student::orderBy('name')->get();

        return view('professor_blacklists.create', compact('professors', 'students'));
    }

    public function store(StoreProfessorBlacklistRequest $request)
    {
        $this->blacklistRepository->create($request->only('professor_id', 'student_id', 'reason'));

        return redirect()->route('professor_blacklists.index')->with('success', 'Blacklist entry created successfully.');
    }

    public function destroy($id)
    {
        $this->blacklistRepository->delete($id);

        return redirect()->route('professor_blacklists.index')->with('success', 'Blacklist entry deleted successfully.');
    }
}
