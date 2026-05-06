<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin') — Crafando</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand: #2563EB; --brand-dark: #1D4ED8;
            --sidebar-bg: #0F172A; --sidebar-hover: #1E293B; --sidebar-active: #1E3A5F;
            --sidebar-border: rgba(255,255,255,.07); --sidebar-text: rgba(255,255,255,.75);
            --sidebar-width: 240px; --topbar-height: 60px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: #F1F5F9; color: #1E293B; }
        .sa-shell { display: flex; min-height: 100vh; }
        .sa-sidebar { position: fixed; top: 0; left: 0; width: var(--sidebar-width); height: 100vh;
            background: var(--sidebar-bg); display: flex; flex-direction: column; z-index: 100; }
        .sa-brand { display: flex; align-items: center; gap: 10px; padding: 0 20px;
            height: var(--topbar-height); border-bottom: 1px solid var(--sidebar-border);
            text-decoration: none; }
        .sa-brand-icon { width: 34px; height: 34px; border-radius: 8px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            display: flex; align-items: center; justify-content: center; color: #fff; font-size: 15px; }
        .sa-brand-text { font-weight: 800; font-size: .95rem; color: #fff; }
        .sa-brand-badge { font-size: .6rem; background: #EF4444; color: #fff; padding: 2px 6px;
            border-radius: 50px; font-weight: 700; margin-left: 4px; }
        .sa-nav { flex: 1; overflow-y: auto; padding: 10px 0; }
        .sa-nav-label { font-size: .62rem; font-weight: 700; color: rgba(255,255,255,.28);
            text-transform: uppercase; letter-spacing: 1px; padding: 12px 20px 4px; }
        .sa-link { display: flex; align-items: center; gap: 10px; padding: 9px 20px;
            color: var(--sidebar-text); text-decoration: none; font-size: .85rem; font-weight: 500;
            transition: background .15s, color .15s; }
        .sa-link:hover { background: var(--sidebar-hover); color: #fff; }
        .sa-link.active { background: var(--sidebar-active); color: #fff;
            border-left: 3px solid var(--brand); }
        .sa-link i { width: 18px; text-align: center; font-size: .88rem; }
        .sa-footer { padding: 14px 20px; border-top: 1px solid var(--sidebar-border); }
        .sa-admin-chip { display: flex; align-items: center; gap: 10px; }
        .sa-admin-avatar { width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            display: flex; align-items: center; justify-content: center; color: #fff;
            font-weight: 700; font-size: .8rem; flex-shrink: 0; }
        .sa-admin-name { font-size: .82rem; font-weight: 600; color: #fff; }
        .sa-admin-role { font-size: .7rem; color: rgba(255,255,255,.4); }
        .sa-content { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; }
        .sa-topbar { background: #fff; border-bottom: 1px solid #E2E8F0; height: var(--topbar-height);
            display: flex; align-items: center; padding: 0 28px; justify-content: space-between;
            position: sticky; top: 0; z-index: 50; }
        .sa-topbar-title { font-weight: 700; font-size: 1rem; }
        .sa-main { padding: 28px; }
        .stat-card { background: #fff; border-radius: 12px; padding: 22px 24px; border: 1px solid #E2E8F0; }
        .stat-icon { width: 46px; height: 46px; border-radius: 10px; display: flex;
            align-items: center; justify-content: center; font-size: 1.1rem; }
        .stat-value { font-size: 1.9rem; font-weight: 800; line-height: 1; }
        .stat-label { font-size: .8rem; color: #64748B; margin-top: 4px; }
        .sa-table thead th { font-size: .75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .5px; color: #64748B; border-bottom: 2px solid #E2E8F0; }
        .badge-plan { padding: 4px 10px; border-radius: 50px; font-size: .72rem; font-weight: 600; }
    </style>
</head>
<body>
<div class="sa-shell">
    <aside class="sa-sidebar">
        <a href="{{ route('super-admin.dashboard') }}" class="sa-brand">
            <div class="sa-brand-icon"><i class="fas fa-crown"></i></div>
            <span class="sa-brand-text">Super Admin</span>
            <span class="sa-brand-badge">ADMIN</span>
        </a>
        <nav class="sa-nav">
            <div class="sa-nav-label">Overview</div>
            <a href="{{ route('super-admin.dashboard') }}" class="sa-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <div class="sa-nav-label">Management</div>
            <a href="{{ route('super-admin.tenants') }}" class="sa-link {{ request()->routeIs('super-admin.tenants*') ? 'active' : '' }}">
                <i class="fas fa-building"></i> Tenants
            </a>
            <a href="{{ route('super-admin.tenants.create') }}" class="sa-link {{ request()->routeIs('super-admin.tenants.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i> Add Tenant
            </a>
        </nav>
        <div class="sa-footer">
            <div class="sa-admin-chip">
                <div class="sa-admin-avatar">{{ strtoupper(substr(session('super_admin_name','A'),0,2)) }}</div>
                <div>
                    <div class="sa-admin-name">{{ session('super_admin_name') }}</div>
                    <div class="sa-admin-role">Super Administrator</div>
                </div>
            </div>
            <form action="{{ route('super-admin.logout') }}" method="POST" class="mt-3">
                @csrf
                <button class="sa-link w-100 border-0 p-0" style="background:none;cursor:pointer;color:rgba(255,255,255,.5);font-size:.8rem;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="sa-content">
        <header class="sa-topbar">
            <div class="sa-topbar-title">@yield('page-title', 'Dashboard')</div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-external-link-alt me-1"></i>View Site
                </a>
            </div>
        </header>
        <main class="sa-main">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
