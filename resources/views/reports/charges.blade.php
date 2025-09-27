@extends('layouts.sideBar')

@section('content')
    @php
        use App\Enums\ChargeType;
    @endphp
    <div class="container py-4" style="max-width: 1200px;">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h4 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Charges Report</h4>
        </div>

        <!-- Filter Form -->
        <form id="filterForm" action="{{ route('reports.charges') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="name" class="form-label">Search by Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter charge name"
                    value="{{ request('name') }}">
            </div>
            <div class="col-md-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-select">
                    <option value="">All Types</option>
                    @foreach (App\Enums\ChargeType::all() as $charge)
                        <option value="{{ $charge['value'] }}"
                            {{ request('type') == $charge['value'] ? 'selected' : '' }}>
                            {{ $charge['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="date_from" class="form-label">From</label>
                <input type="date" name="date_from" id="date_from" class="form-control"
                    value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">To</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 me-1">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <a href="{{ route('reports.chargesPdf', request()->all()) }}" class="btn btn-success w-100" id="exportBtn">
                    <i class="fas fa-file-export me-1"></i> Export PDF
                </a>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <a href="{{ route('reports.charges') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>
        </form>

        @push('scripts')
            <script>
                document.getElementById('filterForm').addEventListener('submit', function() {
                    const dateFrom = this.querySelector('[name="date_from"]');
                    const dateTo = this.querySelector('[name="date_to"]');

                    if (dateFrom && !dateFrom.value) {
                        dateFrom.removeAttribute('name');
                    }
                    if (dateTo && !dateTo.value) {
                        dateTo.removeAttribute('name');
                    }
                });

                document.getElementById('exportBtn').addEventListener('click', function(e) {
                    e.preventDefault();
                    const params = new URLSearchParams();

                    const dateFrom = document.querySelector('[name="date_from"]');
                    const dateTo = document.querySelector('[name="date_to"]');
                    const name = document.querySelector('[name="name"]');
                    const type = document.querySelector('[name="type"]');

                    if (dateFrom && dateFrom.value) params.append('date_from', dateFrom.value);
                    if (dateTo && dateTo.value) params.append('date_to', dateTo.value);
                    if (name && name.value) params.append('name', name.value);
                    if (type && type.value) params.append('type', type.value);

                    window.location.href = "{{ route('reports.chargesPdf') }}?" + params.toString();
                });
            </script>
        @endpush

        <!-- Charges Table -->
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
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
                                    <td>{{ $charge->title }}</td>
                                    <td>{{ number_format($charge->amount, 1) }}</td>
                                    <td>{{ ChargeType::getStringValue($charge->type) }}</td>
                                    <td>{{ $charge->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $charge->createdBy?->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        No charges found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($charges->count() > 0)
                            <tfoot class="table-dark">
                                <tr>
                                    <th colspan="2" class="text-end">Total:</th>
                                    <th>{{ number_format($total, 1) }}</th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
