@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 1200px;">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Sessions Income Report</h4>
            <form action="{{ route('charges.store') }}" method="POST" class="d-flex align-items-center ms-3">
                @csrf
                <input type="hidden" value="{{ auth()->user()->name }}" name="title">
                <input type="hidden" value="{{ App\Enums\chargeType::GAP }}" name="type">
                <input type="number" step="1" name="amount" class="form-control form-control-sm me-2"
                    placeholder="Enter Gap" required>
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    Save
                </button>
            </form>
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
                                <th>Session Date</th>
                                <th>paid Students</th>
                                <th>Centre</th>
                                <th>prof Papper</th>
                                <th>Student Papper</th>
                                <th>Markers</th>
                                <th>Other Center</th>
                                <th>Other Print</th>
                                <th>Attended Student</th>
                                <th>Session Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sessions as $index => $session)
                                <tr class="{{ $session->attended_count <= 0 ? 'table-danger' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $session->professor->name ?? '-' }} -
                                        {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</td>
                                    <td>{{ $session->created_at->format('d-m-Y') }}
                                    <td>{{ $session->total_paid_students }}
                                    </td>
                                    <td>{{ $session->total_center_price > 0 ? number_format($session->total_center_price, 1) : '-' }}
                                    </td>
                                    <td>{{ $session->sessionExtra?->copies > 0 ? number_format($session->sessionExtra?->copies, 1) : '-' }}
                                    </td>
                                    <td>{{ $session->total_printables > 0 ? number_format($session->total_printables, 1) : '-' }}
                                    </td>
                                    <td>{{ $session->sessionExtra?->markers > 0 ? number_format($session->sessionExtra?->markers, 1) : '-' }}
                                    </td>
                                    <td>{{ $session->sessionExtra?->other > 0 ? number_format($session->sessionExtra?->other, 1) : '-' }}
                                    </td>
                                    <td>{{ $session->sessionExtra?->other_print > 0 ? number_format($session->sessionExtra?->other_print, 1) : '-' }}
                                    </td>
                                    <td>{{ $session->attended_count > 0 ? $session->attended_count : '-' }}
                                    </td>
                                    <td class="fw-bold text-primary">
                                        {{ number_format(
                                            $session->total_center_price +
                                                $session->total_professor_price +
                                                $session->total_printables +
                                                $session->sessionExtra?->markers +
                                                $session->sessionExtra?->other +
                                                $session->sessionExtra?->other_print +
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
                                    <th>{{ $sessions->count() }}</th>
                                    <th>{{ $totals['paid_students'] }}</th>
                                    <th>{{ number_format($totals['center_price'], 1) }}</th>
                                    <th>{{ number_format($totals['copies'] ?? 0, 1) }}</th>
                                    <th>{{ number_format($totals['printables'], 1) }}</th>
                                    <th>{{ number_format($totals['markers'] ?? 0, 1) }}</th>
                                    <th>{{ number_format($totals['other_center'] ?? 0, 1) }}</th>
                                    <th>{{ number_format($totals['other_print'] ?? 0, 1) }}</th>
                                    <th>{{ $totals['attended_count'] }}</th>
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
        <div class="card shadow border-0 rounded-4 mb-4">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-6 text-end fw-bold">Gap:</div>
                    <div class="col-md-6 fw-bold text-danger">
                        {{ number_format($gap ?? 0, 1) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 text-end fw-bold">Charges Total:</div>
                    <div class="col-md-6 fw-bold text-danger">
                        {{ number_format(-$charges ?? 0, 1) }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 text-end fw-bold">Final Total:</div>
                    <div class="col-md-6 fw-bold text-success">
                        {{ number_format(($totals['overall_total'] - $charges + $gap ?? 0) - ($totals['charges_total'] ?? 0), 1) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
