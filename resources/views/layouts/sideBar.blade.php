<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

@php
    $logo    = App\Models\Setting::where('name', 'logo')->first();
    $logoUrl = $logo?->getFirstMediaUrl('app_logo');
    $isDemoMode = isset($isDemoMode) && $isDemoMode;
    $tenantName = tenant()?->name ?? config('app.name');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $tenantName)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @if(app()->getLocale() == 'ar')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @endif
    @if($logoUrl)
    <link rel="icon" href="{{ $logoUrl }}" type="image/png">
    @endif
    <style>
        :root {
            --brand: #2563EB; --brand-dark: #1D4ED8; --brand-light: #EFF6FF;
            --sidebar-bg: #0F172A; --sidebar-hover: #1E293B; --sidebar-active: #1E3A5F;
            --sidebar-border: rgba(255,255,255,.07); --sidebar-text: rgba(255,255,255,.75);
            --sidebar-width: 260px; --topbar-height: 64px;
            --content-bg: #F8FAFC; --white: #FFFFFF; --border: #E2E8F0;
            --font: 'Inter', sans-serif;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin:0; padding:0; width:100%; height:100%; font-family:var(--font); color:#1E293B; background:var(--content-bg); }
        .app-shell { display:flex; min-height:100vh; }
        .sidebar { position:fixed; top:0; left:0; width:var(--sidebar-width); height:100vh; background:var(--sidebar-bg); display:flex; flex-direction:column; z-index:1040; transition:transform .28s; overflow:hidden; }
        [dir="rtl"] .sidebar { left:auto; right:0; }
        .sidebar-brand { display:flex; align-items:center; gap:12px; padding:0 20px; height:var(--topbar-height); border-bottom:1px solid var(--sidebar-border); text-decoration:none; flex-shrink:0; }
        .sidebar-brand-icon { width:36px; height:36px; border-radius:9px; background:linear-gradient(135deg,var(--brand),var(--brand-dark)); display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px; flex-shrink:0; }
        .sidebar-brand-text { font-weight:800; font-size:1rem; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .sidebar-user { display:flex; align-items:center; gap:12px; padding:16px 20px; border-bottom:1px solid var(--sidebar-border); flex-shrink:0; }
        .sidebar-avatar { width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid rgba(255,255,255,.15); flex-shrink:0; }
        .sidebar-avatar-placeholder { width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,var(--brand),var(--brand-dark)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:.85rem; flex-shrink:0; }
        .sidebar-user-name { font-size:.88rem; font-weight:600; color:#fff; line-height:1.3; }
        .sidebar-user-role { font-size:.75rem; color:rgba(255,255,255,.45); margin-top:1px; }
        .sidebar-nav { flex:1; overflow-y:auto; padding:12px 0; }
        .sidebar-nav::-webkit-scrollbar { width:4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background:rgba(255,255,255,.1); border-radius:4px; }
        .sidebar-section-label { font-size:.65rem; font-weight:700; color:rgba(255,255,255,.28); text-transform:uppercase; letter-spacing:1px; padding:14px 20px 6px; }
        .sidebar-link { display:flex; align-items:center; gap:12px; padding:9px 20px; color:var(--sidebar-text); text-decoration:none; font-size:.86rem; font-weight:500; transition:background .15s,color .15s; position:relative; }
        .sidebar-link:hover { background:var(--sidebar-hover); color:#fff; }
        .sidebar-link.active { background:var(--sidebar-active); color:#fff; }
        .sidebar-link.active::before { content:''; position:absolute; top:0; bottom:0; left:0; width:3px; background:var(--brand); border-radius:0 3px 3px 0; }
        [dir="rtl"] .sidebar-link.active::before { left:auto; right:0; border-radius:3px 0 0 3px; }
        .sidebar-link-icon { width:20px; text-align:center; font-size:.9rem; flex-shrink:0; }
        .sidebar-group-toggle { display:flex; align-items:center; gap:12px; padding:9px 20px; color:var(--sidebar-text); cursor:pointer; font-size:.86rem; font-weight:500; background:none; border:none; width:100%; transition:background .15s,color .15s; }
        .sidebar-group-toggle:hover { background:var(--sidebar-hover); color:#fff; }
        .sidebar-group-toggle-icon { width:20px; text-align:center; font-size:.9rem; flex-shrink:0; }
        .sidebar-group-arrow { margin-left:auto; font-size:.75rem; transition:transform .2s; color:rgba(255,255,255,.3); }
        [dir="rtl"] .sidebar-group-arrow { margin-left:0; margin-right:auto; }
        .sidebar-group.open .sidebar-group-arrow { transform:rotate(90deg); }
        .sidebar-group-content { max-height:0; overflow:hidden; transition:max-height .25s ease; }
        .sidebar-group.open .sidebar-group-content { max-height:600px; }
        .sidebar-sublink { display:flex; align-items:center; gap:10px; padding:8px 20px 8px 48px; color:rgba(255,255,255,.55); text-decoration:none; font-size:.82rem; font-weight:500; transition:background .15s,color .15s; }
        [dir="rtl"] .sidebar-sublink { padding:8px 48px 8px 20px; }
        .sidebar-sublink:hover { background:var(--sidebar-hover); color:#fff; }
        .sidebar-sublink.active { color:#fff; background:rgba(37,99,235,.15); }
        .sidebar-footer { padding:14px 20px; border-top:1px solid var(--sidebar-border); flex-shrink:0; }
        .topbar { display:none; position:fixed; top:0; left:0; right:0; z-index:1030; height:var(--topbar-height); background:var(--sidebar-bg); align-items:center; padding:0 16px; gap:12px; box-shadow:0 2px 8px rgba(0,0,0,.2); }
        .topbar-menu-btn { background:none; border:none; color:#fff; font-size:1.1rem; cursor:pointer; padding:6px; }
        .topbar-title { flex:1; color:#fff; font-weight:700; font-size:.95rem; }
        .topbar-back { background:none; border:none; color:rgba(255,255,255,.6); font-size:1rem; cursor:pointer; padding:6px; }
        .content-area { flex:1; margin-left:var(--sidebar-width); min-height:100vh; display:flex; flex-direction:column; }
        [dir="rtl"] .content-area { margin-left:0; margin-right:var(--sidebar-width); }
        .page-topbar { background:var(--white); border-bottom:1px solid var(--border); padding:0 28px; height:60px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 1px 3px rgba(0,0,0,.06); position:sticky; top:0; z-index:100; }
        .page-topbar-title { font-size:1rem; font-weight:700; color:#1E293B; }
        .page-topbar-right { display:flex; align-items:center; gap:12px; }
        .topbar-lang-btn { display:flex; align-items:center; gap:6px; padding:7px 14px; background:var(--content-bg); border:1px solid var(--border); border-radius:8px; font-size:.82rem; font-weight:600; color:#64748B; cursor:pointer; text-decoration:none; transition:all .2s; }
        .topbar-lang-btn:hover { border-color:var(--brand); color:var(--brand); }
        .topbar-user-chip { display:flex; align-items:center; gap:8px; padding:4px 12px 4px 4px; background:var(--content-bg); border:1px solid var(--border); border-radius:50px; cursor:pointer; }
        .topbar-user-chip img { width:32px; height:32px; border-radius:50%; object-fit:cover; }
        .topbar-user-chip-name { font-size:.82rem; font-weight:600; }
        .main-content { flex:1; padding:28px; overflow-x:hidden; }
        .demo-banner { background:linear-gradient(135deg,#F59E0B,#D97706); color:#fff; text-align:center; padding:10px 20px; font-size:.85rem; font-weight:600; display:flex; align-items:center; justify-content:center; gap:10px; }
        .demo-banner a { color:#fff; text-decoration:underline; font-weight:700; }
        .sidebar-overlay { display:none; position:fixed; inset:0; z-index:1039; background:rgba(0,0,0,.5); }
        .flash-wrap { margin-bottom:20px; }
        .flash-wrap .alert { border-radius:10px; font-size:.88rem; }
        @media (max-width:1024px) {
            .sidebar { transform:translateX(-100%); }
            [dir="rtl"] .sidebar { transform:translateX(100%); }
            .sidebar.open { transform:translateX(0); }
            .sidebar-overlay.visible { display:block; }
            .topbar { display:flex; }
            .page-topbar { display:none; }
        }
    </style>
    @stack('head')
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a href="{{ url('/') }}" class="sidebar-brand">
            <div class="sidebar-brand-icon"><i class="fas fa-graduation-cap"></i></div>
            <span class="sidebar-brand-text">{{ $tenantName }}</span>
        </a>
        @auth
        <div class="sidebar-user">
            @if(auth()->user()->hasMedia('profile_pic'))
                <img src="{{ auth()->user()->getFirstMediaUrl('profile_pic') }}" alt="{{ auth()->user()->name }}" class="sidebar-avatar">
            @else
                <div class="sidebar-avatar-placeholder">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            @endif
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</div>
            </div>
        </div>
        @endauth
        <nav class="sidebar-nav">
            <div class="sidebar-section-label">{{ __('trans.main') ?? 'Main' }}</div>
            <a href="{{ route('attendances.index') }}" class="sidebar-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fas fa-calendar-check"></i></span>{{ __('trans.attendence') ?? 'Attendance' }}</a>
            <a href="{{ route('professors.schedule') }}" class="sidebar-link {{ request()->routeIs('professors.schedule') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fas fa-calendar-alt"></i></span>{{ __('trans.schedule') ?? 'Schedule' }}</a>
            @can('sessions_view')
            <a href="{{ route('sessions.index') }}" class="sidebar-link {{ request()->routeIs('sessions.*') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fas fa-chalkboard"></i></span>{{ __('trans.sessions') ?? 'Sessions' }}</a>
            @endcan
            <div class="sidebar-section-label">{{ __('trans.people') ?? 'People' }}</div>
            @can('students_view')
            <a href="{{ route('students.index') }}" class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fas fa-user-graduate"></i></span>{{ __('trans.students') ?? 'Students' }}</a>
            @endcan
            @can('professors_view')
            <a href="{{ route('professors.index') }}" class="sidebar-link {{ request()->routeIs('professors.index') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fas fa-chalkboard-teacher"></i></span>{{ __('trans.professors') ?? 'Professors' }}</a>
            @endcan
            <div class="sidebar-group" id="group-blacklists">
                <button class="sidebar-group-toggle" onclick="toggleGroup('group-blacklists')"><span class="sidebar-group-toggle-icon"><i class="fas fa-ban"></i></span>{{ __('trans.blacklists') ?? 'Blacklists' }}<i class="fas fa-chevron-right sidebar-group-arrow"></i></button>
                <div class="sidebar-group-content">
                    <a href="{{ route('professor_blacklists.index') }}" class="sidebar-sublink {{ request()->routeIs('professor_blacklists.*') ? 'active' : '' }}"><i class="fas fa-circle fa-xs"></i> Professor Blacklist</a>
                    <a href="{{ route('student_blacklists.index') }}" class="sidebar-sublink {{ request()->routeIs('student_blacklists.*') ? 'active' : '' }}"><i class="fas fa-circle fa-xs"></i> Student Blacklist</a>
                </div>
            </div>
            @canany(['charges_index', 'income_report', 'monthly_income', 'special_room_report', 'monthly_special_rooms'])
            <div class="sidebar-section-label">{{ __('trans.finance') ?? 'Finance' }}</div>
            @can('charges_index')
            <a href="{{ route('charges.index') }}" class="sidebar-link {{ request()->routeIs('charges.index') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fas fa-receipt"></i></span>{{ __('trans.charges') ?? 'Charges' }}</a>
            <a href="{{ route('charges.gap') }}" class="sidebar-link {{ request()->routeIs('charges.gap') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fas fa-balance-scale"></i></span>{{ __('trans.gap') ?? 'Gap' }}</a>
            @endcan
            @endcanany
            @canany(['sessions_report', 'students_report', 'income_report', 'monthly_income', 'special_room_report', 'monthly_special_rooms'])
            <div class="sidebar-section-label">{{ __('trans.reports') ?? 'Reports' }}</div>
            <div class="sidebar-group {{ request()->routeIs('reports.*') ? 'open' : '' }}" id="group-reports">
                <button class="sidebar-group-toggle" onclick="toggleGroup('group-reports')"><span class="sidebar-group-toggle-icon"><i class="fas fa-chart-bar"></i></span>{{ __('trans.reports') ?? 'Reports' }}<i class="fas fa-chevron-right sidebar-group-arrow"></i></button>
                <div class="sidebar-group-content">
                    @can('sessions_report')
                    <a href="{{ route('reports.index') }}" class="sidebar-sublink {{ request()->routeIs('reports.index') ? 'active' : '' }}"><i class="fas fa-circle fa-xs"></i> {{ __('trans.session_reports') ?? 'Session Reports' }}</a>
                    @endcan
                    @can('students_report')
                    <a href="{{ route('reports.student') }}" class="sidebar-sublink {{ request()->routeIs('reports.student') ? 'active' : '' }}"><i class="fas fa-circle fa-xs"></i> {{ __('trans.student_reports') ?? 'Student Reports' }}</a>
                    @endcan
                    @can('income_report')
                    <a href="{{ route('reports.income') }}" class="sidebar-sublink {{ request()->routeIs('reports.income') ? 'active' : '' }}"><i class="fas fa-circle fa-xs"></i> {{ __('trans.income') ?? 'Income' }}</a>
                    @endcan
                    @can('monthly_income')
                    <a href="{{ route('reports.monthly-income') }}" class="sidebar-sublink {{ request()->routeIs('reports.monthly-income') ? 'active' : '' }}"><i class="fas fa-circle fa-xs"></i> {{ __('trans.monthly_income') ?? 'Monthly Income' }}</a>
                    <a href="{{ route('reports.charges') }}" class="sidebar-sublink {{ request()->routeIs('reports.charges') ? 'active' : '' }}"><i class="fas fa-circle fa-xs"></i> Charges Report</a>
                    <a href="{{ route('reports.student-settlements') }}" class="sidebar-sublink {{ request()->routeIs('reports.student-settlements') ? 'active' : '' }}"><i class="fas fa-circle fa-xs"></i> Student Settlements</a>
                    <a href="{{ route('reports.to_pay') }}" class="sidebar-sublink {{ request()->routeIs('reports.to_pay') ? 'active' : '' }}"><i class="fas fa-circle fa-xs"></i> To Pay</a>
                    @endcan
                    @can('special_room_report')
                    <a href="{{ route('reports.special-rooms') }}" class="sidebar-sublink {{ request()->routeIs('reports.special-rooms') ? 'active' : '' }}"><i class="fas fa-circle fa-xs"></i> {{ __('trans.room 10 & 11') ?? 'Rooms 10 & 11' }}</a>
                    @endcan
                    @can('monthly_special_rooms')
                    <a href="{{ route('reports.monthly-ten-eleven') }}" class="sidebar-sublink {{ request()->routeIs('reports.monthly-ten-eleven') ? 'active' : '' }}"><i class="fas fa-circle fa-xs"></i> {{ __('trans.monthly_special_rooms') ?? 'Monthly Rooms' }}</a>
                    @endcan
                </div>
            </div>
            @endcanany
            <div class="sidebar-section-label">{{ __('trans.system') ?? 'System' }}</div>
            @can('users_view')
            <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fas fa-users-cog"></i></span>{{ __('trans.users') ?? 'Users' }}</a>
            @endcan
            @can('settings_update')
            <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fas fa-cog"></i></span>{{ __('trans.settings') ?? 'Settings' }}</a>
            @endcan
            @can('monthly_income')
            <a href="{{ route('audits.index') }}" class="sidebar-link {{ request()->routeIs('audits.*') ? 'active' : '' }}"><span class="sidebar-link-icon"><i class="fas fa-history"></i></span>Audit Logs</a>
            @endcan
        </nav>
        <div class="sidebar-footer">
            @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-100" style="background:none;border:none;cursor:pointer;padding:9px 0;"><span class="sidebar-link-icon"><i class="fas fa-sign-out-alt"></i></span>{{ __('trans.logout') ?? 'Logout' }}</button>
            </form>
            @endauth
        </div>
    </aside>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <div class="content-area">
        <header class="topbar">
            <button class="topbar-menu-btn" onclick="openSidebar()" aria-label="Open menu"><i class="fas fa-bars"></i></button>
            <div class="topbar-title">{{ $tenantName }}</div>
            <button class="topbar-back" onclick="window.history.back()"><i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i></button>
        </header>
        <header class="page-topbar">
            <div class="page-topbar-title">@yield('page-title', $tenantName)</div>
            <div class="page-topbar-right">
                <a href="/lang/{{ app()->getLocale() == 'en' ? 'ar' : 'en' }}" class="topbar-lang-btn"><i class="fas fa-globe"></i>{{ app()->getLocale() == 'en' ? 'العربية' : 'English' }}</a>
                @auth
                <a href="{{ route('users.profile') }}" class="topbar-user-chip text-decoration-none text-dark">
                    @if(auth()->user()->hasMedia('profile_pic'))
                        <img src="{{ auth()->user()->getFirstMediaUrl('profile_pic') }}" alt="">
                    @else
                        <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#2563EB,#1D4ED8);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                    @endif
                    <span class="topbar-user-chip-name">{{ auth()->user()->name }}</span>
                </a>
                @endauth
            </div>
        </header>
        @if($isDemoMode)
        <div class="demo-banner"><i class="fas fa-eye"></i><span>You are viewing the <strong>Demo</strong> — write operations are disabled. <a href="{{ route('landing.register') }}">Create your own center</a> to get started.</span></div>
        @endif
        <div class="main-content">
            <div class="flash-wrap">
                @if(session('success'))<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
                @if(session('error'))<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
                @if(session('demo_blocked'))<div class="alert alert-warning alert-dismissible fade show"><i class="fas fa-lock me-2"></i>{{ session('demo_blocked') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
                @if($errors->any())<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i><ul class="mb-0 mt-1 small ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
            </div>
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openSidebar(){document.getElementById('sidebar').classList.add('open');document.getElementById('sidebarOverlay').classList.add('visible');}
function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('sidebarOverlay').classList.remove('visible');}
function toggleGroup(id){var g=document.getElementById(id);if(g)g.classList.toggle('open');}
document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('.sidebar-group').forEach(function(g){if(g.querySelector('.sidebar-sublink.active'))g.classList.add('open');});});
</script>
@stack('scripts')
</body>
</html>
