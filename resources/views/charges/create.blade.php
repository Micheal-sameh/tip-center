@extends('layouts.sideBar')

@section('content')
    <div class="container-fluid px-4 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">
                <i class="fas fa-plus me-2 text-success"></i> {{ $title }}
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
                        <label class="form-label">Discription</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="form-control @error('title') is-invalid @enderror" placeholder="Enter title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}"
                            class="form-control @error('amount') is-invalid @enderror" placeholder="Enter amount">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @can('charges_salary')
                        <!-- Created At -->
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="created_at" value="{{ old('created_at', date('Y-m-d')) }}"
                                class="form-control @error('created_at') is-invalid @enderror">
                            @error('created_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endcan

                    <!-- Is gap -->
                    <div class="mb-3">
                        <label class="form-label d-block">Charge Type</label>
                        @php
                            $types = App\Enums\ChargeType::create($lastPart); // should return an array/collection
                            $autoCheck = count($types) === 1 ? $types[0]['value'] : null;
                        @endphp
                        @foreach ($types as $key => $type)
                            <div class="form-check form-check-inline">

                                <input type="radio" name="type" id="type_{{ $type['value'] }}"
                                    value="{{ $type['value'] }}" class="form-check-input" {
                                    {{ old('type') === $type['value'] ? 'checked' : '' }}
                                    {{ $autoCheck === $type['value'] ? 'checked' : '' }}>
                                <label for="type_{{ $type['value'] }}" class="form-check-label">
                                    {{ $type['name'] }}
                                </label>
                            </div>
                        @endforeach
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
