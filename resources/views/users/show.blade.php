@extends('layouts.sideBar')

@section('content')
<div class="container py-4" style="max-width: 800px;">
    <!-- User Details Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <!-- Card Header -->
        <div class="card-header bg-primary text-white px-4 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-circle fa-lg me-3"></i>
                    <h4 class="mb-0">{{ __('trans.user_details') }}</h4>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('trans.back') }}
                </a>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body p-0">
            <div class="row g-0">
                <!-- User Avatar Column -->
                <div class="col-md-4 p-4 text-center border-end">
                    <div class="avatar-wrapper mx-auto mb-3">
                        <div class="avatar bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-user fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h5 class="text-primary fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small">{{ $user->email }}</p>

                    <!-- Status Badge -->
                    <div class="mt-3">
                        @if ($user->status == 1)
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                <i class="fas fa-circle me-1 small"></i> {{ __('trans.active') }}
                            </span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                <i class="fas fa-circle me-1 small"></i> {{ __('trans.inactive') }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- User Info Column -->
                <div class="col-md-8 p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th width="35%" class="text-muted ps-0">{{ __('trans.phone') }}</th>
                                    <td class="fw-medium">{{ $user->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted ps-0">{{ __('trans.birth_date') }}</th>
                                    <td class="fw-medium">{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted ps-0">{{ __('trans.role') }}</th>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1">
                                            {{ $user->roles->first()?->name ?? __('trans.no_role') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted ps-0">{{ __('trans.joined') }}</th>
                                    <td class="fw-medium">{{ $user->created_at->format('d M Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-4 pt-3 border-top">
                        @can('users_update')
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary px-4">
                                <i class="fas fa-edit me-2"></i> {{ __('trans.edit') }}
                            </a>
                        @endcan
                        @can('users_delete')
                            <form action="{{ route('users.delete', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger px-4" onclick="return confirm('{{ __('trans.delete_confirm') }}')">
                                    <i class="fas fa-trash me-2"></i> {{ __('trans.delete') }}
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-wrapper {
        width: 120px;
        height: 120px;
    }

    .avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 3px solid var(--bs-primary);
    }

    @media (max-width: 767.98px) {
        .col-md-4.border-end {
            border-right: none !important;
            border-bottom: 1px solid #dee2e6;
        }

        .avatar-wrapper {
            width: 100px;
            height: 100px;
        }
    }
</style>
@endsection