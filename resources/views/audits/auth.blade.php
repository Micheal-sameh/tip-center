@extends('layouts.sideBar')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height:70vh">
    <div class="card" style="width:100%;max-width:400px;border-radius:16px;box-shadow:0 8px 32px rgba(15,23,42,.15);">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div style="width:60px;height:60px;border-radius:14px;background:linear-gradient(135deg,#1E293B,#2563EB);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fas fa-lock" style="color:#fff;font-size:1.4rem;"></i>
                </div>
                <h4 style="font-weight:700;color:#0F172A;margin-bottom:6px;">Audit Access</h4>
                <p class="text-muted" style="font-size:.9rem;">Enter the admin password to access audit logs.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first('password') }}
                </div>
            @endif

            <form method="POST" action="{{ route('audits.auth.post') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-600" for="audit_password">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="audit_password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter access password" autofocus autocomplete="off">
                        <button type="button" class="btn btn-outline-secondary" id="togglePwd" tabindex="-1">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-unlock me-2"></i>Unlock Audit Logs
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('togglePwd').addEventListener('click', function () {
        const inp = document.getElementById('audit_password');
        const icon = document.getElementById('eyeIcon');
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            inp.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
</script>
@endpush
