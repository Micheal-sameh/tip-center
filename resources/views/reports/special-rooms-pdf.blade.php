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
                <th>Stage</th>
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
                    <td>{{ App\Enums\StagesEnum::getStringValue($session->stage) }}</td>
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
                    <td colspan="3" align="right">Totals</td>
                    <td>{{ $sessions->count() }}</td>
                    <td>{{ $totals['paid_students'] }}</td>
                    <td>{{ number_format($totals['center_price'], 1) }}</td>
                    <td>{{ $totals['attended_count'] }}</td>
                </tr>
            </tfoot>
        @endif
    </table>
</body>

</html>
