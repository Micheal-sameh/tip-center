@extends('layouts.landing')

@section('title', 'Create Your Center — TipCenter')

@section('content')

<style>
    .register-page {
        min-height: calc(100vh - 68px);
        background: linear-gradient(155deg, #EFF6FF 0%, #ffffff 60%, #F0FDF4 100%);
        display: flex; align-items: center; justify-content: center;
        padding: 80px 5%;
    }
    .register-card {
        background: #fff; border-radius: 20px;
        box-shadow: 0 12px 40px rgba(0,0,0,.12);
        padding: 48px; width: 100%; max-width: 520px;
        border: 1px solid #E5E7EB;
    }
    .register-header { text-align: center; margin-bottom: 36px; }
    .register-icon {
        width: 60px; height: 60px; border-radius: 16px;
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.5rem; margin: 0 auto 16px;
    }
    .register-title { font-size: 1.5rem; font-weight: 800; letter-spacing: -.3px; margin-bottom: 8px; }
    .register-sub { font-size: .9rem; color: #6B7280; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: .85rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .form-control {
        width: 100%; padding: 11px 14px; border: 1.5px solid #E5E7EB;
        border-radius: 8px; font-size: .9rem; outline: none;
        transition: border-color .2s, box-shadow .2s; font-family: inherit;
    }
    .form-control:focus { border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
    .form-hint { font-size: .78rem; color: #9CA3AF; margin-top: 5px; }
    .subdomain-wrap { display: flex; align-items: stretch; border: 1.5px solid #E5E7EB; border-radius: 8px; overflow: hidden; transition: border-color .2s, box-shadow .2s; }
    .subdomain-wrap:focus-within { border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
    .subdomain-wrap input { border: none; padding: 11px 14px; flex: 1; outline: none; font-size: .9rem; font-family: inherit; }
    .subdomain-suffix { background: #F3F4F6; padding: 11px 14px; font-size: .85rem; color: #6B7280; font-weight: 500; white-space: nowrap; border-left: 1px solid #E5E7EB; display: flex; align-items: center; }
    .is-invalid { border-color: #EF4444 !important; }
    .invalid-feedback { font-size: .78rem; color: #EF4444; margin-top: 5px; }
    .btn-submit {
        width: 100%; padding: 13px; background: linear-gradient(135deg, #2563EB, #1D4ED8);
        color: #fff; border: none; border-radius: 8px; font-size: 1rem;
        font-weight: 700; cursor: pointer; transition: all .2s; font-family: inherit;
        display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,.35); }
    .trial-badge {
        display: flex; align-items: center; gap: 8px;
        background: #F0FDF4; border: 1px solid #BBF7D0;
        border-radius: 8px; padding: 10px 14px;
        font-size: .82rem; color: #059669; font-weight: 500; margin-bottom: 24px;
    }
    .login-link { text-align: center; margin-top: 20px; font-size: .85rem; color: #6B7280; }
    .login-link a { color: #2563EB; font-weight: 600; text-decoration: none; }
</style>

<div class="register-page">
    <div class="register-card">
        <div class="register-header">
            <div class="register-icon"><i class="fas fa-graduation-cap"></i></div>
            <h1 class="register-title">Create your center</h1>
            <p class="register-sub">Start your 14-day free trial — no credit card required</p>
        </div>

        <div class="trial-badge">
            <i class="fas fa-gift"></i>
            <span>Free 14-day trial — full access, all features included</span>
        </div>

        @if($errors->any())
            <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:.85rem;color:#DC2626;">
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('landing.register.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="center_name">Center / Academy Name</label>
                <input type="text" id="center_name" name="center_name"
                    class="form-control @error('center_name') is-invalid @enderror"
                    value="{{ old('center_name') }}"
                    placeholder="e.g. Al-Fajr Learning Center" required>
                @error('center_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="subdomain">Choose your subdomain</label>
                <div class="subdomain-wrap @error('subdomain') is-invalid @enderror">
                    <input type="text" id="subdomain" name="subdomain"
                        value="{{ old('subdomain') }}"
                        placeholder="mycenter" pattern="[a-z0-9\-]+" required>
                    <span class="subdomain-suffix">.{{ config('app.domain', 'tipcenter.test') }}</span>
                </div>
                <div class="form-hint">Lowercase letters, numbers and hyphens only.</div>
                @error('subdomain')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Admin Email Address</label>
                <input type="email" id="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="admin@mycenter.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="At least 8 characters" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="form-control" placeholder="Repeat password" required>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-rocket"></i> Create My Center
            </button>
        </form>

        <div class="login-link">
            Already have an account?
            <a href="http://{{ request()->getHost() }}">Go to your dashboard</a>
        </div>
    </div>
</div>

@endsection
