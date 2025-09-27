<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Charges Report</title>
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
            width: 100% !important;
            table-layout: fixed;
            border-collapse: collapse;
            word-wrap: break-word;
        }

        th,
        td {
            font-size: 11px;
            padding: 4px;
            word-break: break-word;
        }

        thead {
            background: #2c6fbb;
            color: #fff;
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
            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjUwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxNTAiIGhlaWdodD0iNTAiIGZpbGw9IiMyYzZmYmIiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmaWxsPSJ3aGl0ZSI+TG9nbzwvdGV4dD48L3N2Zz4="
                alt="Logo">
            <h1>Charges Report</h1>
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
                <strong>TOTAL CHARGES</strong>
                <span>{{ count($charges) }}</span>
            </div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($charges as $index => $charge)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-start">{{ $charge->title }}</td>
                        <td>{{ number_format($charge->amount, 1) }}</td>
                        <td>{{ App\Enums\ChargeType::getStringValue($charge->type) }}</td>
                        <td>{{ $charge->created_at->format('d-m-Y') }}</td>
                        <td>{{ $charge->createdBy?->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">No charges found for the selected period</td>
                    </tr>
                @endforelse
            </tbody>
            @if (count($charges))
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-start">Total:</th>
                        <th>{{ number_format($total, 1) }}</th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            @endif
        </table>

        <!-- Summary Section -->
        @if (count($charges))
            <div class="summary-section">
                <div class="summary-card">
                    <div class="summary-row">
                        <div class="summary-label">Total Charges:</div>
                        <div class="summary-value text-primary final-total">
                            {{ number_format($total, 1) }} EGP
                        </div>
                    </div>
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
