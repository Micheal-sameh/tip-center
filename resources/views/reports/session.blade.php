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
                            <option value="" disabled {{ empty($selected_type) ? 'selected' : '' }}>Export Format
                            </option>
                            @foreach (App\Enums\ReportType::all() as $type)
                                <option value="{{ $type['value'] }}"
                                    {{ (int) $selected_type === (int) $type['value'] ? 'selected' : '' }}>
                                    {{ $type['name'] }}
                                </option>
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
                @php
                    $showPhone = $reports->contains(fn($r) => $r->student?->phone > 0);
                    $showParentPhone = $reports->contains(fn($r) => $r->student?->parent_phone > 0);
                    $showMaterials = $reports->contains(fn($r) => $r->materials > 0);
                    $showPrintables = $reports->contains(fn($r) => $r->printables > 0);
                @endphp

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                @if ($showPhone)
                                    <th>Phone</th>
                                @endif
                                @if ($showParentPhone)
                                    <th>Parent Phone</th>
                                @endif
                                <th>Attend</th>
                                <th class="text-end">Payment</th>
                                @if ($showMaterials)
                                    <th>Materials</th>
                                @endif
                                @if ($showPrintables)
                                    <th>Student Papers</th>
                                @endif
                                <th class="text-end">To Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                @php
                                    $rowClass =
                                        $report->is_attend == App\Enums\AttendenceType::ABSENT
                                            ? 'table-danger'
                                            : ($report->to_pay +
                                                $report->to_pay_center +
                                                $report->to_pay_print +
                                                $report->to_pay_materials >
                                            0
                                                ? 'table-warning'
                                                : '');

                                    $toPayTotal = isset($report->student->toPay)
                                        ? $report->student->toPay->sum(
                                            fn($pay) => match ((int) $selected_type) {
                                                App\Enums\ReportType::PROFESSOR => $pay->to_pay +
                                                    $pay->to_pay_materials,
                                                App\Enums\ReportType::CENTER => $pay->to_pay_center +
                                                    $pay->to_pay_print,
                                                default => $pay->to_pay +
                                                    $pay->to_pay_center +
                                                    $pay->to_pay_print +
                                                    $pay->to_pay_materials,
                                            },
                                        )
                                        : 0;
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a
                                            href="{{ route('students.show', $report->student_id) }}">{{ $report->student?->name }}</a>
                                    </td>
                                    @if ($showPhone)
                                        <td>{{ $report->student?->phone }}</td>
                                    @endif
                                    @if ($showParentPhone)
                                        <td>{{ $report->student?->parent_phone }}</td>
                                    @endif
                                    <td>{{ $report->is_attend ? $report->created_at->format('h:i:A') : App\Enums\AttendenceType::getStringValue($report->is_attend) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($report->professor_price + $report->center_price, 2) }}</td>
                                    @if ($showMaterials)
                                        <td>{{ $report->materials }}</td>
                                    @endif
                                    @if ($showPrintables)
                                        <td class="text-end">{{ $report->printables }}</td>
                                    @endif
                                    <td class="text-end fw-bold {{ $toPayTotal > 0 ? 'text-danger' : '' }}">
                                        {{ number_format($toPayTotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Online Payments --}}
                @if ($session->onlines && $session->onlines->isNotEmpty())
                    <h5 class="mt-5 mb-3">Online Payments</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    @if ($selected_type != \App\Enums\ReportType::CENTER)
                                        <th class="text-end">Materials</th>
                                        <th class="text-end">Professor Price</th>
                                    @endif
                                    @if ($selected_type != \App\Enums\ReportType::PROFESSOR)
                                        <th class="text-end">Center Price</th>
                                    @endif
                                    <th>Stage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($session->onlines as $online)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $online->name }}</td>
                                        @if ($selected_type != \App\Enums\ReportType::CENTER)
                                            <td class="text-end">{{ number_format($online->materials, 2) }}</td>
                                            <td class="text-end">{{ number_format($online->professor, 2) }}</td>
                                        @endif
                                        @if ($selected_type != \App\Enums\ReportType::PROFESSOR)
                                            <td class="text-end">{{ number_format($online->center ?? 0, 2) }}</td>
                                        @endif
                                        <td>{{ App\Enums\StagesEnum::getStringValue($online->stage) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Summary --}}
                @php
                    $summaryTotal = $reports->sum(
                        fn($r) => $r->professor_price + $r->center_price + $r->printables + $r->materials,
                    );
                    $summaryTotal +=
                        $selected_type == App\Enums\ReportType::PROFESSOR
                            ? $session->professor->balance
                            : -$session->professor->balance;
                    $summaryTotal +=
                        $selected_type == App\Enums\ReportType::PROFESSOR
                            ? $session->professor->materials_balance
                            : -$session->professor->materials_balance;
                    if ($session->sessionExtra) {
                        $adj = collect([
                            'markers',
                            'copies',
                            'other',
                            'cafeterea',
                            'other_print',
                            'to_professor',
                            'out_going',
                        ])->sum(fn($f) => $session->sessionExtra->$f ?? 0);
                        $summaryTotal += $selected_type == App\Enums\ReportType::PROFESSOR ? -$adj : $adj;
                    }
                    if ($session->onlines->isNotEmpty()) {
                        $summaryTotal += $session->onlines->sum(fn($o) => $o->materials + $o->professor + $o->center);
                    }
                    $toCollect = $reports->sum(
                        fn($report) => $report->student?->toPay?->sum(
                            fn($pay) => match ($selected_type) {
                                App\Enums\ReportType::PROFESSOR => $pay->to_pay + $pay->to_pay_materials,
                                App\Enums\ReportType::CENTER => $pay->to_pay_center + $pay->to_pay_print,
                                default => $pay->to_pay +
                                    $pay->to_pay_center +
                                    $pay->to_pay_print +
                                    $pay->to_pay_materials,
                            },
                        ) ?? 0,
                    );
                @endphp

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
                        @php
                            $balanceValue =
                                $selected_type == App\Enums\ReportType::PROFESSOR
                                    ? $session->professor->balance
                                    : -$session->professor->balance;
                        @endphp
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Balance</h6>
                                    <p class="card-text fs-4 fw-bold">
                                        {{ number_format($selected_type == App\Enums\ReportType::PROFESSOR ? $session->professor->balance : -$session->professor->balance, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($session->professor->materials_balance > 0)
                        @php
                            $materialsBalanceValue =
                                $selected_type == App\Enums\ReportType::PROFESSOR
                                    ? $session->professor->materials_balance
                                    : -$session->professor->materials_balance;
                        @endphp
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Material Balance (Prof)</h6>
                                    <p class="card-text fs-4 fw-bold">
                                        {{ number_format($selected_type == App\Enums\ReportType::PROFESSOR ? $session->professor->materials_balance : -$session->professor->materials_balance, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($showPrintables)
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

                    @if ($showMaterials)
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

                    @if ($session->onlines->isNotEmpty())
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Online Total</h6>
                                    <p class="card-text fs-4 fw-bold">
                                        {{ number_format($session->onlines->sum(fn($o) => $o->materials + $o->professor + $o->center), 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-3 col-6 mb-3">
                        <div class="card h-100 bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-subtitle mb-2 text-muted">Total Value</h6>
                                <p class="card-text fs-4 fw-bold text-primary">{{ number_format($summaryTotal, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6 mb-3">
                        <div class="card h-100 {{ $toCollect > 0 ? 'bg-warning bg-opacity-10' : '' }}">
                            <div class="card-body text-center">
                                <h6 class="card-subtitle mb-2 text-muted">To Collect</h6>
                                <p class="card-text fs-4 fw-bold {{ $toCollect > 0 ? 'text-danger' : '' }}">
                                    {{ number_format($toCollect, 2) }}</p>
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
                            'to_professor' => 'Other (To Professor)',
                        ];
                        $formatVal = fn($val) => $selected_type == App\Enums\ReportType::PROFESSOR
                            ? ($val != 0
                                ? -number_format($val, 2)
                                : 0)
                            : number_format($val ?? 0, 2);
                    @endphp
                    <div class="row g-3 mb-3">
                        @foreach ($fields as $field => $label)
                            <div class="col-md-3 col-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h6 class="card-subtitle mb-2 text-muted">{{ $label }}</h6>
                                        <p class="card-text fs-5 fw-bold">{{ $formatVal($extra->$field ?? 0) }}</p>
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
