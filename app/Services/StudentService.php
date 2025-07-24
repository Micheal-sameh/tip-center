<?php

namespace App\Services;

use App\Repositories\studentRepository;

class StudentService
{
    public function __construct(protected studentRepository $studentRepository) {}

    public function index($input)
    {
        $students = $this->studentRepository->index($input);

        return $students;
    }

    public function show($id)
    {
        $student = $this->studentRepository->show($id);

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

    public function delete($id)
    {
        return $this->studentRepository->delete($id);
    }

    public function changeStatus($id)
    {
        return $this->studentRepository->changeStatus($id);
    }
}
