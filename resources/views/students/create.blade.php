@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="width:93%">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">{{ __('Create Student') }}</h2>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>{{ __('Whoops!') }}</strong> {{ __('There were some problems with your input.') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="student-form" action="{{ route('students.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="{{ __('Name') }}" required value="{{ old('name') }}">
                                <label for="name">{{ __('Name') }}</label>
                                <div class="form-text">Enter the student's full name</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="stage" class="form-select" id="stage" required>
                                    <option value="" selected disabled>Select Stage</option>
                                    @foreach (App\Enums\StagesEnum::all() as $stage)
                                        <option value="{{ $stage['value'] }}"
                                            @if (old('stage') == $stage['value']) selected @endif>
                                            {{ $stage['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="stage">{{ __('Stage') }}</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="tel" name="phone" class="form-control" id="phone"
                                    placeholder="{{ __('Phone') }}" value="{{ old('phone') }}">
                                <label for="phone">{{ __('Phone') }}</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="tel" name="parent_phone" class="form-control" id="parent_phone"
                                    placeholder="{{ __('Parent Phone') }}" value="{{ old('parent_phone') }}">
                                <label for="parent_phone">{{ __('Parent Phone') }}</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="tel" name="parent_phone_2" class="form-control" id="parent_phone_2"
                                    placeholder="{{ __('Parent Phone 2') }}" value="{{ old('parent_phone_2') }}">
                                <label for="parent_phone_2">{{ __('Parent Phone 2') }}</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea name="note" class="form-control" id="note" placeholder="{{ __('Note') }}" style="height: 100px">{{ old('note') }}</textarea>
                                <label for="note">{{ __('Note') }}</label>
                                <div class="form-text">Any additional information about the student (optional)</div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-save me-2"></i>{{ __('Save Student') }}
                            </button>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary px-4 py-2 ms-2">
                                <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-floating label {
            padding: 1rem 0.75rem;
        }

        .form-floating>.form-control:not(:placeholder-shown)~label,
        .form-floating>.form-control:focus~label,
        .form-floating>.form-select~label {
            opacity: 0.8;
            transform: scale(0.85) translateY(-0.8rem) translateX(0.15rem);
        }
    </style>
    <script>
        document.getElementById('student-form').addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.disabled = true;
                }
            });
        });
    </script>

@endsection
