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
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pending</option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Active</option>
                                <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Inactive</option>
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
                                        @if ($session->materials)
                                            <div class="d-flex justify-content-between">
                                                <span>Materials:</span>
                                                <span class="fw-bold">{{ number_format($session->materials, 2) }}
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
                                        @if ($session->status === App\Enums\SessionStatus::ACTIVE)
                                            <button type="button" class="btn btn-sm btn-success status-toggle"
                                                data-bs-toggle="modal" data-bs-target="#statusChangeModal"
                                                data-id="{{ $session->id }}" data-status="active">
                                                <i class="fas fa-check-circle me-1"></i>
                                                {{ App\Enums\SessionStatus::getStringValue($session->status) }}
                                            </button>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times-circle me-1"></i>
                                                {{ App\Enums\SessionStatus::getStringValue($session->status) }}
                                            </span>
                                        @endif


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

        <!-- Status Change Modal -->
        <div class="modal fade" id="statusChangeModal" tabindex="-1" aria-labelledby="statusChangeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusChangeModalLabel">Change Session Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="statusChangeForm" method="POST" action="{{ route('sessions.status', $session->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="markers" class="form-label">Marqueur</label>
                                <input type="number" class="form-control" id="markers" name="markers" min="0"
                                    placeholder="Enter number of markers used">
                            </div>

                            <div class="mb-3">
                                <label for="copies" class="form-label">Copies</label>
                                <input type="number" class="form-control" id="copies" name="copies" min="0"
                                    placeholder="Enter number of copies used">
                            </div>

                            <div class="mb-3">
                                <label for="cafeterea" class="form-label">Cafeterea</label>
                                <input type="number" class="form-control" id="cafeterea" name="cafeterea"
                                    min="0" placeholder="Enter number of cafeterea used">
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional notes"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm Change</button>
                        </div>
                    </form>
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
            // Status modal handler
            $('#statusChangeModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var sessionId = button.data('id');
                var currentStatus = button.data('status');
                var newStatus = currentStatus === 'active' ? 'inactive' : 'active';

                // Update modal content
                var modal = $(this);
                modal.find('#newStatusDisplay').text(newStatus === 'active' ? 'Active' : 'Inactive');
                modal.find('form').attr('action', '/sessions/' + sessionId + '/status');

                // Add hidden input for status
                if (modal.find('input[name="status"]').length === 0) {
                    modal.find('form').append('<input type="hidden" name="status" value="' + newStatus +
                        '">');
                } else {
                    modal.find('input[name="status"]').val(newStatus);
                }
            });

            // Status form submission
            $('#statusChangeForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var submitBtn = form.find('button[type="submit"]');

                // Disable button during submission
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                );

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#statusChangeModal').modal('hide');
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Status updated successfully',
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message || 'Failed to update status'
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).text('Confirm Change');
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
