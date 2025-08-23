@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 1200px;">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Sessions Income Report</h4>
        </div>

        <!-- Filter Form -->
        <form id="filterForm" action="{{ route('reports.income') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="date_from" class="form-label">From</label>
                <input type="date" name="date_from" id="date_from" class="form-control"
                    value="{{ request('date_from') }}">
            </div>
            <div class="col-md-4">
                <label for="date_to" class="form-label">To</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 me-1">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('reports.incomePdf', request()->all()) }}" class="btn btn-success w-100" id="exportBtn">
                    <i class="fas fa-file-export me-1"></i> Export
                </a>
            </div>
        </form>

        @push('scripts')
            <script>
                document.getElementById('filterForm').addEventListener('submit', function() {
                    const dateFrom = this.querySelector('[name="date_from"]');
                    const dateTo = this.querySelector('[name="date_to"]');

                    if (dateFrom && !dateFrom.value) {
                        dateFrom.removeAttribute('name'); // not sent
                    }
                    if (dateTo && !dateTo.value) {
                        dateTo.removeAttribute('name'); // not sent
                    }
                });

                // Export button should also skip nulls
                document.getElementById('exportBtn').addEventListener('click', function(e) {
                    e.preventDefault();
                    const params = new URLSearchParams();

                    const dateFrom = document.querySelector('[name="date_from"]');
                    const dateTo = document.querySelector('[name="date_to"]');

                    if (dateFrom && dateFrom.value) params.append('date_from', dateFrom.value);
                    if (dateTo && dateTo.value) params.append('date_to', dateTo.value);

                    window.location.href = "{{ route('reports.incomePdf') }}?" + params.toString();
                });
            </script>
        @endpush


        <!-- Sessions Table -->
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Professor</th>
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
                                    <td>{{ $session->professor->name ?? '-' }} -
                                        {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</td>
                                    <td>{{ $session->session_students_count > 0 ? $session->session_students_count : '-' }}
                                    </td>
                                    <td>{{ $session->total_center_price > 0 ? number_format($session->total_center_price, 1) : '-' }}
                                    </td>
                                    <td>{{ $session->total_printables > 0 ? number_format($session->total_printables, 1) : '-' }}
                                    </td>
                                    <td>{{ $session->total_materials > 0 ? number_format($session->total_printables, 1) : '-' }}
                                    </td>
                                    <td>{{ $session->sessionExtra?->copies > 0 ? number_format($session->sessionExtra?->copies, 1) : '-' }}
                                    </td>
                                    <td>{{ number_format(0, 1) }}</td>
                                    <td>{{ $session->sessionExtra?->markers > 0 ? number_format($session->sessionExtra?->markers, 1) : '-' }}
                                    </td>
                                    <td>{{ number_format(0, 1) }}</td>
                                    <td class="fw-bold text-primary">
                                        {{ number_format(
                                            $session->total_center_price +
                                                $session->total_professor_price +
                                                $session->total_materials +
                                                $session->total_printables +
                                                $session->sessionExtra?->markers +
                                                $session->sessionExtra?->copies,
                                            1,
                                        ) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-3">
                                        No sessions found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if (count($sessions))
                            <tfoot class="table-dark">
                                <tr>
                                    <th colspan="2" class="text-end">Totals:</th>
                                    <th>{{ $totals['students'] }}</th>
                                    <th>{{ number_format($totals['center_price'], 1) }}</th>
                                    <th>{{ number_format($totals['printables'], 1) }}</th>
                                    <th>{{ number_format($totals['materials'], 1) }}</th>
                                    <th>{{ number_format($totals['copies'] ?? 0, 1) }}</th>
                                    <th>{{ number_format(0, 1) }}</th>
                                    <th>{{ number_format($totals['markers'] ?? 0, 1) }}</th>
                                    <th>{{ number_format(0, 1) }}</th>
                                    <th class="fw-bold text-primary">
                                        {{ number_format($totals['overall_total'], 1) }}
                                    </th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
