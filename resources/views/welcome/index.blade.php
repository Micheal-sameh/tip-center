<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
@php
    $logo = App\Models\Setting::where('name', 'logo')->first();
    $faviconUrl = $logo?->getFirstMediaUrl('app_logo');
    $tenantName = tenant()?->name ?? config('app.name');
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenantName }}</title>
    @if($faviconUrl)<link rel="icon" href="{{ $faviconUrl }}" type="image/png">@endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            height: 100%;
            font-family: 'Inter', -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        body {
            background: linear-gradient(155deg, #0F172A 0%, #1E3A5F 45%, #0F2D40 100%);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            min-height: 100vh; padding: 24px; position: relative; overflow: hidden;
        }

        /* Decorative blobs */
        .blob {
            position: fixed; border-radius: 50%; pointer-events: none;
            animation: blob-float 8s ease-in-out infinite;
        }
        .blob-1 {
            width: 500px; height: 500px; top: -150px; right: -150px;
            background: radial-gradient(circle, rgba(37,99,235,.15) 0%, transparent 70%);
            animation-delay: 0s;
        }
        .blob-2 {
            width: 400px; height: 400px; bottom: -120px; left: -120px;
            background: radial-gradient(circle, rgba(16,185,129,.1) 0%, transparent 70%);
            animation-delay: 3s;
        }
        .blob-3 {
            width: 300px; height: 300px; bottom: 100px; right: 100px;
            background: radial-gradient(circle, rgba(139,92,246,.08) 0%, transparent 70%);
            animation-delay: 5s;
        }
        @keyframes blob-float {
            0%, 100% { transform: translate(0,0) scale(1); }
            33% { transform: translate(20px,-20px) scale(1.05); }
            66% { transform: translate(-10px,10px) scale(.97); }
        }

        /* Header */
        .portal-header {
            text-align: center; margin-bottom: 44px; position: relative; z-index: 1;
        }
        .portal-logo {
            width: 80px; height: 80px; border-radius: 20px; object-fit: contain;
            margin: 0 auto 16px; display: block;
            box-shadow: 0 8px 32px rgba(0,0,0,.3);
        }
        .portal-logo-placeholder {
            width: 80px; height: 80px; border-radius: 20px;
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; color: #fff; margin: 0 auto 16px;
            box-shadow: 0 8px 32px rgba(37,99,235,.4);
        }
        .portal-title {
            font-size: clamp(1.5rem, 4vw, 2.2rem);
            font-weight: 900; color: #fff; letter-spacing: -.5px;
            margin-bottom: 8px;
        }
        .portal-sub { font-size: .95rem; color: rgba(255,255,255,.55); }

        /* Cards */
        .portal-cards {
            display: flex; gap: 18px; flex-wrap: wrap; justify-content: center;
            position: relative; z-index: 1;
        }
        .portal-card {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.12);
            backdrop-filter: blur(12px);
            border-radius: 18px; padding: 32px 28px;
            width: 200px; text-align: center;
            text-decoration: none; color: #fff;
            transition: all .25s cubic-bezier(.4,0,.2,1);
            cursor: pointer;
        }
        .portal-card:hover {
            background: rgba(255,255,255,.13);
            border-color: rgba(255,255,255,.25);
            transform: translateY(-6px);
            box-shadow: 0 20px 48px rgba(0,0,0,.3);
            color: #fff;
        }
        .portal-card-icon {
            width: 60px; height: 60px; border-radius: 16px; margin: 0 auto 18px;
            display: flex; align-items: center; justify-content: center; font-size: 1.6rem;
        }
        .portal-card-icon.blue  { background: linear-gradient(135deg, #2563EB, #1D4ED8); box-shadow: 0 6px 20px rgba(37,99,235,.4); }
        .portal-card-icon.green { background: linear-gradient(135deg, #10B981, #059669); box-shadow: 0 6px 20px rgba(16,185,129,.4); }
        .portal-card-title { font-size: 1rem; font-weight: 700; margin-bottom: 6px; }
        .portal-card-desc  { font-size: .78rem; color: rgba(255,255,255,.5); line-height: 1.5; }

        /* Footer */
        .portal-footer {
            margin-top: 40px; text-align: center;
            font-size: .78rem; color: rgba(255,255,255,.25);
            position: relative; z-index: 1;
        }

        @media (max-width: 480px) {
            .portal-cards { flex-direction: column; align-items: center; }
            .portal-card  { width: 100%; max-width: 280px; }
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="portal-header">
        @if($faviconUrl)
            <img src="{{ $faviconUrl }}" alt="{{ $tenantName }}" class="portal-logo">
        @else
            <div class="portal-logo-placeholder"><i class="fas fa-graduation-cap"></i></div>
        @endif
        <div class="portal-title">{{ $tenantName }}</div>
        <div class="portal-sub">Select your portal to continue</div>
    </div>

    <div class="portal-cards">
        <a href="{{ auth()->check() ? route('attendances.index') : route('loginPage') }}" class="portal-card">
            <div class="portal-card-icon blue"><i class="fas fa-user-tie"></i></div>
            <div class="portal-card-title">Staff</div>
            <div class="portal-card-desc">Manage sessions, students &amp; operations</div>
        </a>
        <a href="{{ route('parents.index') }}" class="portal-card">
            <div class="portal-card-icon green"><i class="fas fa-users"></i></div>
            <div class="portal-card-title">Parent</div>
            <div class="portal-card-desc">View attendance &amp; session updates</div>
        </a>
    </div>

    <div class="portal-footer">
        &copy; {{ date('Y') }} {{ $tenantName }}. All rights reserved.
    </div>
</body>
</html>
