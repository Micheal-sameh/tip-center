@extends('layouts.sideBar')

@section('content')
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Session Report - {{ $session->created_at->format('d-m-Y') }}</h5>
                <form method="GET" action="{{ route('reports.session.pdf') }}" target="_blank" class="form-inline">
                    <input type="hidden" name="session_id" value="{{ $session->id }}">
                    <div class="input-group input-group-sm">
                        <label class="form-check-label me-2" for="withPhones">With Phones</label>
                        <input class="form-check-input me-3" type="checkbox" name="with_phones" id="withPhones"
                            value="1">
                        <select name="type" class="form-select me-2">
                            <option value="" disabled selected>Export Format</option>
                            @foreach (App\Enums\ReportType::all() as $type)
                                <option value="{{ $type['value'] }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" formaction="{{ route('reports.session') }}" formtarget="_self"
                            class="btn btn-info btn-sm me-2">
                            <i class="fas fa-eye me-1"></i> Show
                        </button>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf me-1"></i> Export
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Professor:</strong> {{ $session->professor->name }}</div>
                    <div class="col-md-3"><strong>Stage:</strong>
                        {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</div>
                </div>

                <h5 class="mt-4 mb-3">Students Attendance</h5>

                <!-- Responsive Table (works for both desktop and mobile) -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                @if ($reports->contains(fn($r) => $r->student?->phone > 0))
                                    <th>Phone</th>
                                @endif
                                @if ($reports->contains(fn($r) => $r->student?->parent_phone > 0))
                                    <th>Parent Phone</th>
                                @endif
                                <th>Attend</th>
                                <th class="text-end">Payment</th>
                                @if ($reports->contains(fn($r) => $r->materials > 0))
                                    <th>Materials</th>
                                @endif
                                @if ($reports->contains(fn($r) => $r->printables > 0))
                                    <th>Student Papers</th>
                                @endif
                                <th class="text-end">To Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr
                                    class="{{ $report->is_attend == App\Enums\AttendenceType::ABSENT ? 'table-danger' : ($report->to_pay + $report->to_pay_center > 0 ? 'table-warning' : '') }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a
                                            href="{{ route('students.show', $report->student_id) }}">{{ $report->student?->name }}</a>
                                    </td>

                                    @if ($reports->contains(fn($r) => $r->student?->phone > 0))
                                        <td>{{ $report->student?->phone }}</td>
                                    @endif
                                    @if ($reports->contains(fn($r) => $r->student?->parent_phone > 0))
                                        <td>{{ $report->student?->parent_phone }}</td>
                                    @endif

                                    <td>
                                        {{ $report->is_attend ? $report->created_at->format('h:i:A') : App\Enums\AttendenceType::getStringValue($report->is_attend) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($report->professor_price + $report->center_price, 2) }}
                                    </td>

                                    @if ($reports->contains(fn($r) => $r->materials > 0))
                                        <td>{{ $report->materials }}</td>
                                    @endif
                                    @if ($reports->contains(fn($r) => $r->printables > 0))
                                        <td class="text-end">{{ $report->printables }}</td>
                                    @endif

                                    @if (isset($report->student->toPay))
                                        @php
                                            $total = $report->student->toPay->sum(function ($pay) use ($selected_type) {
                                                if ($selected_type == App\Enums\ReportType::PROFESSOR) {
                                                    return $pay->to_pay;
                                                } elseif ($selected_type == App\Enums\ReportType::CENTER) {
                                                    return $pay->to_pay_center;
                                                } else {
                                                    return $pay->to_pay_center + $pay->to_pay;
                                                }
                                            });
                                        @endphp
                                        <td class="text-end fw-bold {{ $total > 0 ? 'text-danger' : '' }}">
                                            {{ number_format($total, 2) }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>



                {{-- Summary (totals) --}}
                <div class="row mt-4">
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h6 class="card-subtitle mb-2 text-muted">Attended Students</h6>
                                <p class="card-text fs-4 fw-bold">{{ $attendedCount }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($session->professor_price)
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Professor</h6>
                                    <p class="card-text fs-4 fw-bold">
                                        {{ number_format($reports->sum('professor_price'), 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($session->center_price)
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Center</h6>
                                    <p class="card-text fs-4 fw-bold">{{ number_format($reports->sum('center_price'), 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($session->professor->balance)
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">balance</h6>
                                    <p class="card-text fs-4 fw-bold">{{ number_format($session->professor->balance, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($reports->contains(fn($r) => $r->printables > 0))
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Student Papers</h6>
                                    <p class="card-text fs-4 fw-bold">{{ number_format($reports->sum('printables'), 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($reports->contains(fn($r) => $r->materials > 0))
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Materials</h6>
                                    <p class="card-text fs-4 fw-bold">{{ number_format($reports->sum('materials'), 2) }}
                                    </p>
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
                                        $total =
                                            $reports->sum(
                                                fn($r) => $r->professor_price +
                                                    $r->center_price +
                                                    $r->printables +
                                                    $r->materials,
                                            ) + $session->professor->balance;
                                        if ($session->sessionExtra) {
                                            $extra = $session->sessionExtra;
                                            $adjustment =
                                                $extra->markers +
                                                $extra->copies +
                                                $extra->other +
                                                $extra->cafeterea +
                                                $extra->other_print +
                                                $extra->out_going;
                                            $total +=
                                                $selected_type == App\Enums\ReportType::PROFESSOR
                                                    ? -$adjustment
                                                    : $adjustment;
                                        }
                                    @endphp
                                    {{ number_format($total, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @php

                        $total = $reports->sum(function ($report) use ($selected_type) {
                            if (!isset($report->student->toPay)) {
                                return 0;
                            }

                            return $report->student->toPay->sum(function ($pay) use ($selected_type) {
                                if ($selected_type == App\Enums\ReportType::PROFESSOR) {
                                    return $pay->to_pay;
                                } elseif ($selected_type == App\Enums\ReportType::CENTER) {
                                    return $pay->to_pay_center;
                                } else {
                                    return $pay->to_pay_center + $pay->to_pay;
                                }
                            });
                        });
                    @endphp


                    <div class="col-md-3 col-6 mb-3">
                        <div class="card h-100 {{ $total > 0 ? 'bg-warning bg-opacity-10' : '' }}">
                            <div class="card-body text-center">
                                <h6 class="card-subtitle mb-2 text-muted">To Collect</h6>
                                <p class="card-text fs-4 fw-bold {{ $total > 0 ? 'text-danger' : '' }}">
                                    {{ number_format($total, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Extras --}}
                @if ($session->sessionExtra)
                    @php
                        $extra = $session->sessionExtra;

                        $fields = [
                            'markers' => 'Markers',
                            'cafeterea' => 'Cafeterea',
                            'copies' => 'Prof Papers',
                            'other' => 'Others Center',
                            'other_print' => 'Others Print',
                            'out_going' => 'Out Going',
                        ];

                        $formatValue = function ($value, $type) {
                            if ($type == App\Enums\ReportType::PROFESSOR) {
                                return $value > 0 ? -number_format($value, 2) : 0;
                            }
                            return number_format($value ?? 0, 2);
                        };
                    @endphp

                    <div class="row g-3 mb-3">
                        @foreach ($fields as $field => $label)
                            <div class="col-md-3 col-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h6 class="card-subtitle mb-2 text-muted">{{ $label }}</h6>
                                        <p class="card-text fs-5 fw-bold">
                                            {{ $formatValue($extra->$field ?? 0, $selected_type) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if (!empty($extra->notes))
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
