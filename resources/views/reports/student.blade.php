@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 1200px;">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">
                    <i class="fas fa-user-graduate me-2"></i> Student Reports
                </h4>
                <p class="text-muted mb-0">Search for students and view their session reports</p>
            </div>

            <!-- Search Form -->
            <div class="card-body">
                <form method="GET" action="{{ route('reports.student') }}" class="row g-3 mb-4" id="searchForm">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="form-control" placeholder="Search by student code or name">
                        </div>
                    </div>

                    <div class="col-md-5" id="professorDropdownWrapper" style="display: none;">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </span>
                            <select name="professor_id" id="finalProfessorSelect" class="form-select">
                                <option value="">-- Select Professor --</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="student_id" id="selectedStudentId">

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </form>

                @if (isset($students))
                    <!-- Student Table -->
                    <div class="table-responsive mb-4 border rounded">
                        <table class="table table-hover align-middle mb-0" id="studentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Code</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Stage</th>
                                    <th class="text-end pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr data-student-id="{{ $student->id }}" class="cursor-pointer">
                                        <td class="ps-3 fw-bold">{{ $student->code }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->phone ?: 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ App\Enums\StagesEnum::getStringValue($student->stage) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm btn-success select-student-btn">
                                                <i class="fas fa-check me-1"></i> Select
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center text-muted">
                                                <i class="fas fa-user-slash fa-2x mb-2"></i>
                                                No students found matching your criteria
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($students->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $students->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        @isset($reports)
            <!-- Reports Card -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-file-alt me-2"></i> Session Reports
                    </h5>

                    @if (count($reports))
                        <form method="GET" action="{{ route('reports.download.pdf') }}" target="_blank"
                            class="d-flex align-items-center gap-3">

                            @foreach (request()->query() as $key => $value)
                                @if (!in_array($key, ['type']))
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach

                            <div class="input-group input-group-sm" style="width: 200px;">
                                <label class="input-group-text bg-light">
                                    <i class="fas fa-file-download"></i>
                                </label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="" disabled selected>Select source</option>
                                    @foreach (App\Enums\ReportType::all() as $type)
                                        <option value="{{ $type['value'] }}">{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf me-1"></i> Export PDF
                            </button>
                        </form>
                    @endif
                </div>

                <div class="card-body">
                    @if (count($reports))
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="reportsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>Session Date</th>
                                        <th>Professor</th>
                                        <th>Attend Time</th>
                                        <th class="text-end pe-3">Total Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td class="ps-3">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ Carbon\carbon::parse($report->session->created_at)->format('d M Y') ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                            {{ substr($report->session->professor->name ?? 'N/A', 0, 1) }}
                                                        </div>
                                                    </div>
                                                    {{ $report->session->professor->name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>{{ Carbon\carbon::parse($report->created_at)->format('h:i A') ?? 'N/A' }}</td>
                                            <td class="text-end pe-3 fw-bold">
                                                {{ number_format($report->professor_price + $report->center_price + $report->printables, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="d-flex flex-column align-items-center text-muted">
                                <i class="fas fa-file-excel fa-2x mb-2"></i>
                                No reports available for this student
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endisset
    </div>
@endsection

@push('styles')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
        .avatar-sm {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .avatar-title {
            font-size: 0.875rem;
            font-weight: 500;
        }
        tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Make student rows clickable
            document.querySelectorAll('#studentsTable tbody tr').forEach(row => {
                row.addEventListener('click', function(e) {
                    // Don't trigger if clicking on the select button
                    if (!e.target.closest('.select-student-btn')) {
                        const btn = this.querySelector('.select-student-btn');
                        if (btn) btn.click();
                    }
                });
            });

            // Student selection handler
            document.querySelectorAll('.select-student-btn').forEach(btn => {
                btn.addEventListener('click', async function(e) {
                    e.stopPropagation();

                    const row = this.closest('tr');
                    const studentId = row.getAttribute('data-student-id');
                    document.getElementById('selectedStudentId').value = studentId;

                    // Highlight selected row
                    document.querySelectorAll('#studentsTable tbody tr').forEach(r => {
                        r.classList.remove('table-active');
                    });
                    row.classList.add('table-active');

                    try {
                        // Show loading state
                        const dropdownWrapper = document.getElementById('professorDropdownWrapper');
                        dropdownWrapper.style.display = 'block';
                        const dropdown = document.getElementById('finalProfessorSelect');
                        dropdown.innerHTML = '<option value="">Loading professors...</option>';

                        // Fetch professors
                        const response = await fetch(
                            `{{ route('professors.dropdown') }}?student_id=${studentId}`);
                        const data = await response.json();

                        dropdown.innerHTML = '<option value="">-- Select Professor --</option>';

                        if (Array.isArray(data) && data.length) {
                            data.forEach(prof => {
                                const option = document.createElement('option');
                                option.value = prof.id;
                                option.textContent = prof.name;
                                dropdown.appendChild(option);
                            });

                            dropdownWrapper.scrollIntoView({ behavior: 'smooth' });
                        } else {
                            dropdown.innerHTML = '<option value="">No professors available</option>';
                        }
                    } catch (error) {
                        console.error('Error fetching professors:', error);
                        dropdown.innerHTML = '<option value="">Error loading professors</option>';
                    }
                });
            });
        });
    </script>
@endpush