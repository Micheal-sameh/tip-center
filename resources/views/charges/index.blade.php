@extends('layouts.sideBar')

@section('content')
@php
    use App\Enums\ChargeType;
@endphp
    <div class="container-fluid px-4 mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">
                <i class="fas fa-list me-2 text-primary"></i> Charges
            </h4>
            <a href="{{ route('charges.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Add Charge
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('charges.index') }}" class="row g-3 mb-4">
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
                <button type="submit" class="btn btn-primary me-2 w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('charges.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>
        </form>

        <div class="card shadow border-0 rounded-4">
            <div class="card-body">
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Gap</th>
                                <th>Date</th>
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
                                    <td>
                                        <span class="badge {{ $charge->type == ChargeType::GAP ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $charge->type == ChargeType::GAP ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>{{ $charge->created_at->format('d-m-Y') }}</td>
                                    @can('charges_delete')
                                        <td>
                                            <form action="{{ route('charges.destroy', $charge->id) }}" method="post"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
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
                                <p class="mb-1"><strong>Type:</strong> {{ ChargeType::getStringValue($charge->type) }}</p>
                                <p class="mb-1">
                                    <strong>Gap:</strong>
                                    <span class="badge {{ $charge->type == ChargeType::GAP ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $charge->type == ChargeType::GAP ? 'Yes' : 'No' }}
                                    </span>
                                </p>
                                <p class="mb-2"><strong>Date:</strong> {{ $charge->created_at->format('d-m-Y') }}</p>
                                @can('charges_delete')
                                <div class="d-flex justify-content-end">
                                    <form action="{{ route('charges.destroy', $charge->id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">No records found.</p>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $charges->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
