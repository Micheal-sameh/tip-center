@extends('layouts.sideBar')

@section('content')
    <div class="container" style="width: 90%;">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>{{ __('trans.users') }}</h4>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                {{ __('trans.create_user') }}
            </a>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('trans.name') }}</th>
                        <th>{{ __('trans.phone') }}</th>
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
                            <td>{{ $user->birth_date }}</td>
                            <td>
                                @if ($user->status)
                                    <span class="badge bg-success">{{ __('trans.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('trans.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('users.delete', $user) }}" method="POST" class="d-inline-block"
                                    onsubmit="return confirm('{{ __('trans.confirm_delete') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
@endsection
