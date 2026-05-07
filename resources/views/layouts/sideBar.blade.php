<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

@php
    $logo       = App\Models\Setting::where('name', 'logo')->first();
    $faviconUrl = $logo?->getFirstMediaUrl('app_logo');
    $tenantName = tenant()?->name ?? config('app.name');
    $locale     = app()->getLocale();
    $isRtl      = $locale === 'ar';
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
    @if($isRtl)
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    @if($faviconUrl)
    <link rel="icon" href="{{ $faviconUrl }}" type="image/png">
    @endif

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        /* ── Shell Layout (critical, inline to prevent FOUC) ────────── */
        :root {
            --brand:         #2563EB;
            --brand-dark:    #1D4ED8;
            --sidebar-bg:    #0F172A;
            --sidebar-hover: rgba(255,255,255,.07);
            --sidebar-active:rgba(37,99,235,.22);
            --sidebar-text:  rgba(255,255,255,.72);
            --sidebar-w:     252px;
            --topbar-h:      60px;
            --content-bg:    #F8FAFC;
            --border:        #E2E8F0;
            --white:         #FFFFFF;
            --font:          'Inter',-apple-system,sans-serif;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin:0; padding:0; height:100%; font-family:var(--font); color:#1E293B; background:var(--content-bg); -webkit-font-smoothing:antialiased; overflow-x:hidden; }

        .app-shell   { display:flex; min-height:100vh; }

        /* Sidebar */
        .sidebar {
            position:fixed; top:0; left:0; width:var(--sidebar-w); height:100vh;
            background:var(--sidebar-bg); display:flex; flex-direction:column;
            z-index:1040; transition:transform .28s cubic-bezier(.4,0,.2,1);
            overflow:hidden;
        }
        [dir="rtl"] .sidebar { left:auto; right:0; }

        .sidebar-brand {
            display:flex; align-items:center; gap:11px; padding:0 18px;
            height:var(--topbar-h); border-bottom:1px solid rgba(255,255,255,.07);
            text-decoration:none; flex-shrink:0;
        }
        .sidebar-brand-icon {
            width:34px; height:34px; border-radius:9px; flex-shrink:0;
            background:linear-gradient(135deg,var(--brand),var(--brand-dark));
            display:flex; align-items:center; justify-content:center; color:#fff; font-size:.95rem;
        }
        .sidebar-brand-img  { width:34px; height:34px; border-radius:9px; object-fit:cover; flex-shrink:0; }
        .sidebar-brand-name { font-weight:800; font-size:.93rem; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

        .sidebar-user {
            display:flex; align-items:center; gap:11px; padding:14px 18px;
            border-bottom:1px solid rgba(255,255,255,.07); flex-shrink:0;
        }
        .sidebar-avatar { width:38px; height:38px; border-radius:50%; object-fit:cover; border:2px solid rgba(255,255,255,.15); flex-shrink:0; }
        .sidebar-avatar-ph {
            width:38px; height:38px; border-radius:50%; flex-shrink:0;
            background:linear-gradient(135deg,var(--brand),var(--brand-dark));
            display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:.8rem;
        }
        .sidebar-u-name { font-size:.84rem; font-weight:600; color:#fff; line-height:1.3; }
        .sidebar-u-role { font-size:.72rem; color:rgba(255,255,255,.42); margin-top:1px; }

        /* Nav */
        .sidebar-nav { flex:1; overflow-y:auto; padding:8px 0; scrollbar-width:thin; scrollbar-color:rgba(255,255,255,.1) transparent; }
        .sidebar-nav::-webkit-scrollbar { width:3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background:rgba(255,255,255,.1); border-radius:3px; }

        .sidebar-section {
            font-size:.62rem; font-weight:700; color:rgba(255,255,255,.25);
            text-transform:uppercase; letter-spacing:1.2px; padding:14px 18px 5px;
        }
        .sidebar-link {
            display:flex; align-items:center; gap:11px; padding:9px 18px;
            color:var(--sidebar-text); text-decoration:none; font-size:.84rem; font-weight:500;
            transition:background .15s,color .15s; position:relative;
            background:none; border:none; width:100%; cursor:pointer; text-align:left;
        }
        [dir="rtl"] .sidebar-link { text-align:right; }
        .sidebar-link:hover  { background:var(--sidebar-hover); color:#fff; }
        .sidebar-link.active { background:var(--sidebar-active); color:#fff; }
        .sidebar-link.active::before {
            content:''; position:absolute; top:4px; bottom:4px; left:0; width:3px;
            background:var(--brand); border-radius:0 3px 3px 0;
        }
        [dir="rtl"] .sidebar-link.active::before { left:auto; right:0; border-radius:3px 0 0 3px; }
        .sidebar-icon { width:18px; text-align:center; font-size:.85rem; flex-shrink:0; }

        /* Collapsible groups */
        .sidebar-group-btn {
            display:flex; align-items:center; gap:11px; padding:9px 18px;
            color:var(--sidebar-text); font-size:.84rem; font-weight:500;
            width:100%; background:none; border:none; cursor:pointer;
            transition:background .15s,color .15s; text-align:left;
        }
        [dir="rtl"] .sidebar-group-btn { text-align:right; }
        .sidebar-group-btn:hover       { background:var(--sidebar-hover); color:#fff; }
        .sidebar-group-btn.open        { color:#fff; }
        .sidebar-arrow { margin-left:auto; font-size:.7rem; color:rgba(255,255,255,.28); transition:transform .2s; }
        [dir="rtl"] .sidebar-arrow { margin-left:0; margin-right:auto; }
        .sidebar-group-btn.open .sidebar-arrow { transform:rotate(90deg); }
        .sidebar-group-items { overflow:hidden; max-height:0; transition:max-height .25s ease; }
        .sidebar-group-items.open { max-height:600px; }
        .sidebar-sublink {
            display:flex; align-items:center; gap:9px;
            padding:8px 18px 8px 44px; color:rgba(255,255,255,.5);
            text-decoration:none; font-size:.8rem; font-weight:500;
            transition:background .15s,color .15s;
        }
        [dir="rtl"] .sidebar-sublink { padding:8px 44px 8px 18px; }
        .sidebar-sublink:hover  { background:var(--sidebar-hover); color:#fff; }
        .sidebar-sublink.active { color:#fff; background:rgba(37,99,235,.15); }
        .sidebar-sublink .dot   { width:5px; height:5px; border-radius:50%; background:currentColor; flex-shrink:0; }

        .sidebar-footer { padding:12px 18px; border-top:1px solid rgba(255,255,255,.07); flex-shrink:0; }
        .sidebar-logout {
            display:flex; align-items:center; gap:11px; padding:9px 0;
            color:rgba(255,255,255,.55); font-size:.84rem; font-weight:500;
            width:100%; background:none; border:none; cursor:pointer;
            transition:color .15s; text-align:left;
        }
        [dir="rtl"] .sidebar-logout { text-align:right; }
        .sidebar-logout:hover { color:#FF6B6B; }

        /* Overlay */
        .sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1039; backdrop-filter:blur(2px); }
        .sidebar-overlay.show { display:block; }

        /* Mobile topbar */
        .mobile-topbar {
            display:none; position:fixed; top:0; left:0; right:0; height:var(--topbar-h);
            background:var(--sidebar-bg); align-items:center; padding:0 16px; gap:12px;
            z-index:1030; box-shadow:0 2px 8px rgba(0,0,0,.25);
        }
        .mobile-topbar-btn  { background:none; border:none; color:rgba(255,255,255,.8); font-size:1.1rem; cursor:pointer; padding:6px; display:flex; align-items:center; justify-content:center; }
        .mobile-topbar-title{ flex:1; color:#fff; font-weight:700; font-size:.9rem; }

        /* Content area */
        .content-area {
            flex:1; margin-left:var(--sidebar-w); min-height:100vh;
            display:flex; flex-direction:column;
            min-width:0; overflow-x:hidden;
        }
        [dir="rtl"] .content-area { margin-left:0; margin-right:var(--sidebar-w); }

        /* Desktop page topbar */
        .page-topbar {
            background:var(--white); border-bottom:1px solid var(--border);
            padding:0 24px; height:var(--topbar-h);
            display:flex; align-items:center; justify-content:space-between;
            position:sticky; top:0; z-index:100;
            box-shadow:0 1px 3px rgba(0,0,0,.06);
        }
        .page-topbar-title { font-size:.95rem; font-weight:700; color:#1E293B; }
        .page-topbar-right { display:flex; align-items:center; gap:10px; }
        .topbar-lang {
            display:flex; align-items:center; gap:6px; padding:6px 13px;
            background:var(--content-bg); border:1px solid var(--border); border-radius:8px;
            font-size:.78rem; font-weight:600; color:#64748B; cursor:pointer; text-decoration:none;
            transition:all .2s;
        }
        .topbar-lang:hover { border-color:var(--brand); color:var(--brand); }
        .topbar-user {
            display:flex; align-items:center; gap:8px; padding:4px 12px 4px 4px;
            background:var(--content-bg); border:1px solid var(--border); border-radius:40px;
            cursor:pointer; text-decoration:none; color:inherit; transition:all .2s;
        }
        .topbar-user:hover { border-color:var(--brand); }
        .topbar-user-img { width:30px; height:30px; border-radius:50%; object-fit:cover; }
        .topbar-user-ph  {
            width:30px; height:30px; border-radius:50%;
            background:linear-gradient(135deg,var(--brand),var(--brand-dark));
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-weight:700; font-size:.72rem;
        }
        .topbar-user-name { font-size:.8rem; font-weight:600; color:#1E293B; }

        /* Main content */
        .main-content { flex:1; padding:24px; overflow-x:hidden; min-width:0; }
        .flash-zone   { margin-bottom:20px; }
        .flash-zone .alert { border-radius:10px; font-size:.875rem; }

        /* Responsive */
        @media (max-width:1024px) {
            .sidebar      { transform:translateX(-100%); }
            [dir="rtl"] .sidebar { transform:translateX(100%); }
            .sidebar.show { transform:translateX(0); }
            .mobile-topbar{ display:flex; }
            .page-topbar  { display:none; }
            .content-area { margin-left:0; padding-top:var(--topbar-h); }
            [dir="rtl"] .content-area { margin-right:0; }
        }
    </style>
    @stack('head')
</head>
<body>
<div class="app-shell">

    {{-- ── SIDEBAR ─────────────────────────────────────── --}}
    <aside class="sidebar" id="sidebar">

        {{-- Brand --}}
        <a href="{{ url('/') }}" class="sidebar-brand">
            @if($faviconUrl)
                <img src="{{ $faviconUrl }}" alt="logo" class="sidebar-brand-img">
            @else
                <div class="sidebar-brand-icon"><i class="fas fa-graduation-cap"></i></div>
            @endif
            <span class="sidebar-brand-name">{{ $tenantName }}</span>
        </a>

        {{-- User Profile --}}
        @auth
        <div class="sidebar-user">
            @if(auth()->user()->hasMedia('profile_pic'))
                <img src="{{ auth()->user()->getFirstMediaUrl('profile_pic') }}" alt="" class="sidebar-avatar">
            @else
                <div class="sidebar-avatar-ph">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
            @endif
            <div style="min-width:0">
                <div class="sidebar-u-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-u-role">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</div>
            </div>
        </div>
        @endauth

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            <div class="sidebar-section">{{ __('trans.main') ?? 'Main' }}</div>

            <a href="{{ route('attendances.index') }}" class="sidebar-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="fas fa-calendar-check"></i></span>{{ __('trans.attendence') ?? 'Attendance' }}
            </a>
            <a href="{{ route('professors.schedule') }}" class="sidebar-link {{ request()->routeIs('professors.schedule') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="fas fa-calendar-alt"></i></span>{{ __('trans.schedule') ?? 'Schedule' }}
            </a>
            @can('sessions_view')
            <a href="{{ route('sessions.index') }}" class="sidebar-link {{ request()->routeIs('sessions.*') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="fas fa-chalkboard"></i></span>{{ __('trans.sessions') ?? 'Sessions' }}
            </a>
            @endcan

            <div class="sidebar-section">{{ __('trans.people') ?? 'People' }}</div>

            @can('students_view')
            <a href="{{ route('students.index') }}" class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="fas fa-user-graduate"></i></span>{{ __('trans.students') ?? 'Students' }}
            </a>
            @endcan
            @can('professors_view')
            <a href="{{ route('professors.index') }}" class="sidebar-link {{ request()->routeIs('professors.index') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="fas fa-chalkboard-teacher"></i></span>{{ __('trans.professors') ?? 'Professors' }}
            </a>
            @endcan

            {{-- Blacklists group --}}
            <button class="sidebar-group-btn {{ request()->routeIs('professor_blacklists.*','student_blacklists.*') ? 'open' : '' }}" onclick="toggleGroup(this)">
                <span class="sidebar-icon"><i class="fas fa-ban"></i></span>
                {{ __('trans.blacklists') ?? 'Blacklists' }}
                <i class="fas fa-chevron-right sidebar-arrow"></i>
            </button>
            <div class="sidebar-group-items {{ request()->routeIs('professor_blacklists.*','student_blacklists.*') ? 'open' : '' }}">
                <a href="{{ route('professor_blacklists.index') }}" class="sidebar-sublink {{ request()->routeIs('professor_blacklists.*') ? 'active' : '' }}">
                    <span class="dot"></span>Professor Blacklist
                </a>
                <a href="{{ route('student_blacklists.index') }}" class="sidebar-sublink {{ request()->routeIs('student_blacklists.*') ? 'active' : '' }}">
                    <span class="dot"></span>Student Blacklist
                </a>
            </div>

            @canany(['charges_index','income_report','monthly_income','special_room_report','monthly_special_rooms'])
            <div class="sidebar-section">{{ __('trans.finance') ?? 'Finance' }}</div>

            <button class="sidebar-group-btn {{ request()->routeIs('charges.*','reports.charges','reports.student-settlements','reports.income','reports.monthly-income','reports.special-rooms','reports.monthly-ten-eleven') ? 'open' : '' }}" onclick="toggleGroup(this)">
                <span class="sidebar-icon"><i class="fas fa-wallet"></i></span>
                {{ __('trans.finance') ?? 'Finance' }}
                <i class="fas fa-chevron-right sidebar-arrow"></i>
            </button>
            <div class="sidebar-group-items {{ request()->routeIs('charges.*','reports.charges','reports.student-settlements','reports.income','reports.monthly-income','reports.special-rooms','reports.monthly-ten-eleven') ? 'open' : '' }}">
                @can('charges_index')
                <a href="{{ route('charges.index') }}" class="sidebar-sublink {{ request()->routeIs('charges.index') ? 'active' : '' }}">
                    <span class="dot"></span>{{ __('trans.charges') ?? 'Charges' }}
                </a>
                <a href="{{ route('charges.gap') }}" class="sidebar-sublink {{ request()->routeIs('charges.gap') ? 'active' : '' }}">
                    <span class="dot"></span>{{ __('trans.gap') ?? 'Gap' }}
                </a>
                <a href="{{ route('charges.student-print') }}" class="sidebar-sublink {{ request()->routeIs('charges.student-print') ? 'active' : '' }}">
                    <span class="dot"></span>Student Print
                </a>
                @endcan
                @can('monthly_income')
                <a href="{{ route('reports.charges') }}" class="sidebar-sublink {{ request()->routeIs('reports.charges') ? 'active' : '' }}">
                    <span class="dot"></span>Charges Report
                </a>
                <a href="{{ route('reports.student-settlements') }}" class="sidebar-sublink {{ request()->routeIs('reports.student-settlements') ? 'active' : '' }}">
                    <span class="dot"></span>Student Settlements
                </a>
                <a href="{{ route('reports.monthly-income') }}" class="sidebar-sublink {{ request()->routeIs('reports.monthly-income') ? 'active' : '' }}">
                    <span class="dot"></span>{{ __('trans.monthly_income') ?? 'Monthly Income' }}
                </a>
                @endcan
                @can('income_report')
                <a href="{{ route('reports.income') }}" class="sidebar-sublink {{ request()->routeIs('reports.income') ? 'active' : '' }}">
                    <span class="dot"></span>{{ __('trans.income') ?? 'Income' }}
                </a>
                @endcan
                @can('special_room_report')
                <a href="{{ route('reports.special-rooms') }}" class="sidebar-sublink {{ request()->routeIs('reports.special-rooms') ? 'active' : '' }}">
                    <span class="dot"></span>{{ __('trans.room 10 & 11') ?? 'Rooms 10 & 11' }}
                </a>
                @endcan
                @can('monthly_special_rooms')
                <a href="{{ route('reports.monthly-ten-eleven') }}" class="sidebar-sublink {{ request()->routeIs('reports.monthly-ten-eleven') ? 'active' : '' }}">
                    <span class="dot"></span>{{ __('trans.monthly_special_rooms') ?? 'Monthly Rooms' }}
                </a>
                @endcan
            </div>
            @endcanany

            @canany(['sessions_report','students_report'])
            <div class="sidebar-section">{{ __('trans.reports') ?? 'Reports' }}</div>

            <button class="sidebar-group-btn {{ request()->routeIs('reports.index','reports.student') ? 'open' : '' }}" onclick="toggleGroup(this)">
                <span class="sidebar-icon"><i class="fas fa-chart-bar"></i></span>
                {{ __('trans.reports') ?? 'Reports' }}
                <i class="fas fa-chevron-right sidebar-arrow"></i>
            </button>
            <div class="sidebar-group-items {{ request()->routeIs('reports.index','reports.student') ? 'open' : '' }}">
                @can('sessions_report')
                <a href="{{ route('reports.index') }}" class="sidebar-sublink {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                    <span class="dot"></span>{{ __('trans.session_reports') ?? 'Session Reports' }}
                </a>
                @endcan
                @can('students_report')
                <a href="{{ route('reports.student') }}" class="sidebar-sublink {{ request()->routeIs('reports.student') ? 'active' : '' }}">
                    <span class="dot"></span>{{ __('trans.student_reports') ?? 'Student Reports' }}
                </a>
                @endcan
            </div>
            @endcanany

            <div class="sidebar-section">{{ __('trans.system') ?? 'System' }}</div>

            @can('users_view')
            <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="fas fa-users-cog"></i></span>{{ __('trans.users') ?? 'Users' }}
            </a>
            @endcan
            @can('settings_update')
            <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="fas fa-cog"></i></span>{{ __('trans.settings') ?? 'Settings' }}
            </a>
            @endcan
            @can('monthly_income')
            <a href="{{ route('audits.index') }}" class="sidebar-link {{ request()->routeIs('audits.*') ? 'active' : '' }}">
                <span class="sidebar-icon"><i class="fas fa-history"></i></span>Audit Logs
            </a>
            @endcan

        </nav>

        {{-- Footer / Logout --}}
        <div class="sidebar-footer">
            @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <i class="fas fa-sign-out-alt" style="width:18px;text-align:center"></i>{{ __('trans.logout') ?? 'Logout' }}
                </button>
            </form>
            @else
            <a href="{{ route('loginPage') }}" class="sidebar-link">
                <span class="sidebar-icon"><i class="fas fa-sign-in-alt"></i></span>{{ __('trans.login') ?? 'Login' }}
            </a>
            @endauth
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- ── CONTENT AREA ────────────────────────────────── --}}
    <div class="content-area">

        {{-- Mobile Topbar --}}
        <header class="mobile-topbar">
            <button class="mobile-topbar-btn" onclick="openSidebar()" aria-label="Open menu">
                <i class="fas fa-bars"></i>
            </button>
            <div class="mobile-topbar-title">{{ $tenantName }}</div>
            <button class="mobile-topbar-btn" onclick="window.history.back()" aria-label="Back">
                <i class="fas fa-arrow-{{ $isRtl ? 'right' : 'left' }}"></i>
            </button>
        </header>

        {{-- Desktop Topbar --}}
        <header class="page-topbar">
            <div class="page-topbar-title">@yield('page-title', $tenantName)</div>
            <div class="page-topbar-right">
                <a href="/lang/{{ $locale === 'en' ? 'ar' : 'en' }}" class="topbar-lang">
                    <i class="fas fa-globe"></i>{{ $locale === 'en' ? 'العربية' : 'English' }}
                </a>
                @auth
                <a href="{{ route('users.profile') }}" class="topbar-user">
                    @if(auth()->user()->hasMedia('profile_pic'))
                        <img src="{{ auth()->user()->getFirstMediaUrl('profile_pic') }}" alt="" class="topbar-user-img">
                    @else
                        <div class="topbar-user-ph">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                    @endif
                    <span class="topbar-user-name">{{ auth()->user()->name }}</span>
                </a>
                @endauth
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="main-content">
            @if(session('success') || session('error') || session('demo_blocked') || $errors->any())
            <div class="flash-zone">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('demo_blocked'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="fas fa-lock me-2"></i>{{ session('demo_blocked') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0 mt-1 ps-3 small">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
            </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
function openSidebar()  { document.getElementById('sidebar').classList.add('show'); document.getElementById('sidebarOverlay').classList.add('show'); }
function closeSidebar() { document.getElementById('sidebar').classList.remove('show'); document.getElementById('sidebarOverlay').classList.remove('show'); }
function toggleGroup(btn) {
    btn.classList.toggle('open');
    var items = btn.nextElementSibling;
    if (items) items.classList.toggle('open');
}
document.addEventListener('DOMContentLoaded', function () {
    // Auto-dismiss flash messages after 5 s
    setTimeout(function () {
        document.querySelectorAll('.flash-zone .alert').forEach(function (el) {
            var a = bootstrap.Alert.getOrCreateInstance(el);
            if (a) a.close();
        });
    }, 5000);
});
</script>
@stack('scripts')
</body>
</html>
