@extends('layouts.sideBar')

@section('content')
<div class="container" style="width: 90%;">
    <!-- Header -->
    @can('users_create')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>{{ __('trans.users') }}</h4>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                {{ __('trans.create_user') }}
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
                        <th>{{ __('trans.role') }}</th>
                        <th>{{ __('trans.birth_date') }}</th>
                        <th>{{ __('trans.status') }}</th>
                        <th>{{ __('trans.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->roles->first()?->name }}</td>
                            <td>{{ $user->birth_date }}</td>
                            <td>
                                @if ($user->status)
                                    <span class="badge bg-success">{{ __('trans.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('trans.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                @can('users_view')
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan
                                @can('users_update')
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('users_delete')
                                    <form action="{{ route('users.delete', $user) }}" method="POST" class="d-inline-block"
                                        onsubmit="return confirm('{{ __('trans.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">{{ __('trans.no_users_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="d-md-none">
        @forelse ($users as $index => $user)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold">{{ $user->name }}</h5>
                    <p class="mb-1"><strong>{{ __('trans.phone') }}:</strong> {{ $user->phone }}</p>
                    <p class="mb-1"><strong>{{ __('trans.role') }}:</strong> {{ $user->roles->first()?->name }}</p>
                    <p class="mb-1"><strong>{{ __('trans.birth_date') }}:</strong> {{ $user->birth_date }}</p>
                    <p class="mb-2">
                        <strong>{{ __('trans.status') }}:</strong>
                        @if ($user->status)
                            <span class="badge bg-success">{{ __('trans.active') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('trans.inactive') }}</span>
                        @endif
                    </p>
                    <div class="d-flex justify-content-end gap-2">
                        @can('users_view')
                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        @endcan
                        @can('users_update')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endcan
                        @can('users_delete')
                            <form action="{{ route('users.delete', $user) }}" method="POST" onsubmit="return confirm('{{ __('trans.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
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
</div>
@endsection
