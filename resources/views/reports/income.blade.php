@extends('layouts.sideBar')

@section('content')
<div class="container py-4" style="max-width: 1200px;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Today’s Sessions Report</h4>
        <span class="badge bg-primary rounded-pill">
            {{ now()->format('d M Y') }}
        </span>
    </div>

    <!-- Sessions Table -->
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Professor</th>
                            <th>NA</th>
                            <th>C</th>
                            <th>FP</th>
                            <th>LP</th>
                            <th>FE</th>
                            <th>LE</th>
                            <th>M</th>
                            <th>NP</th>
                            <th>Session Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sessions as $index => $session)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $session->professor->name ?? '-' }} - {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</td>
                                <td>{{ $session->session_students_count > 0 ? $session->session_students_count : '-' }}</td>
                                <td>{{ $session->total_center_price > 0 ? number_format($session->total_center_price, 1) : '-' }}</td>
                                <td>{{ $session->sessionExtra?->copies> 0 ? number_format($session->sessionExtra?->copies , 1) : '-' }}</td>
                                <td>{{ $session->total_printables > 0 ? number_format($session->total_printables , 1) : '-' }}</td>
                                <td>{{ number_format(0, 1) }}</td>
                                <td>{{ number_format(0, 1) }}</td>
                                <td>{{ $session->sessionExtra?->markers > 0 ? number_format($session->sessionExtra?->markers , 1) : '-' }}</td>
                                <td>{{ number_format($session->total_professor_price ?: '-', 1) }}</td>
                                <td class="fw-bold text-primary">
                                    {{ number_format(
                                        $session->total_center_price + $session->total_professor_price + $session->total_materials + $session->total_printables
                                        + $session->sessionExtra?->markers + $session->sessionExtra?->copies
                                        , 1) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-3">
                                    No sessions found today
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($sessions))
                        <tfoot class="table-dark">
                            <tr>
                                <th colspan="2" class="text-end">Totals:</th>
                                <th>{{ $totals['students'] }}</th>
                                <th>{{ number_format($totals['center_price'], 1) }}</th>
                                <th>{{ number_format($totals['copies'] ?? 0, 1) }}</th>
                                <th>{{ number_format($totals['printables'], 1) }}</th>
                                <th>{{ number_format(0, 1) }}</th>
                                <th>{{ number_format(0, 1) }}</th>
                                <th>{{ number_format($totals['markers'] ?? 0, 1) }}</th>
                                <th>{{ number_format($totals['professor_price'], 1) }}</th>
                                <th class="fw-bold text-primary">
                                    {{ number_format($totals['overall_total'], 1) }}
                                </th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
