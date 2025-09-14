@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="width: 95%;">
        <h2 class="mb-4">{{ __('messages.Application Settings') }}</h2>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Settings Form --}}
        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="mb-4">
            @csrf
            @method('PUT')

            <div class="row">
                @foreach ($settings as $setting)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $setting->name }}</h5>

                                {{-- Value Field --}}
                                <div class="mb-3">
                                    @if ($setting->type === 'file')
                                        <input type="file" name="settings[{{ $setting->id }}][value]"
                                            class="form-control">
                                    @else
                                        <input type="text" name="settings[{{ $setting->id }}][value]"
                                            value="{{ $setting->value }}" class="form-control">
                                    @endif
                                </div>

                                {{-- Type (Disabled) --}}
                                <div class="mb-2">
                                    <input type="text" value="{{ $setting->type }}" class="form-control" disabled>
                                </div>

                                {{-- Hidden name --}}
                                <input type="hidden" name="settings[{{ $setting->id }}][name]"
                                    value="{{ $setting->name }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> {{ __('messages.save') }}
                </button>

            </div>
        </form>
        <!-- Reset Button -->
        @can('reset_year')
            @if (now()->month == 8)
                {{-- 7 = July --}}
                <!-- Reset Year Button (triggers modal) -->
                <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#confirmResetModal">
                    <i class="fas fa-sync-alt me-1"></i> Reset Year
                </button>
            @endif
        @endcan


        <!-- Modal -->
        <div class="modal fade" id="confirmResetModal" tabindex="-1" aria-labelledby="confirmResetModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('reset.year') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmResetModalLabel">Confirm Reset</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <p class="text-danger fw-bold">
                                This will reset all sessions, session extras, and session students and change the student
                                stages.
                                Are you sure you want to continue?
                            </p>

                            <div class="mb-3">
                                <label for="password" class="form-label">Enter your password to confirm:</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sync-alt me-1"></i> Confirm Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
