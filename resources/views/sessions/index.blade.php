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
        {{-- Error Popup --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-50 start-50 translate-middle"
                style="z-index: 9999; min-width: 300px; max-width: 500px; text-align: center;" role="alert"
                id="popup-message">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{!! nl2br(e($error)) !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Popup --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-50 start-50 translate-middle"
                style="z-index: 9999; min-width: 300px; max-width: 500px; text-align: center;" role="alert"
                id="popup-message">
                {!! nl2br(e(session('success'))) !!}
            </div>
        @endif

        {{-- Auto-hide script --}}
        @if ($errors->any() || session('success'))
            <script>
                setTimeout(function() {
                    let popup = document.getElementById('popup-message');
                    if (popup) {
                        popup.classList.remove('show'); // Bootstrap fade-out
                        setTimeout(() => popup.remove(), 500); // Remove after fade
                    }
                }, 1000);
            </script>
        @endif


        <!-- Search and Filter Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('sessions.index') }}" id="filterForm">
                    <div class="row g-3">
                        <!-- General Search -->
                        <div class="col-md-3">
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
                        <div class="col-md-2">
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
                        <div class="col-md-2">
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                @foreach (App\Enums\SessionType::all() as $type)
                                    <option value="{{ $type['value'] }}"
                                        {{ request('type') == $type['value'] ? 'selected' : '' }}>
                                        {{ $type['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach (App\Enums\SessionStatus::all() as $status)
                                    <option value="{{ $status['value'] }}"
                                        {{ request('status') == $status['value'] ? 'selected' : '' }}>
                                        {{ $status['name'] }}</option>
                                @endforeach
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
            @include('sessions.partials.session_cards')
        </div>
    </div>

    <!-- Professor Selection Modal -->
    <div class="modal fade" id="professorSelectionModal" tabindex="-1" aria-labelledby="professorSelectionModalLabel"
        aria-hidden="true">
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
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            let refreshInterval;

            // Function to reload sessions
            function reloadSessions() {
                const filters = {
                    search: $('input[name="search"]').val(),
                    professor_id: $('select[name="professor_id"]').val(),
                    stage: $('select[name="stage"]').val(),
                    type: $('select[name="type"]').val(),
                    status: $('select[name="status"]').val(),
                    ajax: true
                };

                $.ajax({
                    url: "{{ route('sessions.index') }}",
                    type: "GET",
                    data: filters,
                    success: function(data) {
                        $('#sessionsContainer').html(data);
                        initializeEventHandlers();
                    },
                    error: function(xhr) {
                        console.error('Error reloading sessions:', xhr.responseText);
                    }
                });
            }

            // Initialize event handlers
            function initializeEventHandlers() {
                // Status modal handler
                $(document).off('click', '.extras').on('click', '.extras', function(e) {
                    e.preventDefault();
                    const button = $(this);
                    const sessionId = button.data('id');
                    const modal = $('#statusChangeModal');

                    // Update the form action URL
                    modal.find('form').attr('action', '/sessions/' + sessionId + '/extras');

                    // Clear any previous hidden inputs


                    // Add the status input
                    // Reset form and show modal
                    modal.find('form')[0].reset();
                    modal.modal('show');
                });

                // Professor search in modal
                $('#professorSearch').off('input').on('input', function() {
                    const searchText = $(this).val().toLowerCase();
                    $('#professorList a').each(function() {
                        const professorText = $(this).text().toLowerCase();
                        $(this).toggle(professorText.includes(searchText));
                    });
                });

                // Status form submission
                $('#statusChangeForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const submitBtn = form.find('button[type="submit"]');

                    submitBtn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                    );

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#statusChangeModal').modal('hide');
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                            }).then(() => {
                                reloadSessions();
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = 'Failed to update status';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage
                            });
                        },
                        complete: function() {
                            submitBtn.prop('disabled', false).text('End Session');
                        }
                    });
                });

                // Filter form submission
                $('#filterForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    reloadSessions();
                });
            }

            // Start auto-refresh (300000ms = 5 minutes)
            function startAutoRefresh() {
                if (refreshInterval) {
                    clearInterval(refreshInterval);
                }
                refreshInterval = setInterval(reloadSessions, 300000);
                setTimeout(reloadSessions, 30000); // Initial load after 1 second
            }

            // Initialize
            initializeEventHandlers();
            startAutoRefresh();

            // Clear filters handler
            $('.clear-filters').off('click').on('click', function() {
                $('input[name="search"]').val('');
                $('select[name="professor_id"]').val('');
                $('select[name="stage"]').val('');
                $('select[name="type"]').val('');
                $('select[name="status"]').val('');
                reloadSessions();
            });
        });
    </script>
@endpush
