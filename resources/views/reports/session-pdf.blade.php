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
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }

        .header-text {
            flex: 1;
            text-align: center;
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
        <!-- Replace with your actual logo path -->
        <img src="{{ $faviconUrl }}" class="logo" alt="Company Logo">
        <div class="header-text">
            <h1> {{ $session->professor->name }} - {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</h1>
        </div>
    </div>
    <h1>Session Report - {{ $session->created_at->format('Y-m-d') }}</h1>
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
                <th>Phone</th>
                <th>Phone (P)</th>
                @if ($session->materials)
                    <th>Materials</th>
                @endif
                @if ($session->printables)
                    <th>Printables</th>
                @endif
                <th class="text-end">Payment</th>
                @if ($reports->contains(fn($r) => $r->to_pay > 0))
                    <th class="text-end">To Pay</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr class="{{ $report->to_pay > 0 ? 'table-warning' : '' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $report->student->name }}</td>
                    <td>{{ $report->student->phone }}</td>
                    <td>{{ $report->student->parent_phone }}</td>
                    @if ($session->materials)
                        <td>{{ $report->materials }}</td>
                    @endif
                    @if ($session->printables)
                        <td class="text-end">{{ $report->printables }}</td>
                    @endif
                    <td class="text-end">
                        {{ number_format($report->professor_price + $report->center_price, 2) }}
                    </td>
                    @if ($report->to_pay)
                        <td class="text-end fw-bold {{ $report->to_pay > 0 ? 'text-danger' : '' }}">
                            {{ number_format($report->to_pay, 2) }}
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
                        @if ($selected_type == App\Enums\ReportType::PROFESSOR)
                            {{ $extra->markers > 0 ? -number_format($extra->markers, 2) : 0 }}
                        @else
                            {{ number_format($extra->markers ?? 0, 2) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Cafeterea</td>
                    <td class="text-end">
                        @if ($selected_type == App\Enums\ReportType::PROFESSOR)
                            {{ $extra->cafeterea > 0 ? -number_format($extra->cafeterea, 2) : 0 }}
                        @else
                            {{ number_format($extra->cafeterea ?? 0, 2) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Copies</td>
                    <td class="text-end">
                        @if ($selected_type == App\Enums\ReportType::PROFESSOR)
                            {{ $extra->copies > 0 ? -number_format($extra->copies, 2) : 0 }}
                        @else
                            {{ number_format($extra->copies ?? 0, 2) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Other</td>
                    <td class="text-end">
                        @if ($selected_type == App\Enums\ReportType::PROFESSOR)
                            {{ $extra->other > 0 ? -number_format($extra->other, 2) : 0 }}
                        @else
                            {{ number_format($extra->other ?? 0, 2) }}
                        @endif
                    </td>
                </tr>
                @if ($session->sessionExtra->notes)
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
                <td class="text-end">{{ $reports->count() }}</td>
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
                    {{ number_format($total, 2) }}
                </td>
            </tr>
            <tr class="{{ $reports->sum('to_pay') > 0 ? 'bg-warning' : '' }}">
                <th>Amount To Collect</th>
                <td class="text-end {{ $reports->sum('to_pay') > 0 ? 'text-danger' : '' }} total-value">
                    {{ number_format($reports->sum('to_pay'), 2) }}
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
