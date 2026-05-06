@extends('super-admin.layout')
@section('title', $tenant->name)
@section('page-title', $tenant->name)

@section('content')
<div class="mb-3">
    <a href="{{ route('super-admin.tenants') }}" class="text-muted text-decoration-none small">
        <i class="fas fa-arrow-left me-1"></i>Back to Tenants
    </a>
</div>

<div class="row g-4 mb-4">
    {{-- Tenant Info --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-header bg-white border-bottom" style="border-radius:12px 12px 0 0;padding:18px 24px;">
                <h6 class="mb-0 fw-bold">Tenant Details</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0 small">
                    <tr><th class="text-muted ps-0" style="width:35%">Name</th><td class="fw-600">{{ $tenant->name }}</td></tr>
                    <tr><th class="text-muted ps-0">ID / Slug</th><td><code>{{ $tenant->id }}</code></td></tr>
                    <tr><th class="text-muted ps-0">Email</th><td>{{ $tenant->email }}</td></tr>
                    <tr><th class="text-muted ps-0">Plan</th>
                        <td><span class="badge bg-primary">{{ ucfirst($tenant->plan ?? 'free') }}</span></td>
                    </tr>
                    <tr><th class="text-muted ps-0">Status</th>
                        <td>
                            @if($tenant->is_demo)
                                <span class="badge bg-warning text-dark">Demo Mode</span>
                            @else
                                <span class="badge bg-success">Live</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th class="text-muted ps-0">Domain</th>
                        <td>
                            <a href="http://{{ $tenant->domains->first()?->domain }}" target="_blank" class="text-primary">
                                {{ $tenant->domains->first()?->domain }}
                                <i class="fas fa-external-link-alt ms-1" style="font-size:.65rem;"></i>
                            </a>
                        </td>
                    </tr>
                    <tr><th class="text-muted ps-0">Created</th><td>{{ $tenant->created_at?->format('M d, Y H:i') }}</td></tr>
                </table>
            </div>
            <div class="card-footer bg-white border-top d-flex gap-2" style="border-radius:0 0 12px 12px;padding:14px 24px;">
                <form action="{{ route('super-admin.tenants.toggle-demo', $tenant->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-eye me-1"></i>{{ $tenant->is_demo ? 'Disable Demo' : 'Enable Demo' }}
                    </button>
                </form>
                <form action="{{ route('super-admin.tenants.delete', $tenant->id) }}" method="POST"
                    onsubmit="return confirm('Permanently delete this tenant and their database?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash me-1"></i>Delete Tenant
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Users --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-header bg-white border-bottom" style="border-radius:12px 12px 0 0;padding:18px 24px;">
                <h6 class="mb-0 fw-bold">Users <span class="badge bg-primary ms-1">{{ $users->count() }}</span></h6>
            </div>
            <div class="card-body p-0">
                <table class="table sa-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="pe-4">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4 fw-600">{{ $user->name }}</td>
                            <td class="text-muted small">{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles ?? [] as $role)
                                    <span class="badge bg-info text-dark">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge {{ $user->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="pe-4 text-muted small">{{ $user->created_at?->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No users found in this tenant.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
