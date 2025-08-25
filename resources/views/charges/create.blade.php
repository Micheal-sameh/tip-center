@extends('layouts.sideBar')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">
            <i class="fas fa-plus me-2 text-success"></i> Create gap
        </h4>
        <a href="{{ route('charges.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body">
            <form action="{{ route('charges.store') }}" method="POST">
                @csrf

                <!-- Title -->
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           class="form-control @error('title') is-invalid @enderror"
                           placeholder="Enter title">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Amount -->
                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input type="number"
                           step="0.01"
                           name="amount"
                           value="{{ old('amount') }}"
                           class="form-control @error('amount') is-invalid @enderror"
                           placeholder="Enter amount">
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Is gap -->
                <div class="mb-3 form-check">
                    <input type="checkbox"
                           name="is_gap"
                           value="1"
                           class="form-check-input"
                           id="isgapCheck"
                           {{ old('is_gap') ? 'checked' : '' }}>
                    <label for="isgapCheck" class="form-check-label">Is gap?</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Save
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
