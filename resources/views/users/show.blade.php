@extends('layouts.sideBar')

@section('content')
    <div class="container py-5" style="width: 90%;">

        <!-- User Details Card -->
        <div class="card border-0 shadow rounded-4 overflow-hidden">

            <!-- Card Header -->
            <div class="bg-primary text-white px-4 py-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-circle fa-2x me-3"></i>
                    <h4 class="mb-0 me-2">{{ __('trans.user_details') }}</h4>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('trans.back') }}
                </a>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">
                <div class="row">
                    <!-- User Avatar -->
                    {{-- <div class="col-md-4 text-center mb-4">
                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto" style="width: 140px; height: 140px;">
                        <i class="fas fa-user fa-3x text-secondary"></i>
                    </div>
                    <h5 class="mt-3 text-primary fw-bold">{{ $user->name }}</h5>
                </div> --}}

                    <!-- User Info -->
                    <div class="col-md-8">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th class="text-muted">{{ __('trans.phone') }}</th>
                                <td class="text-dark">{{ $user->phone }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th class="text-muted">{{ __('trans.birth_date') }}</th>
                                <td class="text-dark">{{ $user->birth_date }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th class="text-muted">{{ __('trans.role') }}</th>
                                <td class="text-dark"> {{ $user->roles->first()?->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('trans.status') }}</th>
                                <td>
                                    @if ($user->status)
                                        <span class="badge bg-success px-3 py-1">{{ __('trans.active') }}</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-1">{{ __('trans.inactive') }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end mt-4 gap-2">
                    @can('users_update')
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> {{ __('trans.edit') }}
                        </a>
                    @endcan
                    @can('users_delete')
                        <form action="{{ route('users.delete', $user->id) }}" method="POST"
                            onsubmit="return confirm('{{ __('trans.delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> {{ __('trans.delete') }}
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

    </div>
@endsection
