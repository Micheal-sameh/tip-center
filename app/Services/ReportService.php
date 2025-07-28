<?php

namespace App\Services;

use App\Repositories\SessionRepository;
use App\Repositories\SessionStudentRepository;

class ReportService
{
    public function __construct(
        protected SessionStudentRepository $sessionStudentRepository,
        protected SessionRepository $sessionRepository,
    ) {}

    public function index($input)
    {
        $sessions = $this->sessionRepository->reports($input);

        return $sessions;
    }

    public function show($id)
    {
        $student = $this->sessionStudentRepository->show($id);

        return $student;
    }

    public function session($session_id)
    {
        $student = $this->sessionStudentRepository->session($session_id);

        return $student;
    }
}
