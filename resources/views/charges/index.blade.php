@extends('layouts.sideBar')

@section('content')
    @php
        use App\Enums\ChargeType;
    @endphp
    <div class="container-fluid px-4 mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">
                <i class="fas fa-list me-2 text-primary"></i> {{ $title }}
            </h4>
            <a href="{{ route('charges.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Add {{ $title }}
            </a>
        </div>

        <!-- Filter Form -->
        <form id="filter-form" method="GET" action="{{ route('charges.' . $route) }}" class="row g-3 mb-4">
            <!-- Name -->
            <div class="col-md-2">
                <label for="name" class="form-label">Search by Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter charge name"
                    value="{{ request('name') }}">
            </div>

            <!-- Type -->
            @if ($route != 'gap')
                <div class="col-md-2">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">Charge type</option>
                        @foreach (App\Enums\ChargeType::all() as $charge)
                            <option value="{{ $charge['value'] }}"
                                {{ request('type') == $charge['value'] ? 'selected' : '' }}>
                                {{ $charge['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Date From -->
            <div class="col-md-2">
                <label for="date_from" class="form-label">From</label>
                <input type="date" name="date_from" id="date_from" class="form-control"
                    value="{{ request('date_from') }}">
            </div>

            <!-- Date To -->
            <div class="col-md-2">
                <label for="date_to" class="form-label">To</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>

            <!-- Actions -->
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2 w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('charges.' . $route) }}" class="btn btn-secondary w-100">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>
        </form>

        <!-- Delete Confirmation Form -->
        <form id="deleteChargeForm" action="" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <div class="card shadow border-0 rounded-4">
            <div class="card-body">
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Created By</th>
                                @can('charges_delete')
                                    <th>Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($charges as $charge)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $charge->title }}</td>
                                    <td>{{ $charge->amount }}</td>
                                    <td>{{ ChargeType::getStringValue($charge->type) }}</td>
                                    <td>{{ $charge->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $charge->createdBy?->name }}</td>
                                    @can('charges_delete')
                                        @if (!$charge->created_at->lt(today()))
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger delete-charge-btn"
                                                    data-charge-id="{{ $charge->id }}"
                                                    data-title="{{ $charge->title }}"
                                                    data-type="{{ App\Enums\ChargeType::getStringValue($charge->type) }}"
                                                    data-amount="{{ $charge->amount }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        @endif
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="d-md-none">
                    @forelse ($charges as $charge)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h5 class="fw-bold mb-1">{{ $charge->title }}</h5>
                                <p class="mb-1"><strong>Amount:</strong> {{ $charge->amount }}</p>
                                <p class="mb-1"><strong>Type:</strong> {{ ChargeType::getStringValue($charge->type) }}
                                </p>
                                <p class="mb-1">
                                    <strong>Gap:</strong>
                                    <span
                                        class="badge {{ $charge->type == ChargeType::GAP ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $charge->type == ChargeType::GAP ? 'Yes' : 'No' }}
                                    </span>
                                </p>
                                <p class="mb-2"><strong>Date:</strong> {{ $charge->created_at->format('d-m-Y') }}</p>
                                @can('charges_delete')
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-sm btn-danger delete-charge-btn"
                                            data-charge-id="{{ $charge->id }}"
                                            data-title="{{ $charge->title }}"
                                            data-type="{{ App\Enums\ChargeType::getStringValue($charge->type) }}"
                                            data-amount="{{ $charge->amount }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">No records found.</p>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center pt-2">
                    @if ($charges->hasPages())
                        <nav>
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($charges->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $charges->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}"
                                            rel="prev">&laquo;</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($charges->getUrlRange(1, $charges->lastPage()) as $page => $url)
                                    <li class="page-item {{ $charges->currentPage() === $page ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ $url . '&' . http_build_query(request()->except('page')) }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($charges->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $charges->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}"
                                            rel="next">&raquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">&raquo;</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('filter-form').addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.disabled = true;
                }
            });
        });

        // Delete charge handler
        $(document).off('click', '.delete-charge-btn').on('click', '.delete-charge-btn', function() {
            const button = $(this);
            const chargeId = button.data('charge-id');
            const title = button.data('title');
            const type = button.data('type');
            const amount = button.data('amount');

            Swal.fire({
                title: 'Delete Charge',
                text: `Are you sure you want to delete "${title}" as ${type} with amount ${amount} EGP?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('#deleteChargeForm');
                    form.attr('action', `/charges/${chargeId}`);
                    form.submit();
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Delete charge handler
            $(document).off('click', '.delete-charge-btn').on('click', '.delete-charge-btn', function() {
                const button = $(this);
                const chargeId = button.data('charge-id');
                const title = button.data('title');
                const type = button.data('type');
                const amount = button.data('amount');

                Swal.fire({
                    title: 'Delete Charge',
                    text: `Are you sure you want to delete "${title}" as ${type} with amount ${amount} EGP?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#deleteChargeForm');
                        form.attr('action', `/charges/${chargeId}`);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
