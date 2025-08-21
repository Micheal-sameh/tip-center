@extends('layouts.sideBar')

@section('content')
    <div class="container-fluid px-4 mt-4" style="width:93%">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-gradient m-0">
                <i class="fas fa-user-plus me-2 text-success"></i> {{ __('trans.create_user') }}
            </h4>
            <a href="{{ route('users.index') }}" class="btn btn-outline-dark btn-sm">
                <i class="fas fa-arrow-left me-1"></i> {{ __('trans.back') }}
            </a>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow rounded-4 bg-white">
            <div class="card-body px-5 py-4">

                <form id="userForm" method="POST" action="{{ route('users.store') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="mb-4 row">
                        <label class="col-md-2 col-form-label fw-medium text-muted">{{ __('trans.name') }}</label>
                        <div class="col-md-10">
                            <input type="text" name="name"
                                class="form-control form-control-lg border border-success-subtle shadow-sm rounded-3"
                                placeholder="{{ __('trans.name') }}" value="{{ old('name') }}" required>
                            @if ($errors->has('name'))
                                <div class="text-danger small mt-1">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-4 row">
                        <label class="col-md-2 col-form-label fw-medium text-muted">{{ __('trans.email') }}</label>
                        <div class="col-md-10">
                            <input type="email" name="email"
                                class="form-control form-control-lg border border-primary-subtle shadow-sm rounded-3"
                                placeholder="{{ __('trans.email') }}" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <div class="text-danger small mt-1">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="mb-4 row">
                        <label class="col-md-2 col-form-label fw-medium text-muted">{{ __('trans.phone') }}</label>
                        <div class="col-md-10">
                            <input type="text" name="phone"
                                class="form-control form-control-lg border border-primary-subtle shadow-sm rounded-3"
                                placeholder="{{ __('trans.phone') }}" value="{{ old('phone') }}" required>
                            @if ($errors->has('phone'))
                                <div class="text-danger small mt-1">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Birth Date (optional, will be removed if empty) --}}
                    <div class="mb-4 row">
                        <label class="col-md-2 col-form-label fw-medium text-muted">{{ __('trans.birth_date') }}</label>
                        <div class="col-md-10">
                            <input type="date" name="birth_date"
                                class="form-control form-control-lg border border-info-subtle shadow-sm rounded-3"
                                value="{{ old('birth_date') }}">
                            @if ($errors->has('birth_date'))
                                <div class="text-danger small mt-1">{{ $errors->first('birth_date') }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="mb-5 row">
                        <label class="col-md-2 col-form-label fw-medium text-muted">{{ __('trans.role') }}</label>
                        <div class="col-md-10">
                            <select name="role_id"
                                class="form-select form-select-lg border border-secondary-subtle shadow-sm rounded-3"
                                required>
                                <option value="">{{ __('trans.select_role') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ __($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('role'))
                                <div class="text-danger small mt-1">{{ $errors->first('role') }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3">
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-check me-1"></i> {{ __('trans.save') }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-dark px-4 py-2 rounded-pill shadow-sm">
                            {{ __('trans.cancel') }}
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- JS: remove empty fields before submit -->
    <script>
        document.getElementById('userForm').addEventListener('submit', function () {
            this.querySelectorAll('input, select, textarea').forEach(function (el) {
                if (!el.value) {
                    el.removeAttribute('name'); // prevents sending empty values
                }
            });
        });
    </script>

    <!-- Optional Gradient Style -->
    <style>
        .text-gradient {
            background: linear-gradient(to right, #16a085, #27ae60);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endsection
