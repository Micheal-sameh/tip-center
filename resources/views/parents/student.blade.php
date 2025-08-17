<!DOCTYPE html>
<html lang="en">

@php
    $logo = App\Models\Setting::where('name', 'logo')->first();
    $faviconUrl = $logo?->getFirstMediaUrl('app_logo');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report - {{ $reports?->first()?->student?->name ?? 'N/A' }}</title>
    <link rel="icon" href="{{ $faviconUrl }}" type="image/png">

    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3498db;
            flex-wrap: wrap;
            /* Allow wrapping on mobile */
            gap: 10px;
        }

        .logo {
            height: 60px;
            max-width: 200px;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
            flex: 1 1 auto;
        }

        .header h2 {
            color: #2c3e50;
            margin: 0 0 5px 0;
            font-size: 24px;
        }

        .header p {
            margin: 0;
            color: #7f8c8d;
            font-size: 14px;
        }

        /* Student info */
        .student-info {
            margin-bottom: 20px;
            font-size: 14px;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
            /* Mobile horizontal scroll */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
            min-width: 600px;
            /* Keeps layout from breaking */
        }

        th {
            background-color: #3498db;
            color: white;
            text-align: left;
            padding: 12px 8px;
            font-weight: 600;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .total-row {
            font-weight: bold;
            background-color: #e8f4fc !important;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .date {
            white-space: nowrap;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        /* Mobile adjustments */
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }

            .header h2 {
                font-size: 18px;
            }

            .header p {
                font-size: 12px;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 8px 5px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        {{-- Logo --}}

        <img src="{{ $faviconUrl }}" class="logo" alt="Company Logo">

        <div class="header-text">
            <h2>Student Session Report</h2>
            <p>Detailed session history and payments</p>
        </div>

        <div style="width: 200px;"></div>
    </div>

    <div class="student-info">
        <strong>{{ $reports?->first()?->student?->name ?? 'N/A' }}</strong>
        (Code: {{ $reports?->first()?->student?->code ?? 'N/A' }}) |
        Report Date: {{ now()->format('d M Y') }} |
        Total Sessions: {{ count($reports) }}
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Session Date</th>
                    <th>Professor</th>
                    <th class="text-center">Attend Time</th>
                    <th>Amount Paid</th>
                    @if ($reports->contains(fn($r) => $r->to_pay > 0))
                        <th>To Pay</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="date">{{ \Carbon\Carbon::parse($report->session->created_at)->format('d M Y') }}
                        </td>
                        <td>{{ $report->session->professor->name ?? 'N/A' }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($report->created_at)->format('h:i A') }}</td>
                        <td class="fw-bold">
                            {{ number_format($report->professor_price + $report->center_price + $report->printables + $report->materials, 2) }}
                        </td>
                        @if ($reports->contains(fn($r) => $r->to_pay > 0))
                            <td class="text-center">{{ $report->to_pay ?? 'N/A' }}</td>
                        @endif
                    </tr>
                @endforeach

                @if (count($reports) > 0)
                    <tr class="total-row">
                        <td colspan="4" class="text-right"><strong>Total Amount:</strong></td>
                        <td class="text-right">
                            <strong>
                                {{ number_format(
                                    $reports->sum(function ($report) {
                                        return $report->professor_price + $report->center_price + $report->printables + $report->materials;
                                    }),
                                    2,
                                ) }}
                            </strong>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if (count($reports) === 0)
        <div style="text-align: center; padding: 40px; color: #7f8c8d;">
            <p>No session records found for this student.</p>
        </div>
    @endif

    <div class="footer">
        Generated on {{ now()->format('d M Y H:i') }} | &copy; {{ date('Y') }} {{ config('app.name') }}
    </div>
</body>

</html>
