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
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }

        .header img {
            height: 50px;
            vertical-align: middle;
            margin-right: 10px;
        }

        .header h1 {
            display: inline-block;
            font-size: 20px;
            color: #2c6fbb;
            margin: 0;
            vertical-align: middle;
        }

        .report-info {
            width: 100%;
            margin: 15px 0;
            border: 1px solid #dee2e6;
            background: #e9f2fb;
            padding: 10px;
        }

        .info-item {
            display: inline-block;
            width: 32%;
            vertical-align: top;
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

        .summary {
            margin-top: 15px;
            text-align: right;
        }

        .total-box {
            display: inline-block;
            border: 1px solid #2c6fbb;
            background: #e9f2fb;
            padding: 10px 15px;
            text-align: right;
        }

        .total-label {
            font-size: 12px;
            color: #6c757d;
        }

        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #2c6fbb;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="report-container">
        {{-- <!-- Header -->
        @php
            $logo = App\Models\Setting::where('name', 'logo')->first();
            $faviconUrl = $logo?->getFirstMediaUrl('app_logo');
        @endphp --}}
        <div class="header">

            {{-- <img src="{{ $faviconUrl }}" alt="Logo"> --}}
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
                    <th>LE</th>
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
                        <td>{{ $session->session_students_count ?: '-' }}</td>
                        <td>{{ $session->total_center_price > 0 ? number_format($session->total_center_price, 1) : '-' }}
                        </td>
                        <td>{{ $session->total_printables > 0 ? number_format($session->total_printables, 1) : '-' }}
                        </td>
                        <td>{{ $session->total_materials > 0 ? number_format($session->total_materials, 1) : '-' }}
                        </td>
                        <td>{{ $session->sessionExtra?->copies > 0 ? number_format($session->sessionExtra?->copies, 1) : '-' }}
                        </td>
                        <td>{{ number_format(0, 1) }}</td>
                        <td>{{ $session->sessionExtra?->markers > 0 ? number_format($session->sessionExtra?->markers, 1) : '-' }}
                        </td>
                        <td>{{ number_format(0, 1) }}</td>
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
                        <td colspan="11">No sessions found for the selected period</td>
                    </tr>
                @endforelse
            </tbody>
            @if (count($sessions))
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-start">Totals:</th>
                        <th>{{ $totals['students'] }}</th>
                        <th>{{ number_format($totals['center_price'], 1) }}</th>
                        <th>{{ number_format($totals['printables'], 1) }}</th>
                        <th>{{ number_format($totals['materials'], 1) }}</th>
                        <th>{{ number_format($totals['copies'] ?? 0, 1) }}</th>
                        <th>{{ number_format(0, 1) }}</th>
                        <th>{{ number_format($totals['markers'] ?? 0, 1) }}</th>
                        <th>{{ number_format(0, 1) }}</th>
                        <th class="text-primary">{{ number_format($totals['overall_total'], 1) }}</th>
                    </tr>
                </tfoot>
            @endif
        </table>

        @if (count($sessions))
            <div class="summary">
                <div class="total-box">
                    <div class="total-label">Grand Total</div>
                    <div class="total-amount">{{ number_format($totals['overall_total'], 1) }} EGP</div>
                </div>
            </div>
        @endif

        <div class="footer">
            <p>© {{ date('Y') }} Tip. All rights reserved.</p>
            <p>This report is generated automatically. For any inquiries, please contact administration.</p>
        </div>
    </div>
</body>

</html>
