@extends('layouts.sideBar')

@section('content')
    <style>
        @media (max-width: 768px) {
            .form-wrapper {
                min-height: 100vh;
            }
        }
    </style>

    <div class="container form-wrapper d-flex justify-content-center align-items-center py-4">
        <div class="card shadow-lg border-0 w-100" style="max-width: 650px;">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center rounded-top">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>{{ __('Edit Professor') }}
                </h5>
                <a href="{{ route('professors.index') }}" class="btn btn-sm btn-light border">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('Back') }}
                </a>
            </div>

            <div class="card-body bg-white px-4 py-4">
                <form action="{{ route('professors.update', $professor->id) }}" method="POST" class="needs-validation"
                    novalidate id="edit-professor-form">
                    @csrf
                    @method('PUT')

                    @php
                        $inputs = [
                            ['name' => 'name', 'label' => 'Name', 'type' => 'text'],
                            ['name' => 'phone', 'label' => 'Phone', 'type' => 'text'],
                            ['name' => 'optional_phone', 'label' => 'Optional Phone', 'type' => 'text'],
                            ['name' => 'subject', 'label' => 'Subject', 'type' => 'text'],
                            ['name' => 'school', 'label' => 'School', 'type' => 'text'],
                            ['name' => 'birth_date', 'label' => 'Birth Date', 'type' => 'date'],
                        ];
                    @endphp

                    @foreach ($inputs as $input)
                        <div class="mb-3">
                            <label for="{{ $input['name'] }}" class="form-label fw-semibold">
                                {{ __($input['label']) }}
                            </label>

                            @if ($input['name'] === 'name')
                                <input type="text" name="name_disabled" id="name_disabled" class="form-control shadow-sm"
                                    value="{{ $professor->name }}" disabled>
                                <input type="hidden" name="name" value="{{ $professor->name }}">
                            @else
                                <input type="{{ $input['type'] }}" name="{{ $input['name'] }}" id="{{ $input['name'] }}"
                                    class="form-control shadow-sm @error($input['name']) is-invalid @enderror"
                                    value="{{ old($input['name'], $professor->{$input['name']}) }}"
                                    data-original="{{ $professor->{$input['name']} }}">
                                @error($input['name'])
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            @endif
                        </div>
                    @endforeach

                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Stages') }}</label>

                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button"
                                data-bs-toggle="dropdown">
                                {{ __('Select Stages') }}
                            </button>
                            <ul class="dropdown-menu p-2 dropdown-checkbox shadow-sm">
                                @foreach (App\Enums\StagesEnum::all() as $stage)
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="stages[]"
                                                value="{{ $stage['value'] }}" id="stage_{{ $stage['value'] }}"
                                                {{ in_array($stage['value'], old('stages', $professor->stages->pluck('stage')->toArray() ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="stage_{{ $stage['value'] }}">
                                                {{ $stage['name'] }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">{{ __('Status') }}</label>
                    <select name="status" id="status" class="form-select shadow-sm" data-original="{{ $professor->status }}">
                        <option value="1" {{ $professor->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="0" {{ $professor->status == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    </select>
                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                </div> --}}

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
            document.getElementById('edit-professor-form').addEventListener('submit', function(e) {
                const fields = this.querySelectorAll('input, select');
                fields.forEach(field => {
                    const original = field.dataset.original;
                    const current = field.value.trim();
                    if (original !== undefined && original === current) {
                        field.disabled = true;
                    }
                });
            });
        </script>
    @endpush
@endsection
