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
        $reports->load('session.professor', 'session.sessionExtra');

        return $reports;
    }

    public function income($input)
    {
        $sessions = $this->sessionRepository->income($input);
        $charges = $this->chargeRepository->income($input);
        $gap = $this->chargeRepository->incomeGap($input);
        $settle = $this->chargeRepository->incomeSettle($input);
        $studentPrint = $this->chargeRepository->incomeStudentPrint($input);
        $totals = [
            'paid_students' => 0,
            'center_price' => 0,
            'printables' => 0,
            'copies' => 0,
            'markers' => 0,
            'other_center' => 0,
            'other_print' => 0,
            'overall_total' => 0,
            'attended_count' => 0,
            'to_professor' => 0,
            'online' => 0,
        ];

        $sessions->each(function ($session) use (&$totals) {
            $totals['paid_students'] += $session->total_paid_students;
            $totals['attended_count'] += $session->attended_count;
            $totals['center_price'] += $session->total_center_price;
            $totals['printables'] += $session->total_printables ?? 0;
            $totals['markers'] += $session->sessionExtra?->markers ?? 0;
            $totals['other_center'] += $session->sessionExtra?->other ?? 0;
            $totals['other_print'] += $session->sessionExtra?->other_print ?? 0;
            $totals['to_professor'] += $session->sessionExtra?->to_professor ?? 0;
            $totals['copies'] += $session->sessionExtra?->copies ?? 0;
            $totals['online'] += $session->totalOnline ?? 0;
        });

        $totals['overall_total'] =
            $totals['center_price']
            + $totals['printables']
            + $totals['markers']
            + $totals['other_center']
            + $totals['other_print']
            + $totals['to_professor']
            + $totals['online']
            + $totals['copies']
            + $settle
            + $gap
            + $studentPrint
            - $charges;

        return compact('sessions', 'totals', 'charges', 'gap', 'settle', 'studentPrint');
    }

    public function monthlyIncome($month)
    {
        $reports = $this->sessionRepository->monthlyIncome($month);
        $center = $reports->sum('center');
        $center = $reports->sum(function ($item) {
            return $item->center + $item->other_center + $item->online_center;
        });
        $copies = $reports->sum(function ($item) {
            return $item->print + $item->copies + $item->other_print + $item->charges_student_print;
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
        $studentPrint = $reports->sum('charges_student_print');

        return compact('reports', 'center', 'copies', 'markers', 'total_income', 'gap', 'charges_center',
            'charges_markers', 'charges_others', 'charges_copies', 'total_charges', 'total_difference',
            'net_center', 'net_copies', 'net_markers', 'net_others', 'studentPrint');
    }

    public function monthlyTenAndEleven($month)
    {
        $reports = $this->sessionRepository->monthlyTenAndEleven($month);
        $totals = [
            'center' => $reports->sum('center_income'),
            'other_center' => $reports->sum('other'),
            'charges' => $reports->sum('charges_ten_eleven'),
        ];

        $totals['overall_total'] = $totals['center'] + $totals['other_center'] - $totals['charges'];

        return compact('reports', 'totals');
    }

    public function specialRooms($input)
    {
        $sessions = $this->sessionRepository->specialRooms($input);
        $settle = $this->chargeRepository->specialRoomsIncome($input);
        $charges = $this->chargeRepository->specialRoomsCharge($input);
        $totals = [
            'attended_count' => 0,
            'paid_students' => 0,
            'center_price' => 0,
            'overall_total' => 0,
        ];

        $sessions->each(function ($session) use (&$totals) {
            $totals['paid_students'] += $session->total_paid_students;
            $totals['attended_count'] += $session->attended_count;
            $totals['center_price'] += $session->center + $session->sessionExtra?->other;
        });
        $totals['overall_total'] = $totals['center_price'] + $settle - $charges;

        return compact('sessions', 'totals', 'settle', 'charges');
    }
}
