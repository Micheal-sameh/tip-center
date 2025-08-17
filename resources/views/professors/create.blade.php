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
                    <i class="fas fa-user-plus me-2"></i>{{ __('Create Professor') }}
                </h5>
                <a href="{{ route('professors.index') }}" class="btn btn-sm btn-light border">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('Back') }}
                </a>
            </div>

            <div class="card-body bg-white px-4 py-4">
                <form action="{{ route('professors.store') }}" method="POST" class="needs-validation" novalidate
                    id="professor-form">
                    @csrf

                    @php
                        $inputs = [
                            ['name' => 'name', 'label' => 'Name', 'required' => true],
                            ['name' => 'phone', 'label' => 'Phone', 'required' => true],
                            ['name' => 'optional_phone', 'label' => 'Optional Phone', 'required' => false],
                            ['name' => 'subject', 'label' => 'Subject', 'required' => true],
                            ['name' => 'school', 'label' => 'School', 'required' => true],
                            ['name' => 'birth_date', 'label' => 'Birth Date', 'type' => 'date', 'required' => false],
                        ];
                        $weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
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
                    {{-- <div class="mb-3">
                        <label for="type" class="form-label fw-semibold">Professor Type</label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror"
                            required>
                            <option value="">Choose type...</option>
                            @foreach (App\Enums\SessionType::all() as $type)
                                <option value="{{ $type['value'] }}"> {{ $type['name'] }} </option>
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
                            @if (old('stage_schedules'))
                                @foreach (old('stage_schedules') as $i => $data)
                                    @include('professors.partial.stage_schedule_row', [
                                        'index' => $i,
                                        'data' => $data,
                                    ])
                                @endforeach
                            @else
                                @include('professors.partial.stage_schedule_row', ['index' => 0])
                            @endif
                        </div>

                        {{-- Hidden template for cloning --}}
                        <div id="stage-schedule-template" class="d-none">
                            <div class="stage-row row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Stage</label>
                                    <select class="form-select" data-name="stage_schedules[__INDEX__][stage]">
                                        <option value="">Choose...</option>
                                        @foreach (App\Enums\StagesEnum::all() as $stage)
                                            <option value="{{ $stage['value'] }}">{{ $stage['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Day</label>
                                    <select class="form-select" data-name="stage_schedules[__INDEX__][day]">
                                        <option value="">Choose...</option>
                                        @foreach ($weekdays as $day)
                                            <option value="{{ strtolower($day) }}">{{ $day }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">From</label>
                                    <input type="time" class="form-control" data-name="stage_schedules[__INDEX__][from]">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">To</label>
                                    <input type="time" class="form-control" data-name="stage_schedules[__INDEX__][to]">
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn btn-danger remove-stage-row">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
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
                            <i class="fas fa-save me-1"></i> {{ __('Save Professor') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let index = {{ old('stage_schedules') ? count(old('stage_schedules')) : 1 }};

                document.getElementById('add-stage-row').addEventListener('click', function() {
                    const template = document.getElementById('stage-schedule-template').innerHTML;
                    const newRow = document.createElement('div');
                    newRow.innerHTML = template.replace(/__INDEX__/g, index);

                    // Assign real name attributes
                    newRow.querySelectorAll('[data-name]').forEach(el => {
                        el.setAttribute('name', el.getAttribute('data-name').replace(/__INDEX__/g,
                            index));
                        el.removeAttribute('data-name');
                    });

                    document.getElementById('stage-schedule-wrapper').appendChild(newRow.firstElementChild);
                    index++;
                });

                document.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-stage-row')) {
                        e.target.closest('.stage-row').remove();
                    }
                });
            });
            document.getElementById('professor-form').addEventListener('submit', function(e) {
                const form = e.target;

                // Disable empty non-required text/date inputs
                form.querySelectorAll('input:not([type=hidden]):not([type=checkbox])').forEach(input => {
                    if (!input.required && input.value.trim() === '') {
                        input.disabled = true;
                    }
                });

                // Disable incomplete stage schedule rows
                form.querySelectorAll('.stage-row').forEach(row => {
                    const stage = row.querySelector('[name^="stage_schedules"][name$="[stage]"]');
                    const day = row.querySelector('[name^="stage_schedules"][name$="[day]"]');
                    const from = row.querySelector('[name^="stage_schedules"][name$="[from]"]');
                    const to = row.querySelector('[name^="stage_schedules"][name$="[to]"]');

                    // If any field is missing → disable the whole row
                    if (!stage?.value || !day?.value || !from?.value || !to?.value) {
                        [stage, day, from, to].forEach(el => {
                            if (el) el.disabled = true;
                        });
                    }
                });
            });
        </script>
    @endpush

@endsection
