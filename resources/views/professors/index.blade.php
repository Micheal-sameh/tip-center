@extends('layouts.sideBar')

@section('content')
    <div class="container" style="width: 90%;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>{{ __('trans.professors') }}</h4>
            @can('professors_create')
                <a href="{{ route('professors.create') }}" class="btn btn-primary">
                    {{ __('trans.create_professor') }}
                </a>
            @endcan
        </div>

        <form id="filterForm" class="mb-4">
            <div class="row align-items-end g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('trans.name') }}</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                        placeholder="{{ __('trans.search_name') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('trans.stages') }}</label>
                    <div class="dropdown">
                        <button class="form-control text-start dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{ __('trans.select_stages') }}
                        </button>
                        <ul class="dropdown-menu p-3" style="max-height: 250px; overflow-y: auto;">
                            @foreach (\App\Enums\StagesEnum::getValues() as $value)
                                <li>
                                    <label class="form-check">
                                        <input class="form-check-input" type="checkbox" name="stages[]"
                                            value="{{ $value }}"
                                            {{ collect(request('stages'))->contains($value) ? 'checked' : '' }}>
                                        {{ \App\Enums\StagesEnum::getStringValue($value) }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> {{ __('trans.search') }}
                    </button>
                </div>
            </div>
        </form>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="text-center my-4" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">{{ __('trans.loading') }}...</p>
        </div>

        <!-- Professors Table Container (will be updated via AJAX) -->
        <div id="professorsTableContainer">
            @include('professors.partial.table-users', ['professors' => $professors])
        </div>

        <!-- Pagination Container (will be updated via AJAX) -->
        <div class="d-flex justify-content-center pt-2" id="paginationContainer">
            @if ($professors->hasPages())
                <nav>
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($professors->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $professors->previousPageUrl() }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $start = max(1, $professors->currentPage() - 3);
                            $end = min($professors->lastPage(), $professors->currentPage() + 3);
                        @endphp

                        {{-- Show first page + dots if needed --}}
                        @if ($start > 1)
                            <li class="page-item"><a class="page-link" href="{{ $professors->url(1) }}">1</a></li>
                            @if ($start > 2)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif

                        {{-- Main page loop --}}
                        @foreach ($professors->getUrlRange($start, $end) as $page => $url)
                            <li class="page-item {{ $professors->currentPage() === $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        {{-- Show last page + dots if needed --}}
                        @if ($end < $professors->lastPage())
                            @if ($end < $professors->lastPage() - 1)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item"><a class="page-link"
                                    href="{{ $professors->url($professors->lastPage()) }}">{{ $professors->lastPage() }}</a>
                            </li>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($professors->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $professors->nextPageUrl() }}" rel="next">&raquo;</a>
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

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Translations for JS -->
    <script>
        const activeText = @json(__('trans.active'));
        const inactiveText = @json(__('trans.inactive'));
        const statusSuccessMsg = @json(__('trans.status_updated_successfully'));
        const statusFailMsg = @json(__('trans.failed_to_update_status'));
        const confirmDeleteMsg = @json(__('trans.confirm_delete'));
    </script>

    <!-- AJAX Scripts -->
    <script>
        $(document).ready(function() {
            // Handle form submission with AJAX
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                loadProfessors($(this).serialize());
            });

            // Handle pagination clicks
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                loadProfessors($('#filterForm').serialize() + '&' + $(this).attr('href').split('?')[1]);
            });

            // Initial load
            function loadProfessors(query = '') {
                $('#loadingIndicator').show();
                $('#professorsTableContainer, #paginationContainer').hide();

                $.ajax({
                    url: '{{ route('professors.index') }}?' + query,
                    type: 'GET',
                    success: function(response) {
                        $('#professorsTableContainer').html(response.html);
                        $('#paginationContainer').html(response.pagination);
                        $('#loadingIndicator').hide();
                        $('#professorsTableContainer, #paginationContainer').fadeIn();
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        $('#loadingIndicator').hide();
                        alert('Error loading professors');
                    }
                });
            }
        });

        // Status toggle function
        function toggleStatus(professorId) {
            const buttons = document.querySelectorAll(`#status-btn-${professorId}`);
            const current = buttons[0].classList.contains('bg-success');
            const newStatus = !current;

            buttons.forEach(button => {
                button.disabled = true;
                button.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`;
            });

            $.ajax({
                url: `/professors/${professorId}/status`,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: {
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        buttons.forEach(button => {
                            button.classList.toggle('bg-success', newStatus);
                            button.classList.toggle('bg-secondary', !newStatus);
                            button.textContent = newStatus ? activeText : inactiveText;
                            button.disabled = false;
                        });
                    } else {
                        alert(response.message || statusFailMsg);
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert(statusFailMsg);
                    buttons.forEach(button => {
                        button.disabled = false;
                        button.innerHTML = current ? activeText : inactiveText;
                    });
                }
            });
        }

        // Delete confirmation handler
        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const row = form.closest('tr, .card');

            Swal.fire({
                title: 'Delete Professor',
                text: confirmDeleteMsg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            row.fadeOut(300, function() {
                                $(this).remove();
                                // Reload the table to update pagination
                                loadProfessors($('#filterForm').serialize());
                            });
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            Swal.fire({
                                title: 'Error',
                                text: 'Error deleting professor',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
