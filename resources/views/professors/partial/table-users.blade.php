@extends('layouts.sideBar')

@section('content')
    <div class="container" style="width: 90%;">
        @can('professors_create')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>{{ __('trans.professors') }}</h4>
                <a href="{{ route('professors.create') }}" class="btn btn-primary">
                    {{ __('trans.create_professor') }}
                </a>
            </div>
        @endcan

        <!-- Desktop Table -->
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('trans.name') }}</th>
                            <th>{{ __('trans.phone') }}</th>
                            <th>{{ __('trans.subject') }}</th>
                            <th>{{ __('trans.school') }}</th>
                            <th>{{ __('trans.birth_date') }}</th>
                            <th>{{ __('trans.status') }}</th>
                            <th>{{ __('trans.stages') }}</th>
                            <th>{{ __('trans.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($professors as $index => $professor)
                            <tr id="professor-row-{{ $professor->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td><a href="{{ route('professors.show', $professor) }}"> {{ $professor->name }} </a></td>
                                <td>{{ $professor->phone }} @if ($professor->optional_phone)
                                        <br>{{ $professor->optional_phone }}
                                    @endif
                                </td>
                                <td>{{ $professor->subject }}</td>
                                <td>{{ $professor->school }}</td>
                                <td>{{ $professor->birth_date }}</td>
                                <td>
                                    <button id="status-btn-{{ $professor->id }}"
                                        onclick="toggleStatus({{ $professor->id }})"
                                        class="badge border-0 px-3 py-2 {{ $professor->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $professor->status == 1 ? __('trans.active') : __('trans.inactive') }}
                                    </button>
                                </td>
                                <td>
                                    @foreach ($professor->stages as $stage)
                                        <span class="badge bg-info text-dark me-1">
                                            {{ \App\Enums\StagesEnum::getStringValue($stage->stage) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>

                                    @can('professors_update')
                                        <a href="{{ route('professors.edit', $professor) }}" class="btn btn-sm btn-warning"><i
                                                class="fas fa-edit"></i></a>
                                    @endcan
                                    @can('professors_delete')
                                        <form action="{{ route('professors.delete', $professor) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('{{ __('trans.confirm_delete') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-muted">{{ __('trans.no_professors_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="d-md-none">
            @forelse ($professors as $professor)
                <div class="card mb-3 shadow-sm" id="professor-card-{{ $professor->id }}">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-bold">{{ $professor->name }}</h5>
                        <p class="mb-1"><strong>{{ __('trans.phone') }}:</strong> {{ $professor->phone }} @if ($professor->optional_phone)
                                {{ $professor->optional_phone }}
                            @endif
                        </p>
                        <p class="mb-1"><strong>{{ __('trans.school') }}:</strong> {{ $professor->school }}</p>
                        <p class="mb-1"><strong>{{ __('trans.subject') }}:</strong> {{ $professor->subject }}</p>
                        <p class="mb-1"><strong>{{ __('trans.birth_date') }}:</strong> {{ $professor->birth_date }}</p>
                        <p class="mb-2">
                            <strong>{{ __('trans.status') }}:</strong>
                            <button id="status-btn-{{ $professor->id }}" onclick="toggleStatus({{ $professor->id }})"
                                class="badge border-0 px-3 py-2 {{ $professor->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                {{ $professor->status == 1 ? __('trans.active') : __('trans.inactive') }}
                            </button>
                        </p>
                        <p>
                            @foreach ($professor->stages as $stage)
                                <span class="badge bg-info text-dark me-1">
                                    {{ \App\Enums\StagesEnum::getStringValue($stage->stage) }}
                                </span>
                            @endforeach
                        </p>
                        <div class="d-flex justify-content-end gap-2">
                            @can('professors_view')
                                <a href="{{ route('professors.show', $professor) }}" class="btn btn-sm btn-info"><i
                                        class="fas fa-eye"></i></a>
                            @endcan
                            @can('professors_update')
                                <a href="{{ route('professors.edit', $professor) }}" class="btn btn-sm btn-warning"><i
                                        class="fas fa-edit"></i></a>
                            @endcan
                            @can('professors_delete')
                                <form action="{{ route('professors.delete', $professor) }}" method="POST"
                                    onsubmit="return confirm('{{ __('trans.confirm_delete') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center">{{ __('trans.no_professors_found') }}</p>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $professors->links() }}
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
    <script>
        function toggleStatus(professorId) {
            const buttons = document.querySelectorAll(`#status-btn-${professorId}`);
            const current = buttons[0].classList.contains('bg-success'); // status from first match
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
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        buttons.forEach(button => {
                            button.classList.toggle('bg-success', newStatus);
                            button.classList.toggle('bg-secondary', !newStatus);
                            button.textContent = newStatus ? activeText : inactiveText;
                            button.disabled = false;
                        });
                    } else {
                        alert(data.message || statusFailMsg);
                    }
                })
                .catch(error => {
                    alert(statusFailMsg + ': ' + error.message);
                });
        }
    </script>
@endsection
