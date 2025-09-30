<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Session Report - {{ $session->created_at->format('Y-m-d') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
            text-align: center;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .header-text h1 {
            font-size: 18px;
            margin: 0;
            color: #333;
        }

        .subheader {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table th {
            background-color: #343a40;
            color: white;
            padding: 8px;
            text-align: left;
        }

        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .table-warning {
            background-color: #fff3cd;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-primary {
            color: #0d6efd;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .bg-warning {
            background-color: #fff3cd;
        }

        .totals-table th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }

        .totals-table td {
            font-weight: bold;
        }

        .total-value {
            font-size: 1.1em;
        }

        .expenses-table {
            margin-top: 20px;
        }

        .expenses-table th {
            background-color: #e9ecef;
            color: #333;
        }
    </style>
</head>

<body>
    @php
        $logo = App\Models\Setting::where('name', 'logo')->first();
        $faviconUrl = $logo?->getFirstMediaPath('app_logo');
    @endphp
    <div class="header-container">
        <img src="{{ $faviconUrl }}" class="logo" alt="Company Logo">
        <div class="header-text">
            <h1>Session Report - {{ $session->created_at->format('d-m-Y') }}</h1>
        </div>
    </div>

    <div class="subheader">
        <div><strong>Professor:</strong> {{ $session->professor->name }}</div>
        <div><strong>Stage:</strong> {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</div>
    </div>

    <h5 class="mt-4 mb-3">Students Attendance</h5>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student Name</th>
                @if ($reports->contains(fn($r) => $r->student?->phone > 0))
                    <th>Phone</th>
                @endif
                @if ($reports->contains(fn($r) => $r->student?->parent_phone > 0))
                    <th>parent Phone</th>
                @endif
                <th>Attending</th>
                <th class="text-end">Payment</th>
                <th>Materials</th>
                <th>Student Papers</th>
                <th class="text-end">To Pay</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr
                    class="{{ $report->is_attend == App\Enums\AttendenceType::ABSENT ? 'table-danger' : ($report->to_pay + $report->to_pay_center + $report->to_pay_print + $report->to_pay_materials > 0 ? 'table-warning' : '') }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $report->student?->name }}</td>
                    @if ($report->student?->phone)
                        <td>{{ $report->student?->phone }}</td>
                    @endif
                    @if ($report->student?->parent_phone)
                        <td>{{ $report->student?->parent_phone }}</td>
                    @endif
                    <td>{{ $report->is_attend ? $report->created_at->format('h:i:A') : App\Enums\AttendenceType::getStringValue($report->is_attend) }}
                    </td>
                    <td class="text-end">
                        {{ number_format($report->professor_price + $report->center_price, 2) }}
                    </td>
                    <td>{{ $report->materials }}</td>
                    <td class="text-end">{{ $report->printables ?? 0 }}</td>
                    @php
                        $total = $report->student->toPay->sum(function ($pay) use ($selected_type) {
                            if ($selected_type == App\Enums\ReportType::PROFESSOR) {
                                return $pay->to_pay + $pay->to_pay_materials;
                            } elseif ($selected_type == App\Enums\ReportType::CENTER) {
                                return $pay->to_pay_center + $pay->to_pay_print;
                            } else {
                                return $pay->to_pay_center + $pay->to_pay + $pay->to_pay_print + $pay->to_pay_materials;
                            }
                        });
                    @endphp
                    @if ($total > 0)
                        <td class="text-end fw-bold {{ $total > 0 ? 'text-danger' : '' }}">
                            {{ number_format($total, 2) }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (!$session->onlines->isEmpty())
        <h5 class="mt-4 mb-3">Students Online</h5>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    @if ($selected_type == \App\Enums\ReportType::ALL || $selected_type == \App\Enums\ReportType::STUDENT)
                        <th class="text-end">Price</th>
                        <th class="text-end">Materials</th>
                    @endif
                    @if ($selected_type == \App\Enums\ReportType::PROFESSOR)
                        <th class="text-end">Materials</th>
                        <th class="text-end">Professor Price</th>
                    @endif
                    @if ($selected_type == \App\Enums\ReportType::CENTER)
                        <th class="text-end">Center Price</th>
                    @endif
                    <th>Stage</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($session->onlines as $online)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $online->name }}</td>
                        @if ($selected_type == \App\Enums\ReportType::ALL || $selected_type == \App\Enums\ReportType::STUDENT)
                            <td class="text-end">
                                {{ number_format($online->professor + $online->center, 2) }}</td>
                            <td class="text-end">{{ number_format($online->materials, 2) }}</td>
                        @endif
                        @if ($selected_type == \App\Enums\ReportType::PROFESSOR)
                            <td class="text-end">{{ number_format($online->materials, 2) }}</td>
                            <td class="text-end">{{ number_format($online->professor, 2) }}</td>
                        @endif
                        @if ($selected_type == \App\Enums\ReportType::CENTER)
                            <td class="text-end">{{ number_format($online->center, 2) }}</td>
                        @endif
                        <td>{{ App\Enums\StagesEnum::getStringValue($online->stage) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Settlements --}}
    @if ($settlements->isNotEmpty())
        <h5 class="mt-4 mb-3">Settlements</h5>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th class="text-end">Amount</th>
                    @if ($selected_type != \App\Enums\ReportType::PROFESSOR)
                        <th class="text-end">Center</th>
                        <th class="text-end">Printables</th>
                    @endif
                    @if ($selected_type != \App\Enums\ReportType::CENTER)
                        <th class="text-end">Professor</th>
                        <th class="text-end">Materials</th>
                    @endif
                    <th>Description</th>
                    <th>Settled At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settlements as $settlement)
                    @php
                        $amount = match ((int) $selected_type) {
                            \App\Enums\ReportType::PROFESSOR => $settlement->professor_amount + $settlement->materials,
                            \App\Enums\ReportType::CENTER => $settlement->center + $settlement->printables,
                            default => $settlement->amount,
                        };
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $settlement->student->name ?? 'N/A' }}</td>
                        <td class="text-end">{{ number_format($amount, 2) }}</td>
                        @if ($selected_type != \App\Enums\ReportType::PROFESSOR)
                            <td class="text-end">{{ number_format($settlement->center, 2) }}</td>
                            <td class="text-end">{{ number_format($settlement->printables, 2) }}</td>
                        @endif
                        @if ($selected_type != \App\Enums\ReportType::CENTER)
                            <td class="text-end">{{ number_format($settlement->professor_amount, 2) }}</td>
                            <td class="text-end">{{ number_format($settlement->materials, 2) }}</td>
                        @endif
                        <td>{{ $settlement->description ?? '-' }}</td>
                        <td>{{ $settlement->settled_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-info fw-bold">
                    @php
                        $total_amount = $settlements->sum(function ($settlement) use ($selected_type) {
                            return match ((int) $selected_type) {
                                \App\Enums\ReportType::PROFESSOR => $settlement->professor_amount +
                                    $settlement->materials,
                                \App\Enums\ReportType::CENTER => $settlement->center + $settlement->printables,
                                default => $settlement->amount,
                            };
                        });
                    @endphp
                    <th colspan="2">Totals</th>
                    <th class="text-end">{{ number_format($total_amount, 2) }}</th>
                    @if ($selected_type != \App\Enums\ReportType::PROFESSOR)
                        <th class="text-end">{{ number_format($settlementTotals['total_center'], 2) }}</th>
                        <th class="text-end">{{ number_format($settlementTotals['total_printables'], 2) }}
                        </th>
                    @endif
                    @if ($selected_type != \App\Enums\ReportType::CENTER)
                        <th class="text-end">{{ number_format($settlementTotals['total_professor'], 2) }}
                        </th>
                        <th class="text-end">{{ number_format($settlementTotals['total_materials'], 2) }}
                        </th>
                    @endif
                    <th colspan="3"></th>
                </tr>
            </tfoot>
        </table>
    @endif

    @if ($session->sessionExtra)
        @php $extra = $session->sessionExtra; @endphp
        <h5 class="mt-4 mb-3">Expenses</h5>
        <table class="expenses-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Markers</td>
                    <td class="text-end">
                        {{ $selected_type == App\Enums\ReportType::PROFESSOR ? ($extra->markers > 0 ? -number_format($extra->markers, 2) : 0) : number_format($extra->markers ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>Cafeterea</td>
                    <td class="text-end">
                        {{ $selected_type == App\Enums\ReportType::PROFESSOR ? ($extra->cafeterea > 0 ? -number_format($extra->cafeterea, 2) : 0) : number_format($extra->cafeterea ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>Prof papper</td>
                    <td class="text-end">
                        {{ $selected_type == App\Enums\ReportType::PROFESSOR ? ($extra->copies > 0 ? -number_format($extra->copies, 2) : 0) : number_format($extra->copies ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>Other Center </td>
                    <td class="text-end">
                        {{ $selected_type == App\Enums\ReportType::PROFESSOR ? ($extra->other > 0 ? -number_format($extra->other, 2) : 0) : number_format($extra->other ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>Other Print</td>
                    <td class="text-end">
                        {{ $selected_type == App\Enums\ReportType::PROFESSOR ? ($extra->other_print > 0 ? -number_format($extra->other_print, 2) : 0) : number_format($extra->other_print ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>Out Going</td>
                    <td class="text-end">
                        {{ $selected_type == App\Enums\ReportType::PROFESSOR ? -number_format($extra->out_going, 2) : number_format($extra->out_going ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>To Prof</td>
                    <td class="text-end">
                        {{ $selected_type == App\Enums\ReportType::PROFESSOR ? -number_format($extra->to_professor, 2) : number_format($extra->to_professor ?? 0, 2) }}
                    </td>
                </tr>
                @if ($extra->notes)
                    <tr>
                        <td colspan="2"><strong>Notes:</strong> {{ $extra->notes ?? 'N/A' }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif

    <h5 class="mt-4 mb-3">Session Totals</h5>
    <table class="totals-table">
        <tbody>
            <tr>
                <th>Total Students</th>
                <td class="text-end">{{ $attendedCount }}</td>
            </tr>
            @if (!$session->onlines->isEmpty())
                <tr>
                    <th>Total online</th>
                    <td class="text-end">
                        {{ $session->onlines->sum(function ($online) {
                            return $online->materials + $online->professor + $online->center;
                        }) }}
                    </td>
                </tr>
            @endif
            @if ($session->professor_price)
                <tr>
                    <th>Professor Fees</th>
                    <td class="text-end">{{ number_format($reports->sum('professor_price'), 2) }}</td>
                </tr>
            @endif
            @if ($session->professor->balance)
                <tr>
                    <th>Balance</th>
                    <td class="text-end">{{ number_format($session->professor->balance), 2 }}</td>
                </tr>
            @endif
            @if ($session->professor->materials_balance)
                <tr>
                    <th>Materials Balance (Prof)</th>
                    <td class="text-end">{{ number_format($session->professor->materials_balance), 2 }}</td>
                </tr>
            @endif
            @if ($session->materials)
                <tr>
                    <th>Materials</th>
                    <td class="text-end">{{ number_format($reports->sum('materials'), 2) }}</td>
                </tr>
            @endif
            @if ($session->center_price)
                <tr>
                    <th>Center Fees</th>
                    <td class="text-end">{{ number_format($reports->sum('center_price'), 2) }}</td>
                </tr>
            @endif
            <tr class="bg-light">
                <th>Total Session Value</th>
                <td class="text-end text-primary total-value">
                    @php
                        $total = $reports->sum(
                            fn($r) => $r->professor_price + $r->center_price + $r->printables + $r->materials,
                        );
                        $total +=
                            $selected_type == App\Enums\ReportType::PROFESSOR
                                ? $session->professor->balance
                                : -$session->professor->balance;
                        $total +=
                            $selected_type == App\Enums\ReportType::PROFESSOR
                                ? $session->professor->materials_balance
                                : -$session->professor->materials_balance;
                        if ($session->sessionExtra) {
                            $adjustment =
                                $extra->markers +
                                $extra->copies +
                                $extra->other +
                                $extra->cafeterea +
                                $extra->other_print +
                                $extra->to_professor +
                                $extra->out_going;
                            $total += $selected_type == App\Enums\ReportType::PROFESSOR ? -$adjustment : $adjustment;
                        }
                        if (!$session->onlines->isEmpty()) {
                            $onlines = $session->onlines;

                            $onlineTotal = $onlines->sum(function ($online) {
                                return $online->materials + $online->professor + $online->center;
                            });
                            $total += $onlineTotal;
                        }
                    @endphp
                    {{ number_format($total + $total_amount, 2) }}
                </td>
            </tr>
            @php

                $total = $reports->sum(function ($report) use ($selected_type) {
                    if (!isset($report->student->toPay)) {
                        return 0;
                    }

                    return $report->student->toPay->sum(function ($pay) use ($selected_type) {
                        if ($selected_type == App\Enums\ReportType::PROFESSOR) {
                            return $pay->to_pay + $pay->to_pay_materials;
                        } elseif ($selected_type == App\Enums\ReportType::CENTER) {
                            return $pay->to_pay_center + $pay->to_pay_print;
                        } else {
                            return $pay->to_pay_center + $pay->to_pay + $pay->to_pay_print + $pay->to_pay_materials;
                        }
                    });
                });
            @endphp
            <tr class="{{ $total > 0 ? 'bg-warning' : '' }}">
                <th> To Collect</th>
                <td class="text-end {{ $total > 0 ? 'text-danger' : '' }} total-value">
                    {{ number_format($total, 2) }}
                </td>
            </tr>

        </tbody>
    </table>
</body>

</html>
