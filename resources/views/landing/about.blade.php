@extends('layouts.landing')

@section('title', 'About TipCenter — Our Mission & Story')
@section('meta_description', 'Learn about TipCenter, the SaaS platform built specifically for educational centers. Discover our mission, values, and the team behind the platform.')

@section('content')

<style>
    /* ── PAGE SPECIFIC ── */
    .about-hero {
        background: linear-gradient(155deg, #EFF6FF 0%, #ffffff 60%, #F0FDF4 100%);
        padding: 96px 5% 80px;
        text-align: center;
    }
    .about-hero-title { font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; letter-spacing: -.5px; margin-bottom: 16px; }
    .about-hero-sub { font-size: 1.1rem; color: #6B7280; max-width: 640px; margin: 0 auto; }

    .about-mission {
        padding: 96px 5%;
    }
    .about-mission-inner {
        max-width: 1100px; margin: 0 auto;
        display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;
    }
    .mission-visual {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        border-radius: 20px; padding: 48px 36px;
        color: #fff; text-align: center;
    }
    .mission-icon { font-size: 3.5rem; margin-bottom: 20px; }
    .mission-heading { font-size: 1.5rem; font-weight: 800; margin-bottom: 12px; }
    .mission-text { font-size: .95rem; opacity: .85; line-height: 1.7; }
    .mission-content h2 { font-size: 2rem; font-weight: 800; margin-bottom: 20px; letter-spacing: -.4px; }
    .mission-content p { font-size: 1rem; color: #6B7280; line-height: 1.75; margin-bottom: 16px; }

    .values-section { background: #F9FAFB; padding: 96px 5%; }
    .values-inner { max-width: 1100px; margin: 0 auto; }
    .values-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 48px; }
    .value-card {
        background: #fff; border-radius: 12px; padding: 28px;
        border: 1px solid #E5E7EB; text-align: center;
    }
    .value-icon { font-size: 2rem; margin-bottom: 14px; }
    .value-title { font-size: 1rem; font-weight: 700; margin-bottom: 8px; }
    .value-desc { font-size: .88rem; color: #6B7280; line-height: 1.65; }

    .system-section { padding: 96px 5%; }
    .system-inner { max-width: 1100px; margin: 0 auto; }
    .system-modules { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 48px; }
    .module-item {
        display: flex; gap: 16px; padding: 20px;
        background: #F9FAFB; border-radius: 10px;
        border: 1px solid #E5E7EB; align-items: flex-start;
    }
    .module-icon {
        width: 44px; height: 44px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
    }
    .module-title { font-weight: 700; margin-bottom: 4px; }
    .module-desc { font-size: .85rem; color: #6B7280; line-height: 1.5; }

    .tech-section { background: #111827; color: #fff; padding: 96px 5%; }
    .tech-inner { max-width: 1100px; margin: 0 auto; }
    .tech-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-top: 48px; }
    .tech-card {
        background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
        border-radius: 12px; padding: 24px; text-align: center;
    }
    .tech-logo { font-size: 2rem; margin-bottom: 12px; }
    .tech-name { font-weight: 700; font-size: .95rem; margin-bottom: 6px; }
    .tech-desc { font-size: .8rem; color: rgba(255,255,255,.5); }

    .team-section { padding: 96px 5%; background: #F9FAFB; }
    .team-inner { max-width: 900px; margin: 0 auto; text-align: center; }

    .about-cta {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        padding: 80px 5%; text-align: center; color: #fff;
    }
    .about-cta h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: 14px; }
    .about-cta p { font-size: 1.05rem; opacity: .85; max-width: 520px; margin: 0 auto 32px; }
    .about-cta-btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }

    @media (max-width: 768px) {
        .about-mission-inner { grid-template-columns: 1fr; }
        .mission-visual { display: none; }
        .values-grid { grid-template-columns: 1fr; }
        .system-modules { grid-template-columns: 1fr; }
        .tech-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

{{-- ══ HERO ══ --}}
<section class="about-hero" style="padding-top:120px;">
    <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 16px;background:#EFF6FF;color:#2563EB;border-radius:50px;font-size:.8rem;font-weight:600;margin-bottom:20px;border:1px solid rgba(37,99,235,.2);">
        <i class="fas fa-info-circle"></i> About Us
    </div>
    <h1 class="about-hero-title">Built for educators,<br><span style="color:#2563EB">by educators</span></h1>
    <p class="about-hero-sub">TipCenter was created to solve the real operational challenges that educational centers face every day — from manual attendance sheets to scattered payment records.</p>
</section>

{{-- ══ MISSION ══ --}}
<section class="about-mission">
    <div class="about-mission-inner">
        <div class="mission-visual">
            <div class="mission-icon">🎓</div>
            <div class="mission-heading">Our Mission</div>
            <div class="mission-text">To empower every educational center with modern, affordable technology that lets educators focus on teaching — not administration.</div>
        </div>
        <div class="mission-content">
            <h2>Why we built TipCenter</h2>
            <p>Educational centers — from small tutoring hubs to large multi-branch academies — were drowning in manual paperwork. Attendance was tracked on paper, payments were recorded in notebooks, and reports were assembled from scattered spreadsheets.</p>
            <p>We built TipCenter to change that. Our platform gives every center a fully automated, cloud-based back office so administrators and professors can focus on what truly matters: educating students.</p>
            <p>As a <strong>multi-tenant SaaS platform</strong>, every center gets its own isolated environment, ensuring complete data privacy and security from day one.</p>
            <div style="display:flex;gap:32px;margin-top:32px;">
                <div>
                    <div style="font-size:1.8rem;font-weight:900;color:#2563EB;">2023</div>
                    <div style="font-size:.82rem;color:#6B7280;font-weight:500;">Founded</div>
                </div>
                <div>
                    <div style="font-size:1.8rem;font-weight:900;color:#10B981;">500+</div>
                    <div style="font-size:.82rem;color:#6B7280;font-weight:500;">Active Centers</div>
                </div>
                <div>
                    <div style="font-size:1.8rem;font-weight:900;color:#8B5CF6;">50k+</div>
                    <div style="font-size:.82rem;color:#6B7280;font-weight:500;">Students Managed</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ VALUES ══ --}}
<section class="values-section">
    <div class="values-inner">
        <div style="text-align:center;margin-bottom:0;">
            <p style="font-size:.8rem;font-weight:700;color:#2563EB;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">Our principles</p>
            <h2 style="font-size:2rem;font-weight:800;letter-spacing:-.4px;">What we stand for</h2>
        </div>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">🔒</div>
                <h3 class="value-title">Data Privacy First</h3>
                <p class="value-desc">Each tenant has a fully isolated database. Your students' data is never shared, never mixed, always secure.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">⚡</div>
                <h3 class="value-title">Simplicity & Speed</h3>
                <p class="value-desc">We obsess over UX. Every workflow — from recording attendance to generating a PDF report — should take seconds, not minutes.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🌍</div>
                <h3 class="value-title">Multi-Language Support</h3>
                <p class="value-desc">Full Arabic (RTL) and English (LTR) support built into every page. Your team works in the language they prefer.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">📊</div>
                <h3 class="value-title">Data-Driven Decisions</h3>
                <p class="value-desc">Rich reports and analytics help center administrators make informed decisions about professors, students and finances.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🤝</div>
                <h3 class="value-title">Customer Success</h3>
                <p class="value-desc">We measure our success by yours. Our support team is available to help you get the most out of TipCenter.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🚀</div>
                <h3 class="value-title">Continuous Innovation</h3>
                <p class="value-desc">We ship updates regularly based on real feedback from educational administrators, not guesses.</p>
            </div>
        </div>
    </div>
</section>

{{-- ══ WHAT THE SYSTEM DOES ══ --}}
<section class="system-section">
    <div class="system-inner">
        <div style="text-align:center;margin-bottom:0;">
            <p style="font-size:.8rem;font-weight:700;color:#2563EB;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">Platform capabilities</p>
            <h2 style="font-size:2rem;font-weight:800;letter-spacing:-.4px;margin-bottom:12px;">Everything in one platform</h2>
            <p style="font-size:1rem;color:#6B7280;max-width:580px;margin:0 auto;">TipCenter is a complete educational center management system — from the first day a student enrolls to their final payment.</p>
        </div>

        <div class="system-modules">
            <div class="module-item">
                <div class="module-icon" style="background:#EFF6FF;color:#2563EB;"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <div class="module-title">Student Enrollment & Profiles</div>
                    <div class="module-desc">Auto-generated student codes, stage assignments, parent contacts, and complete academic history in one place.</div>
                </div>
            </div>
            <div class="module-item">
                <div class="module-icon" style="background:#F0FDF4;color:#10B981;"><i class="fas fa-chalkboard-teacher"></i></div>
                <div>
                    <div class="module-title">Professor Management</div>
                    <div class="module-desc">Track professor profiles, subjects, stages, schedules, financial balances and settlements.</div>
                </div>
            </div>
            <div class="module-item">
                <div class="module-icon" style="background:#FFF7ED;color:#F59E0B;"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <div class="module-title">Session Scheduling</div>
                    <div class="module-desc">Create and manage sessions for any grade, assign professors and rooms, track online vs in-person attendance.</div>
                </div>
            </div>
            <div class="module-item">
                <div class="module-icon" style="background:#FDF4FF;color:#8B5CF6;"><i class="fas fa-clipboard-check"></i></div>
                <div>
                    <div class="module-title">Attendance Tracking</div>
                    <div class="module-desc">Record presence, absences and materials distribution per session. Link attendance directly to payment status.</div>
                </div>
            </div>
            <div class="module-item">
                <div class="module-icon" style="background:#FFF1F2;color:#F43F5E;"><i class="fas fa-credit-card"></i></div>
                <div>
                    <div class="module-title">Payments & Charges</div>
                    <div class="module-desc">Collect and track payments per student per session, manage special pricing, record charges and view balances.</div>
                </div>
            </div>
            <div class="module-item">
                <div class="module-icon" style="background:#ECFEFF;color:#06B6D4;"><i class="fas fa-file-pdf"></i></div>
                <div>
                    <div class="module-title">PDF Reports & Exports</div>
                    <div class="module-desc">Generate professional income reports, student reports, session summaries and special room reports as downloadable PDFs.</div>
                </div>
            </div>
            <div class="module-item">
                <div class="module-icon" style="background:#FFFBEB;color:#D97706;"><i class="fas fa-ban"></i></div>
                <div>
                    <div class="module-title">Blacklists & Special Cases</div>
                    <div class="module-desc">Manage professor and student blacklists, handle special pricing exceptions and student settlement cases.</div>
                </div>
            </div>
            <div class="module-item">
                <div class="module-icon" style="background:#F0FDF4;color:#16A34A;"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <div class="module-title">Roles, Permissions & Audit</div>
                    <div class="module-desc">Role-based access control with full audit trails — know exactly who did what and when.</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ TECH STACK ══ --}}
<section class="tech-section">
    <div class="tech-inner">
        <div style="text-align:center;margin-bottom:0;">
            <p style="font-size:.8rem;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">Built with modern technology</p>
            <h2 style="font-size:2rem;font-weight:800;color:#fff;margin-bottom:12px;">Our technology stack</h2>
            <p style="color:rgba(255,255,255,.6);max-width:500px;margin:0 auto;">Engineered for reliability, security, and scale. TipCenter is built on battle-tested open-source technology.</p>
        </div>
        <div class="tech-grid">
            <div class="tech-card">
                <div class="tech-logo">🐘</div>
                <div class="tech-name">Laravel 10</div>
                <div class="tech-desc">Robust PHP framework for a secure, maintainable backend</div>
            </div>
            <div class="tech-card">
                <div class="tech-logo">🏠</div>
                <div class="tech-name">stancl/tenancy</div>
                <div class="tech-desc">Per-tenant database isolation for complete data privacy</div>
            </div>
            <div class="tech-card">
                <div class="tech-logo">🛡️</div>
                <div class="tech-name">Spatie Permissions</div>
                <div class="tech-desc">Fine-grained role and permission management</div>
            </div>
            <div class="tech-card">
                <div class="tech-logo">📄</div>
                <div class="tech-name">mPDF / DomPDF</div>
                <div class="tech-desc">High-quality PDF generation for reports and print</div>
            </div>
        </div>
    </div>
</section>

{{-- ══ ABOUT CTA ══ --}}
<section class="about-cta">
    <h2>Experience TipCenter yourself</h2>
    <p>Try the live demo or register your center today — no credit card required.</p>
    <div class="about-cta-btns">
        <a href="{{ route('landing.demo') }}" class="btn btn-white btn-xl">
            <i class="fas fa-play-circle"></i> View Live Demo
        </a>
        <a href="{{ route('landing.register') }}" class="btn btn-ghost btn-xl">
            <i class="fas fa-rocket"></i> Start Free Trial
        </a>
    </div>
</section>

@endsection
