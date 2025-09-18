@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 900px;">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>
                    Add online payment for Session #{{ $session->id }} - {{ $session->professor->name }} -
                    {{ App\Enums\StagesEnum::getStringValue($session->stage) }}
                </h5>
                <a href="{{ route('sessions.index') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card-body">
                <form id="extra-form" action="{{ route('sessions.online', $session->id) }}" method="POST">
                    @csrf
                    @method('put')
                    <input name= 'session_id'type='hidden' value='{{$session->id}}'>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-highlighter me-1 text-secondary"></i> name
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="materials" class="form-label fw-bold">
                                <i class="fas fa-file-alt me-1 text-secondary"></i> Materials
                            </label>
                            <input type="number" class="form-control" id="materials" name="materials"
                                value="{{ old('materials') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="professor" class="form-label fw-bold">
                                <i class="fas fa-mug-hot me-1 text-secondary"></i> Professor
                            </label>
                            <input type="number" class="form-control" id="professor" name="professor"
                                value="{{ old('professor') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="center" class="form-label fw-bold">
                                <i class="fas fa-hand-holding-usd me-1 text-secondary"></i> Center
                            </label>
                            <input type="number" class="form-control" id="center" name="center"
                                value="{{ old('center') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="stage" class="form-label fw-bold">
                                <i class="fas fa-print me-1 text-secondary"></i> Stage
                            </label>
                            <select class="form-select" id="stage" name="stage">
                                <option value="" disabled selected>Select Stage</option>
                                @foreach ($stages as $stage)
                                    <option value="{{ $stage['value'] }}"
                                        {{ old('stage') == $stage['value'] ? 'selected' : '' }}>{{ $stage['name'] }}
                                    </option>
                                @endforeach

                            </select>
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
