<?php

namespace App\Services;

use App\Repositories\SessionRepository;

class SessionService
{
    public function __construct(protected SessionRepository $sessionRepository) {}

    public function index($input)
    {
        $sessions = $this->sessionRepository->index($input);

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
        $session->load('sessionExtra');

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

    public function close($input, $id)
    {
        return $this->sessionRepository->close($input, $id);
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
}
