<?php

namespace App\Services;

use App\Repositories\ChargeRepository;
use App\Repositories\SessionRepository;
use App\Repositories\SessionStudentRepository;
use App\Repositories\StudentRepository;
use App\Repositories\StudentSettlementRepository;

class ReportService
{
    public function __construct(
        protected SessionStudentRepository $sessionStudentRepository,
        protected SessionRepository $sessionRepository,
        protected StudentRepository $studentRepository,
        protected ChargeRepository $chargeRepository,
        protected StudentSettlementRepository $studentSettlementRepository,
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
        $session = $this->sessionRepository->findById($input['session_id']);
        $input['session'] = $session;
        $reports = $this->sessionStudentRepository->session($input);
        $sessionId = $input['session_id'];
        $settlements = $this->studentSettlementRepository->session($input);

        $settlementTotals = [
            'total_center' => $settlements->sum('center'),
            'total_professor' => $settlements->sum('professor_amount'),
            'total_materials' => $settlements->sum('materials'),
            'total_printables' => $settlements->sum('printables'),
        ];

        return compact('reports', 'settlements', 'settlementTotals');
    }

    public function sessionWithCalculations($input)
    {
        $session = $this->sessionRepository->findById($input['session_id']);
        $input['session'] = $session;
        $reports = $this->sessionStudentRepository->session($input);
        $settlements = $this->studentSettlementRepository->session($input);
        $selectedType = $input['type'] ?? \App\Enums\ReportType::ALL;

        // Calculate settlement totals
        $settlementTotals = [
            'total_center' => $settlements->sum('center'),
            'total_professor' => $settlements->sum('professor_amount'),
            'total_materials' => $settlements->sum('materials'),
            'total_printables' => $settlements->sum('printables'),
        ];

        // Calculate basic metrics
        $attendedCount = $reports->where('is_attend', true)->count();
        $showPhone = $reports->contains(fn ($r) => $r->student?->phone > 0);
        $showParentPhone = $reports->contains(fn ($r) => $r->student?->parent_phone > 0);
        $showMaterials = $reports->contains(fn ($r) => $r->materials > 0);
        $showPrintables = $reports->contains(fn ($r) => $r->printables > 0);

        // Compute data for each report
        $computedReports = $reports->map(function ($report) use ($selectedType) {
            return $this->computeReportRow($report, $selectedType);
        });

        // Calculate summary data
        $summaryData = $this->calculateSummary($reports, $session, $settlements, $selectedType, $settlementTotals);

        // Calculate totals data (cards)
        $totalsData = $this->calculateTotals($reports, $session, $settlements, $selectedType, $summaryData);

        return \App\DTOs\SessionReportDTO::create([
            'reports' => $reports,
            'session' => $session,
            'settlements' => $settlements,
            'settlementTotals' => $settlementTotals,
            'selectedType' => $selectedType,
            'attendedCount' => $attendedCount,
            'showPhone' => $showPhone,
            'showParentPhone' => $showParentPhone,
            'showMaterials' => $showMaterials,
            'showPrintables' => $showPrintables,
            'computedReports' => $computedReports,
            'summaryData' => $summaryData,
            'totalsData' => $totalsData,
        ]);
    }

    private function computeReportRow($report, $selectedType)
    {
        // Calculate settlement amount for this student
        $settlementForStudent = $report->settlements;
        $settlementAmount = $settlementForStudent->sum(function ($settlement) use ($selectedType) {
            return match ((int) $selectedType) {
                \App\Enums\ReportType::PROFESSOR => $settlement->professor_amount + $settlement->materials,
                \App\Enums\ReportType::CENTER => $settlement->center + $settlement->printables,
                default => $settlement->amount,
            };
        });

        // Calculate payment value minus settlement
        $reportValue = $report->professor_price + $report->center_price;
        $reportValue -= $settlementAmount;

        // Calculate to pay total
        $toPayTotal = $report->student
            ?->toPay
            ->sum(function ($p) use ($selectedType) {
                return match ((int) $selectedType) {
                    \App\Enums\ReportType::PROFESSOR => $p->to_pay + $p->to_pay_materials,
                    \App\Enums\ReportType::CENTER => $p->to_pay_center + $p->to_pay_print,
                    default => $p->to_pay + $p->to_pay_center + $p->to_pay_print + $p->to_pay_materials,
                };
            }) ?? 0;

        $toPayTotal += $settlementAmount;

        // Determine row class
        $rowClass = $report->is_attend == \App\Enums\AttendenceType::ABSENT
            ? 'table-danger'
            : ($report->to_pay + $report->to_pay_center + $report->to_pay_print + $report->to_pay_materials > 0
                ? 'table-warning'
                : '');

        return [
            'settlementAmount' => $settlementAmount,
            'reportValue' => $reportValue,
            'toPayTotal' => $toPayTotal,
            'rowClass' => $rowClass,
        ];
    }

    private function calculateSummary($reports, $session, $settlements, $selectedType, $settlementTotals)
    {
        // Calculate summary total
        $summaryTotal = $reports->sum(
            fn ($r) => $r->professor_price + $r->center_price + $r->printables + $r->materials
        );

        $summaryTotal += $selectedType == \App\Enums\ReportType::PROFESSOR
            ? $session->professor->balance
            : -$session->professor->balance;

        $summaryTotal += $selectedType == \App\Enums\ReportType::PROFESSOR
            ? $session->professor->materials_balance
            : -$session->professor->materials_balance;

        if ($session->sessionExtra) {
            $adj = collect([
                'markers',
                'copies',
                'other',
                'cafeterea',
                'other_print',
                'to_professor',
                'out_going',
            ])->sum(fn ($f) => $session->sessionExtra->$f ?? 0);
            $summaryTotal += $selectedType == \App\Enums\ReportType::PROFESSOR ? -$adj : $adj;
        }

        if ($session->onlines->isNotEmpty()) {
            $onlineTotal = $session->onlines->sum(function ($o) use ($selectedType) {
                return match ((int) $selectedType) {
                    \App\Enums\ReportType::PROFESSOR => $o->materials + $o->professor,
                    \App\Enums\ReportType::CENTER => $o->center ?? 0,
                    default => $o->materials + $o->professor + $o->center,
                };
            });
            $summaryTotal += $onlineTotal;
        }

        if ($settlements->isNotEmpty()) {
            $totalAmount = $settlements->sum(function ($settlement) use ($selectedType) {
                return match ((int) $selectedType) {
                    \App\Enums\ReportType::PROFESSOR => $settlement->professor_amount + $settlement->materials,
                    \App\Enums\ReportType::CENTER => $settlement->center + $settlement->printables,
                    default => $settlement->amount,
                };
            });
            $summaryTotal += $totalAmount;
        }

        // Calculate to collect
        $toCollect = $reports->sum(
            fn ($report) => $report->student
                ?->toPay
                ->sum(
                    fn ($pay) => match ((int) $selectedType) {
                        \App\Enums\ReportType::PROFESSOR => $pay->to_pay + $pay->to_pay_materials,
                        \App\Enums\ReportType::CENTER => $pay->to_pay_center + $pay->to_pay_print,
                        default => $pay->to_pay + $pay->to_pay_center + $pay->to_pay_print + $pay->to_pay_materials,
                    }
                ) ?? 0
        );

        $toCollect += $reports->sum(function ($report) use ($selectedType) {
            $settlementForStudent = $report->settlements;

            return $settlementForStudent->sum(function ($settlement) use ($selectedType) {
                return match ((int) $selectedType) {
                    \App\Enums\ReportType::PROFESSOR => $settlement->professor_amount + $settlement->materials,
                    \App\Enums\ReportType::CENTER => $settlement->center + $settlement->printables,
                    default => $settlement->amount,
                };
            });
        });

        // Calculate settlements totals
        $totalSettlementsForProfessor = $reports->sum(function ($report) use ($selectedType) {
            $settlementForStudent = $report->settlements;

            return $settlementForStudent->sum(function ($settlement) use ($selectedType) {
                return match ((int) $selectedType) {
                    \App\Enums\ReportType::PROFESSOR => $settlement->professor_amount + $settlement->materials,
                    \App\Enums\ReportType::CENTER => $settlement->center + $settlement->printables,
                    default => $settlement->amount,
                };
            });
        });

        $totalSettlementsForCenter = $reports->sum(function ($report) {
            $settlementForStudent = $report->settlements;

            return $settlementForStudent->sum(function ($settlement) {
                return $settlement->center + $settlement->printables;
            });
        });

        return [
            'summaryTotal' => $summaryTotal,
            'toCollect' => $toCollect,
            'totalSettlementsForProfessor' => $totalSettlementsForProfessor,
            'totalSettlementsForCenter' => $totalSettlementsForCenter,
        ];
    }

    private function calculateTotals($reports, $session, $settlements, $selectedType, $summaryData)
    {
        $data = [];

        // Professor total
        if ($session->professor_price) {
            $professorTotal = $reports->sum('professor_price');
            if ($session->onlines->isNotEmpty()) {
                $professorTotal += $session->onlines->sum(fn ($o) => $o->professor);
            }
            if ($settlements->isNotEmpty()) {
                $professorTotal += $settlements->sum('professor_amount');
            }
            $data['professorTotal'] = $professorTotal - $summaryData['totalSettlementsForProfessor'];
        }

        // Center total
        if ($session->center_price) {
            $centerTotal = $reports->sum('center_price');
            if ($session->onlines->isNotEmpty()) {
                $centerTotal += $session->onlines->sum(fn ($o) => $o->center ?? 0);
            }
            if ($settlements->isNotEmpty()) {
                $centerTotal += $settlements->sum('center');
            }
            $data['centerTotal'] = $centerTotal - $summaryData['totalSettlementsForCenter'];
        }

        // Balance
        if ($session->professor->balance) {
            $data['balanceValue'] = $selectedType == \App\Enums\ReportType::PROFESSOR
                ? $session->professor->balance
                : -$session->professor->balance;
        }

        // Materials balance
        if ($session->professor->materials_balance > 0) {
            $data['materialsBalanceValue'] = $selectedType == \App\Enums\ReportType::PROFESSOR
                ? $session->professor->materials_balance
                : -$session->professor->materials_balance;
        }

        // Printables total
        $showPrintables = $reports->contains(fn ($r) => $r->printables > 0);
        if ($showPrintables) {
            $printablesTotal = $reports->sum('printables');
            if ($settlements->isNotEmpty()) {
                $printablesTotal += $settlements->sum('printables');
            }
            $data['printablesTotal'] = $printablesTotal;
        }

        // Materials total
        $showMaterials = $reports->contains(fn ($r) => $r->materials > 0);
        if ($showMaterials) {
            $materialsTotal = $reports->sum('materials');
            if ($session->onlines->isNotEmpty()) {
                $materialsTotal += $session->onlines->sum('materials');
            }
            if ($settlements->isNotEmpty()) {
                $materialsTotal += $settlements->sum('materials');
            }
            $data['materialsTotal'] = $materialsTotal;
        }

        // Online total
        if ($session->onlines->isNotEmpty()) {
            $data['onlineTotal'] = $session->onlines->sum(fn ($o) => $o->materials + $o->professor + $o->center);
        }

        // Total settlement amount for settlements table
        if ($settlements->isNotEmpty()) {
            $data['totalSettlementAmount'] = $settlements->sum(function ($settlement) use ($selectedType) {
                return match ((int) $selectedType) {
                    \App\Enums\ReportType::PROFESSOR => $settlement->professor_amount + $settlement->materials,
                    \App\Enums\ReportType::CENTER => $settlement->center + $settlement->printables,
                    default => $settlement->amount,
                };
            });
        }

        return $data;
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
        $settle = $this->studentSettlementRepository->incomeCenter($input);
        $print = $this->studentSettlementRepository->incomePrint($input);
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
            + $print
            + $gap
            + $studentPrint
            - $charges;

        return compact('sessions', 'totals', 'charges', 'gap', 'settle', 'print', 'studentPrint');
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
        $settle = $this->studentSettlementRepository->specialRoomsIncome($input);
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

    public function chargesReport($input)
    {
        return $this->chargeRepository->chargesReport($input);
    }

    public function studentSettlements($input)
    {
        $settlements = $this->studentSettlementRepository->index($input);

        $query = $this->studentSettlementRepository->settlementsFilter($input);
        $totals = [
            'total_amount' => (clone $query)->sum('amount'),
            'total_center' => (clone $query)->sum('center'),
            'total_professor' => (clone $query)->sum('professor_amount'),
            'total_materials' => (clone $query)->sum('materials'),
            'total_printables' => (clone $query)->sum('printables'),
        ];

        return compact('settlements', 'totals');
    }

    public function toPay($input)
    {
        return $this->sessionStudentRepository->toPay($input);
    }
}
