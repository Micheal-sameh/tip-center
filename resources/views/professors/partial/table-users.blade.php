@extends('layouts.sideBar')

@section('content')
    @php
        $logo = App\Models\Setting::where('name', 'logo')->first();
    @endphp
    <div class="container-fluid px-4" style="max-width: 98%;">
        @can('professors_create')
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">{{ __('trans.professors') }}</h4>
                <a href="{{ route('professors.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> {{ __('trans.create_professor') }}
                </a>
            </div>
        @endcan

        <!-- Filter Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('professors.index') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small mb-1">{{ __('trans.name') }}</label>
                            <input type="text" name="name" value="{{ request('name') }}"
                                class="form-control form-control-sm" placeholder="{{ __('trans.search_name') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small mb-1">{{ __('trans.stages') }}</label>
                            <div class="dropdown">
                                <button class="form-control form-control-sm text-start dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('trans.select_stages') }}
                                </button>
                                <ul class="dropdown-menu p-3" style="max-height: 250px; overflow-y: auto;">
                                    @foreach (\App\Enums\StagesEnum::getValues() as $value)
                                        <li>
                                            <label class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-2" type="checkbox" name="stages[]"
                                                    value="{{ $value }}"
                                                    {{ collect(request('stages'))->contains($value) ? 'checked' : '' }}>
                                                <span class="form-check-label">
                                                    {{ \App\Enums\StagesEnum::getStringValue($value) }}
                                                </span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-search me-1"></i> {{ __('trans.search') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @php
            $has_balance = 0;
             //$professors->contains(
               // fn($professor) => $professor->stageBalances->sum('balance') + $professor->stageBalances->sum('materials_balance') > 0,
            //);
        @endphp
        <!-- Main Content Card -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <!-- Desktop Table -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="40" class="text-center">#</th>
                                    <th>{{ __('trans.image') }}</th>
                                    <th>{{ __('trans.name') }}</th>
                                    <th>{{ __('trans.phone') }}</th>
                                    <th>{{ __('trans.subject') }}</th>
                                    <th>{{ __('trans.school') }}</th>
                                    <th>{{ __('trans.birth_date') }}</th>
                                    <th>{{ __('trans.status') }}</th>
                                    {{-- <th>{{ __('trans.type') }}</th> --}}
                                    <th width="120">{{ __('trans.stages') }}</th>
                                    @if ($has_balance)
                                        <th width="80">{{ __('trans.balance') }}</th>
                                        <th width="80">{{ __('Balance Materials') }}</th>
                                    @endif
                                    <th width="120">{{ __('trans.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($professors as $index => $professor)
                                    <tr id="professor-row-{{ $professor->id }}"
                                        class="{{ $professor->status ? '' : 'text-muted' }}">
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">
                                            @if ($professor->hasMedia('professors_images'))
                                                <img src="{{ $professor->getFirstMediaUrl('professors_images') }}"
                                                    class="rounded-circle shadow-sm"
                                                    style="width: 45px; height: 45px; object-fit: cover;">
                                            @else
                                                <img src="{{ $logo?->getFirstMediaUrl('app_logo') ?? asset('images/default-avatar.png') }}"
                                                    class="rounded-circle shadow-sm"
                                                    style="width: 45px; height: 45px; object-fit: cover;">
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('professors.show', $professor) }}"
                                                class="text-decoration-none">
                                                {{ $professor->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ $professor->phone }}</span>
                                                @if ($professor->optional_phone)
                                                    <small class="text-muted">{{ $professor->optional_phone }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $professor->subject }}</td>
                                        <td>{{ $professor->school }}</td>
                                        <td>{{ $professor->birth_date?->format('d-m-Y') }}</td>
                                        <td>
                                            <button id="status-btn-{{ $professor->id }}"
                                                onclick="toggleStatus({{ $professor->id }})"
                                                class="badge border-0 px-2 py-1 rounded-1 {{ $professor->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $professor->status == 1 ? __('trans.active') : __('trans.inactive') }}
                                            </button>
                                        </td>
                                        {{-- <td>{{ App\Enums\SessionType::getStringValue($professor->type) }}</td> --}}
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($professor->stages as $stage)
                                                    <span
                                                        class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 py-1 px-2 small">
                                                        {{ \App\Enums\StagesEnum::getStringValue($stage->stage) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        @if ($has_balance)
                                            <td class="text-center">
                                                {{ $professor->stageBalances->sum('balance') }}</td>
                                            <td class="text-center">
                                                {{ $professor->stageBalances->sum('materials_balance') }}</td>
                                        @endif
                                        <td>
                                            <div class="d-flex gap-1">
                                                @can('professors_update')
                                                    <a href="{{ route('professors.edit', $professor) }}"
                                                        class="btn btn-sm btn-outline-warning" title="{{ __('trans.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('sessions_create')
                                                    <form action="{{ route('sessions.create', $professor->id) }}"
                                                        method="GET" class="d-inline">
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            title="{{ __('trans.create_session') }}">
                                                            <i class="fas fa-calendar-plus me-1"></i> + Session
                                                        </button>
                                                    </form>
                                                @endcan
                                                @if ($professor->stageBalances->sum('balance') + $professor->stageBalances->sum('materials_balance') > 0)
                                                    <form action="{{ route('professors.settle', $professor->id) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            title="{{ __('trans.settle') }}">
                                                            <i class="fas fa-money-bill-wave me-1"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle me-2"></i> {{ __('trans.no_professors_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Cards -->
                <div class="d-md-none">
                    @forelse ($professors as $professor)
                        <div class="card mb-3 border-0 shadow-sm rounded-3 mx-2" id="professor-card-{{ $professor->id }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    @if ($professor->hasMedia('professors_images'))
                                        <img src="{{ $professor->getFirstMediaUrl('professors_images') }}"
                                            class="rounded-circle shadow-sm"
                                            style="width: 45px; height: 45px; object-fit: cover;">
                                    @else
                                        <img src="{{ $logo?->getFirstMediaUrl('app_logo') ?? asset('images/default-avatar.png') }}"
                                            class="rounded-circle shadow-sm"
                                            style="width: 45px; height: 45px; object-fit: cover;">
                                    @endif
                                    <h5 class="card-title mb-0">
                                        <a href="{{ route('professors.show', $professor) }}"
                                            class="text-decoration-none">
                                            {{ $professor->name }}
                                        </a>
                                    </h5>
                                    <button id="status-btn-{{ $professor->id }}"
                                        onclick="toggleStatus({{ $professor->id }})"
                                        class="badge border-0 px-2 py-1 rounded-1 {{ $professor->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $professor->status == 1 ? __('trans.active') : __('trans.inactive') }}
                                    </button>
                                </div>

                                <div class="row small g-2 mb-2">
                                    <div class="col-6">
                                        <div class="text-muted">{{ __('trans.phone') }}</div>
                                        <div>{{ $professor->phone }}</div>
                                        @if ($professor->optional_phone)
                                            <div class="text-muted">{{ $professor->optional_phone }}</div>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted">{{ __('trans.school') }}</div>
                                        <div>{{ $professor->school }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted">{{ __('trans.subject') }}</div>
                                        <div>{{ $professor->subject }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted">{{ __('trans.birth_date') }}</div>
                                        <div>{{ $professor->birth_date?->format('d-m-Y') }}</div>
                                    </div>
                                    {{-- <div class="col-6">
                                        <div class="text-muted">{{ __('trans.type') }}</div>
                                        <div>{{ App\Enums\SessionType::getStringValue($professor->type) }}</div>
                                    </div> --}}
                                </div>

                                <div class="mb-3">
                                    <div class="text-muted small mb-1">{{ __('trans.stages') }}</div>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($professor->stages as $stage)
                                            <span
                                                class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 py-1 px-2 small">
                                                {{ \App\Enums\StagesEnum::getStringValue($stage->stage) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    @can('professors_view')
                                        <a href="{{ route('professors.show', $professor) }}"
                                            class="btn btn-sm btn-outline-info" title="{{ __('trans.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('professors_update')
                                        <a href="{{ route('professors.edit', $professor) }}"
                                            class="btn btn-sm btn-outline-warning" title="{{ __('trans.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('sessions_create')
                                        <form action="{{ route('sessions.create', $professor->id) }}" method="GET"
                                            class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                title="{{ __('trans.create_session') }}">
                                                <i class="fas fa-calendar-plus me-1"></i> + Session
                                            </button>
                                        </form>
                                    @endcan
                                    @if ($professor->stageBalances->sum('balance') + $professor->stageBalances->sum('materials_balance') > 0)
                                        <form action="{{ route('professors.settle', $professor->id) }}" method="post"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                title="{{ __('trans.settle') }}">
                                                <i class="fas fa-money-bill-wave me-1"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <p class="mb-0">{{ __('trans.no_professors_found') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center pt-2">
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
                                    <a class="page-link" href="{{ $professors->previousPageUrl() }}"
                                        rel="prev">&laquo;</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                                $start = max(1, $professors->currentPage() - 2);
                                $end = min($professors->lastPage(), $professors->currentPage() + 2);
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
                                    <a class="page-link" href="{{ $professors->nextPageUrl() }}"
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

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Translations for JS -->
    <script>
        const activeText = @json(__('trans.active'));
        const inactiveText = @json(__('trans.inactive'));
        const statusSuccessMsg = @json(__('trans.status_updated_successfully'));
        const statusFailMsg = @json(__('trans.failed_to_update_status'));
    </script>

    <!-- Status Toggle Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleStatus(professorId) {
            const buttons = document.querySelectorAll(`#status-btn-${professorId}`);
            const current = buttons[0].classList.contains('bg-success');

            Swal.fire({
                title: 'Change Professor Status',
                text: 'Are you sure you want to change this professor\'s status?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                const newStatus = !current;

                buttons.forEach(button => {
                    button.disabled = true;
                    button.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`;
                });

                fetch(`/professors/${professorId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            buttons.forEach(button => {
                                button.classList.toggle('bg-success', newStatus);
                                button.classList.toggle('bg-secondary', !newStatus);
                                button.textContent = newStatus ? activeText : inactiveText;
                                button.disabled = false;
                            });

                            // Update row/card appearance
                            const row = document.querySelector(`#professor-row-${professorId}`);
                            const card = document.querySelector(`#professor-card-${professorId}`);
                            if (row) row.classList.toggle('text-muted', !newStatus);
                            if (card) card.classList.toggle('text-muted', !newStatus);
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || statusFailMsg,
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: statusFailMsg + ': ' + error.message,
                            icon: 'error'
                        });
                    });
            });
        }

        // Form submission handler to remove empty name parameter
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            const nameInput = this.querySelector('input[name="name"]');
            if (nameInput && nameInput.value.trim() === '') {
                nameInput.remove();
            }

            const checkboxes = this.querySelectorAll('input[name="stages[]"]');
            checkboxes.forEach(cb => {
                if (!cb.checked) cb.disabled = true;
            });
        });
    </script>
@endsection
