<!DOCTYPE html>
<html>
<head>
    <title>{{ $reports?->first()?->student->name }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">{{ $reports?->first()?->student->name }}</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Session</th>
                <th>Professor</th>
                <th>Attend</th>
                <th>Paid</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->session->created_at)->format('d-m-Y') }}</td>
                    <td>{{ $report->session->professor->name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d-m-Y') }}</td>
                    <td>{{ $report->professor_price + $report->center_price + $report->printables }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
