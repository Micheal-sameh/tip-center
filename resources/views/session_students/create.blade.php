@extends('layouts.sideBar')

@section('content')
    @if (session('error') || $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong>
            @if (session('error'))
                {{ session('error') }}
            @else
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container" style="max-width: 800px">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Create Attendance Record</h4>
            <div class="badge bg-primary rounded-pill">
                <i class="fas fa-calendar-check me-1"></i> {{ now()->format('M d, Y') }}
            </div>
        </div>

        <!-- Student Info Card -->
        <div class="card shadow-sm mb-4 border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Student Information</h5>
            </div>
            <div class="card-body">
                <h5 class="mb-2">{{ $student->name }}</h5>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-id-card me-1"></i> {{ $student->code }}
                    </span>
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-phone me-1"></i> {{ $student->phone }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Session Info Card -->
        <div class="card shadow-sm mb-4 border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Session Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Professor</h6>
                        <p>{{ $session->professor->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Stage</h6>
                        <p>{{ \App\Enums\StagesEnum::getStringValue($session->stage) }}</p>
                    </div>
                </div>
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
                        <p>${{ number_format($session->center_price, 2) }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Professor Price</h6>
                        <p>${{ number_format($session->professor_price, 2) }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Printables</h6>
                        <p>${{ number_format($session->printables ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Buttons -->
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Select Payment Mode</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                        data-bs-target="#simplePaymentModal">
                        <i class="fas fa-dollar-sign me-1"></i> Simple Payment
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#advancedPaymentModal">
                        <i class="fas fa-cogs me-1"></i> Advanced Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Payment Modal -->
    <div class="modal fade" id="simplePaymentModal" tabindex="-1" aria-labelledby="simplePaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('attendances.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="session_id" value="{{ $session->id }}">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Simple Payment</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="total_paid" class="form-label">Amount Paid</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">$</span>
                            <input type="number" name="total_paid" id="paidAmountInput" step="1" min="0"
                                class="form-control" required>
                        </div>
                        <label class="form-label">Remaining</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" id="remainingDisplay" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Advanced Payment Modal -->
    <div class="modal fade" id="advancedPaymentModal" tabindex="-1" aria-labelledby="advancedPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('attendances.store') }}" method="POST">
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
                            <input type="number" name="center_price" step="1" min="0"
                                class="form-control" value="{{ $session->center_price }}">
                        </div>
                        <div class="col-md-4">
                            <label>Professor Paid</label>
                            <input type="number" name="professor_price" step="1" min="0"
                                class="form-control" value="{{ $session->professor_price }}">
                        </div>
                        <div class="col-md-4">
                            <label>Printables</label>
                            <input type="number" name="printables" step="1" min="0" class="form-control"
                                value="{{ $session->printables ?? 0 }}">
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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paidInput = document.getElementById('paidAmountInput');
            const remainingDisplay = document.getElementById('remainingDisplay');
            const total = {{ $session->center_price + $session->professor_price + ($session->printables ?? 0) }};

            function updateRemaining() {
                const paid = parseFloat(paidInput.value) || 0;
                const remaining = (total - paid).toFixed(2);
                remainingDisplay.value = remaining;
            }

            paidInput.addEventListener('input', updateRemaining);

            // Trigger update when modal is shown
            const simpleModal = document.getElementById('simplePaymentModal');
            simpleModal.addEventListener('shown.bs.modal', function() {
                updateRemaining();
            });
        });
    </script>
@endsection
@endsection
