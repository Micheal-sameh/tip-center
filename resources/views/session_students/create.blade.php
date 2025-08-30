@extends('layouts.sideBar')

@php
    $specialCase = $student->specialCases->firstWhere('id', $session->professor_id);
@endphp
@section('content')
    @if ($message)
        <div class="alert alert-warning">
            {{ $message }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-50 start-50 translate-middle"
            style="z-index: 9999; min-width: 300px; max-width: 500px; text-align: center;" role="alert" id="error-popup">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{!! nl2br(e($error)) !!}</li>
                @endforeach
            </ul>
        </div>


        <script>
            // Auto-hide after 5 seconds
            setTimeout(function() {
                let popup = document.getElementById('error-popup');
                if (popup) {
                    popup.classList.remove('show'); // Bootstrap fade-out
                    setTimeout(() => popup.remove(), 500); // Remove after fade
                }
            }, 5000);
        </script>
    @endif

    @if ($to_pay && $to_pay > 0)
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                <strong>Warning:</strong> This student has a pending payment of
                <strong>{{ number_format($to_pay, 2) }} EGP</strong> from a previous sessions.
            </div>
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
                            '',
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
                                    class="form-control" placeholder="Enter total paid"
                                    value="{{ old('total_paid', (int) $defaultTotal) }}" required>
                                <button type="submit" class="btn btn-success">Pay</button>
                            </div>
                        </div>
                    </form>
                    @if ($to_pay && $to_pay > 0)
                        <button class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#settleDueModal">
                            <i class="fas fa-wallet me-1"></i> Settle Previous Dues
                        </button>
                    @endif
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
                                value="{{ $specialCase ? $specialCase->pivot->center_price : $session->center_price }}">
                        </div>
                        <div class="col-md-4">
                            <label>Professor Paid</label>
                            <input type="number" name="professor_price" step="1" min="0" class="form-control"
                                value="{{ $specialCase ? $specialCase->pivot->professor_price : $session->professor_price }}">
                        </div>
                        <div class="col-md-4">
                            <label>Printables</label>
                            <input type="number" name="printables" step="1" min="0" class="form-control"
                                value="{{ $session->printables ?? 0 }}">
                        </div>
                        <div class="col-md-4">
                            <label>Materials</label>
                            <input type="number" name="materials" step="1" min="0" class="form-control"
                                value="{{ $session->materials ?? 0 }}">
                        </div>
                        <div class="col-md-4">
                            <label>To Pay</label>
                            <input type="number" name="to_pay" value="0" step="1" min="0"
                                class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($to_pay && $to_pay > 0)
        <!-- Settle Due Modal -->
        <div class="modal fade" id="settleDueModal" tabindex="-1" aria-labelledby="settleDueModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('students.settle_due', $student->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        <input type="hidden" name="previous_to_pay_id" value="{{ $to_pay_id ?? '' }}">

                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title" id="settleDueModalLabel">
                                <i class="fas fa-wallet me-2"></i>Settle Previous Dues
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <p>
                                This student has <strong>{{ number_format($to_pay, 2) }} EGP</strong> in unpaid dues.
                            </p>

                            <div class="mb-3">
                                <label for="settled_to_pay" class="form-label">Amount to Pay Now</label>
                                <input type="number" class="form-control" name="paid" id="settled_to_pay"
                                    step="1" min="0" max="{{ $to_pay }}" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-check-circle me-1"></i> Settle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    @if (collect($errors->all())->contains(fn($e) => str_contains($e, 'underpaid')))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('advancedPaymentModal'));
                modal.show();
            });
        </script>
    @endif

    @if (collect($errors->all())->contains(fn($e) => str_contains($e, 'underpaid')) || $specialCase)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('advancedPaymentModal'));
                modal.show();
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paidInput = document.getElementById('paidAmountInput');
            const total =
                {{ $session->center_price + $session->professor_price + ($session->printables ?? 0) + ($session->materials ?? 0) }};

            paidInput?.addEventListener('input', function() {
                const paid = parseFloat(paidInput.value) || 0;
                const remaining = total - paid;
                console.log('Remaining:', remaining.toFixed(2));
                // You can update a remaining display input here if needed
            });
        });
    </script>

@endpush
