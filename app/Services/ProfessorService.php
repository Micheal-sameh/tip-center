<?php

namespace App\Services;

use App\Repositories\ProfessorRepository;

class ProfessorService
{
    public function __construct(protected ProfessorRepository $professorRepository) {}

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

    public function changeStatus($id)
    {
        return $this->professorRepository->changeStatus($id);
    }
}
