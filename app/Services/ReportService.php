<?php

namespace App\Services;

use App\Repositories\SessionRepository;
use App\Repositories\SessionStudentRepository;
use App\Repositories\StudentRepository;

class ReportService
{
    public function __construct(
        protected SessionStudentRepository $sessionStudentRepository,
        protected SessionRepository $sessionRepository,
        protected StudentRepository $studentRepository,
    ) {}

    public function index($input)
    {
        $sessions = $this->sessionRepository->reports($input);

        return $sessions;
    }

    public function parent($input)
    {
        $student = $this->studentRepository->parent($input);
        if (! $student) {
            return false;
        }
        $reports = $this->sessionStudentRepository->parent($student);
        $reports->load('session.professor', 'student');

        return $reports;
    }

    public function session($input)
    {
        $student = $this->sessionStudentRepository->session($input);

        return $student;
    }

    public function student($input)
    {
        $reports = $this->sessionStudentRepository->student($input);
        $reports->load('session.sessionExtra');

        return $reports;
    }

    public function income($input)
    {
        $reports = $this->sessionRepository->income($input);

        return $reports;
    }
}
