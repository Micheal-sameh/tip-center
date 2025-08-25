<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sessions Income Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .report-container {
            width: 100%;
            background: #fff;
            border: 1px solid #dee2e6;
            padding: 20px;
            box-sizing: border-box;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .header img {
            height: 50px;
            margin-right: 10px;
        }

        .header h1 {
            font-size: 20px;
            color: #2c6fbb;
            margin: 0;
        }

        .report-info {
            width: 100%;
            margin: 15px 0;
            border: 1px solid #dee2e6;
            background: #e9f2fb;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
        }

        .info-item {
            flex: 1;
        }

        .info-item strong {
            font-size: 11px;
            color: #6c757d;
            display: block;
        }

        .info-item span {
            font-size: 13px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        thead {
            background: #2c6fbb;
            color: #fff;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 6px;
            font-size: 11px;
            text-align: center;
        }

        td.text-start,
        th.text-start {
            text-align: left;
        }

        .text-primary {
            color: #2c6fbb;
            font-weight: bold;
        }

        tfoot th {
            background: #343a40;
            color: #fff;
            font-weight: bold;
        }

        .summary-section {
            width: 100%;
            margin-top: 20px;
        }

        .summary-card {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            width: 50%;
            margin-right: auto;
        }

        .summary-row {
            display: flex;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #dee2e6;
        }

        .summary-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
            font-size: 14px;
        }

        .summary-label {
            flex: 1;
            text-align: left;
            font-weight: bold;
            padding-right: 15px;
            color: #495057;
        }

        .summary-value {
            flex: 1;
            font-weight: bold;
            text-align: left;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-success {
            color: #28a745;
        }

        .summary {
            margin-top: 20px;
            text-align: right;
        }

        .total-box {
            display: inline-block;
            border: 2px solid #2c6fbb;
            background: #e9f2fb;
            padding: 12px 20px;
            text-align: right;
            border-radius: 6px;
        }

        .total-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .total-amount {
            font-size: 20px;
            font-weight: bold;
            color: #2c6fbb;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }

        .final-total {
            background-color: #e9f2fb;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="report-container">
        <div class="header">
            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjUwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxNTAiIGhlaWdodD0iNTAiIGZpbGw9IiMyYzZmYmIiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmaWxsPSJ3aGl0ZSI+TG9nbzwvdGV4dD48L3N2Zz4=" alt="Logo">
            <h1>Sessions Income Report</h1>
        </div>

        <!-- Report Info -->
        <div class="report-info">
            <div class="info-item">
                <strong>GENERATED ON</strong>
                <span>{{ now()->format('F j, Y') }}</span>
            </div>
            <div class="info-item">
                <strong>PERIOD</strong>
                <span>{{ $date_from->format('d-m-Y') }} - {{ $date_to->format('d-m-Y') }}</span>
            </div>
            <div class="info-item">
                <strong>TOTAL SESSIONS</strong>
                <span>{{ count($sessions) }}</span>
            </div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-start">Professor</th>
                    <th>NA</th>
                    <th>C</th>
                    <th>FP</th>
                    <th>LP</th>
                    <th>FE</th>
                    <th>M</th>
                    <th>NP</th>
                    <th>Session Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sessions as $index => $session)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-start">
                            {{ $session->professor->name ?? '-' }}
                            ({{ App\Enums\StagesEnum::getStringValue($session->stage) }})
                        </td>
                        <td>{{ $session->attended_count > 0 ? $session->attended_count : '-' }}</td>
                        <td>{{ $session->total_center_price > 0 ? number_format($session->total_center_price, 1) : '-' }}
                        </td>
                        <td>{{ $session->total_printables > 0 ? number_format($session->total_printables, 1) : '-' }}
                        </td>
                        <td>{{ $session->total_materials > 0 ? number_format($session->total_materials, 1) : '-' }}
                        </td>
                        <td>{{ $session->sessionExtra?->copies > 0 ? number_format($session->sessionExtra?->copies, 1) : '-' }}
                        </td>
                        <td>{{ $session->sessionExtra?->markers > 0 ? number_format($session->sessionExtra?->markers, 1) : '-' }}
                        </td>
                        <td>{{ $session->session_students_count > 0 ? $session->session_students_count : '-' }}</td>
                        <td class="text-primary">
                            {{ number_format(
                                $session->total_center_price +
                                    $session->total_professor_price +
                                    $session->total_materials +
                                    $session->total_printables +
                                    ($session->sessionExtra?->markers ?? 0) +
                                    ($session->sessionExtra?->copies ?? 0),
                                1,
                            ) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center;">No sessions found for the selected period</td>
                    </tr>
                @endforelse
            </tbody>
            @if (count($sessions))
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-start">Totals:</th>
                        <th>{{ $totals['attended_count'] }}</th>
                        <th>{{ number_format($totals['center_price'], 1) }}</th>
                        <th>{{ number_format($totals['printables'], 1) }}</th>
                        <th>{{ number_format($totals['materials'], 1) }}</th>
                        <th>{{ number_format($totals['copies'] ?? 0, 1) }}</th>
                        <th>{{ number_format($totals['markers'] ?? 0, 1) }}</th>
                        <th>{{ $totals['students'] }}</th>
                        <th class="text-primary">{{ number_format($totals['overall_total'], 1) }}</th>
                    </tr>
                </tfoot>
            @endif
        </table>

        <!-- Summary Section -->
        @if (count($sessions))
            <div class="summary-section">
                <div class="summary-card">
                    <div class="summary-row">
                        <div class="summary-label">Gap:</div>
                        <div class="summary-value text-danger">
                            {{ number_format($gap ?? 0, 1) }} EGP
                        </div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">Charges Total:</div>
                        <div class="summary-value text-danger">
                            -{{ number_format($charges ?? 0, 1) }} EGP
                        </div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">Final Total:</div>
                        <div class="summary-value text-success final-total">
                            {{ number_format(($totals['overall_total'] - $charges + $gap ?? 0) - ($totals['charges_total'] ?? 0), 1) }} EGP
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="summary">
                <div class="total-box">
                    <div class="total-label">Grand Total</div>
                    <div class="total-amount"> {{ number_format(($totals['overall_total'] - $charges + $gap ?? 0) - ($totals['charges_total'] ?? 0), 1) }} EGP</div>
                </div>
            </div> --}}
        @endif

        <div class="footer">
            <p>© {{ date('Y') }} Tip. All rights reserved.</p>
            <p>This report is generated automatically. For any inquiries, please contact administration.</p>
        </div>
    </div>
</body>

</html>