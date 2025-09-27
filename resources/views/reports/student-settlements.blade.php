@extends('layouts.sideBar')

@section('title', 'Student Settlements')

@section('content')
    <div class="container-fluid px-4 py-3">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-hand-holding-usd me-2"></i> Student Settlements
            </h2>
            <a href="{{ route('reports.student-settlements') }}" class="btn btn-outline-secondary">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </a>
        </div>

        <!-- Filters Card -->
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <form action="{{ route('reports.student-settlements') }}" method="GET" class="row g-3">
                    <div class="col-md-2">
                        <label for="date_from" class="form-label fw-semibold">From</label>
                        <input type="date" name="date_from" id="date_from" class="form-control"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label fw-semibold">To</label>
                        <input type="date" name="date_to" id="date_to" class="form-control"
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="student_id" class="form-label fw-semibold">Student</label>
                        <select name="student_id" id="student_id" class="form-select">
                            <option value="">All Students</option>
                            @foreach (\App\Models\Student::all() as $student)
                                <option value="{{ $student->id }}"
                                    {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="professor_id" class="form-label fw-semibold">Professor</label>
                        <select name="professor_id" id="professor_id" class="form-select">
                            <option value="">All Professors</option>
                            @foreach (\App\Models\Professor::all() as $professor)
                                <option value="{{ $professor->id }}"
                                    {{ request('professor_id') == $professor->id ? 'selected' : '' }}>
                                    {{ $professor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="name" class="form-label fw-semibold">Search</label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Student/Professor" value="{{ request('name') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-2 align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('reports.student-settlements') }}" class="btn btn-light w-100">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Settlements Table -->
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Session</th>
                                <th>Professor</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Center</th>
                                <th class="text-end">Professor</th>
                                <th class="text-end">Materials</th>
                                <th class="text-end">Printables</th>
                                <th>Description</th>
                                <th>Settled At</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($settlements as $settlement)
                                <tr>
                                    <td>{{ $settlement->id }}</td>
                                    <td>{{ $settlement->student->name ?? 'N/A' }}</td>
                                    <td><span
                                            class="badge bg-info bg-opacity-10 text-info">#{{ $settlement->session_id }}</span>
                                    </td>
                                    <td>{{ $settlement->professor->name ?? 'N/A' }}</td>
                                    <td class="text-end fw-semibold text-primary">
                                        {{ number_format($settlement->amount, 2) }}</td>
                                    <td class="text-end">{{ number_format($settlement->center, 2) }}</td>
                                    <td class="text-end text-success">{{ number_format($settlement->professor_amount, 2) }}
                                    </td>
                                    <td class="text-end">{{ number_format($settlement->materials, 2) }}</td>
                                    <td class="text-end">{{ number_format($settlement->printables, 2) }}</td>
                                    <td>{{ $settlement->description ?? '-' }}</td>
                                    <td><span
                                            class="text-muted small">{{ $settlement->settled_at?->format('Y-m-d H:i') ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $settlement->createdBy->name ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle me-2"></i> No settlements found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">Totals:</td>
                                <td class="text-end text-primary">{{ $totals['total_amount'] }}</td>
                                <td class="text-end">{{ $totals['total_center'] }}</td>
                                <td class="text-end text-success">{{ $totals['total_professor'] }}</td>
                                <td class="text-end">{{ $totals['total_materials'] }}</td>
                                <td class="text-end">{{ $totals['total_printables'] }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    {{ $settlements->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
