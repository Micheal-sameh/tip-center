<!DOCTYPE html>
<html>
<head>
    <title>Search Student Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f8f9fa;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-container img {
            max-width: 180px;
            height: auto;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-sm" style="min-width: 320px;">
        <div class="logo-container">
            <img src="{{ asset('path/to/your/logo.png') }}" alt="Logo" />
        </div>

        <h3 class="mb-4 text-center">Search Student Report</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="GET" action="{{ route('parents.student') }}">
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="Enter phone number">
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Student Code</label>
                <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" placeholder="Enter student code">
            </div>

            <button type="submit" class="btn btn-primary w-100">Search</button>
        </form>
    </div>
</body>
</html>
