<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Room 10 & 11 Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #f0f0f0;
        }

        .totals {
            font-weight: bold;
            background: #ddd;
        }

        h3 {
            margin-bottom: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
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
        .summary {
            margin-top: 20px;
            text-align: right;
        }

    </style>
</head>

<body>
    <div class="header">
        <h3>Room 10 & 11 Report</h3>
        <small>Generated at {{ now()->format('d-m-Y H:i') }}</small>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Professor</th>
                <th>Session Date</th>
                <th>Students Attends</th>
                <th>Center</th>
                <th>Students Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $index => $session)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $session->professor->name ?? '-' }} -
                        {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</td>
                    <td>{{ $session->created_at->format('d-m-Y') }}</td>
                    <td>{{ $session->total_paid_students > 0 ? $session->total_paid_students : '-' }}</td>
                    <td>{{ $session->center > 0 ? number_format($session->center, 1) : '-' }}</td>
                    <td>{{ $session->session_students_count > 0 ? $session->session_students_count : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No sessions found</td>
                </tr>
            @endforelse
        </tbody>
        @if (count($sessions))
            <tfoot>
                <tr class="totals">
                    <td colspan="2" align="right">Totals</td>
                    <td>{{ $sessions->count() }}</td>
                    <td>{{ $totals['paid_students'] }}</td>
                    <td>{{ number_format($totals['center_price'], 1) }}</td>
                    <td>{{ $totals['attended_count'] }}</td>
                </tr>
            </tfoot>
        @endif
    </table>
    @if (count($sessions))
        <div class="summary-section">
            <div class="summary-card">
                <div class="summary-row">
                    <div class="summary-label">Charges Total:</div>
                    <div class="summary-value text-danger">
                        -{{ number_format($charges ?? 0, 1) }} EGP
                    </div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">Students Settle:</div>
                    <div class="summary-value text-danger">
                        {{ number_format($settle ?? 0, 1) }} EGP
                    </div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">Final Total:</div>
                    <div class="summary-value text-success final-total">
                        {{ number_format($totals['overall_total'], 1) }}
                        EGP
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
</body>

</html>
