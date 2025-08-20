@extends('layouts.sideBar')

@section('content')
    <div class="container" style="max-width: 800px">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Attend Student to Session</h4>
            <div class="badge bg-primary rounded-pill">
                <i class="fas fa-calendar-check me-1"></i> {{ now()->format('M d, Y') }}
            </div>
        </div>

        <!-- Add Student Button -->
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>{{ __('Add Student') }}
            </a>
        </div>

        <!-- Search Student Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="studentSearch" class="form-control border-start-0"
                                placeholder="Search by name, code or phone..." autocomplete="off" autofocus>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="clearSearch" class="btn btn-outline-secondary w-100 shadow-sm">
                            <i class="fas fa-times me-1"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @isset($all_students)
            <!-- Student Results Section -->
            <div id="studentResultsContainer" class="mb-4" style="display: none;">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Students</h5>
                        <span class="badge bg-primary rounded-pill" id="studentCount">{{ $all_students->count() }}
                            students</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" id="studentResultsList">
                            @foreach ($all_students as $student)
                                <a href="{{ route('attendances.index', ['student_id' => $student->id]) }}"
                                    class="list-group-item list-group-item-action student-item">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $student->name }}</h6>
                                            <div class="d-flex gap-2">
                                                <small class="text-muted">{{ $student->code ?? '' }}</small>
                                                <small class="text-muted">{{ $student->phone ?? '' }}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-light text-dark">
                                                {{ App\Enums\StagesEnum::getStringValue($student->stage) ?? '' }}
                                            </span>
                                            <span class="badge bg-light text-dark ms-2">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endisset

        <!-- Selected Student Section -->
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
                                @if ($selected_student->note)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-book me-1"></i>
                                        {{ $selected_student->note }}
                                    </span>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sessions Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Available Sessions</h5>
                </div>
                <div class="card-body">
                    @if ($my_sessions->isEmpty())
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-info-circle me-2"></i> No available sessions found for this student.
                        </div>
                    @else
                        <div class="row g-3" id="sessions-container">
                            @foreach ($my_sessions as $session)
                                <div class="col-md-6 col-lg-4">
                                    <a href="{{ route('attendances.create', [
                                        'student_id' => $selected_student->id,
                                        'session_id' => $session->id,
                                    ]) }}"
                                        class="text-decoration-none">
                                        <div class="card session-card h-100 m-0 position-relative">
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
                                                        {{ \App\Enums\StagesEnum::getStringValue($session->stage) }} -
                                                        <i class="fas fas fa-tags me-1 text-muted"></i>
                                                        {{ \App\Enums\SessionType::getStringValue($session->type) }}
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
                    @endif
                </div>
            </div>
        @endisset
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('studentSearch');
            const studentList = document.getElementById('studentResultsList');
            const studentItems = studentList.querySelectorAll('.student-item');
            const studentCountBadge = document.getElementById('studentCount');
            const resultsContainer = document.getElementById('studentResultsContainer');
            const clearBtn = document.getElementById('clearSearch');

            function filterStudents() {
                const filter = searchInput.value.toLowerCase();
                let visibleCount = 0;

                if (filter.trim() === '') {
                    resultsContainer.style.display = 'none';
                    return;
                }

                studentItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        item.style.display = '';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                studentCountBadge.textContent = visibleCount + (visibleCount === 1 ? ' student' : ' students');
                resultsContainer.style.display = visibleCount > 0 ? 'block' : 'none';
            }

            searchInput.addEventListener('input', filterStudents);

            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                resultsContainer.style.display = 'none';
                studentItems.forEach(item => item.style.display = '');
                studentCountBadge.textContent =
                    '{{ isset($all_students) ? $all_students->count() : 0 }} students';
                searchInput.focus();
            });
        });
    </script>
@endpush

@section('styles')
    <style>
        .session-card {
            transition: all 0.2s ease;
            border: 1px solid #e9ecef;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
            cursor: pointer;
            border-radius: 8px;
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

        #studentSearch:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .list-group-item.active {
            background-color: #e7f1ff;
            border-color: #dee2e6;
            color: #0d6efd;
        }

        .list-group-item.active .badge {
            background-color: #0d6efd !important;
            color: white !important;
        }
    </style>
@endsection
