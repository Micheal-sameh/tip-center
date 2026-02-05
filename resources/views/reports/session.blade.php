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
                            <option value="" disabled {{ empty($selectedType) ? 'selected' : '' }}>Export Format
                            </option>
                            @foreach (App\Enums\ReportType::all() as $type)
                                <option value="{{ $type['value'] }}"
                                    {{ (int) $selectedType === (int) $type['value'] ? 'selected' : '' }}>
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
                            @foreach ($reports as $index => $report)
                                @php
                                    $computed = $computedReports[$index];
                                @endphp
                                <tr class="{{ $computed['rowClass'] }}">
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
                                    <td class="text-end">{{ number_format($computed['reportValue'], 2) }}</td>
                                    @if ($showMaterials)
                                        <td>{{ $report->materials }}</td>
                                    @endif
                                    @if ($showPrintables)
                                        <td class="text-end">{{ $report->printables }}</td>
                                    @endif
                                    <td class="text-end fw-bold {{ $computed['toPayTotal'] > 0 ? 'text-danger' : '' }}">
                                        {{ number_format($computed['toPayTotal'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Settlements --}}
                @if ($settlements->isNotEmpty())
                    <h5 class="mt-5 mb-3">Settlements</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-success">
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Amount</th>
                                    @if ($selectedType != \App\Enums\ReportType::PROFESSOR)
                                        <th>Center</th>
                                        <th>Printables</th>
                                    @endif
                                    @if ($selectedType != \App\Enums\ReportType::CENTER)
                                        <th>Professor</th>
                                        <th>Materials</th>
                                    @endif
                                    <th>Description</th>
                                    <th>Settled At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($settlements as $settlement)
                                    <tr>
                                        @php
                                            $amount = match ((int) $selectedType) {
                                                \App\Enums\ReportType::PROFESSOR => $settlement->professor_amount +
                                                    $settlement->materials,
                                                \App\Enums\ReportType::CENTER => $settlement->center +
                                                    $settlement->printables,
                                                default => $settlement->amount,
                                            };
                                        @endphp
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $settlement->student->name ?? 'N/A' }}</td>
                                        <td class="text-end">{{ number_format($amount, 2) }}</td>
                                        @if ($selectedType != \App\Enums\ReportType::PROFESSOR)
                                            <td class="text-end">{{ number_format($settlement->center, 2) }}</td>
                                            <td class="text-end">{{ number_format($settlement->printables, 2) }}</td>
                                        @endif
                                        @if ($selectedType != \App\Enums\ReportType::CENTER)
                                            <td class="text-end">{{ number_format($settlement->professor_amount, 2) }}</td>
                                            <td class="text-end">{{ number_format($settlement->materials, 2) }}</td>
                                        @endif
                                        <td>{{ $settlement->description ?? '-' }}</td>
                                        <td>{{ $settlement->settled_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-info fw-bold">
                                    <th colspan="2">Totals</th>
                                    <th class="text-end">{{ number_format($totalsData['totalSettlementAmount'] ?? 0, 2) }}</th>
                                    @if ($selectedType != \App\Enums\ReportType::PROFESSOR)
                                        <th class="text-end">{{ number_format($settlementTotals['total_center'], 2) }}</th>
                                        <th class="text-end">{{ number_format($settlementTotals['total_printables'], 2) }}
                                        </th>
                                    @endif
                                    @if ($selectedType != \App\Enums\ReportType::CENTER)
                                        <th class="text-end">{{ number_format($settlementTotals['total_professor'], 2) }}
                                        </th>
                                        <th class="text-end">{{ number_format($settlementTotals['total_materials'], 2) }}
                                        </th>
                                    @endif
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif

                {{-- Online Payments --}}
                @if ($session->onlines && $session->onlines->isNotEmpty())
                    <h5 class="mt-5 mb-3">Online Payments</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    @if ($selectedType != \App\Enums\ReportType::CENTER)
                                        <th class="text-end">Materials</th>
                                        <th class="text-end">Professor Price</th>
                                    @endif
                                    @if ($selectedType != \App\Enums\ReportType::PROFESSOR)
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
                                        @if ($selectedType != \App\Enums\ReportType::CENTER)
                                            <td class="text-end">{{ number_format($online->materials, 2) }}</td>
                                            <td class="text-end">{{ number_format($online->professor, 2) }}</td>
                                        @endif
                                        @if ($selectedType != \App\Enums\ReportType::PROFESSOR)
                                            <td class="text-end">{{ number_format($online->center ?? 0, 2) }}</td>
                                        @endif
                                        <td>{{ App\Enums\StagesEnum::getStringValue($online->stage) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Summary Cards --}}
                <div class="row mt-4">
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h6 class="card-subtitle mb-2 text-muted">Attended Students</h6>
                                <p class="card-text fs-4 fw-bold">{{ $attendedCount }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($session->professor_price && isset($totalsData['professorTotal']))
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Professor</h6>
                                    <p class="card-text fs-4 fw-bold">
                                        {{ number_format($totalsData['professorTotal'], 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($session->center_price && isset($totalsData['centerTotal']))
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Center</h6>
                                    <p class="card-text fs-4 fw-bold">{{ number_format($totalsData['centerTotal'], 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($session->professor->balance && isset($totalsData['balanceValue']))
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Balance</h6>
                                    <p class="card-text fs-4 fw-bold">
                                        {{ number_format($totalsData['balanceValue'], 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($session->professor->materials_balance > 0 && isset($totalsData['materialsBalanceValue']))
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Material Balance (Prof)</h6>
                                    <p class="card-text fs-4 fw-bold">
                                        {{ number_format($totalsData['materialsBalanceValue'], 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($showPrintables && isset($totalsData['printablesTotal']))
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Student Papers</h6>
                                    <p class="card-text fs-4 fw-bold">{{ number_format($totalsData['printablesTotal'], 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($showMaterials && isset($totalsData['materialsTotal']))
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Materials</h6>
                                    <p class="card-text fs-4 fw-bold">{{ number_format($totalsData['materialsTotal'], 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($session->onlines->isNotEmpty() && isset($totalsData['onlineTotal']))
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Online Total</h6>
                                    <p class="card-text fs-4 fw-bold">
                                        {{ number_format($totalsData['onlineTotal'], 2) }}
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
                                    {{ number_format($summaryData['summaryTotal'], 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6 mb-3">
                        <div class="card h-100 {{ $summaryData['toCollect'] > 0 ? 'bg-warning bg-opacity-10' : '' }}">
                            <div class="card-body text-center">
                                <h6 class="card-subtitle mb-2 text-muted">To Collect</h6>
                                <p class="card-text fs-4 fw-bold {{ $summaryData['toCollect'] > 0 ? 'text-danger' : '' }}">
                                    {{ number_format($summaryData['toCollect'], 2) }}</p>
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
                        $formatVal = fn($val) => $selectedType == App\Enums\ReportType::PROFESSOR
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
