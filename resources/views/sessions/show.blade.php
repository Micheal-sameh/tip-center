@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 1200px;">
        <!-- student Details Card -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
            <!-- Card Header with Gradient Background -->
            <div class="bg-gradient-primary text-white px-4 py-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">{{ __('trans.student_details') }}</h4>
                    </div>
                </div>
                <a href="{{ route('students.index') }}" class="btn btn-outline-light btn-sm rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('trans.back') }}
                </a>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">
                <div class="row">
                    <!-- student Avatar Column -->
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="position-relative d-inline-block">
                            <div class="rounded-circle bg-light border border-4 border-primary d-flex align-items-center justify-content-center mx-auto"
                                 style="width: 160px; height: 160px; background-color: #f0f8ff;">
                                <i class="fas fa-user-tie fa-4x text-primary"></i>
                            </div>
                        </div>
                        <h3 class="mt-4 text-primary fw-bold">{{ $student->name }}</h3>
                        <h5 class="mt-4 text-primary fw-bold">{{ $student->code }}</h5>
                    </div>

                    <!-- student Info Column -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card mb-3 p-3 rounded-3 bg-light">
                                    <h6 class="text-muted mb-2"></i>{{ __('trans.code') }}</h6>
                                    <p class="mb-0 fw-bold">{{ $student->code }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card mb-3 p-3 rounded-3 bg-light">
                                    <h6 class="text-muted mb-2"><i class="fas fa-phone me-2"></i>{{ __('trans.phone') }}</h6>
                                    <p class="mb-0 fw-bold">{{ $student->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card mb-3 p-3 rounded-3 bg-light">
                                    <h6 class="text-muted mb-2"><i class="fas fa-birthday-cake me-2"></i>{{ __('trans.birth_date') }}</h6>
                                    <p class="mb-0 fw-bold">{{ $student->birth_date }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card mb-3 p-3 rounded-3 bg-light">
                                    <h6 class="text-muted mb-2"><i class="fas fa-phone me-2"></i>{{ __('trans.parent_phone') }}</h6>
                                    <p class="mb-0 fw-bold">{{ $student->parent_phone }}</p>
                                    <p class="mb-0 fw-bold">{{ $student->parent_phone_2 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card mb-3 p-3 rounded-3 bg-light">
                                    <h6 class="text-muted mb-2"><i class="fas fa-book me-2"></i>{{ __('trans.stage') }}</h6>
                                    <p class="mb-0 fw-bold">{{ App\Enums\StagesEnum::getStringValue($student->stage) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card mb-3 p-3 rounded-3 bg-light">
                                    <h6 class="text-muted mb-2"><i class="fas fa-note me-2"></i>{{ __('trans.note') }}</h6>
                                    <p class="mb-0 fw-bold">{{ $student->note }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stages Section -->
                <div class="mt-4 pt-3 border-top">
                    <h5 class="text-muted fw-semibold mb-3 d-flex align-items-center">
                        <i class="fas fa-graduation-cap me-2"></i> {{ __('trans.stages') }}
                    </h5>
                    @if ($student->stages && count($student->stages))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($student->stages as $stage)
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill border border-primary border-opacity-25">
                                    <i class="fas fa-layer-group me-1"></i>
                                    {{ \App\Enums\stagesEnum::getStringValue($stage->stage) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-light border d-inline-block">
                            <i class="fas fa-info-circle me-2"></i> {{ __('trans.no_stages_assigned') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end gap-3 py-3 px-4">
                @can('students_update')
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-edit me-2"></i> {{ __('trans.edit') }}
                    </a>
                @endcan
                @can('students_delete')
                    <form action="{{ route('students.delete', $student->id) }}" method="POST"
                        onsubmit="return confirm('{{ __('trans.delete_confirm') }}')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger rounded-pill px-4">
                            <i class="fas fa-trash-alt me-2"></i> {{ __('trans.delete') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
        }
        .info-card {
            transition: all 0.3s ease;
            height: 100%;
        }
        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .rounded-4 {
            border-radius: 1rem !important;
        }
    </style>
@endsection