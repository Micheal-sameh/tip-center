@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="width:93%">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1 me-1">{{ __('Students') }}</h4>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('students.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>{{ __('Add Student') }}
                </a>
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter me-2"></i>{{ __('Filter') }}
                </button>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="{{ __('Search students...') }}" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="stageFilter">
                            <option value="">{{ __('All Stages') }}</option>
                            @foreach (App\Enums\StagesEnum::all() as $stage)
                                <option value="{{ $stage['value'] }}"
                                    {{ request('stage') == $stage['value'] ? 'selected' : '' }}>
                                    {{ $stage['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="sortBy">
                            <option value="name_asc" {{ request('sort_by', 'name_asc') == 'name_asc' ? 'selected' : '' }}>
                                {{ __('Sort by Name (A-Z)') }}
                            </option>
                            <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>
                                {{ __('Sort by Name (Z-A)') }}
                            </option>
                            <option value="date_asc" {{ request('sort_by') == 'date_asc' ? 'selected' : '' }}>
                                {{ __('Sort by Oldest') }}
                            </option>
                            <option value="date_desc" {{ request('sort_by') == 'date_desc' ? 'selected' : '' }}>
                                {{ __('Sort by Newest') }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <!-- Total Students -->
            <div class="col-6 col-md-3 mb-3 mb-md-0">
                <div class="card bg-primary bg-opacity-10 border-primary border-opacity-25 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-primary mb-0">{{ __('Total Students') }}</h6>
                                <h3 class="mb-0">{{ $totalStudents }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-25 p-2 p-md-3 rounded">
                                <i class="fas fa-users text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="col-6 col-md-3 mb-3 mb-md-0">
                <div class="card bg-info bg-opacity-10 border-info border-opacity-25 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-info mb-0">{{ __('This Month') }}</h6>
                                <h3 class="mb-0">{{ App\Models\Student::newThisMonth() }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-25 p-2 p-md-3 rounded">
                                <i class="fas fa-calendar-plus text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Birthdays -->
            <div class="col-6 col-md-3">
                <div class="card bg-warning bg-opacity-10 border-warning border-opacity-25 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-warning mb-0">{{ __('Birthdays') }}</h6>
                                <h3 class="mb-0">{{ App\Models\student::hasBirthdayToday() }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-25 p-2 p-md-3 rounded">
                                <i class="fas fa-birthday-cake text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student List Section (Will be loaded via AJAX) -->
        <div id="studentListContainer">
            @include('students.partial.table-students')
        </div>

        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">{{ __('Filter Students') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="filterForm">
                            <div class="mb-3">
                                <label class="form-label">{{ __('Stage') }}</label>
                                <select class="form-select" name="stage">
                                    <option value="">{{ __('All Stages') }}</option>
                                    @foreach (App\Enums\StagesEnum::all() as $stage)
                                        <option value="{{ $stage['value'] }}"
                                            {{ request('stage') == $stage['value'] ? 'selected' : '' }}>
                                            {{ $stage['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="mb-3">
                                <label class="form-label">{{ __('Status') }}</label>
                                <select class="form-select" name="status">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                        {{ __('Active') }}</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        {{ __('Inactive') }}</option>
                                </select>
                            </div> --}}
                            <div class="mb-3">
                                <label class="form-label">{{ __('Date Range') }}</label>
                                <div class="input-daterange input-group">
                                    <input type="date" class="form-control" name="from"
                                        value="{{ request('from') }}">
                                    <span class="input-group-text">{{ __('to') }}</span>
                                    <input type="date" class="form-control" name="to"
                                        value="{{ request('to') }}">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="button" class="btn btn-primary"
                            id="applyFilters">{{ __('Apply Filters') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .avatar {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .avatar-text {
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
            }

            .avatar-sm .avatar-text {
                width: 32px;
                height: 32px;
                font-size: 0.875rem;
            }

            .avatar-lg .avatar-text {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }

            .table-hover tbody tr {
                transition: all 0.2s ease;
            }

            .table-hover tbody tr:hover {
                background-color: rgba(0, 0, 0, 0.02);
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            .fa-spinner {
                animation: spin 1s linear infinite;
            }

            .loading-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(255, 255, 255, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            }
        </style>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // Function to update the URL with current filters
            function updateUrlParams(params) {
                const url = new URL(window.location.href);
                Object.keys(params).forEach(key => {
                    if (params[key]) {
                        url.searchParams.set(key, params[key]);
                    } else {
                        url.searchParams.delete(key);
                    }
                });
                window.history.pushState({}, '', url);
            }

            // Function to get current filter values
            function getCurrentFilters() {
                const params = new URLSearchParams(window.location.search);
                return {
                    search: params.get('search') || '',
                    stage: params.get('stage') || '',
                    status: params.get('status') || '',
                    from: params.get('from') || '',
                    to: params.get('to') || '',
                    sort_by: params.get('sort_by') || 'name_asc'
                };
            }

            // Function to load students with filters
            function loadStudents() {
                const filters = getCurrentFilters();
                const url = new URL('{{ route('students.index') }}');

                // Add filters to URL
                Object.keys(filters).forEach(key => {
                    if (filters[key]) {
                        url.searchParams.set(key, filters[key]);
                    }
                });

                // Show loading state
                $('#studentListContainer').prepend(
                    '<div class="loading-overlay">' +
                    '<div class="text-center py-5">' +
                    '<i class="fas fa-spinner fa-spin fa-2x"></i>' +
                    '</div>' +
                    '</div>'
                );

                // AJAX request
                $.ajax({
                    url: url.toString(),
                    type: 'GET',
                    success: function(response) {
                        // Extract just the student list part from the response
                        const newContent = $(response).find('#studentListContainer').html();
                        $('#studentListContainer').html(newContent);
                    },
                    error: function(xhr) {
                        alert('Error loading students');
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        $('.loading-overlay').remove();
                    }
                });
            }

            // Initialize filters from URL on page load
            $(document).ready(function() {
                // Search functionality
                let searchTimeout;

                $('#searchInput').on('input', function() {
                    clearTimeout(searchTimeout);

                    const inputValue = $(this).val();

                    searchTimeout = setTimeout(function() {
                        const filters = getCurrentFilters();
                        filters.search = inputValue;
                        updateUrlParams(filters);
                        loadStudents();
                    }, 700); // 500ms debounce delay
                });


                $('#clearSearch').click(function() {
                    $('#searchInput').val('');
                    const filters = getCurrentFilters();
                    filters.search = '';
                    updateUrlParams(filters);
                    loadStudents();
                });

                // Stage filter
                $('#stageFilter').change(function() {
                    const filters = getCurrentFilters();
                    filters.stage = $(this).val();
                    updateUrlParams(filters);
                    loadStudents();
                });

                // Sort by
                $('#sortBy').change(function() {
                    const filters = getCurrentFilters();
                    filters.sort_by = $(this).val();
                    updateUrlParams(filters);
                    loadStudents();
                });

                // Apply filters from modal
                $('#applyFilters').click(function() {
                    const filters = {
                        stage: $('select[name="stage"]').val(),
                        status: $('select[name="status"]').val(),
                        from: $('input[name="from"]').val(),
                        to: $('input[name="to"]').val()
                    };

                    // Merge with existing filters
                    const currentFilters = getCurrentFilters();
                    const newFilters = {
                        ...currentFilters,
                        ...filters
                    };

                    updateUrlParams(newFilters);
                    loadStudents();

                    // Close modal
                    $('#filterModal').modal('hide');
                });

                // Handle browser back/forward buttons
                window.addEventListener('popstate', function() {
                    loadStudents();
                });

                // Initialize date pickers if needed
                if ($.fn.datepicker) {
                    $('.input-daterange').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        todayHighlight: true
                    });
                }
            });
        </script>
    </div>
@endsection
