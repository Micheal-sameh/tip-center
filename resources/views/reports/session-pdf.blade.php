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
                <th>Printables</th>
                <th class="text-end">To Pay</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr
                    class="{{ $report->is_attend == App\Enums\AttendenceType::ABSENT ? 'table-danger' : ($report->to_pay + $report->to_pay_center > 0 ? 'table-warning' : '') }}">
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
                                return $pay->to_pay;
                            } elseif ($selected_type == App\Enums\ReportType::CENTER) {
                                return $pay->to_pay_center;
                            } else {
                                return $pay->to_pay_center + $pay->to_pay;
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
                    <td>Copies</td>
                    <td class="text-end">
                        {{ $selected_type == App\Enums\ReportType::PROFESSOR ? ($extra->copies > 0 ? -number_format($extra->copies, 2) : 0) : number_format($extra->copies ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>Other</td>
                    <td class="text-end">
                        {{ $selected_type == App\Enums\ReportType::PROFESSOR ? ($extra->other > 0 ? -number_format($extra->other, 2) : 0) : number_format($extra->other ?? 0, 2) }}
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
                <td class="text-end">{{ $reports->where('is_attend', 1)->count() }}</td>
            </tr>
            @if ($session->professor_price)
                <tr>
                    <th>Professor Fees</th>
                    <td class="text-end">{{ number_format($reports->sum('professor_price'), 2) }}</td>
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
                        if ($session->sessionExtra) {
                            $adjustment = $extra->markers + $extra->copies + $extra->other + $extra->cafeterea;
                            $total += $selected_type == App\Enums\ReportType::PROFESSOR ? -$adjustment : $adjustment;
                        }
                    @endphp
                    {{ number_format($total + $session->professor->balance, 2) }}
                </td>
            </tr>
            @php

                $total = $reports->sum(function ($report) use ($selected_type) {
                    if (!isset($report->student->toPay)) {
                        return 0;
                    }

                    return $report->student->toPay->sum(function ($pay) use ($selected_type) {
                        if ($selected_type == App\Enums\ReportType::PROFESSOR) {
                            return $pay->to_pay;
                        } elseif ($selected_type == App\Enums\ReportType::CENTER) {
                            return $pay->to_pay_center;
                        } else {
                            return $pay->to_pay_center + $pay->to_pay;
                        }
                    });
                });
            @endphp
            <tr class="{{ $total > 0 ? 'bg-warning' : '' }}">
                <th> To Collect</th>
                <td
                    class="text-end {{ $total > 0 ? 'text-danger' : '' }} total-value">
                    {{ number_format($total, 2) }}
                </td>
            </tr>

        </tbody>
    </table>
</body>

</html>
