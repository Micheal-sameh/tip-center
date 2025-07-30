<!DOCTYPE html>
<html>
<head>
    <title>Student Report - {{ $reports?->first()?->student->name ?? 'N/A' }}</title>
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
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3498db;
        }
        .logo {
            height: 60px;
            max-width: 200px;
            object-fit: contain;
        }
        .header-text {
            text-align: center;
            flex-grow: 1;
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
        .student-info {
            margin-bottom: 20px;
            font-size: 14px;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
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
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Company Logo -->
        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Company Logo">

        <div class="header-text">
            <h2>Student Session Report</h2>
            <p>Detailed session history and payments</p>
        </div>

        <!-- Optional: Empty space to balance layout -->
        <div style="width: 200px;"></div>
    </div>

    <div class="student-info">
        <strong>{{ $reports?->first()?->student->name ?? 'N/A' }}</strong>
        (Code: {{ $reports?->first()?->student->code ?? 'N/A' }}) |
        Report Date: {{ now()->format('d M Y') }} |
        Total Sessions: {{ count($reports) }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Session Date</th>
                <th>Professor</th>
                <th class="text-center">Attend Time</th>
                <th class="text-right">Amount Paid</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="date">{{ \Carbon\Carbon::parse($report->session->created_at)->format('d M Y') }}</td>
                    <td>{{ $report->session->professor->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($report->created_at)->format('h:i A') }}</td>
                    <td class="text-right">{{ number_format($report->professor_price + $report->center_price + $report->printables, 2) }}</td>
                </tr>
            @endforeach
            @if(count($reports) > 0)
                <tr class="total-row">
                    <td colspan="4" class="text-right"><strong>Total Amount:</strong></td>
                    <td class="text-right">
                        <strong>
                            {{ number_format($reports->sum(function($report) {
                                return $report->professor_price + $report->center_price + $report->printables;
                            }), 2) }}
                        </strong>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    @if(count($reports) === 0)
        <div style="text-align: center; padding: 40px; color: #7f8c8d;">
            <p>No session records found for this student.</p>
        </div>
    @endif

    <div class="footer">
        Generated on {{ now()->format('d M Y H:i') }} | &copy; {{ date('Y') }} {{ config('app.name') }}
    </div>
</body>
</html>