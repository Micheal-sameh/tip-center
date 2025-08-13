@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 850px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ __('Edit Session') }}</h4>
                <a href="{{ route('sessions.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('Back') }}
                </a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('sessions.update', $session->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Professor Information (disabled) -->
                    <div class="mb-4">
                        <h5 class="mb-3 text-muted"><i class="fas fa-chalkboard-teacher me-2"></i> Professor Information
                        </h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control-plaintext"
                                                value="{{ $session->professor->name }}" readonly>
                                            <label>Professor Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control-plaintext"
                                                value="{{ App\Enums\StagesEnum::getStringValue($session->stage) }}"
                                                readonly>
                                            <label>Stage</label>
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
                            <!-- Pricing -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" name="professor_price" id="professor_price"
                                        value="{{ old('professor_price', $session->professor_price) }}" placeholder="0.00"
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
                                        value="{{ old('center_price', $session->center_price) }}" placeholder="0.00"
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
                                        value="{{ old('printables', $session->printables) }}" placeholder="0.00"
                                        class="form-control @error('printables') is-invalid @enderror">
                                    <label for="printables">{{ __('Printables Cost') }}</label>
                                    @error('printables')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- materials Fees -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="1" name="materials" id="materials"
                                        value="{{ old('materials', $session->materials) }}" placeholder="0.00"
                                        class="form-control @error('materials') is-invalid @enderror">
                                    <label for="materials">{{ __('materials Fees') }}</label>
                                    @error('materials')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="integer" step="1" name="room" id="room Number"
                                        value="{{ old('room', $session->room) }}" placeholder="1"
                                        class="form-control @error('room Number') is-invalid @enderror">
                                    <label for="room Number">{{ __('room Number') }}</label>
                                    @error('room Number')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select id="type" name="type"
                                    class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="" disabled selected>{{ __('Select type') }}</option>
                                    @foreach (App\Enums\SessionType::all() as $type)
                                        <option value="{{ $type['value'] }}"
                                            {{ old('type') == $type['value'] ? 'selected' : '' }}>
                                            {{ $type['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="type">{{ __('type') }}</label>
                                @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <!-- Timing -->
                    <div class="mb-4">
                        <h5 class="mb-3 text-muted"><i class="fas fa-clock me-2"></i> Timing</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="time" name="start_at" id="start_at"
                                        value="{{ old('start_at', optional($session->start_at)->format('H:i')) }}"
                                        class="form-control @error('start_at') is-invalid @enderror">
                                    <label for="start_at">{{ __('Start Time') }}</label>
                                    @error('start_at')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="time" name="end_at" id="end_at"
                                        value="{{ old('end_at', optional($session->end_at)->format('H:i')) }}"
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
                            <i class="fas fa-save me-1"></i> {{ __('Update Session') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
