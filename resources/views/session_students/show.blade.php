@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 850px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Session Details</h4>
                <a href="javascript:history.back()" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card-body">
                <!-- Basic Info Section -->
                <div class="mb-4 p-3 bg-light rounded">
                    <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted">Professor</p>
                            <p class="fw-bold">{{ $session->professor->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted">Stage</p>
                            <p class="fw-bold">{{ App\Enums\StagesEnum::getStringValue($session->stage) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="mb-4 p-3 bg-light rounded">
                    <h5 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Pricing</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <p class="mb-1 text-muted">Professor Price</p>
                            <p class="fw-bold">{{ number_format($session->professor_price, 2) }} {{ config('app.currency', 'EGP') }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <p class="mb-1 text-muted">Center Price</p>
                            <p class="fw-bold">{{ number_format($session->center_price, 2) }} {{ config('app.currency', 'EGP') }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <p class="mb-1 text-muted">Printables</p>
                            <p class="fw-bold">{{ $session->printables ? number_format($session->printables, 2).' '.config('app.currency', 'EGP') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Timing Section -->
                @if($session->start_at && $session->end_at)
                <div class="mb-4 p-3 bg-light rounded">
                    <h5 class="mb-3"><i class="fas fa-clock me-2"></i>Timing</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted">Start Time</p>
                            <p class="fw-bold">{{ \Carbon\Carbon::parse($session->start_at)->format('h:i A') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 text-muted">End Time</p>
                            <p class="fw-bold">{{ \Carbon\Carbon::parse($session->end_at)->format('h:i A') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Status Section -->
                <div class="mb-4 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted">Status</p>
                            <span class="badge bg-{{ $session->status === App\Enums\SessionStatus::ACTIVE ? 'success' : 'secondary' }}">
                                {{ App\Enums\SessionStatus::getStringValue($session->status) }}
                            </span>
                        </div>
                        <div>
                            <form action="{{ route('sessions.status', $session->id) }}" method="POST" class="d-inline me-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $session->status === App\Enums\SessionStatus::ACTIVE ? 'inactive' : 'active' }}">
                                <button type="submit" class="btn btn-sm btn-{{ $session->status === App\Enums\SessionStatus::ACTIVE ? 'outline-danger' : 'outline-success' }}">
                                    {{ $session->status === App\Enums\SessionStatus::ACTIVE ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <a href="{{ route('sessions.edit', $session->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Meta Info -->
                <div class="mt-4 pt-3 border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Created At</p>
                            <p class="small">{{ $session->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Last Updated</p>
                            <p class="small">{{ $session->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection