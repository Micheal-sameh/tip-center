<!DOCTYPE html>
<html lang="en">
@php
    $logo = App\Models\Setting::where('name', 'logo')->first();
    $faviconUrl = $logo?->getFirstMediaUrl('app_logo');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Student Report</title>
    <link rel="icon" href="{{ $faviconUrl }}" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 150px;
            height: auto;
        }

        @media (max-width: 576px) {
            .card {
                padding: 1rem !important;
            }

            .logo-container img {
                max-width: 100px;
            }

            h3 {
                font-size: 1.25rem;
            }
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100 p-3">

    <div class="card p-4 shadow-sm w-100" style="max-width: 400px;">

        {{-- Logo --}}
        <div class="logo-container">
            <img src="{{ $faviconUrl }}" alt="App Logo" class="profile-img img-fluid">
        </div>

        <h3 class="mb-4 text-center">Search Student Report</h3>

        {{-- Specific Error Message --}}
        @if (session('error'))
            <div class="alert alert-danger p-2 text-center mb-3">
                {{ session('error') }}
            </div>
        @endif
        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger p-2">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Search Form --}}
        <form method="GET" action="{{ route('parents.student') }}">
            <div class="mb-3">
                <label for="phone" class="form-label">Parent Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}"
                    placeholder="Enter phone number">
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Student Code</label>
                <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}"
                    placeholder="Enter student code">
            </div>

            <button type="submit" class="btn btn-primary w-100">Search</button>
        </form>

        <div class="mt-3 text-center">
            <a href="{{ route('parents.schedule') }}" class="btn btn-outline-secondary w-100">View Schedule</a>
        </div>
    </div>

    {{-- Schedule Section --}}
    @if(isset($schedule) && count($schedule) > 0)
        <div class="mt-4">
            <h4 class="mb-3 text-center">Professor Schedule</h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
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
                                <td>{{ $session->professor_name }}</td>
                                <td>{{ $days[$session->day] ?? 'Unknown' }}</td>
                                <td>{{ \Carbon\Carbon::parse($session->from)->format('h:i A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($session->to)->format('h:i A') }}</td>
                                <td>{{ \App\Enums\StagesEnum::getStringValue($session->stage) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</body>

</html>
