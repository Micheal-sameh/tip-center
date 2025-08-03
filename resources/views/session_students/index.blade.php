@extends('layouts.sideBar')

@section('content')
    <div class="container" style="max-width: 800px">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Attend Student to Session</h4>
            <div class="badge bg-primary rounded-pill">
                <i class="fas fa-calendar-check me-1"></i> {{ now()->format('M d, Y') }}
            </div>
        </div>
        <!-- Add Student Button -->
        <div class="d-flex justify-content-end mb-2">
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>{{ __('Add Student') }}
            </a>
        </div>


        <!-- Search Student Form -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('attendances.index') }}" class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-user-graduate text-muted"></i>
                            </span>
                            <input type="text" name="code" class="form-control border-start-0"
                                placeholder="Search by phone or code" value="{{ request('code') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100 shadow-sm">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Display Students -->
        @if (isset($students) && $students->isNotEmpty())
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Search Results</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach ($students as $student)
                            <a href="{{ route('attendances.index', ['student_id' => $student->id]) }}"
                                class="list-group-item list-group-item-action {{ request('student_id') == $student->id ? 'active' : '' }}">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $student->name }}</h6>
                                        <small class="text-muted">{{ $student->code }}</small>
                                    </div>
                                    <div class="text-end">
                                        <small class="d-block">{{ $student->phone }}</small>
                                        <small
                                            class="d-block">{{ App\Enums\StagesEnum::getStringValue($student->stage) }}</small>
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Show Selected Student Info -->
        @isset($selected_student)
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Selected Student</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="mb-2">{{ $selected_student->name }}</h5>
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-id-card me-1"></i> {{ $selected_student->code }}
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-phone me-1"></i> {{ $selected_student->phone }}
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-layer-group me-1"></i>
                                    {{ App\Enums\StagesEnum::getStringValue($selected_student->stage) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sessions Display -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>default Sessions</h5>
                </div>
                <div class="card-body">
                    @isset($my_sessions)
                        <div class="row g-3" id="sessions-container">
                            @foreach ($my_sessions as $my_session)
                                <div class="col-md-6 col-lg-4">
                                    <a href="{{ route('attendances.create', [
                                        'student_id' => $selected_student->id,
                                        'session_id' => $my_session->id,
                                    ]) }}"
                                        class="text-decoration-none">
                                        <div class="card session-card h-100 m-0 position-relative" style="border-radius: 10px;">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div
                                                        class="avatar-sm bg-light-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="fas fa-chalkboard-teacher text-primary fs-5"></i>
                                                    </div>
                                                    <h6 class="mb-0 text-truncate">{{ $my_session->professor->name }}</h6>
                                                </div>

                                                <div class="d-flex flex-column gap-2">
                                                    <span class="badge bg-light text-dark text-start">
                                                        <i class="fas fa-layer-group me-1 text-muted"></i>
                                                        {{ \App\Enums\StagesEnum::getStringValue($my_session->stage) }}
                                                    </span>
                                                    <span class="badge bg-light text-dark text-start">
                                                        <i class="fas fa-clock me-1 text-muted"></i>
                                                        {{ \Carbon\Carbon::parse($my_session->start_at)->format('h:i A') }} -
                                                        {{ \Carbon\Carbon::parse($my_session->end_at)->format('h:i A') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endisset
                </div>
            </div>
            {{--  --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Available Sessions</h5>
                </div>
                <div class="card-body">
                    @isset($sessions)
                        <div class="row g-3" id="sessions-container">
                            @foreach ($sessions as $session)
                                <div class="col-md-6 col-lg-4">
                                    <a href="{{ route('attendances.create', [
                                        'student_id' => $selected_student->id,
                                        'session_id' => $session->id,
                                    ]) }}"
                                        class="text-decoration-none">
                                        <div class="card session-card h-100 m-0 position-relative" style="border-radius: 10px;">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div
                                                        class="avatar-sm bg-light-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="fas fa-chalkboard-teacher text-primary fs-5"></i>
                                                    </div>
                                                    <h6 class="mb-0 text-truncate">{{ $session->professor->name }}</h6>
                                                </div>

                                                <div class="d-flex flex-column gap-2">
                                                    <span class="badge bg-light text-dark text-start">
                                                        <i class="fas fa-layer-group me-1 text-muted"></i>
                                                        {{ \App\Enums\StagesEnum::getStringValue($session->stage) }}
                                                    </span>
                                                    <span class="badge bg-light text-dark text-start">
                                                        <i class="fas fa-clock me-1 text-muted"></i>
                                                        {{ \Carbon\Carbon::parse($session->start_at)->format('h:i A') }} -
                                                        {{ \Carbon\Carbon::parse($session->end_at)->format('h:i A') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endisset
                </div>
            </div>
        @elseif(request()->filled('code') && !request()->filled('student_id'))
            <div class="alert alert-warning shadow-sm">
                <i class="fas fa-exclamation-circle me-2"></i> No student found with that phone or code.
            </div>
        @endisset
    </div>

@section('styles')
    <style>
        .session-card {
            transition: all 0.2s ease;
            border: 1px solid #e9ecef;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
            cursor: pointer;
        }

        .session-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border-color: #dee2e6;
            background-color: #f8f9fa;
        }

        .avatar-sm {
            width: 2rem;
            height: 2rem;
            font-size: 1rem;
        }

        .avatar-lg {
            width: 3.5rem;
            height: 3.5rem;
            font-size: 1.5rem;
        }

        .bg-light-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .badge {
            font-weight: 500;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
        }

        .text-truncate {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection
@endsection
