<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentBlacklistRequest;
use App\Models\Student;
use App\Repositories\StudentBlacklistRepository;
use Illuminate\Http\Request;

class StudentBlacklistController extends Controller
{
    public function __construct(protected StudentBlacklistRepository $blacklistRepository) {}

    public function index(Request $request)
    {
        $blacklists = $this->blacklistRepository->index();

        return view('student_blacklists.index', compact('blacklists'));
    }

    public function create()
    {
        $student_id = request()->student_id;
        $students = Student::when($student_id, fn ($q) => $q->where('id', $student_id))->whereDoesntHave('centerBlacklists')
            ->orderBy('name')->get();
        if ($student_id && $students->isEmpty()) {
            return redirect()->back()->with('error', 'Student Already black listed in the Center');
        }

        return view('student_blacklists.create', compact('students'));
    }

    public function store(StoreStudentBlacklistRequest $request)
    {
        $this->blacklistRepository->create($request->only('student_id', 'reason'));

        return redirect()->route('student_blacklists.index')->with('success', 'Student added to blacklist successfully.');
    }

    public function destroy($id)
    {
        $this->blacklistRepository->delete($id);

        return redirect()->route('student_blacklists.index')->with('success', 'Blacklist entry deleted successfully.');
    }
}
