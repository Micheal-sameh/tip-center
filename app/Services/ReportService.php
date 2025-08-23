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
        $sessions = $this->sessionRepository->income($input);

        $totals = [
            'students' => 0,
            'center_price' => 0,
            'printables' => 0,
            'materials' => 0,
            'copies' => 0,
            'markers' => 0,
            'overall_total' => 0,
        ];

        $sessions->each(function ($session) use (&$totals) {
            $totals['students'] += $session->session_students_count;
            $totals['center_price'] += $session->total_center_price;
            $totals['printables'] += $session->sessionExtra?->printables ?? 0;
            $totals['materials'] += $session->materials ?? 0;
            $totals['markers'] += $session->sessionExtra?->markers ?? 0;
            $totals['copies'] += $session->sessionExtra?->copies ?? 0;
        });

        $totals['overall_total'] =
            $totals['center_price']
            + $totals['printables']
            + $totals['materials']
            + $totals['markers']
            + $totals['copies'];

        return compact('sessions', 'totals');
    }
}
