@extends('layouts.landing')

@section('title', 'Your Center is Ready — TipCenter')

@section('content')

<style>
    .success-page {
        min-height: calc(100vh - 68px);
        background: linear-gradient(155deg, #F0FDF4 0%, #ffffff 60%, #EFF6FF 100%);
        display: flex; align-items: center; justify-content: center;
        padding: 80px 5%;
    }
    .success-card {
        background: #fff; border-radius: 20px;
        box-shadow: 0 12px 40px rgba(0,0,0,.12);
        padding: 56px 48px; width: 100%; max-width: 520px;
        text-align: center; border: 1px solid #E5E7EB;
    }
    .success-icon {
        width: 72px; height: 72px; border-radius: 50%;
        background: linear-gradient(135deg, #10B981, #059669);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 2rem; margin: 0 auto 24px;
        box-shadow: 0 8px 24px rgba(16,185,129,.3);
    }
    .success-title { font-size: 1.7rem; font-weight: 800; margin-bottom: 12px; letter-spacing: -.3px; }
    .success-sub { color: #6B7280; font-size: .95rem; line-height: 1.65; margin-bottom: 32px; }
    .success-url {
        background: #F3F4F6; border-radius: 10px; padding: 14px 18px;
        font-family: monospace; font-size: .9rem; word-break: break-all;
        margin-bottom: 28px; color: #2563EB; font-weight: 600;
        border: 1px solid #E5E7EB;
    }
    .btn-go {
        display: inline-flex; align-items: center; gap: 10px;
        padding: 14px 32px; background: linear-gradient(135deg, #2563EB, #1D4ED8);
        color: #fff; border-radius: 10px; text-decoration: none;
        font-weight: 700; font-size: 1rem; transition: all .2s;
    }
    .btn-go:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,.35); }
    .steps-list { text-align: left; margin: 32px 0; display: flex; flex-direction: column; gap: 12px; }
    .steps-list-item { display: flex; align-items: flex-start; gap: 12px; font-size: .88rem; color: #374151; }
    .steps-list-num {
        width: 24px; height: 24px; border-radius: 50%; background: #EFF6FF;
        color: #2563EB; font-weight: 700; font-size: .75rem;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;
    }
</style>

<div class="success-page">
    <div class="success-card">
        <div class="success-icon"><i class="fas fa-check"></i></div>
        <h1 class="success-title">Your center is ready! 🎉</h1>
        <p class="success-sub">
            <strong>{{ session('center_name') }}</strong> has been created successfully.
            Your private dashboard is available at:
        </p>

        <div class="success-url">{{ session('tenant_url') }}</div>

        <div class="steps-list">
            <div class="steps-list-item">
                <div class="steps-list-num">1</div>
                <span>Click the link above (or the button below) to open your dashboard</span>
            </div>
            <div class="steps-list-item">
                <div class="steps-list-num">2</div>
                <span>Log in with the email and password you just set</span>
            </div>
            <div class="steps-list-item">
                <div class="steps-list-num">3</div>
                <span>Add your professors, students, and schedule your first session</span>
            </div>
        </div>

        <a href="{{ session('tenant_url') }}" class="btn-go">
            <i class="fas fa-external-link-alt"></i> Open My Dashboard
        </a>

        <p style="margin-top:20px;font-size:.8rem;color:#9CA3AF;">
            Your 14-day free trial started today. No credit card needed.
        </p>
    </div>
</div>

@endsection
