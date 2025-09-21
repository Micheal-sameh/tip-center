@extends('layouts.sideBar')

@section('content')
    <div class="container-fluid px-4 mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-gradient m-0 d-flex align-items-center">
                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                Monthly Report -
                <span class="ms-1 text-primary">
                    {{ \Carbon\Carbon::parse($month)->format('F Y') }}
                </span>
            </h4>

            <!-- Month Search -->
            <form action="{{ route('reports.monthly-income') }}" method="GET" class="d-flex align-items-center">
                <input type="month" name="month" class="form-control me-2 shadow-sm rounded-pill px-3"
                    value="{{ request('month', now()->format('Y-m')) }}" onchange="this.form.submit()">
                <noscript>
                    <button type="submit" class="btn btn-primary rounded-pill px-3">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </noscript>
            </form>
        </div>

        <!-- Table Card -->
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-gradient text-white rounded-top-4"
                style="background: linear-gradient(45deg, #007bff, #6610f2);">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-chart-line me-2"></i> Detailed Income & Expenses</h5>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th>Day</th>
                            <th>Center</th>
                            <th>Other Center</th>
                            <th>Exp Charges</th>
                            <th>Net Center</th>
                            <th>NET</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td class="fw-semibold">{{ \Carbon\Carbon::parse($report->day)->format('d') }}</td>
                                <td>{{ number_format($report->center_income, 2) }}</td>
                                <td>{{ number_format($report->other, 2) }}</td>
                                <td>{{ number_format($report->charges_ten_eleven, 2) }}</td>
                                <td>{{ number_format($report->center_income + $report->other, 2) }}</td>
                                <td class="{{ $report->net >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ number_format($report->net, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary fw-bold sticky-bottom">
                        <tr>
                            <td>Total</td>
                            <td>{{ number_format($totals['center'], 2) }}</td>
                            <td>{{ number_format($totals['other_center'], 2) }}</td>
                            <td>{{ number_format($totals['charges'], 2) }}</td>
                            <td class="text-danger">{{ number_format($totals['charges'], 2) }}</td>
                            <td class="{{ $totals['overall_total'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($totals['overall_total'], 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
