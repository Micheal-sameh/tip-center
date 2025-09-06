@extends('layouts.sideBar')

@section('content')
<div class="container py-4" style="max-width: 1200px;">

    <!-- Header -->
    <div class="card-header bg-white d-flex justify-content-between align-items-center mb-3 mt-2">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-user-graduate me-2"></i> Student Reports
            </h4>
            <p class="text-muted mb-0">Search for students and view their session reports</p>
        </div>
        <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> {{ __('trans.back') }}
        </a>
    </div>

    <!-- Search Form -->
    <div class="card-body">
        <form method="GET" action="{{ route('reports.student') }}" class="row g-3 mb-4 align-items-center" id="searchForm">

            <!-- Student Search -->
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="studentSearchInput"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Search by student code, name or phone"
                           autocomplete="off">
                </div>
            </div>

            <!-- Professor Dropdown (hidden until student is chosen) -->
            <div class="col-md-4" id="professorDropdownWrapper" style="display:none;">
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

            <!-- Search Button -->
            <div class="col-md-3">
                <button type="submit" id="searchBtn" class="btn btn-primary w-100">
                    <span id="loader" class="spinner-border spinner-border-sm me-2 d-none"></span>
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>
        </form>

        <!-- Students Table -->
        @if (isset($students))
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
                                <td class="ps-3 fw-bold student-code">{{ $student->code }}</td>
                                <td class="student-name">{{ $student->name }}</td>
                                <td class="student-phone">{{ $student->phone ?: 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info student-stage">
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

            @if ($students->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $students->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<!-- Reports Section -->
@isset($reports)
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-file-alt me-2"></i> Session Reports
            </h5>

            @if (count($reports))
                <!-- PDF Export Form -->
                <form method="GET" action="{{ route('reports.download.pdf') }}" target="_blank"
                      class="d-flex align-items-center gap-3">

                    @foreach (request()->query() as $key => $value)
                        @if (!in_array($key, ['type']))
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <div class="input-group input-group-sm" style="width:200px;">
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
                                <th>Materials</th>
                                <th>Printables</th>
                                <th>Total Paid</th>
                                <th>To Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr class="{{ $report->is_attend == App\Enums\AttendenceType::ABSENT ? 'table-danger' : ($report->to_pay > 0 ? 'table-warning' : '') }}">
                                    <td class="ps-3">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ \Carbon\Carbon::parse($report->session->created_at)->format('d M Y') ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $report->session->professor->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($report->created_at)->format('h:i A') ?? 'N/A' }}</td>
                                    <td>{{ $report->materials ?? 'N/A' }}</td>
                                    <td>{{ $report->printables ?? 'N/A' }}</td>
                                    <td class="fw-bold">
                                        {{ number_format($report->professor_price + $report->center_price + $report->printables, 2) }}
                                    </td>
                                    <td>{{ $report->to_pay ?? 'N/A' }}</td>
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
@endsection

@push('styles')
<style>
    .cursor-pointer { cursor: pointer; }
    tr:hover { background-color: rgba(0,0,0,0.02); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput   = document.getElementById('studentSearchInput');
    const tableRows     = document.querySelectorAll('#studentsTable tbody tr');
    const searchForm    = document.getElementById('searchForm');
    const loader        = document.getElementById('loader');
    const searchBtn     = document.getElementById('searchBtn');

    // Debounce helper
    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Filter rows locally
    function filterRows(query) {
        query = query.trim().toLowerCase();
        tableRows.forEach(row => {
            const code  = row.querySelector('.student-code').textContent.toLowerCase();
            const name  = row.querySelector('.student-name').textContent.toLowerCase();
            const phone = row.querySelector('.student-phone').textContent.toLowerCase();
            row.style.display = (code.includes(query) || name.includes(query) || phone.includes(query)) ? '' : 'none';
        });
    }

    // Submit form programmatically
    function submitSearch() {
        if (searchInput.value.trim().length > 0) {
            searchForm.submit();
        }
    }

    const debouncedSubmit = debounce(submitSearch, 700);

    searchInput.addEventListener('input', function () {
        filterRows(this.value);
        debouncedSubmit();
    });

    // Row click → trigger select
    tableRows.forEach(row => {
        row.addEventListener('click', function (e) {
            if (!e.target.closest('.select-student-btn')) {
                const btn = this.querySelector('.select-student-btn');
                if (btn) btn.click();
            }
        });
    });

    // Select student handler
    document.querySelectorAll('.select-student-btn').forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.stopPropagation();

            const row       = this.closest('tr');
            const studentId = row.getAttribute('data-student-id');
            document.getElementById('selectedStudentId').value = studentId;

            // Highlight active
            tableRows.forEach(r => r.classList.remove('table-active'));
            row.classList.add('table-active');

            // Load professors
            const dropdownWrapper = document.getElementById('professorDropdownWrapper');
            dropdownWrapper.style.display = 'block';
            const dropdown = document.getElementById('finalProfessorSelect');
            dropdown.innerHTML = '<option value="">Loading professors...</option>';

            try {
                const response = await fetch(`{{ route('professors.dropdown') }}?student_id=${studentId}`);
                const data     = await response.json();

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

    // Show loader on submit
    searchForm.addEventListener('submit', () => {
        searchBtn.disabled = true;
        loader.classList.remove('d-none');
    });

    // Focus input on load
    if (searchInput) {
        searchInput.focus();
        const val = searchInput.value;
        searchInput.value = '';
        searchInput.value = val;
    }
});
</script>
@endpush
