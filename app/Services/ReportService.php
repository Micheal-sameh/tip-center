<?php

namespace App\Services;

use App\Repositories\ChargeRepository;
use App\Repositories\SessionRepository;
use App\Repositories\SessionStudentRepository;
use App\Repositories\StudentRepository;

class ReportService
{
    public function __construct(
        protected SessionStudentRepository $sessionStudentRepository,
        protected SessionRepository $sessionRepository,
        protected StudentRepository $studentRepository,
        protected ChargeRepository $chargeRepository,
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
        $charges = $this->chargeRepository->income($input);
        $gap = $this->chargeRepository->incomeGap($input);
        $totals = [
            'paid_students' => 0,
            'center_price' => 0,
            'printables' => 0,
            'copies' => 0,
            'markers' => 0,
            'overall_total' => 0,
            'attended_count' => 0,
        ];

        $sessions->each(function ($session) use (&$totals) {
            $totals['paid_students'] += $session->total_paid_students;
            $totals['attended_count'] += $session->attended_count;
            $totals['center_price'] += $session->total_center_price;
            $totals['printables'] += $session->total_printables ?? 0;
            $totals['markers'] += $session->sessionExtra?->markers ?? 0;
            $totals['copies'] += $session->sessionExtra?->copies ?? 0;
        });

        $totals['overall_total'] =
            $totals['center_price']
            + $totals['printables']
            + $totals['markers']
            + $totals['copies'];

        return compact('sessions', 'totals', 'charges', 'gap');
    }

    public function monthlyIncome($month)
    {
        $reports = $this->sessionRepository->monthlyIncome($month);
        $center = $reports->sum('center');
        $center = $reports->sum(function ($item) {
            return $item->center + $item->other_center;
        });
        $copies = $reports->sum(function ($item) {
            return $item->print + $item->copies + $item->other_print;
        });
        $markers = $reports->sum('markers');
        $total_income = $reports->sum('income_total');
        $gap = $reports->sum('charges_gap');
        $charges_center = $reports->sum('charges_center');
        $charges_markers = $reports->sum('charges_markers');
        $charges_others = $reports->sum('charges_others');
        $charges_copies = $reports->sum('charges_copies');
        $total_charges = $reports->sum('charges_total');
        $total_difference = $reports->sum('difference_total');
        $net_center = $reports->sum('net_center');
        $net_copies = $reports->sum('net_copies');
        $net_markers = $reports->sum('net_markers');
        $net_others = $reports->sum('net_others');

        return compact('reports', 'center', 'copies', 'markers', 'total_income', 'gap', 'charges_center',
            'charges_markers', 'charges_others', 'charges_copies', 'total_charges', 'total_difference',
            'net_center', 'net_copies', 'net_markers', 'net_others');
    }

    public function specialRooms($input)
    {
        $sessions = $this->sessionRepository->specialRooms($input);
        $totals = [
            'attended_count' => 0,
            'paid_students' => 0,
            'center_price' => 0,
        ];

        $sessions->each(function ($session) use (&$totals) {
            $totals['paid_students'] += $session->total_paid_students;
            $totals['attended_count'] += $session->attended_count;
            $totals['center_price'] += $session->center;
        });

        return compact('sessions', 'totals');
    }
}
