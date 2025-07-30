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

    public function session($input)
    {
        $student = $this->sessionStudentRepository->session($input);

        return $student;
    }

    public function student($input)
    {
        $report = $this->sessionStudentRepository->student($input);

        return $report;
    }
}
