@extends('layouts.sideBar')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{!! nl2br(e($error)) !!}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container" style="max-width: 800px">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Create Attendance Record</h4>
            <div class="badge bg-primary rounded-pill">
                <i class="fas fa-calendar-check me-1"></i> {{ now()->format('M d, Y') }}
            </div>
        </div>

        <!-- Pricing Card -->
        <div class="card shadow-sm mb-4 border-secondary">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-money-bill-alt me-2"></i>Session Pricing</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6>Center Price</h6>
                        <p>{{ number_format($session->center_price, 2) }} EGP</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Professor Price</h6>
                        <p>{{ number_format($session->professor_price, 2) }} EGP</p>
                    </div>
                    @if ($session->printables)
                        <div class="col-md-4">
                            <h6>Printables</h6>
                            <p>{{ number_format($session->printables ?? 0, 2) }} EGP</p>
                        </div>
                    @endif
                    @if ($session->materials)
                        <div class="col-md-4">
                            <h6>Materials</h6>
                            <p>{{ number_format($session->materials ?? 0, 2) }} EGP</p>
                        </div>
                    @endif

                    @php
                        $defaultTotal = number_format(
                            ($session->materials ?? 0) +
                            ($session->printables ?? 0) +
                            ($session->professor_price ?? 0) +
                            ($session->center_price ?? 0),
                            2,
                            '.',
                            ''
                        );
                    @endphp

                    <form method="POST" action="{{ route('attendances.store', $session->id) }}">
                        @csrf
                        <div class="col-md-6">
                            <h6>Total Paid</h6>
                            <div class="input-group">
                                <input type="hidden" name="session_id" value="{{ $session->id }}">
                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                <input type="number" id="paidAmountInput" name="total_paid" step="1" min="0"
                                       class="form-control"
                                       placeholder="Enter total paid"
                                       value="{{ old('total_paid', (int) $defaultTotal) }}"
                                       required>
                                <button type="submit" class="btn btn-success">Pay</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Buttons -->
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Select Payment Mode</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#advancedPaymentModal">
                        <i class="fas fa-cogs me-1"></i> Advanced Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Payment Modal -->
    <div class="modal fade" id="advancedPaymentModal" tabindex="-1" aria-labelledby="advancedPaymentModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('attendances.store', $session->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="session_id" value="{{ $session->id }}">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Advanced Payment</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>

                    <div class="modal-body row g-3">
                        <div class="col-md-4">
                            <label>Center Paid</label>
                            <input type="number" name="center_price" step="1" min="0" class="form-control"
                                   value="{{ $session->center_price }}">
                        </div>
                        <div class="col-md-4">
                            <label>Professor Paid</label>
                            <input type="number" name="professor_price" step="1" min="0" class="form-control"
                                   value="{{ $session->professor_price }}">
                        </div>
                        @if ($session->printables)
                            <div class="col-md-4">
                                <label>Printables</label>
                                <input type="number" name="printables" step="1" min="0" class="form-control"
                                       value="{{ $session->printables ?? 0 }}">
                            </div>
                        @endif
                        @if ($session->materials)
                            <div class="col-md-4">
                                <label>Materials</label>
                                <input type="number" name="materials" step="1" min="0" class="form-control"
                                       value="{{ $session->materials ?? 0 }}">
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if (collect($errors->all())->contains(fn($e) => str_contains($e, 'underpaid')))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('advancedPaymentModal'));
                modal.show();
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paidInput = document.getElementById('paidAmountInput');
            const total = {{ $session->center_price + $session->professor_price + ($session->printables ?? 0) + ($session->materials ?? 0) }};

            paidInput?.addEventListener('input', function () {
                const paid = parseFloat(paidInput.value) || 0;
                const remaining = total - paid;
                console.log('Remaining:', remaining.toFixed(2));
                // You can update a remaining display input here if needed
            });
        });
    </script>
@endpush
