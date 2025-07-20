<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow rounded-4 p-4 w-100" style="max-width: 400px;">
            @php
                $logo = App\Models\Setting::where('name', 'logo')->first();

            @endphp
            <!-- Logo -->
            <div class="text-center mb-4">
            {{-- @dd($logo?->getFirstMediaUrl('app_logo')) --}}
                <img src="{{ $logo?->getFirstMediaUrl('app_logo') }}" alt="Logo" class="img-fluid" style="max-height: 80px;">
            </div>

            <!-- Title -->
            {{-- <h4 class="text-center mb-4"> {{ config('app.name') }}</h4> --}}

            <!-- Error Message -->
            @if (session('error'))
                <div class="alert alert-danger small">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" type="email" name="email" required autofocus
                        class="form-control @error('email') is-invalid @enderror">

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" required
                        class="form-control @error('password') is-invalid @enderror">

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    {{-- <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot Password?</a> --}}
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>

                {{-- <p class="text-center small mb-0">
                    Don’t have an account?
                    <a href="{{ route('register') }}" class="text-primary text-decoration-none">Register</a>
                </p> --}}
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
