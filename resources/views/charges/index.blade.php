@extends('layouts.sideBar')

@section('content')
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

        <!-- Table -->
        <div class="card shadow border-0 rounded-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Gap</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($charges as $charge)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $charge->title }}</td>
                                    <td>{{ $charge->amount }}</td>
                                    <td>
                                        <span class="badge {{ $charge->is_gap ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $charge->is_gap ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>{{ $charge->created_at->format('d-m-Y') }}</td>
                                    @if ($charge->created_at->isToday())
                                        <td>
                                            <form action="{{ route('charges.destroy', $charge->id) }}" method="post"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
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
                                        <a class="page-link" href="{{ $charges->previousPageUrl() }}"
                                            rel="prev">&laquo;</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($charges->getUrlRange(1, $charges->lastPage()) as $page => $url)
                                    <li class="page-item {{ $charges->currentPage() === $page ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($charges->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $charges->nextPageUrl() }}" rel="next">&raquo;</a>
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
@endsection
