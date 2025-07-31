@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 850px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ __('Create New Session') }}</h4>
                <a href="{{ route('sessions.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('Back') }}
                </a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('sessions.store') }}">
                    @csrf

                    <!-- Professor Information (read-only) -->
                    <div class="mb-4">
                        <h5 class="mb-3 text-muted"><i class="fas fa-chalkboard-teacher me-2"></i> Professor Information</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{ $professor->name ?? 'Not selected' }}" readonly>
                                            <label>Professor Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="hidden" name="professor_id" value="{{ $professor->id ?? '' }}">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Session Details -->
                    <div class="mb-4">
                        <h5 class="mb-3 text-muted"><i class="fas fa-calendar-alt me-2"></i> Session Details</h5>
                        <div class="row g-3">
                            <!-- Stage -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select id="stage" name="stage" class="form-select @error('stage') is-invalid @enderror" required>
                                        <option value="" disabled selected>{{ __('Select Stage') }}</option>
                                        @foreach($stages as $stage)
                                            <option value="{{ $stage->stage }}">
                                                {{ App\Enums\StagesEnum::getStringValue($stage->stage )}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="stage">{{ __('Stage') }}</label>
                                    @error('stage')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" name="professor_price" id="professor_price"
                                           value="{{ old('professor_price') }}" placeholder="0.00"
                                           class="form-control @error('professor_price') is-invalid @enderror" required>
                                    <label for="professor_price">{{ __('Professor Price') }}</label>
                                    @error('professor_price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" name="center_price" id="center_price"
                                           value="{{ old('center_price') }}" placeholder="0.00"
                                           class="form-control @error('center_price') is-invalid @enderror" required>
                                    <label for="center_price">{{ __('Center Price') }}</label>
                                    @error('center_price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" name="printables" id="printables"
                                           value="{{ old('printables') }}" placeholder="0.00"
                                           class="form-control @error('printables') is-invalid @enderror">
                                    <label for="printables">{{ __('Printables Cost') }}</label>
                                    @error('printables')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timing -->
                    <div class="mb-4">
                        <h5 class="mb-3 text-muted"><i class="fas fa-clock me-2"></i> Timing (Optional)</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="time" name="start_at" id="start_at" value="{{ old('start_at') }}"
                                           class="form-control @error('start_at') is-invalid @enderror">
                                    <label for="start_at">{{ __('Start Time') }}</label>
                                    @error('start_at')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="time" name="end_at" id="end_at" value="{{ old('end_at') }}"
                                           class="form-control @error('end_at') is-invalid @enderror">
                                    <label for="end_at">{{ __('End Time') }}</label>
                                    @error('end_at')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> {{ __('Create Session') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection