@extends('layouts.sideBar')

@section('content')
    <div class="container py-4">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-file-alt me-2"></i>Session Reports</h4>
        </div>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('reports.index') }}" class="row g-2 mb-4">
            <div class="col-md-2">
                <input type="text" name="professor" class="form-control" placeholder="Search by professor name"
                    value="{{ request('professor') }}">
            </div>
            <div class="col-md-2">
                <select name="stage" class="form-select">
                    <option value="">All Stages</option>
                    @foreach (\App\Enums\StagesEnum::all() as $stage)
                        <option value="{{ $stage['value'] }}" {{ request('stage') == $stage['value'] ? 'selected' : '' }}>
                            {{ $stage['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="from" class="form-control" value="{{ request('from') }}"
                    placeholder="From date">
            </div>
            <div class="col-md-2">
                <input type="date" name="to" class="form-control" value="{{ request('to') }}" placeholder="To date">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>
        </form>

        {{-- Desktop Table --}}
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Professor</th>
                            <th>Stage</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $index => $session)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $session->professor->name }}</td>
                                <td>{{ \App\Enums\StagesEnum::getStringValue($session->stage) }}</td>
                                <td>{{ $session->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $session->status === \App\Enums\SessionStatus::ACTIVE ? 'success' : 'secondary' }}">
                                        {{ \App\Enums\SessionStatus::getStringValue($session->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                        onclick="openReport({{ $session->id }})">
                                        <i class="fas fa-file-alt me-1"></i> View Report
                                    </button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No sessions available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Cards --}}
        <div class="d-md-none">
            @forelse($sessions as $index => $session)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-2">{{ $session->professor->name }}</h5>
                        <p class="mb-1"><strong>Stage:</strong>
                            {{ \App\Enums\StagesEnum::getStringValue($session->stage) }}</p>
                        <p class="mb-1"><strong>Date:</strong> {{ $session->created_at->format('M d, Y') }}</p>
                        <p class="mb-1">
                            <strong>Status:</strong>
                            <span
                                class="badge bg-{{ $session->status === \App\Enums\SessionStatus::ACTIVE ? 'success' : 'secondary' }}">
                                {{ \App\Enums\SessionStatus::getStringValue($session->status) }}
                            </span>
                        </p>
                        <div class="d-grid mt-2">
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                onclick="openReport({{ $session->id }})">
                                <i class="fas fa-file-alt me-1"></i> View Report
                            </button>

                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">No sessions available.</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            @if ($sessions->hasPages())
                <nav>
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($sessions->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $sessions->previousPageUrl() }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        @php
                            $current = $sessions->currentPage();
                            $last = $sessions->lastPage();
                            $start = max($current - 2, 2);
                            $end = min($current + 2, $last - 1);
                        @endphp

                        {{-- First page --}}
                        <li class="page-item {{ $current === 1 ? 'active' : '' }}">
                            <a class="page-link" href="{{ $sessions->url(1) }}">1</a>
                        </li>

                        {{-- Dots before start --}}
                        @if ($start > 2)
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        @endif

                        {{-- Page range --}}
                        @for ($page = $start; $page <= $end; $page++)
                            <li class="page-item {{ $current === $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $sessions->url($page) }}">{{ $page }}</a>
                            </li>
                        @endfor

                        {{-- Dots after end --}}
                        @if ($end < $last - 1)
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        @endif

                        {{-- Last page --}}
                        @if ($last > 1)
                            <li class="page-item {{ $current === $last ? 'active' : '' }}">
                                <a class="page-link" href="{{ $sessions->url($last) }}">{{ $last }}</a>
                            </li>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($sessions->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $sessions->nextPageUrl() }}" rel="next">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
    @endsection

    @push('scripts')
        <script>
            function openReport(sessionId) {
                const url = `{{ route('reports.session') }}?session_id=${sessionId}&type=1`;
                window.location.href = url;
            }
        </script>
    @endpush
