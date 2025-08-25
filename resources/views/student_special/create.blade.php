@extends('layouts.sideBar')

@section('content')
    <div class="container py-4 d-flex justify-content-center">
        <div class="card shadow-sm rounded-3" style="max-width: 650px; width: 100%;">
            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-3">
                <h6 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i> {{ __('trans.add_special_case') }}
                </h6>
                <a href="{{ route('students.index') }}" class="btn btn-outline-light btn-sm rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('trans.back') }}
                </a>
            </div>

            <!-- Form -->
            <div class="card-body p-3">
                <form action="{{ route('student-special-cases.store') }}" method="POST">
                    @csrf
                    <input name='student_id' type='hidden' value='{{$student->id}}'>
                    <!-- Student -->
                    <div class="mb-2">
                        <label class="form-label fw-bold small">{{ __('trans.student') }}</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $student->name }}" disabled>
                    </div>

                    <!-- Dynamic Special Cases -->
                    <div id="special-cases-wrapper">
                        <div class="special-case border rounded p-2 mb-2 bg-light">
                            <div class="row g-2 align-items-end">
                                <!-- Professor -->
                                <div class="col-md-6">
                                    <label class="form-label small">{{ __('trans.professor') }}</label>
                                    <select name="cases[0][professor_id]" class="form-select form-select-sm" required>
                                        <option value="">{{ __('trans.choose_professor') }}</option>
                                        @foreach ($professors as $professor)
                                            <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Professor Price -->
                                <div class="col-md-3">
                                    <label class="form-label small">{{ __('trans.professor_price') }}</label>
                                    <input type="number" step="0.01" name="cases[0][professor_price]" class="form-control form-control-sm">
                                </div>

                                <!-- Center Price -->
                                <div class="col-md-3">
                                    <label class="form-label small">{{ __('trans.center_price') }}</label>
                                    <input type="number" step="0.01" name="cases[0][center_price]" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Row Button -->
                    <button type="button" id="add-case" class="btn btn-sm btn-outline-primary rounded-pill">
                        <i class="fas fa-plus"></i> {{ __('trans.add_another_case') }}
                    </button>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary btn-sm rounded-pill">
                            <i class="fas fa-times me-1"></i> {{ __('trans.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-success btn-sm rounded-pill">
                            <i class="fas fa-save me-1"></i> {{ __('trans.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Template for JS -->
    <template id="special-case-template">
        <div class="special-case border rounded p-2 mb-2 bg-light">
            <div class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small">{{ __('trans.professor') }}</label>
                    <select name="cases[__INDEX__][professor_id]" class="form-select form-select-sm" required>
                        <option value="">{{ __('trans.choose_professor') }}</option>
                        @foreach ($professors as $professor)
                            <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">{{ __('trans.professor_price') }}</label>
                    <input type="number" step="0.01" name="cases[__INDEX__][professor_price]" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">{{ __('trans.center_price') }}</label>
                    <input type="number" step="0.01" name="cases[__INDEX__][center_price]" class="form-control form-control-sm">
                </div>
            </div>
        </div>
    </template>

    @push('scripts')
        <script>
            let caseIndex = 1;
            document.getElementById('add-case').addEventListener('click', function() {
                let template = document.getElementById('special-case-template').innerHTML;
                template = template.replace(/__INDEX__/g, caseIndex);
                document.getElementById('special-cases-wrapper').insertAdjacentHTML('beforeend', template);
                caseIndex++;
            });
        </script>
    @endpush
@endsection
