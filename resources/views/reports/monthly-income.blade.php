@extends('layouts.sideBar')

@section('content')
    <div class="container-fluid px-4 mt-4" style="width:93%">

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
                            <th>C</th>
                            <th>Co</th>
                            <th>M</th>
                            <th>Gap</th>
                            <th class="text-success">Income Total</th>
                            <th>Ex C</th>
                            <th>Ex Co</th>
                            <th>Ex M</th>
                            <th>Ex Others</th>
                            <th class="text-danger">Ex Total</th>
                            <th>Net C</th>
                            <th>Net Co</th>
                            <th>Net M</th>
                            <th>Net Other</th>
                            <th>Difference</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td class="fw-semibold">{{ \Carbon\Carbon::parse($report->day)->format('d M Y') }}</td>
                                <td>{{ number_format($report->center, 2) }}</td>
                                <td>{{ number_format($report->copies, 2) }}</td>
                                <td>{{ number_format($report->markers, 2) }}</td>
                                <td>{{ number_format($report->charges_gap, 2) }}</td>
                                <td class="fw-bold text-success">{{ number_format($report->income_total, 2) }}</td>
                                <td>{{ number_format($report->charges_center, 2) }}</td>
                                <td>{{ number_format($report->charges_copies, 2) }}</td>
                                <td>{{ number_format($report->charges_markers, 2) }}</td>
                                <td>{{ number_format($report->charges_others, 2) }}</td>
                                <td class="fw-bold text-danger">{{ number_format($report->charges_total, 2) }}</td>
                                <td>{{ number_format($report->net_center, 2) }}</td>
                                <td>{{ number_format($report->net_copies, 2) }}</td>
                                <td>{{ number_format($report->net_markers, 2) }}</td>
                                <td>{{ number_format($report->net_others, 2) }}</td>
                                <td class="{{ $report->difference_total >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ number_format($report->difference_total, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary fw-bold sticky-bottom">
                        <tr>
                            <td>Total</td>
                            <td>{{ number_format($totals['center'], 2) }}</td>
                            <td>{{ number_format($totals['copies'], 2) }}</td>
                            <td>{{ number_format($totals['markers'], 2) }}</td>
                            <td>{{ number_format($totals['gap'], 2) }}</td>
                            <td class="text-success">{{ number_format($totals['total_income'], 2) }}</td>
                            <td>{{ number_format($totals['charges_center'], 2) }}</td>
                            <td>{{ number_format($totals['charges_copies'], 2) }}</td>
                            <td>{{ number_format($totals['charges_markers'], 2) }}</td>
                            <td>{{ number_format($totals['charges_others'], 2) }}</td>
                            <td class="text-danger">{{ number_format($totals['total_charges'], 2) }}</td>
                            <td>{{ number_format($totals['net_center'], 2) }}</td>
                            <td>{{ number_format($totals['net_copies'], 2) }}</td>
                            <td>{{ number_format($totals['net_markers'], 2) }}</td>
                            <td>{{ number_format($totals['net_others'], 2) }}</td>
                            <td class="{{ $totals['total_difference'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($totals['total_difference'], 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
