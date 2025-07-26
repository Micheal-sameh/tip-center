@extends('layouts.sideBar')

@section('content')
    <div class="container" style="width:93%">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Sessions</h1>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#professorSelectionModal">
                <i class="fas fa-plus me-1"></i> New Session
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search and Filter Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('sessions.index') }}" id="filterForm">
                    <div class="row g-3">
                        <!-- General Search -->
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Search sessions..."
                                    value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Professor Filter -->
                        <div class="col-md-3">
                            <select name="professor_id" class="form-select">
                                <option value="">All Professors</option>
                                @foreach ($professors as $professor)
                                    <option value="{{ $professor->id }}"
                                        {{ request('professor_id') == $professor->id ? 'selected' : '' }}>
                                        {{ $professor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stage Filter -->
                        <div class="col-md-3">
                            <select name="stage" class="form-select">
                                <option value="">All Stages</option>
                                @foreach (App\Enums\StagesEnum::all() as $stage)
                                    <option value="{{ $stage['value'] }}"
                                        {{ request('stage') == $stage['value'] ? 'selected' : '' }}>
                                        {{ $stage['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pendind</option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Active</option>
                                <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-12 mt-2">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i> Apply Filters
                                </button>
                                <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sessions Container -->
        <div id="sessionsContainer">
            @if ($sessions->isEmpty())
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h4>No sessions found</h4>
                        <p class="text-muted">Create your first session by clicking the button above</p>
                    </div>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach ($sessions as $key => $session)
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <div
                                    class="card-header bg-{{ $session->status === App\Enums\SessionStatus::ACTIVE ? 'success' : 'secondary' }} text-white py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="fw-bold">#{{ $key + 1 }}</small>
                                        <span class="badge bg-white text-dark status-badge">
                                            {{ App\Enums\SessionStatus::getStringValue($session->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">{{ $session->professor->name }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</h6>

                                    <div class="my-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Professor Price:</span>
                                            <span class="fw-bold">{{ number_format($session->professor_price, 2) }}
                                                {{ config('app.currency', 'EGP') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Center Price:</span>
                                            <span class="fw-bold">{{ number_format($session->center_price, 2) }}
                                                {{ config('app.currency', 'EGP') }}</span>
                                        </div>
                                        @if ($session->printables)
                                            <div class="d-flex justify-content-between">
                                                <span>Printables:</span>
                                                <span class="fw-bold">{{ number_format($session->printables, 2) }}
                                                    {{ config('app.currency', 'EGP') }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if ($session->start_at && $session->end_at)
                                        <div class="d-flex align-items-center text-muted mt-3">
                                            <i class="fas fa-clock me-2"></i>
                                            <small>
                                                {{ \Carbon\Carbon::parse($session->start_at)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($session->end_at)->format('h:i A') }}
                                            </small>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <form method="POST" action="{{ route('sessions.status', $session->id) }}"
                                            class="d-inline status-toggle-form" data-id="{{ $session->id }}"
                                            data-status="{{ $session->status === App\Enums\SessionStatus::ACTIVE ? 'active' : 'inactive' }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="btn btn-sm btn-{{ $session->status === App\Enums\SessionStatus::ACTIVE ? 'success' : 'secondary' }}">
                                                <i
                                                    class="fas {{ $session->status === App\Enums\SessionStatus::ACTIVE ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                                {{ App\Enums\SessionStatus::getStringValue($session->status) }}
                                            </button>
                                        </form>


                                        <div class="btn-group">
                                            <a href="{{ route('sessions.show', $session->id) }}"
                                                class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('sessions_update')
                                            <a href="{{ route('sessions.edit', $session->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            {{-- <form action="{{ route('sessions.delete', $session->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="Delete" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-2">
                    @if ($sessions->hasPages())
                        <nav>
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($sessions->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $sessions->previousPageUrl() }}"
                                            rel="prev">&laquo;</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($sessions->getUrlRange(1, $sessions->lastPage()) as $page => $url)
                                    <li class="page-item {{ $sessions->currentPage() === $page ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($sessions->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $sessions->nextPageUrl() }}"
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
            @endif
        </div>

        <!-- Professor Selection Modal -->
        <div class="modal fade" id="professorSelectionModal" tabindex="-1"
            aria-labelledby="professorSelectionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="professorSelectionModalLabel">Select Professor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="professorSearch" class="form-label">Search Professors</label>
                            <input type="text" class="form-control" id="professorSearch"
                                placeholder="Type professor name...">
                        </div>

                        <div class="list-group" id="professorList" style="max-height: 400px; overflow-y: auto;">
                            @foreach (App\Models\Professor::select('id', 'name')->get() as $professor)
                                <a href="{{ route('sessions.create', ['professor_id' => $professor->id]) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $professor->name }}</h6>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">Select</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Status toggle handler
            $(document).on('click', '.status-toggle', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const btn = $(this);
                const sessionId = btn.data('id');
                const currentStatus = btn.data('status');
                const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

                // Visual feedback
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

                // AJAX request using your exact route
                $.ajax({
                    url: `/sessions/${sessionId}/status`, // Matches your route
                    type: 'PUT',
                    data: {
                        status: newStatus
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        // Update button appearance
                        btn.removeClass(
                                `btn-${currentStatus === 'active' ? 'success' : 'secondary'}`)
                            .addClass(`btn-${newStatus === 'active' ? 'success' : 'secondary'}`)
                            .html(
                                `<i class="fas ${newStatus === 'active' ? 'fa-check-circle' : 'fa-times-circle'} me-1"></i> ${newStatus === 'active' ? 'Active' : 'Inactive'}`
                            )
                            .data('status', newStatus);

                        // Update card header
                        btn.closest('.card').find('.card-header')
                            .removeClass(
                                `bg-${currentStatus === 'active' ? 'success' : 'secondary'}`)
                            .addClass(`bg-${newStatus === 'active' ? 'success' : 'secondary'}`);

                        // Update status badge
                        btn.closest('.card').find('.status-badge')
                            .text(newStatus === 'active' ? 'Active' : 'Inactive');

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Status updated',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Update failed',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                    }
                });
            });

            // Professor search in modal
            $('#professorSearch').on('input', function() {
                const searchText = $(this).val().toLowerCase();
                $('#professorList a').each(function() {
                    const professorText = $(this).text().toLowerCase();
                    $(this).toggle(professorText.includes(searchText));
                });
            });
        });
    </script>
@endsection
