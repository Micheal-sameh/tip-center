@extends('layouts.sideBar')

@section('content')
<div class="container py-4" style="width:93%">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">{{ __('Student Details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">{{ __('Students') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $student->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>{{ __('Edit') }}
            </a>
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('Back') }}
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Student Info -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar avatar-xl me-4">
                            <span class="avatar-text bg-primary rounded-circle">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h2 class="mb-1">{{ $student->name }}</h2>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ App\Enums\StagesEnum::getStringValue($student->stage) }}
                                </span>
                                @if($student->isBirthdayToday())
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-cake-candles me-1"></i> {{ __('Birthday Today') }}
                                </span>
                                @endif
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    ID: {{ $student->code }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title text-muted mb-3">
                                        <i class="fas fa-id-card me-2"></i>{{ __('Contact Information') }}
                                    </h5>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <i class="fas fa-phone me-2 text-muted"></i>
                                            @if($student->phone)
                                                <a href="tel:{{ $student->phone }}">{{ $student->phone }}</a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </li>
                                        {{-- <li class="mb-2">
                                            <i class="fas fa-envelope me-2 text-muted"></i>
                                            @if($student->email)
                                                <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </li> --}}
                                        {{-- <li>
                                            <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                            @if($student->address)
                                                {{ $student->address }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </li> --}}
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Parent Information -->
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title text-muted mb-3">
                                        <i class="fas fa-user-tie me-2"></i>{{ __('Parent Information') }}
                                    </h5>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <i class="fas fa-phone me-2 text-muted"></i>
                                            @if($student->parent_phone)
                                                <a href="tel:{{ $student->parent_phone }}">{{ $student->parent_phone }}</a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </li>
                                        <li>
                                            <i class="fas fa-phone me-2 text-muted"></i>
                                            @if($student->parent_phone_2)
                                                <a href="tel:{{ $student->parent_phone_2 }}">{{ $student->parent_phone_2 }}</a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title text-muted mb-3">
                                        <i class="fas fa-info-circle me-2"></i>{{ __('Additional Information') }}
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <p class="mb-1 text-muted">{{ __('Birth Date') }}</p>
                                            <p class="mb-0">
                                                @if($student->birth_date)
                                                    {{ $student->birth_date }} ({{ $student->age }} years)
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <p class="mb-1 text-muted">{{ __('Gender') }}</p>
                                            <p class="mb-0">
                                                @if($student->gender)
                                                    {{ ucfirst($student->gender) }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">{{ __('Registration Date') }}</p>
                                            <p class="mb-0">
                                                {{ $student->created_at->format('Y-m-d') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        @if($student->note)
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title text-muted mb-3">
                                        <i class="fas fa-sticky-note me-2"></i>{{ __('Notes') }}
                                    </h5>
                                    <div class="bg-white p-3 rounded border">
                                        {!! nl2br(e($student->note)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar with Actions and Stats -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    {{-- <h5 class="card-title text-muted mb-3">{{ __('Quick Actions') }}</h5>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary text-start">
                            <i class="fas fa-file-invoice me-2"></i> {{ __('Generate Report') }}
                        </a>
                        <a href="#" class="btn btn-outline-success text-start">
                            <i class="fas fa-money-bill-wave me-2"></i> {{ __('Record Payment') }}
                        </a>
                        <a href="#" class="btn btn-outline-info text-start">
                            <i class="fas fa-calendar-check me-2"></i> {{ __('Schedule Session') }}
                        </a> --}}
                        <a href="{{ route('students.edit', $student)}}" class="btn btn-outline-info text-start">
                            <i class="fas fa-edit me-2"></i> {{ __('Edit') }}
                        </a>
                        <form action="{{ route('students.delete', $student) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger text-start"
                                onclick="return confirm('{{ __('Are you sure you want to delete this student?') }}')">
                                <i class="fas fa-trash me-2"></i> {{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- <!-- Student Stats -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-muted mb-3">{{ __('Student Statistics') }}</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar-day me-2 text-primary"></i> {{ __('Sessions Attended') }}</span>
                            <span class="badge bg-primary rounded-pill">24</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-money-bill-wave me-2 text-success"></i> {{ __('Total Payments') }}</span>
                            <span class="badge bg-success rounded-pill">$1,200</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-clock me-2 text-warning"></i> {{ __('Last Session') }}</span>
                            <span class="text-muted small">2 days ago</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-star me-2 text-info"></i> {{ __('Progress Level') }}</span>
                            <span class="badge bg-info rounded-pill">Intermediate</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div> --}}
    </div>
</div>

<style>
    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-text {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    .avatar-xl .avatar-text {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }
    .card.bg-light {
        background-color: #f8f9fa !important;
    }
    .list-group-item {
        padding: 0.75rem 0;
        background-color: transparent;
    }
</style>
@endsection