@php
    $logo = App\Models\Setting::where('name', 'logo')->first();
    $faviconUrl = $logo?->getFirstMediaUrl('app_logo');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Professors Session Schedule - Public</title>
    <link rel="icon" href="{{ $faviconUrl }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .profile-img {
            max-width: 150px;
            height: auto;
            border-radius: 50%;
            object-fit: contain;
        }

        @media (max-width: 576px) {
            .profile-img {
                max-width: 100px;
            }
        }
    </style>
</head>
<body>
<div class="container py-4" style="max-width: 1200px;">
    {{-- Logo --}}
    <div class="mb-4 text-center">
        <img src="{{ $faviconUrl }}" alt="App Logo" class="profile-img img-fluid">
    </div>

    <h3 class="mb-4 text-center">Professors Session Schedule</h3>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ url()->current() }}" class="row g-3">
                <div class="col-md-4">
                    <label for="professor_name" class="form-label">Professor Name</label>
                    <input type="text" class="form-control" id="professor_name" name="professor_name" value="{{ request('professor_name') }}" placeholder="Search by professor name" />
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
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
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
                                <th>#</th>
                                <th>Professor</th>
                                <th>Day</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Stage</th>
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
</body>
</html>
