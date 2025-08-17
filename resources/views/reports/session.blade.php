@extends('layouts.sideBar')

@section('content')
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Session Report - {{ $session->created_at->format('d-m-Y') }}</h5>
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
                    <div class="col-md-3"><strong>Stage:</strong> {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</div>
                </div>

                <h5 class="mt-4 mb-3">Students Attendance</h5>

                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Phone</th>
                                <th>Phone (P)</th>
                                <th>Attend</th>
                                @if ($session->materials)
                                    <th>Materials</th>
                                @endif
                                @if ($session->printables)
                                    <th>Printables</th>
                                @endif
                                <th class="text-end">Payment</th>
                                @if ($reports->contains(fn($r) => $r->to_pay > 0))
                                    <th class="text-end">To Pay</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr class="{{ $report->to_pay > 0 ? 'table-warning' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="{{route('students.show', $report->student_id)}}">{{ $report->student?->name }} </a></td>
                                    <td>{{ $report->student?->phone }}</td>
                                    <td>{{ $report->student?->parent_phone }}</td>
                                    <td>{{ $report->created_at->format('h:i:A') }}</td>
                                    @if ($session->materials)
                                        <td>{{ $report->materials }}</td>
                                    @endif
                                    @if ($session->printables)
                                        <td class="text-end">{{ $report->printables }}</td>
                                    @endif
                                    <td class="text-end">{{ number_format($report->professor_price + $report->center_price, 2) }}</td>
                                    @if ($report->to_pay)
                                        <td class="text-end fw-bold {{ $report->to_pay > 0 ? 'text-danger' : '' }}">
                                            {{ number_format($report->to_pay, 2) }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="d-md-none">
                    @foreach ($reports as $report)
                        <div class="card mb-3 {{ $report->to_pay > 0 ? 'border-warning bg-warning bg-opacity-10' : '' }}">
                            <div class="card-body">
                                <h6 class="fw-bold mb-1">{{ $loop->iteration }}. {{ $report->student?->name }}</h6>
                                <p class="mb-1"><strong>Phone:</strong> {{ $report->student?->phone }}</p>
                                <p class="mb-1"><strong>Phone (P):</strong> {{ $report->student?->parent_phone }}</p>
                                @if ($session->materials)
                                    <p class="mb-1"><strong>Materials:</strong> {{ $report->materials }}</p>
                                @endif
                                @if ($session->printables)
                                    <p class="mb-1"><strong>Printables:</strong> {{ $report->printables }}</p>
                                @endif
                                <p class="mb-1"><strong>Payment:</strong> {{ number_format($report->professor_price + $report->center_price, 2) }}</p>
                                @if ($report->to_pay)
                                    <p class="mb-0 text-danger fw-bold">
                                        <strong>To Pay:</strong> {{ number_format($report->to_pay, 2) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Summary (totals) --}}
                <div class="row mt-4">
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h6 class="card-subtitle mb-2 text-muted">Total Students</h6>
                                <p class="card-text fs-4 fw-bold">{{ $reports->count() }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($session->professor_price)
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Professor</h6>
                                    <p class="card-text fs-4 fw-bold">{{ number_format($reports->sum('professor_price'), 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($session->materials)
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Materials</h6>
                                    <p class="card-text fs-4 fw-bold">{{ number_format($reports->sum('materials'), 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($session->center_price)
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
                                    @php
                                        $total = $reports->sum(fn($r) =>
                                            $r->professor_price +
                                            $r->center_price +
                                            $r->printables +
                                            $r->materials);
                                        if ($session->sessionExtra) {
                                            $extra = $session->sessionExtra;
                                            $adjustment = $extra->markers + $extra->copies + $extra->other + $extra->cafeterea;
                                            $total += $selected_type == App\Enums\ReportType::PROFESSOR ? -$adjustment : $adjustment;
                                        }
                                    @endphp
                                    {{ number_format($total, 2) }}
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

                {{-- Extras --}}
                @if ($session->sessionExtra)
                    @php $extra = $session->sessionExtra; @endphp
                    <div class="row g-3 mb-3">
                        @foreach (['markers', 'cafeterea', 'copies', 'other'] as $field)
                            <div class="col-md-3 col-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h6 class="card-subtitle mb-2 text-muted">{{ ucfirst($field) }}</h6>
                                        <p class="card-text fs-5 fw-bold">
                                            @if ($selected_type == App\Enums\ReportType::PROFESSOR)
                                                {{ $extra->$field > 0 ? -number_format($extra->$field, 2) : 0 }}
                                            @else
                                                {{ number_format($extra->$field ?? 0, 2) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($extra->notes)
                            <div class="col-md-3 col-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h6 class="card-subtitle mb-2 text-muted">Notes</h6>
                                        <p class="card-text fs-6 fw-bold">{{ $extra->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
