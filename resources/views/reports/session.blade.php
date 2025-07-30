@extends('layouts.sideBar')

@section('content')
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Session Report - {{ $session->created_at->format('Y-m-d') }}</h5>
                <form method="GET" action="{{ route('reports.session.pdf') }}" target="_blank" class="form-inline">
                    <input type="hidden" name="session_id" value="{{ $session->id }}">
                    <div class="input-group input-group-sm">
                        <select name="type" class="form-select" required>
                            <option value="" disabled selected>Export Format</option>
                            @foreach (App\Enums\ReportType::all() as $type)
                                <option value="{{ $type['value'] }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf me-1"></i> Export
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Professor:</strong> {{ $session->professor->name }}</div>
                    <div class="col-md-3"><strong>Time:</strong> {{ $session->created_at->format('h:i A') }}</div>
                    <div class="col-md-3"><strong>Stage:</strong> {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</div>
                    <div class="col-md-3">
                        <strong>Total Price:</strong>
                        {{ number_format($session->professor_price + $session->center_price + $session->printables, 2) }}
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Students Attendance</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Phone</th>
                                <th>Phone (P)</th>
                                <th>Book</th>
                                <th class="text-end">Payment</th>
                                <th class="text-end">To Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr class="{{ $report->to_pay > 0 ? 'table-warning' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $report->student->name }}</td>
                                    <td>{{ $report->student->phone }}</td>
                                    <td>{{ $report->student->parent_phone }}</td>
                                    <td>{{ $report->book }}</td>
                                    <td class="text-end">{{ number_format($report->professor_price + $report->center_price + $report->printables, 2) }}</td>
                                    <td class="text-end fw-bold {{ $report->to_pay > 0 ? 'text-danger' : '' }}">
                                        {{ number_format($report->to_pay, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h6 class="card-subtitle mb-2 text-muted">Total Students</h6>
                                <p class="card-text fs-4 fw-bold">{{ $reports->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h6 class="card-subtitle mb-2 text-muted">Professor</h6>
                                <p class="card-text fs-4 fw-bold">{{ number_format($reports->sum('professor_price'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @if ($reports?->first()?->center_price)
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Center</h6>
                                    <p class="card-text fs-4 fw-bold">{{ number_format($reports->sum('center_price'), 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card h-100 bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-subtitle mb-2 text-muted">Total Value</h6>
                                <p class="card-text fs-4 fw-bold text-primary">
                                    {{ number_format($reports->sum(fn($r) => $r->professor_price + $r->center_price + $r->printables), 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card h-100 {{ $reports->sum('to_pay') > 0 ? 'bg-warning bg-opacity-10' : '' }}">
                            <div class="card-body text-center">
                                <h6 class="card-subtitle mb-2 text-muted">To Collect</h6>
                                <p class="card-text fs-4 fw-bold {{ $reports->sum('to_pay') > 0 ? 'text-danger' : '' }}">
                                    {{ number_format($reports->sum('to_pay'), 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection