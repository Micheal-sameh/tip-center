@extends('super-admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Tenants</div>
                </div>
                <div class="stat-icon" style="background:#EFF6FF;color:#2563EB;">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">{{ $stats['active'] }}</div>
                    <div class="stat-label">Active Centers</div>
                </div>
                <div class="stat-icon" style="background:#F0FDF4;color:#16A34A;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">{{ $stats['demo'] }}</div>
                    <div class="stat-label">Demo Tenants</div>
                </div>
                <div class="stat-icon" style="background:#FFFBEB;color:#D97706;">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between" style="border-radius:12px 12px 0 0;padding:18px 24px;">
        <h6 class="mb-0 fw-700">Recent Tenants</h6>
        <a href="{{ route('super-admin.tenants') }}" class="btn btn-sm btn-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <table class="table sa-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Center Name</th>
                    <th>Subdomain</th>
                    <th>Email</th>
                    <th>Plan</th>
                    <th>Demo</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent as $tenant)
                <tr>
                    <td class="ps-4 fw-600">{{ $tenant->name }}</td>
                    <td>
                        <a href="http://{{ $tenant->domains->first()?->domain }}" target="_blank" class="text-primary text-decoration-none small">
                            {{ $tenant->domains->first()?->domain }}
                            <i class="fas fa-external-link-alt ms-1" style="font-size:.65rem;"></i>
                        </a>
                    </td>
                    <td class="text-muted small">{{ $tenant->email }}</td>
                    <td>
                        <span class="badge-plan {{ $tenant->plan === 'enterprise' ? 'bg-purple text-white' : ($tenant->plan === 'professional' ? 'bg-primary text-white' : 'bg-secondary text-white') }}
                            badge">
                            {{ ucfirst($tenant->plan ?? 'free') }}
                        </span>
                    </td>
                    <td>
                        @if($tenant->is_demo)
                            <span class="badge bg-warning text-dark">Demo</span>
                        @else
                            <span class="badge bg-success">Live</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $tenant->created_at?->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('super-admin.tenants.show', $tenant->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No tenants yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
