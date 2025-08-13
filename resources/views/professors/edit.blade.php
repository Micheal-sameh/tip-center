@extends('layouts.sideBar')

@section('content')
    <style>
        @media (max-width: 768px) {
            .form-wrapper {
                min-height: 100vh;
            }
        }

        .stage-row {
            border-bottom: 1px solid #ddd;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
    </style>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container form-wrapper d-flex justify-content-center align-items-center py-4">
        <div class="card shadow-lg border-0 w-100" style="max-width: 700px;">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                <h5 class="mb-0">
                    <i class="fas fa-user-edit me-2"></i>{{ __('Edit Professor') }}
                </h5>
                <a href="{{ route('professors.index') }}" class="btn btn-sm btn-light border">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('Back') }}
                </a>
            </div>

            <div class="card-body bg-white px-4 py-4">
                <form action="{{ route('professors.update', $professor->id) }}" method="POST" class="needs-validation"
                    novalidate id="professor-form">
                    @csrf
                    @method('PUT')

                    @php
                        $inputs = [
                            ['name' => 'name', 'label' => 'Name', 'required' => true],
                            ['name' => 'phone', 'label' => 'Phone', 'required' => true],
                            ['name' => 'optional_phone', 'label' => 'Optional Phone', 'required' => false],
                            ['name' => 'subject', 'label' => 'Subject', 'required' => true],
                            ['name' => 'school', 'label' => 'School', 'required' => true],
                        ];
                    @endphp

                    @foreach ($inputs as $input)
                        @php $value = old($input['name'], $professor->{$input['name']}); @endphp
                        <div class="mb-3">
                            <label for="{{ $input['name'] }}" class="form-label fw-semibold">
                                {{ __($input['label']) }}
                            </label>
                            <input type="{{ $input['type'] ?? 'text' }}" name="{{ $input['name'] }}"
                                id="{{ $input['name'] }}"
                                class="form-control shadow-sm @error($input['name']) is-invalid @enderror"
                                value="{{ $value }}" {{ $input['required'] ? 'required' : '' }}>
                            @error($input['name'])
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endforeach
                    <div class="mb-3">
                        <label for="birth_date" class="form-label fw-semibold">Birth Date</label>
                        <input type="date" name="birth_date" id="birth_date"
                            class="form-control shadow-sm @error('birth_date') is-invalid @enderror"
                            value="{{ old('birth_date', isset($professor) ? \Carbon\Carbon::parse($professor->birth_date)->format('Y-m-d') : '') }}"
                            required>
                        @error('birth_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- <div class="mb-3">
                        <label for="type" class="form-label fw-semibold">Professor Type</label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror"
                            required>
                            @foreach (App\Enums\SessionType::all() as $type)
                                <option
                                    value="{{ $type['value'] }}"{{ old('type', $professor->type ?? '') == $type['value'] ? 'selected' : '' }}>
                                    {{ $type['name'] }} </option>
                            @endforeach
                        </select>
                        @error('type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> --}}


                    {{-- Stages with Day and Time --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Teaching Stages & Schedule</label>

                        <div id="stage-schedule-wrapper">
                            @php $schedules = old('stage_schedules', $professor->stages->toArray()); @endphp
                            @foreach ($schedules as $i => $data)
                                @include('professors.partial.stage_schedule_row', [
                                    'index' => $i,
                                    'data' => $data,
                                ])
                            @endforeach
                        </div>

                        <div id="stage-schedule-template" class="d-none">
                            @include('professors.partial.stage_schedule_row', [
                                'index' => '__INDEX__',
                                'data' => [],
                            ])
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-stage-row">
                            <i class="fas fa-plus me-1"></i> Add Another Stage
                        </button>

                        @error('stage_schedules')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success shadow">
                            <i class="fas fa-save me-1"></i> {{ __('Update Professor') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let index = {{ count(old('stage_schedules', $professor->stages)) }};

                document.getElementById('add-stage-row').addEventListener('click', function() {
                    const template = document.getElementById('stage-schedule-template').innerHTML;
                    const newRowHtml = template.replace(/__INDEX__/g, index);
                    document.getElementById('stage-schedule-wrapper').insertAdjacentHTML('beforeend',
                        newRowHtml);
                    index++;
                });

                document.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-stage-row')) {
                        e.target.closest('.stage-row').remove();
                    }
                });

                document.getElementById('professor-form').addEventListener('submit', function(e) {
                    const form = e.target;
                    form.querySelectorAll('input:not([type=hidden]):not([type=checkbox])').forEach(input => {
                        if (!input.required && input.value.trim() === '') {
                            input.disabled = true;
                        }
                    });

                    form.querySelectorAll('.stage-row').forEach(row => {
                        const stage = row.querySelector('[name^="stage_schedules"][name$="[stage]"]');
                        const day = row.querySelector('[name^="stage_schedules"][name$="[day]"]');
                        const from = row.querySelector('[name^="stage_schedules"][name$="[from]"]');
                        const to = row.querySelector('[name^="stage_schedules"][name$="[to]"]');

                        if (!stage?.value || !day?.value || !from?.value || !to?.value) {
                            stage.disabled = true;
                            day.disabled = true;
                            from.disabled = true;
                            to.disabled = true;
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
