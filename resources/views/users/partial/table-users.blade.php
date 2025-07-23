@extends('layouts.sideBar')

@section('content')
<div class="container py-4" style="max-width: 95%;">
    @can('users_create')
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark mb-0">{{ __('trans.users') }}</h4>
            <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-user-plus me-2"></i> {{ __('trans.create_user') }}
            </a>
        </div>
    @endcan

    {{-- Desktop Table --}}
    <div class="d-none d-md-block">
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('trans.name') }}</th>
                        <th>{{ __('trans.phone') }}</th>
                        <th>{{ __('trans.role') }}</th>
                        <th>{{ __('trans.birth_date') }}</th>
                        <th>{{ __('trans.status') }}</th>
                        <th>{{ __('trans.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr id="user-row-{{ $user->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold text-dark">{{ $user->name }}</td>
                            <td>{{ $user->phone }}</td>
                            <td><span class="badge bg-info text-dark">{{ $user->roles->first()?->name ?? '-' }}</span></td>
                            <td>{{ $user->birth_date }}</td>
                            <td>
                                <button id="status-btn-{{ $user->id }}"
                                    onclick="toggleStatus({{ $user->id }})"
                                    class="badge border-0 px-3 py-2 rounded-pill {{ $user->status == 1? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->status == 1 ? __('trans.active') : __('trans.inactive') }}
                                </button>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    @can('users_view')
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info" title="{{ __('trans.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('users_update')
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="{{ __('trans.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('users_delete')
                                        <form action="{{ route('users.delete', $user) }}" method="POST" class="d-inline-block"
                                            onsubmit="return confirm('{{ __('trans.confirm_delete') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('trans.delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">{{ __('trans.no_users_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="d-md-none">
        @forelse ($users as $user)
            <div class="card shadow-sm mb-3" id="user-card-{{ $user->id }}">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">{{ $user->name }}</h5>
                    <p class="mb-1"><strong>{{ __('trans.phone') }}:</strong> {{ $user->phone }}</p>
                    <p class="mb-1"><strong>{{ __('trans.role') }}:</strong> <span class="badge bg-info text-dark">{{ $user->roles->first()?->name ?? '-' }}</span></p>
                    <p class="mb-1"><strong>{{ __('trans.birth_date') }}:</strong> {{ $user->birth_date }}</p>
                    <p class="mb-2"><strong>{{ __('trans.status') }}:</strong>
                        <button id="status-btn-{{ $user->id }}" onclick="toggleStatus({{ $user->id }})"
                            class="badge border-0 px-3 py-2 rounded-pill {{ $user->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                            {{ $user->status == 1 ? __('trans.active') : __('trans.inactive') }}
                        </button>
                    </p>
                    <div class="d-flex justify-content-end gap-2">
                        @can('users_view')
                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        @endcan
                        @can('users_update')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endcan
                        @can('users_delete')
                            <form action="{{ route('users.delete', $user) }}" method="POST"
                                onsubmit="return confirm('{{ __('trans.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted text-center">{{ __('trans.no_users_found') }}</p>
        @endforelse
    </div>
    <div class="d-flex justify-content-center pt-3">
        @if ($users->hasPages())
            <nav>
                <ul class="pagination">
                    @if ($users->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">&laquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">&laquo;</a>
                        </li>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        <li class="page-item {{ $users->currentPage() === $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if ($users->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">&raquo;</a>
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

<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Status Toggle Script --}}
<script>
    function toggleStatus(userId) {
        const button = document.getElementById(`status-btn-${userId}`);
        const currentStatus = button.classList.contains('bg-success');
        const newStatus = !currentStatus;

        button.disabled = true;
        button.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`;

        fetch(`/users/${userId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                button.classList.toggle('bg-success', newStatus);
                button.classList.toggle('bg-secondary', !newStatus);
                button.textContent = newStatus ? '{{ __("trans.active") }}' : '{{ __("trans.inactive") }}';
            } else {
                throw new Error(data.message || 'Failed to update status');
            }
        })
        .catch(error => {
            alert("{{ __('trans.failed_to_update_status') }}: " + error.message);
        })
        .finally(() => {
            button.disabled = false;
        });
    }
</script>
@endsection
