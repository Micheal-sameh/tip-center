@extends('layouts.sideBar')

@section('content')
    <style>
        @media (max-width: 768px) {
            .form-wrapper {
                min-height: 100vh;
            }
        }

        .dropdown-checkbox {
            max-height: 250px;
            overflow-y: auto;
        }

        .dropdown-menu {
            width: 100%;
        }

        .dropdown-toggle::after {
            margin-left: 0.5rem;
        }
    </style>

    <div class="container form-wrapper d-flex justify-content-center align-items-center py-4">
        <div class="card shadow-lg border-0 w-100" style="max-width: 650px;">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>{{ __('Create Professor') }}
                </h5>
                <a href="{{ route('professors.index') }}" class="btn btn-sm btn-light border">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('Back') }}
                </a>
            </div>

            <div class="card-body bg-white px-4 py-4">
                <form action="{{ route('professors.store') }}" method="POST" class="needs-validation" novalidate id="professor-form">
                    @csrf

                    @php
                        $inputs = [
                            ['name' => 'name', 'label' => 'Name', 'required' => true],
                            ['name' => 'phone', 'label' => 'Phone', 'required' => true],
                            ['name' => 'optional_phone', 'label' => 'Optional Phone', 'required' => false],
                            ['name' => 'subject', 'label' => 'Subject', 'required' => true],
                            ['name' => 'school', 'label' => 'School', 'required' => true],
                            ['name' => 'birth_date', 'label' => 'Birth Date', 'type' => 'date', 'required' => true],
                        ];
                    @endphp

                    @foreach ($inputs as $input)
                        <div class="mb-3">
                            <label for="{{ $input['name'] }}" class="form-label fw-semibold">
                                {{ __($input['label']) }}
                            </label>
                            <input type="{{ $input['type'] ?? 'text' }}" name="{{ $input['name'] }}"
                                   id="{{ $input['name'] }}"
                                   class="form-control shadow-sm @error($input['name']) is-invalid @enderror"
                                   value="{{ old($input['name']) }}" {{ $input['required'] ? 'required' : '' }}>
                            @error($input['name'])
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endforeach

                    {{-- Dropdown with Checkboxes --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Stages') }}</label>

                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                {{ __('Select Stages') }}
                            </button>
                            <ul class="dropdown-menu p-2 dropdown-checkbox shadow-sm">
                                @foreach (App\Enums\StagesEnum::all() as $stage)
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="stages[]"
                                                   value="{{ $stage['value'] }}"
                                                   id="stage_{{ $stage['value'] }}"
                                                   {{ in_array($stage['value'], old('stages', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="stage_{{ $stage['value'] }}">
                                                {{ $stage['name'] }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @error('stages')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success shadow">
                            <i class="fas fa-save me-1"></i> {{ __('Save Professor') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
<script>
    document.getElementById('professor-form').addEventListener('submit', function (e) {
        const form = e.target;

        // Remove empty text/date inputs (except required ones)
        form.querySelectorAll('input:not([type=hidden]):not([type=checkbox])').forEach(input => {
            if (!input.required && input.value.trim() === '') {
                input.disabled = true;
            }
        });

        // Remove hidden stages input if empty
        const stagesInput = document.getElementById('stages');
        if (stagesInput && stagesInput.value.trim() === '') {
            stagesInput.disabled = true;
        }
    });
</script>
@endpush

@endsection
