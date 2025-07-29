<?php

namespace App\Services;

use App\Repositories\ProfessorRepository;
use App\Repositories\StudentRepository;

class ProfessorService
{
    public function __construct(
        protected ProfessorRepository $professorRepository,
        protected StudentRepository $studentRepository,
    ) {}

    public function index($input)
    {
        $professors = $this->professorRepository->index($input);

        return $professors;
    }

    public function show($id)
    {
        $professor = $this->professorRepository->show($id);

        return $professor;
    }

    public function store($input)
    {
        $professor = $this->professorRepository->store($input);

        return $professor;
    }

    public function update($input, $id)
    {
        $professor = $this->professorRepository->update($input, $id);

        return $professor;
    }

    public function delete($id)
    {
        return $this->professorRepository->delete($id);
    }

    public function dropdown($input = null)
    {
        if (! is_null($input) && $input['student_id']) {
            $input['stage'] = $this->studentRepository->findById($input['student_id'])?->stage;
        }
        $professors = $this->professorRepository->dropdown($input);
        // $professors->load('stages');

        return $professors;
    }

    public function changeStatus($id)
    {
        return $this->professorRepository->changeStatus($id);
    }
}
