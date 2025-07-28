<?php

namespace App\Services;

use App\Repositories\SessionRepository;
use App\Repositories\SessionStudentRepository;

class SessionStudentService
{
    public function __construct(
        protected SessionStudentRepository $sessionStudentRepository,
        protected SessionRepository $sessionRepository,
    ) {}

    public function index($input)
    {
        $students = $this->sessionStudentRepository->index($input);

        return $students;
    }

    public function show($id)
    {
        $student = $this->sessionStudentRepository->show($id);

        return $student;
    }

    public function store($input)
    {
        $session = $this->sessionRepository->findById($input->session_id);
        if ($input->total_paid) {
            return $this->sessionStudentRepository->simplePay($input, $session);
        }

        return $this->sessionStudentRepository->advancedPay($input);

        return $attendence;
    }

    public function update($input, $id)
    {
        $student = $this->sessionStudentRepository->update($input, $id);

        return $student;
    }

    public function delete($id)
    {
        return $this->sessionStudentRepository->delete($id);
    }

    public function changeStatus($id)
    {
        return $this->sessionStudentRepository->changeStatus($id);
    }
}
