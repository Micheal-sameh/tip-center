<!DOCTYPE html>
<html>

<head>
    <title>{{ $session->professor->name }} - {{ $session->created_at->format('Y-m-d') }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            color: #2d3748;
            line-height: 1.5;
            padding: 25px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .logo {
            height: 80px;
            max-width: 220px;
            object-fit: contain;
        }

        .header-content {
            flex-grow: 1;
            text-align: center;
            padding: 0 20px;
        }

        .header h1 {
            color: #1a365d;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .header p {
            margin: 5px 0 0;
            color: #718096;
            font-size: 15px;
        }

        /* Professor and Stage side by side */
        .info-row {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-card {
            flex: 1;
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .info-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #1a202c;
            font-weight: 500;
        }

        .section-title {
            color: #2d3748;
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .students-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 15px;
            font-size: 14px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .students-table th {
            background-color: #2b6cb0;
            color: white;
            padding: 14px 12px;
            text-align: left;
            font-weight: 600;
        }

        .students-table td {
            padding: 12px;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
        }

        .students-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .unpaid-row {
            background-color: #fffaf0 !important;
        }

        .text-right {
            text-align: right;
        }

        .total-box {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            /* Remove flex-wrap or set to nowrap */
            flex-wrap: nowrap;
            /* Allow horizontal scrolling if needed */
            overflow-x: auto;
            padding-bottom: 10px;
            /* Space for scrollbar */
        }

        .total-item {
            /* Remove max-width constraint */
            flex: 1;
            min-width: 180px;
            /* Set a reasonable minimum width */
            background: #ebf8ff;
            padding: 18px 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            /* Prevent text from wrapping inside items */
            white-space: nowrap;
        }

        /* For PDF/printing */
        @media print {
            .total-box {
                flex-wrap: nowrap;
                overflow-x: visible;
            }

            .total-item {
                flex: 1;
            }
        }

        .total-item-title {
            font-size: 14px;
            color: #4a5568;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .total-value {
            font-size: 20px;
            font-weight: 700;
            color: #2b6cb0;
        }

        .highlight-total {
            background: #bee3f8;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 13px;
            color: #718096;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }

        .money {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .warning-text {
            color: #c05621;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="header">
        @php
            $logoSetting = App\Models\Setting::where('name', 'logo')->first();
            $logoUrl = $logoSetting?->getFirstMediaPath('app_logo');
        @endphp

        @if ($logoUrl)
            <img src="{{ $logoUrl }}" alt="App Logo" class="img-fluid mb-3 pt-3" style="max-height: 100px;">
        @else
            <p class="text-muted">No logo available</p>
        @endif
        <div class="header-content">
            <h1>{{ $session->professor->name }} - {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</h1>
            <p>{{ $session->created_at->format('l, F j, Y') }} • {{ $session->created_at->format('h:i A') }}</p>
        </div>
        <div style="width: 80px;"></div>
    </div>

    {{-- <h3 class="section-title">Students Attendance</h3> --}}
    <table class="students-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Phone</th>
                <th>Phone (P)</th>
                @if ($session->materials)
                    <th>Materials</th>
                @endif
                @if ($session->printables)
                    <th>Printables</th>
                @endif
                <th class="text-right">Payment</th>
                <th class="text-right">To Pay</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr class="{{ $report->to_pay > 0 ? 'unpaid-row' : '' }}">
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
                    <td class="text-right money">
                        {{ number_format($report->professor_price + $report->center_price, 2) }}
                    </td>
                    <td class="text-right money {{ $report->to_pay > 0 ? 'warning-text' : '' }}">
                        {{ number_format($report->to_pay, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-item">
            <div class="total-item-title">Total Students</div>
            <div class="total-value">{{ $reports->count() }}</div>
        </div>
        @if ($session->professor_price)
            <div class="total-item">
                <div class="total-item-title">Professor Earnings</div>
                <div class="total-value money">{{ number_format($reports->sum('professor_price'), 2) }}</div>
            </div>
        @endif
        @if ($session->center_price)
            <div class="total-item">
                <div class="total-item-title">Center Earnings</div>
                <div class="total-value money">{{ number_format($reports->sum('center_price'), 2) }}</div>
            </div>
        @endif
        @if ($session->materials)
            <div class="total-item">
                <div class="total-item-title">Material</div>
                <div class="total-value money">{{ number_format($reports->sum('materials'), 2) }}</div>
            </div>
        @endif
        <div class="total-item highlight-total">
            <div class="total-item-title">Total Session Value</div>
            <div class="total-value money">
                {{ number_format($reports->sum(function ($r) {return $r->professor_price + $r->center_price + $r->printables + $r->materials;}),2) }}
            </div>
        </div>
        <div class="total-item {{ $reports->sum('to_pay') > 0 ? 'unpaid-row' : '' }}">
            <div class="total-item-title">Amount to Collect</div>
            <div class="total-value money {{ $reports->sum('to_pay') > 0 ? 'warning-text' : '' }}">
                {{ number_format($reports->sum('to_pay'), 2) }}
            </div>
        </div>
    </div>

    <div class="footer">
        Generated on {{ now()->format('M j, Y \a\t h:i A') }} | &copy; {{ date('Y') }} {{ config('app.name') }}
    </div>
</body>

</html>
