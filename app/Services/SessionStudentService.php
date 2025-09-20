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
        $attend = $this->sessionStudentRepository->alreadyAttend($input->session_id, $input->student_id);
        if ($attend) {
            if ($input->has('total_paid')) {
                $input->center_price = $session->center_price;
                $input->professor_price = $session->professor_price;
                $input->printables = $session->printables;
                $input->materials = $session->materials;
            }
            $attendance = $this->update($input, $attend->id);

            return 0;
        }
        if ($input->total_paid) {
            return $this->sessionStudentRepository->simplePay($input, $session);
        }

        return $this->sessionStudentRepository->advancedPay($input);

    }

    public function update($input, $id)
    {
        return $this->sessionStudentRepository->update($input, $id);
    }

    public function updateToPay($input, $id)
    {
        return $this->sessionStudentRepository->updateToPay($input, $id);
    }

    public function pay($id)
    {
        return $this->sessionStudentRepository->pay($id);
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
