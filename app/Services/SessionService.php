<?php

namespace App\Services;

use App\Repositories\ProfessorRepository;
use App\Repositories\SessionRepository;
use App\Repositories\SessionStudentRepository;

class SessionService
{
    public function __construct(
        protected SessionRepository $sessionRepository,
        protected SessionStudentRepository $sessionStudentRepository,
        protected ProfessorRepository $professorRepository,
    ) {}

    public function index($input)
    {
        $sessions = $this->sessionRepository->index($input);
        $sessions->load('sessionExtra');
        if (! isset($input['student_id'])) {
            $onlineSessions = $this->sessionRepository->onlineSessions();

            return compact('sessions', 'onlineSessions');
        }

        return $sessions;
    }

    public function show($id)
    {
        $session = $this->sessionRepository->show($id);

        return $session;
    }

    public function report($input)
    {
        $session = $this->sessionRepository->report($input);
        $session->load('sessionExtra', 'sessionStudent.createdBy');

        return $session;
    }

    public function store($input)
    {
        $session = $this->sessionRepository->store($input);

        return $session;
    }

    public function update($input, $id)
    {
        $session = $this->sessionRepository->update($input, $id);

        return $session;
    }

    public function delete($id)
    {
        return $this->sessionRepository->delete($id);
    }

    public function extras($input, $id)
    {
        $session = $this->sessionRepository->extras($input, $id);

        return $session;
    }

    public function status($status, $id)
    {
        return $this->sessionRepository->status($status, $id);
    }

    public function mySessions($input)
    {
        return $this->sessionRepository->mySessions($input);
    }

    public function lastSession($session, $student)
    {
        return $this->sessionRepository->lastSession($session, $student);
    }

    public function students($id)
    {
        $session = $this->sessionRepository->show($id);
        $session->load('sessionStudents');

        return $session;
    }

    public function automaticCreateSessions()
    {
        $professors = $this->professorRepository->todaySessions();
        $this->sessionRepository->automaticCreateSessions($professors);
    }
}
