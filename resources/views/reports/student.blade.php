@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 900px;">
        <h4 class="mb-4">Search Students</h4>

        <!-- Search Form -->
        <form method="GET" action="{{ route('reports.student') }}" class="row g-3 mb-4" id="searchForm">
            <div class="col-md-5">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search by code or name">
            </div>

            <div class="col-md-5" id="professorDropdownWrapper" style="display: none;">
                <select name="professor_id" id="finalProfessorSelect" class="form-select"></select>
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
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-hover align-middle" id="studentsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Stage</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr data-student-id="{{ $student->id }}">
                                <td>{{ $student->code }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>{{ App\Enums\StagesEnum::getStringValue($student->stage) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-success select-student-btn">
                                        Select
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $students->withQueryString()->links() }}
        @endif

        @isset($reports)
            <!-- Reports Table -->
            <div class="card mt-4">
                <div class="card-body">
                    @if (isset($reports) && count($reports))
                        <a href="{{ route('reports.download.pdf', request()->query()) }}"
                            class="btn btn-danger btn-sm mb-3 float-end" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Download PDF
                        </a>
                    @endif
                    <h5 class="card-title">Reports</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="reportsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Session</th>
                                    <th>Professor</th>
                                    <th>Attend</th>
                                    <th>paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $report)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ Carbon\carbon::parse($report->session->created_at)->format('d-m-Y') ?? 'N/A' }}
                                        </td>
                                        <td>{{ $report->session->professor->name ?? 'N/A' }}</td>
                                        <td>{{ Carbon\carbon::parse($report->created_at)->format('d-m-Y') ?? 'N/A' }} </td>
                                        <td>{{ $report->professor_price + $report->center_price + $report->printables }} </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No reports available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endisset
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.select-student-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const row = this.closest('tr');
                const studentId = row.getAttribute('data-student-id');
                document.getElementById('selectedStudentId').value = studentId;

                const response = await fetch(
                    `{{ route('professors.dropdown') }}?student_id=${studentId}`);
                const data = await response.json();

                const dropdown = document.getElementById('finalProfessorSelect');
                dropdown.innerHTML = '<option value="">-- Select Professor --</option>';

                if (Array.isArray(data) && data.length) {
                    data.forEach(prof => {
                        const option = document.createElement('option');
                        option.value = prof.id;
                        option.textContent = prof.name;
                        dropdown.appendChild(option);
                    });

                    document.getElementById('professorDropdownWrapper').style.display = 'block';
                    document.getElementById('professorDropdownWrapper').scrollIntoView({
                        behavior: 'smooth'
                    });
                } else {
                    dropdown.innerHTML = '<option value="">No professors available</option>';
                    document.getElementById('professorDropdownWrapper').style.display = 'block';
                }
            });
        });

        document.getElementById('searchForm').addEventListener('submit', function(e) {
            console.log('Form Submitted with:', {
                search: this.search.value,
                student_id: this.student_id.value,
                professor_id: this.professor_id?.value,
            });
        });
    </script>
@endpush
