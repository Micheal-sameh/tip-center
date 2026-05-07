<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
@php
    $logo = App\Models\Setting::where('name', 'logo')->first();
    $logoUrl = $logo?->getFirstMediaUrl('app_logo');
    $tenantName = tenant()?->name ?? config('app.name');
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('trans.login') ?? 'Login' }} — {{ $tenantName }}</title>
    @if($logoUrl)<link rel="icon" href="{{ $logoUrl }}" type="image/png">@endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            margin: 0; padding: 0; height: 100%;
            font-family: 'Inter', -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        body {
            background: linear-gradient(135deg, #0F172A 0%, #1E3A5F 50%, #0F172A 100%);
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; padding: 20px;
        }
        /* Decorative circles */
        body::before {
            content: ''; position: fixed; top: -200px; right: -200px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,.18) 0%, transparent 70%);
            pointer-events: none;
        }
        body::after {
            content: ''; position: fixed; bottom: -200px; left: -200px;
            width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(16,185,129,.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .login-card {
            background: rgba(255,255,255,.97);
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,.4), 0 0 0 1px rgba(255,255,255,.1);
            width: 100%; max-width: 420px;
            padding: 40px 36px;
            position: relative; z-index: 1;
        }
        .login-logo {
            display: flex; flex-direction: column; align-items: center;
            margin-bottom: 28px; gap: 10px;
        }
        .login-logo img { width: 68px; height: 68px; object-fit: contain; border-radius: 14px; }
        .login-logo-placeholder {
            width: 68px; height: 68px; border-radius: 14px;
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.8rem;
        }
        .login-title { font-size: 1.3rem; font-weight: 800; color: #1E293B; margin: 0; }
        .login-sub   { font-size: .85rem; color: #64748B; margin: 3px 0 0; }

        .login-divider { height: 1px; background: #E2E8F0; margin: 0 0 22px; }

        .form-label { font-size: .8rem; font-weight: 600; color: #64748B; margin-bottom: 5px; }
        .form-control {
            border-radius: 9px; border: 1.5px solid #E2E8F0;
            font-size: .9rem; padding: 10px 14px;
            transition: all .2s; color: #1E293B; background: #F8FAFC;
        }
        .form-control:focus {
            border-color: #2563EB; background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,.12); outline: none;
        }
        .form-control.is-invalid { border-color: #EF4444; }
        .form-control.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }
        .invalid-feedback { font-size: .78rem; color: #DC2626; }

        .input-group .form-control { border-radius: 9px 0 0 9px; }
        .input-group .btn-toggle-pw {
            background: #F8FAFC; border: 1.5px solid #E2E8F0; border-left: none;
            border-radius: 0 9px 9px 0; color: #94A3B8; padding: 0 14px;
            cursor: pointer; transition: all .2s;
        }
        .input-group .btn-toggle-pw:hover { color: #2563EB; background: #EFF6FF; }

        .btn-login {
            width: 100%; padding: 11px;
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            color: #fff; font-weight: 700; font-size: .95rem;
            border: none; border-radius: 10px; cursor: pointer;
            transition: all .2s; letter-spacing: .2px;
            box-shadow: 0 4px 14px rgba(37,99,235,.35);
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #1D4ED8, #1E40AF);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37,99,235,.45);
        }
        .btn-login:active { transform: translateY(0); }

        .form-check-input:checked { background-color: #2563EB; border-color: #2563EB; }
        .form-check-label { font-size: .82rem; color: #64748B; }

        .alert { border-radius: 10px; font-size: .85rem; border: none; padding: 11px 14px; }
        .alert-danger  { background: #FEF2F2; color: #991B1B; }
        .alert-success { background: #ECFDF5; color: #065F46; }
    </style>
</head>
<body>
    <div class="login-card">

        {{-- Logo + Title --}}
        <div class="login-logo">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $tenantName }}">
            @else
                <div class="login-logo-placeholder"><i class="fas fa-graduation-cap"></i></div>
            @endif
            <div class="text-center">
                <div class="login-title">{{ $tenantName }}</div>
                <div class="login-sub">{{ __('trans.login') ?? 'Sign in to your account' }}</div>
            </div>
        </div>

        <div class="login-divider"></div>

        {{-- Alerts --}}
        @if(session('error'))
            <div class="alert alert-danger mb-3"><i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success mb-3"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('trans.email') ?? 'Email address' }}</label>
                <input id="email" type="email" name="email" required autofocus
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="you@example.com">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">{{ __('trans.password') ?? 'Password' }}</label>
                <div class="input-group">
                    <input id="password" type="password" name="password" required
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••">
                    <button type="button" class="btn-toggle-pw" id="togglePw" aria-label="Toggle password">
                        <i class="fas fa-eye" id="togglePwIcon"></i>
                    </button>
                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">{{ __('trans.remember_me') ?? 'Remember me' }}</label>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>{{ __('trans.login') ?? 'Sign in' }}
            </button>
        </form>
    </div>

    <script>
        const pwInput  = document.getElementById('password');
        const toggleBtn= document.getElementById('togglePw');
        const toggleIcon= document.getElementById('togglePwIcon');
        toggleBtn.addEventListener('click', function () {
            const isPassword = pwInput.type === 'password';
            pwInput.type = isPassword ? 'text' : 'password';
            toggleIcon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    </script>
</body>
</html>
