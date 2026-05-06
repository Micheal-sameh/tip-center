@extends('super-admin.layout')
@section('title', 'Add Tenant')
@section('page-title', 'Add New Tenant')

@section('content')
<div class="mb-3">
    <a href="{{ route('super-admin.tenants') }}" class="text-muted text-decoration-none small">
        <i class="fas fa-arrow-left me-1"></i>Back to Tenants
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-header bg-white border-bottom" style="border-radius:14px 14px 0 0;padding:20px 28px;">
                <h6 class="mb-0 fw-bold">New Educational Center</h6>
                <p class="text-muted small mb-0 mt-1">A subdomain and admin account will be created automatically.</p>
            </div>
            <div class="card-body" style="padding:28px;">
                <form action="{{ route('super-admin.tenants.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-600">Center Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Al Noor Education Center">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">Subdomain <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="subdomain" value="{{ old('subdomain') }}"
                                class="form-control @error('subdomain') is-invalid @enderror"
                                placeholder="alnoor" oninput="this.value=this.value.toLowerCase().replace(/[^a-z0-9-]/g,'')">
                            <span class="input-group-text text-muted">.crafando.com</span>
                            @error('subdomain')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-text">Only lowercase letters, numbers, and hyphens. This cannot be changed.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">Admin Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="admin@center.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">This will be the admin user's login email inside the tenant.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">Admin Password <span class="text-danger">*</span></label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Min 8 characters">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">Plan</label>
                        <select name="plan" class="form-select">
                            <option value="free" {{ old('plan') === 'free' ? 'selected' : '' }}>Free</option>
                            <option value="professional" selected {{ old('plan') === 'professional' ? 'selected' : '' }}>Professional</option>
                            <option value="enterprise" {{ old('plan') === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('super-admin.tenants') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Tenant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
