@extends('layouts.sideBar')

@section('content')
    <div class="container-fluid py-3" style="max-width:1400px">

        {{-- Page Header --}}
        <div class="tc-page-header mb-4">
            <div>
                <h1 class="tc-page-title"><i class="fas fa-calendar-alt me-2 text-brand"></i>Monthly Income Report
                    &mdash; {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h1>
            </div>
            <form action="{{ route('reports.monthly-income') }}" method="GET" class="d-flex align-items-center gap-2">
                <input type="month" name="month" class="form-control form-control-sm"
                    value="{{ request('month', now()->format('Y-m')) }}" onchange="this.form.submit()">
            </form>
        </div>

        <!-- Table Card -->
        <div class="tc-table-wrap mb-4">
            <div class="tc-data-bar">
                <span><i class="fas fa-chart-line me-2"></i>Detailed Income &amp; Expenses</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0 text-center">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Center</th>
                            <th>Copies</th>
                            <th>Markers</th>
                            <th>Gap</th>
                            <th class="text-success">Total Income</th>
                            <th>Exp Centre</th>
                            <th>Exp Copies</th>
                            <th>Exp Markers</th>
                            <th>Exp Others</th>
                            <th class="text-danger">Ex Total</th>
                            <th>Total Centre</th>
                            <th>Total Copies</th>
                            <th>Total Markers</th>
                            <th>Total Other</th>
                            <th>Total Gap</th>
                            <th>NET</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td class="fw-semibold">{{ \Carbon\Carbon::parse($report->day)->format('d') }}</td>
                                <td>{{ number_format($report->center + $report->other_center + $report->online_center, 2) }}
                                </td>
                                <td>{{ number_format($report->copies + $report->print + $report->other_print + $report->charges_student_print, 2) }}</td>
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
                                <td>{{ number_format($report->charges_gap, 2) }}</td>
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
                            <td>{{ number_format($totals['gap'], 2) }}</td>
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
