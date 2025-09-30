@extends('layouts.sideBar')

@section('content')
<div class="container py-4" style="max-width: 1200px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Professors Session Schedule</h3>

    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('professors.schedule') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="professor_name" class="form-label">Professor Name</label>
                    <input type="text" class="form-control" id="professor_name" name="professor_name" value="{{ request('professor_name') }}" placeholder="Search by professor name">
                </div>
                <div class="col-md-4">
                    <label for="stage" class="form-label">Stage</label>
                    <select class="form-select" id="stage" name="stage">
                        <option value="">All Stages</option>
                        @foreach(\App\Enums\StagesEnum::all() as $stageOption)
                            <option value="{{ $stageOption['value'] }}" {{ request('stage') == $stageOption['value'] ? 'selected' : '' }}>
                                {{ $stageOption['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                    <a href="{{ route('professors.schedule') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(count($schedule) === 0)
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> No session schedules available.
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">#</th>
                                <th class="border-0">Professor</th>
                                <th class="border-0">Day</th>
                                <th class="border-0">From</th>
                                <th class="border-0">To</th>
                                <th class="border-0">Stage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            @endphp
                            @foreach($schedule as $index => $session)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $session->professor_name }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $days[$session->day] ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($session->from)->format('h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($session->to)->format('h:i A') }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ \App\Enums\StagesEnum::getStringValue($session->stage) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
