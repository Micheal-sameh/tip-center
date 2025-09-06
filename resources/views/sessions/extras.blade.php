@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 900px;">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>
                    Update Extras for Session #{{ $session->id }} - {{ $session->professor->name }} -
                    {{ App\Enums\StagesEnum::getStringValue($session->stage) }}
                </h5>
                <a href="{{ route('sessions.index') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card-body">
                <form id="extra-form" action="{{ route('sessions.extras', $session->id) }}" method="POST">
                    @csrf
                    @method('put')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="markers" class="form-label fw-bold">
                                <i class="fas fa-marker me-1 text-secondary"></i> Markers
                            </label>
                            <input type="number" class="form-control" id="markers" name="markers" min="0"
                                value="{{ old('markers') }}" placeholder="{{ $session->sessionExtra->markers }}">
                        </div>

                        <div class="col-md-6">
                            <label for="copies" class="form-label fw-bold">
                                <i class="fas fa-copy me-1 text-secondary"></i> Copies
                            </label>
                            <input type="number" class="form-control" id="copies" name="copies" min="0"
                                value="{{ old('copies') }}" placeholder="{{ $session->sessionExtra->copies }}">
                        </div>

                        <div class="col-md-6">
                            <label for="cafeterea" class="form-label fw-bold">
                                <i class="fas fa-coffee me-1 text-secondary"></i> cafeterea
                            </label>
                            <input type="number" class="form-control" id="cafeterea" name="cafeterea" min="0"
                                value="{{ old('cafeterea') }}" placeholder="{{ $session->sessionExtra->cafeterea ?? 0 }}">
                        </div>

                        <div class="col-md-6">
                            <label for="other" class="form-label fw-bold">
                                <i class="fas fa-ellipsis-h me-1 text-secondary"></i> Other
                            </label>
                            <input type="number" class="form-control" id="other" name="other" min="0"
                                value="{{ old('other') }}" placeholder="{{ $session->sessionExtra->other }}">
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label fw-bold">
                                <i class="fas fa-sticky-note me-1 text-secondary"></i> Notes
                            </label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                placeholder="{{ $session->sessionExtra->notes }}">{{ old('notes', $session->sessionExtra->notes ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Extras
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('extra-form');
            if (!form) return;

            form.addEventListener('submit', function() {
                const inputs = form.querySelectorAll('input, select, textarea');

                inputs.forEach(input => {
                    const type = input.type;
                    const shouldIgnore =
                        type === 'hidden' || type === 'submit' ||
                        input.disabled || !input.name;

                    if (shouldIgnore) return;

                    if (input.value.trim() === '') {
                        input.remove();
                    }
                });
            });
        });
    </script>
@endpush
