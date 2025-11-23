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
        $student_id = request()->get('student_id');
        $professor_id = request()->get('professor_id');

        // Filter students based on professor stages if professor_id provided
        if ($professor_id) {
            $professor = Professor::with('stages')->find($professor_id);
            if ($professor) {
                $stageIds = $professor->stages->pluck('stage')->toArray();
                $students = Student::whereIn('stage', $stageIds)->orderBy('name')->get();
            } else {
                $students = collect(); // Empty collection if no professor found
            }
        } else {
            $students = collect(); // Return empty collection instead of all students
        }

        if ($student_id) {
            $student = Student::find($student_id);
            if ($student) {
                $professors = Professor::whereHas('stages', function ($query) use ($student) {
                    $query->where('stage', $student->stage);
                })->orderBy('name')->get();
            } else {
                $professors = Professor::orderBy('name')->get();
            }
            $students = Student::where('id', $student_id)->orderBy('name')->get();
        } else {
            $professors = Professor::orderBy('name')->get();
        }

        return view('professor_blacklists.create', compact('professors', 'students', 'professor_id'));
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

    public function getStudentsByProfessor($professorId)
    {
        $professor = Professor::with('stages')->findOrFail($professorId);
        $stageIds = $professor->stages->pluck('stage');

        $students = Student::whereIn('stage', $stageIds)
            ->hasAttendToProf($professorId)
            ->orderBy('name')->get(['id', 'name']);

        return response()->json($students);
    }
}
