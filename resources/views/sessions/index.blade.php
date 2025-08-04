@extends('layouts.sideBar')

@section('content')
    <div class="container-fluid px-4 py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1 fw-bold text-dark"><i class="fas fa-calendar-day text-primary me-2"></i>Sessions
                </h2>
            </div>
            <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal"
                data-bs-target="#professorSelectionModal">
                <i class="fas fa-plus me-2"></i>New Session
            </button>
        </div>

        <!-- Alert Messages - Fixed Position -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="card-title mb-3 text-dark"><i class="fas fa-sliders-h text-primary me-2"></i>Filters</h5>
                <form method="GET" action="{{ route('sessions.index') }}" id="filterForm">
                    <div class="row g-3">
                        <!-- Search Field -->
                        <div class="col-md-4">
                            <label for="searchInput" class="form-label small text-muted">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fas fa-search text-muted"></i></span>
                                <input type="text" id="searchInput" name="search" class="form-control border-start-0"
                                    placeholder="professor..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Professor Filter -->
                        <div class="col-md-3">
                            <label for="professorFilter" class="form-label small text-muted">Professor</label>
                            <select name="professor_id" id="professorFilter" class="form-select">
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
                            <label for="stageFilter" class="form-label small text-muted">Stage</label>
                            <select name="stage" id="stageFilter" class="form-select">
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
                            <label for="statusFilter" class="form-label small text-muted">Status</label>
                            <select name="status" id="statusFilter" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach (App\Enums\SessionStatus::all() as $status)

                                    <option value="{{$status['value']}}" {{ request('status') == $status['value'] ? 'selected' : '' }}>{{$status['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-12 mt-2">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary px-4 py-2">
                                    <i class="fas fa-filter me-2"></i>Apply
                                </button>
                                <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sessions List Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark"><i class="fas fa-list-ul text-primary me-2"></i>Sessions</h5>
                    <span class="badge bg-light text-dark rounded-pill px-3 py-2" >
                        {{ $sessions->total() }} Sessions
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="sessionsContainer">
                    @include('sessions.partials.session_cards')
                </div>
            </div>
        </div>
    </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark"><i class="fas fa-list-ul text-primary me-2"></i>Online Sessions</h5>
                    <span class="badge bg-light text-dark rounded-pill px-3 py-2" >
                        {{ $online_sessions->total() }} Online
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="sessionsContainer">
                    @include('sessions.partials.onlineSession_cards')
                </div>
            </div>
        </div>
    </div>

    <!-- Professor Selection Modal -->
    <div class="modal fade" id="professorSelectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-tie me-2"></i>Select Professor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="professorSearch" class="form-label">Search Professors</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="professorSearch"
                                placeholder="Professor name...">
                        </div>
                    </div>

                    <div class="list-group" id="professorList" style="max-height: 50vh; overflow-y: auto;">
                        @foreach (App\Models\Professor::select('id', 'name')->get() as $professor)
                            <a href="{{ route('sessions.create', ['professor_id' => $professor->id]) }}"
                                class="list-group-item list-group-item-action border-0 rounded-2 mb-2 py-3 px-4 hover-shadow">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;">
                                            {{ substr($professor->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $professor->name }}</h6>
                                            <small class="text-muted">ID: {{ $professor->id }}</small>
                                        </div>
                                    </div>
                                    <span class="text-primary">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom Styles */
        .card {
            border-radius: 0.5rem;
        }

        .list-group-item {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .list-group-item:hover {
            transform: translateX(5px);
            border-left-color: var(--bs-primary);
            background-color: var(--bs-light);
        }

        .hover-shadow:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        #professorList::-webkit-scrollbar {
            width: 6px;
        }

        #professorList::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #professorList::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        #professorList::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .input-group-text {
            transition: all 0.2s ease;
        }

        .input-group:focus-within .input-group-text {
            color: var(--bs-primary);
        }

        #sessionsContainer.loading {
            position: relative;
            min-height: 200px;
        }

        #sessionsContainer.loading::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Debounce function for better performance
            const debounce = (func, wait) => {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            };

            // Session reload function with loading state
            const reloadSessions = debounce(function() {
                const $container = $('#sessionsContainer');
                $container.addClass('loading');

                $.ajax({
                    url: "{{ route('sessions.index') }}",
                    type: "GET",
                    data: {
                        search: $('#searchInput').val(),
                        professor_id: $('#professorFilter').val(),
                        stage: $('#stageFilter').val(),
                        status: $('#statusFilter').val(),
                        ajax: true
                    },
                    beforeSend: function() {
                        $container.html(`
                            <div class="text-center py-5 my-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 text-muted">Loading sessions...</p>
                            </div>
                        `);
                    },
                    success: function(data) {
                        $container.html(data).removeClass('loading');
                        updateSessionCount();
                    },
                    error: function(xhr) {
                        $container.removeClass('loading');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load sessions. Please try again.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            }, 300);

            // Update session count
            function updateSessionCount() {
                const count = $('#sessionsContainer .session-card').length;
                $('#sessionCount').text(`${count} ${count === 1 ? 'session' : 'sessions'}`);
            }

            // Initialize event handlers
            function initEventHandlers() {
                // Professor search
                $('#professorSearch').on('input', debounce(function() {
                    const searchText = $(this).val().toLowerCase();
                    $('#professorList a').each(function() {
                        const professorText = $(this).text().toLowerCase();
                        $(this).toggle(professorText.includes(searchText));
                    });
                }, 200));

                // Filter form submission
                $('#filterForm').on('submit', function(e) {
                    e.preventDefault();
                    reloadSessions();
                });

                // Real-time search
                $('#searchInput').on('input', function() {
                    reloadSessions();
                });

                // Status change handler
                $(document).on('click', '.status-toggle', function(e) {
                    e.preventDefault();
                    const sessionId = $(this).data('id');

                    Swal.fire({
                        title: 'Change Session Status?',
                        text: "Are you sure you want to change this session's status?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it!',
                        cancelButtonText: 'Cancel',
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return $.ajax({
                                url: `/sessions/${sessionId}/close`,
                                type: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr(
                                        'content'),
                                    status: 'inactive'
                                }
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Session status updated.',
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            reloadSessions();
                        }
                    });
                });
            }

            // Initialize everything
            initEventHandlers();
            updateSessionCount();

            // Auto-refresh every 5 minutes
            setInterval(reloadSessions, 300000);
        });
    </script>
@endpush
