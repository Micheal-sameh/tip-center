@extends('super-admin.layout')
@section('title', 'Tenants')
@section('page-title', 'All Tenants')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
            class="form-control" style="width:280px;">
        <button class="btn btn-primary">Search</button>
        @if(request('search'))
            <a href="{{ route('super-admin.tenants') }}" class="btn btn-outline-secondary">Clear</a>
        @endif
    </form>
    <a href="{{ route('super-admin.tenants.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Tenant
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-0">
        <table class="table sa-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Center Name</th>
                    <th>Subdomain / URL</th>
                    <th>Email</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $tenant)
                <tr>
                    <td class="ps-4">
                        <div class="fw-600">{{ $tenant->name }}</div>
                        <div class="text-muted" style="font-size:.72rem;">ID: {{ $tenant->id }}</div>
                    </td>
                    <td>
                        <a href="http://{{ $tenant->domains->first()?->domain }}" target="_blank"
                            class="text-primary text-decoration-none small">
                            {{ $tenant->domains->first()?->domain }}
                            <i class="fas fa-external-link-alt ms-1" style="font-size:.62rem;"></i>
                        </a>
                    </td>
                    <td class="text-muted small">{{ $tenant->email }}</td>
                    <td>
                        <span class="badge {{ $tenant->plan === 'enterprise' ? 'bg-dark' : ($tenant->plan === 'professional' ? 'bg-primary' : 'bg-secondary') }}">
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
                    <td class="pe-4">
                        <div class="d-flex gap-1">
                            <a href="{{ route('super-admin.tenants.show', $tenant->id) }}"
                                class="btn btn-sm btn-outline-primary" title="View users">
                                <i class="fas fa-users"></i>
                            </a>
                            <form action="{{ route('super-admin.tenants.toggle-demo', $tenant->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-warning" title="{{ $tenant->is_demo ? 'Disable Demo' : 'Enable Demo' }}">
                                    <i class="fas fa-eye{{ $tenant->is_demo ? '-slash' : '' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('super-admin.tenants.delete', $tenant->id) }}" method="POST"
                                onsubmit="return confirm('Delete tenant {{ $tenant->name }}? This cannot be undone.');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-5">No tenants found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tenants->hasPages())
    <div class="card-footer bg-white border-top" style="border-radius:0 0 12px 12px;padding:14px 24px;">
        {{ $tenants->links() }}
    </div>
    @endif
</div>
@endsection
