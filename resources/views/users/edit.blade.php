@extends('layouts.sideBar')

@section('content')
<div class="container-fluid px-4 mt-4" style="width:93%">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-gradient m-0">
            <i class="fas fa-user-edit me-2 text-warning"></i> {{ __('trans.edit_user') }}
        </h4>
        <a href="{{ route('users.index') }}" class="btn btn-outline-dark btn-sm">
            <i class="fas fa-arrow-left me-1"></i> {{ __('trans.back') }}
        </a>
    </div>

    <!-- Edit Form -->
    <div class="card border-0 shadow rounded-4 bg-white">
        <div class="card-body px-5 py-4">
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-4 row">
                    <label class="col-md-2 col-form-label fw-medium text-muted">{{ __('trans.name') }}</label>
                    <div class="col-md-10">
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="form-control form-control-lg border border-success-subtle shadow-sm rounded-3"
                               required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Phone --}}
                <div class="mb-4 row">
                    <label class="col-md-2 col-form-label fw-medium text-muted">{{ __('trans.phone') }}</label>
                    <div class="col-md-10">
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="form-control form-control-lg border border-primary-subtle shadow-sm rounded-3"
                               required>
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Birth Date --}}
                <div class="mb-4 row">
                    <label class="col-md-2 col-form-label fw-medium text-muted">{{ __('trans.birth_date') }}</label>
                    <div class="col-md-10">
                        <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}"
                               class="form-control form-control-lg border border-info-subtle shadow-sm rounded-3">
                        @error('birth_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-4 row">
                    <label class="col-md-2 col-form-label fw-medium text-muted">{{ __('trans.status') }}</label>
                    <div class="col-md-10">
                        <select name="status" class="form-select form-select-lg border shadow-sm rounded-3">
                            <option value="1" {{ $user->status ? 'selected' : '' }}>{{ __('trans.active') }}</option>
                            <option value="0" {{ !$user->status ? 'selected' : '' }}>{{ __('trans.inactive') }}</option>
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Role --}}
                <div class="mb-5 row">
                    <label class="col-md-2 col-form-label fw-medium text-muted">{{ __('trans.role') }}</label>
                    <div class="col-md-10">
                        <select name="role_id" class="form-select form-select-lg border border-secondary-subtle shadow-sm rounded-3" required>
                            <option value="">{{ __('trans.select_role') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ __($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-3">
                    <button type="submit" class="btn btn-warning text-white px-4 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-save me-1"></i> {{ __('trans.update') }}
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-dark px-4 py-2 rounded-pill shadow-sm">
                        {{ __('trans.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .text-gradient {
        background: linear-gradient(to right, #f39c12, #e67e22);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endsection
