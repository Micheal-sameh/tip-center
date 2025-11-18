<?php

namespace App\Services;

use App\Repositories\ProfessorRepository;
use App\Repositories\SessionStudentRepository;
use App\Repositories\StudentRepository;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function __construct(
        protected StudentRepository $studentRepository,
        protected SessionStudentRepository $sessionStudentRepository,
        protected ProfessorRepository $professorRepository,
    ) {}

    public function index($input)
    {
        $students = $this->studentRepository->index($input);

        return $students;
    }

    public function show($id)
    {
        $student = $this->studentRepository->show($id);
        $student->load('specialCases');

        return $student;
    }

    public function search($search)
    {
        $student = $this->studentRepository->search($search);

        return $student;
    }

    public function store($input)
    {
        $student = $this->studentRepository->store($input);

        return $student;
    }

    public function update($input, $id)
    {
        $student = $this->studentRepository->update($input, $id);

        return $student;
    }

    public function delete($id, $password)
    {
        $result = $this->studentRepository->delete($id, $password);

        if (! $result['success']) {
            return $result;
        }

        return $result;
    }

    public function changeStatus($id)
    {
        return $this->studentRepository->changeStatus($id);
    }

    public function profilePic($image, $id)
    {
        $student = $this->studentRepository->profilePic($image, $id);

        return $student;
    }

    public function settleDue($paid, $id)
    {
        DB::beginTransaction();
        $attendences = $this->sessionStudentRepository->settleDue($paid, $id);
        $attendences->each(function ($attendence) {
            $this->professorRepository->settleDue($attendence);
        });
        DB::commit();

        return $attendences;
    }

    public function dropdown()
    {
        return $this->studentRepository->dropdown();
    }

    public function createSpecial($input)
    {
        return $this->studentRepository->createSpecial($input);
    }

    public function updateSpecialCase($input)
    {
        return $this->studentRepository->updateSpecialCase($input);
    }
}
