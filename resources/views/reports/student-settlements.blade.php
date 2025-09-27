@extends('layouts.sideBar')

@section('title', 'Student Settlements')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                <div class="card-body">
                    <!-- Filter Form -->
                    <form action="{{ route('reports.student-settlements') }}" method="GET" class="row g-3 mb-4">
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="student_id" class="form-label">Student</label>
                            <select name="student_id" id="student_id" class="form-select">
                                <option value="">All Students</option>
                                @foreach(\App\Models\Student::all() as $student)
                                    <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="professor_id" class="form-label">Professor</label>
                            <select name="professor_id" id="professor_id" class="form-select">
                                <option value="">All Professors</option>
                                @foreach(\App\Models\Professor::all() as $professor)
                                    <option value="{{ $professor->id }}" {{ request('professor_id') == $professor->id ? 'selected' : '' }}>
                                        {{ $professor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="name" class="form-label">Search Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Student/Professor" value="{{ request('name') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('reports.student-settlements') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-undo me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                    <h4 class="card-title">Student Settlements</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Session</th>
                                    <th>Professor</th>
                                    <th>Amount</th>
                                    <th>Center</th>
                                    <th>Professor</th>
                                    <th>Materials</th>
                                    <th>Printables</th>
                                    <th>Description</th>
                                    <th>Settled At</th>
                                    <th>Created By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settlements as $settlement)
                                <tr>
                                    <td>{{ $settlement->id }}</td>
                                    <td>{{ $settlement->student->name }}</td>
                                    <td>{{ $settlement->session_id ?? 'N/A' }}</td>
                                    <td>{{ $settlement->professor->name }}</td>
                                    <td>{{ $settlement->amount }}</td>
                                    <td>{{ $settlement->center }}</td>
                                    <td>{{ $settlement->professor_amount }}</td>
                                    <td>{{ $settlement->materials }}</td>
                                    <td>{{ $settlement->printables }}</td>
                                    <td>{{ $settlement->description }}</td>
                                    <td>{{ $settlement->settled_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $settlement->createdBy->name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center">No settlements found</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">Totals</th>
                                    <th>{{ $totals['total_amount'] }}</th>
                                    <th>{{ $totals['total_center'] }}</th>
                                    <th>{{ $totals['total_professor'] }}</th>
                                    <th>{{ $totals['total_materials'] }}</th>
                                    <th>{{ $totals['total_printables'] }}</th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $settlements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
