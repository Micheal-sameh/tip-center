@extends('layouts.landing')

@section('title', 'TipCenter — Education Center Management Platform')
@section('meta_description', 'TipCenter is the all-in-one SaaS platform for educational centers. Manage students, professors, sessions, attendance, payments and reports — all in one place.')

@section('content')

{{-- ══════════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════════ --}}
<section class="hero">
    <div class="hero-inner">
        <div>
            <div class="hero-badge">
                <i class="fas fa-rocket"></i> Now with Multi-Tenant SaaS Architecture
            </div>
            <h1 class="hero-title">
                Run your educational center<br>
                <span>smarter & faster</span>
            </h1>
            <p class="hero-sub">
                TipCenter gives you a complete cloud platform to manage students,
                professors, sessions, attendance, payments and reports — all from
                one beautiful dashboard.
            </p>
            <div class="hero-actions">
                <a href="{{ route('landing.register') }}" class="btn btn-primary btn-xl">
                    <i class="fas fa-play"></i> Start Free 14-Day Trial
                </a>
                <a href="{{ route('landing.demo') }}" class="btn btn-outline btn-xl">
                    <i class="fas fa-desktop"></i> View Live Demo
                </a>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-value">500+</div>
                    <div class="stat-label">Centers</div>
                </div>
                <div class="stat">
                    <div class="stat-value">50k+</div>
                    <div class="stat-label">Students</div>
                </div>
                <div class="stat">
                    <div class="stat-value">99.9%</div>
                    <div class="stat-label">Uptime</div>
                </div>
            </div>
        </div>

        {{-- Mockup Dashboard Preview --}}
        <div class="hero-visual">
            <div class="hero-visual-header">
                <div class="dot dot-red"></div>
                <div class="dot dot-yellow"></div>
                <div class="dot dot-green"></div>
                <span class="hero-visual-title">Dashboard Overview</span>
            </div>
            <div class="mock-dashboard">
                <div class="mock-cards">
                    <div class="mock-card">
                        <div class="mock-card-label">Total Students</div>
                        <div class="mock-card-value blue">248</div>
                    </div>
                    <div class="mock-card">
                        <div class="mock-card-label">Sessions Today</div>
                        <div class="mock-card-value green">12</div>
                    </div>
                    <div class="mock-card">
                        <div class="mock-card-label">Monthly Revenue</div>
                        <div class="mock-card-value orange">$4,820</div>
                    </div>
                    <div class="mock-card">
                        <div class="mock-card-label">Professors</div>
                        <div class="mock-card-value purple">18</div>
                    </div>
                </div>
                <div class="mock-table">
                    <div class="mock-table-header">
                        <span style="flex:2">Student</span>
                        <span style="flex:1.5">Session</span>
                        <span style="flex:1">Status</span>
                    </div>
                    <div class="mock-table-row">
                        <span style="flex:2;font-weight:600">Ahmed Hassan</span>
                        <span style="flex:1.5;color:#6B7280">Math G11</span>
                        <span style="flex:1"><span class="mock-badge present">Present</span></span>
                    </div>
                    <div class="mock-table-row">
                        <span style="flex:2;font-weight:600">Sara Ali</span>
                        <span style="flex:1.5;color:#6B7280">Physics G12</span>
                        <span style="flex:1"><span class="mock-badge present">Present</span></span>
                    </div>
                    <div class="mock-table-row">
                        <span style="flex:2;font-weight:600">Omar Khaled</span>
                        <span style="flex:1.5;color:#6B7280">Chemistry G10</span>
                        <span style="flex:1"><span class="mock-badge pending">Pending</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     TRUST LOGOS (social proof bar)
══════════════════════════════════════════════════════════ --}}
<section style="padding: 32px 5%; background: #F9FAFB; border-top: 1px solid #E5E7EB; border-bottom: 1px solid #E5E7EB;">
    <div style="max-width:1100px;margin:0 auto;text-align:center;">
        <p style="font-size:.82rem;font-weight:600;color:#9CA3AF;text-transform:uppercase;letter-spacing:1px;margin-bottom:24px;">
            Trusted by educational centers across the region
        </p>
        <div style="display:flex;justify-content:center;align-items:center;flex-wrap:wrap;gap:40px;opacity:.55;font-size:1.4rem;color:#374151;">
            <span style="font-weight:800">Al-Fajr Center</span>
            <span style="font-weight:800">Horizon Academy</span>
            <span style="font-weight:800">Elite Institute</span>
            <span style="font-weight:800">Nova Learning</span>
            <span style="font-weight:800">Bright Minds</span>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     FEATURES
══════════════════════════════════════════════════════════ --}}
<section class="features" id="features">
    <div class="section-inner">
        <div class="section-center">
            <p class="section-tag">Everything you need</p>
            <h2 class="section-title">Powerful features, built for educators</h2>
            <p class="section-sub">Every tool your center needs to operate efficiently, all in one platform.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background:#EFF6FF;color:#2563EB;">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="feature-title">Student Management</h3>
                <p class="feature-desc">Manage student profiles, academic stages, contact info, and payment history. Generate individual student codes automatically.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#F0FDF4;color:#10B981;">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3 class="feature-title">Professor Profiles</h3>
                <p class="feature-desc">Track professor subjects, schedules, financial settlements, stage assignments and balances in real time.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#FFF7ED;color:#F59E0B;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="feature-title">Session & Attendance</h3>
                <p class="feature-desc">Schedule sessions, record attendance with a single click, track online students and manage materials distribution.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#FDF4FF;color:#8B5CF6;">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3 class="feature-title">Payments & Charges</h3>
                <p class="feature-desc">Record student payments, manage special pricing cases, handle refunds and view outstanding balances at a glance.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#FFF1F2;color:#F43F5E;">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="feature-title">Rich Reports</h3>
                <p class="feature-desc">Income reports, session summaries, student settlements, and printable PDFs — all filtered by date, professor or stage.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#ECFEFF;color:#06B6D4;">
                    <i class="fas fa-building"></i>
                </div>
                <h3 class="feature-title">Multi-Tenant SaaS</h3>
                <p class="feature-desc">Every educational center gets its own isolated database and subdomain. Fully SaaS-ready with trial periods and plan management.</p>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     HOW IT WORKS
══════════════════════════════════════════════════════════ --}}
<section id="how-it-works" style="padding:96px 5%;">
    <div class="section-inner">
        <div class="section-center">
            <p class="section-tag">Simple process</p>
            <h2 class="section-title">Up and running in minutes</h2>
            <p class="section-sub">No technical knowledge required. Set up your center and start managing everything in 4 steps.</p>
        </div>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <h3 class="step-title">Create your account</h3>
                <p class="step-desc">Register your center with a unique subdomain and get instant access — no credit card required.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <h3 class="step-title">Add professors & students</h3>
                <p class="step-desc">Import or manually enter your professors and students. Assign stages and subjects.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <h3 class="step-title">Schedule sessions</h3>
                <p class="step-desc">Create sessions, assign professors and automatically link enrolled students.</p>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <h3 class="step-title">Track & Report</h3>
                <p class="step-desc">Record attendance, collect payments and generate detailed financial and academic reports.</p>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     PRICING
══════════════════════════════════════════════════════════ --}}
<section id="pricing" style="padding:96px 5%;background:#F9FAFB;">
    <div class="section-inner">
        <div class="section-center">
            <p class="section-tag">Transparent pricing</p>
            <h2 class="section-title">Simple, honest pricing</h2>
            <p class="section-sub">Start free, upgrade as you grow. No hidden fees, no long-term contracts.</p>
        </div>

        <div class="pricing-grid">
            <div class="pricing-card">
                <p class="pricing-plan">Starter</p>
                <div class="pricing-price">Free <span>/ 14 days</span></div>
                <p class="pricing-desc">Perfect for trying out the platform.</p>
                <ul class="pricing-features">
                    <li><i class="fas fa-check"></i> Up to 50 students</li>
                    <li><i class="fas fa-check"></i> 5 professors</li>
                    <li><i class="fas fa-check"></i> All core features</li>
                    <li class="off"><i class="fas fa-times"></i> PDF reports</li>
                    <li class="off"><i class="fas fa-times"></i> Priority support</li>
                </ul>
                <a href="{{ route('landing.register') }}" class="btn btn-outline" style="width:100%;justify-content:center;">Start Free Trial</a>
            </div>

            <div class="pricing-card popular">
                <div class="popular-badge">Most Popular</div>
                <p class="pricing-plan">Professional</p>
                <div class="pricing-price">$29 <span>/ month</span></div>
                <p class="pricing-desc">Everything a growing center needs.</p>
                <ul class="pricing-features">
                    <li><i class="fas fa-check"></i> Unlimited students</li>
                    <li><i class="fas fa-check"></i> Unlimited professors</li>
                    <li><i class="fas fa-check"></i> All features + PDF reports</li>
                    <li><i class="fas fa-check"></i> Email support</li>
                    <li class="off"><i class="fas fa-times"></i> Custom branding</li>
                </ul>
                <a href="{{ route('landing.register') }}" class="btn btn-primary" style="width:100%;justify-content:center;">Get Started</a>
            </div>

            <div class="pricing-card">
                <p class="pricing-plan">Enterprise</p>
                <div class="pricing-price">$79 <span>/ month</span></div>
                <p class="pricing-desc">For large centers with advanced needs.</p>
                <ul class="pricing-features">
                    <li><i class="fas fa-check"></i> Everything in Professional</li>
                    <li><i class="fas fa-check"></i> Custom branding</li>
                    <li><i class="fas fa-check"></i> Dedicated database</li>
                    <li><i class="fas fa-check"></i> Priority support</li>
                    <li><i class="fas fa-check"></i> SLA guarantee</li>
                </ul>
                <a href="{{ route('landing.register') }}" class="btn btn-outline" style="width:100%;justify-content:center;">Contact Sales</a>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     TESTIMONIALS
══════════════════════════════════════════════════════════ --}}
<section class="testimonials">
    <div class="section-inner">
        <div class="section-center">
            <p class="section-tag">What centers say</p>
            <h2 class="section-title">Loved by educational administrators</h2>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"TipCenter transformed how we run our center. We used to spend hours on spreadsheets — now everything is automated and accurate."</p>
                <div class="testimonial-author">
                    <div class="author-avatar" style="background:linear-gradient(135deg,#2563EB,#1D4ED8)">AM</div>
                    <div>
                        <div class="author-name">Ahmad Mansour</div>
                        <div class="author-role">Director, Al-Fajr Learning Center</div>
                    </div>
                </div>
            </div>
            <div class="testimonial">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"The attendance and payment tracking alone saved us 10+ hours per week. The PDF reports are professional and ready to share with parents."</p>
                <div class="testimonial-author">
                    <div class="author-avatar" style="background:linear-gradient(135deg,#10B981,#059669)">RA</div>
                    <div>
                        <div class="author-name">Rania Al-Sayed</div>
                        <div class="author-role">Admin Manager, Horizon Academy</div>
                    </div>
                </div>
            </div>
            <div class="testimonial">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"Getting set up took less than 30 minutes. The multi-tenant design means each branch has its own isolated data — perfect for our network."</p>
                <div class="testimonial-author">
                    <div class="author-avatar" style="background:linear-gradient(135deg,#8B5CF6,#6D28D9)">KO</div>
                    <div>
                        <div class="author-name">Khalid Omar</div>
                        <div class="author-role">CEO, Elite Education Group</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     CTA
══════════════════════════════════════════════════════════ --}}
<section class="cta-section">
    <div class="section-inner">
        <h2 class="section-title">Ready to modernize your center?</h2>
        <p class="section-sub">Join hundreds of educational centers already using TipCenter. Start your free 14-day trial — no credit card needed.</p>
        <div class="cta-actions">
            <a href="{{ route('landing.register') }}" class="btn btn-white btn-xl">
                <i class="fas fa-rocket"></i> Start Free Trial
            </a>
            <a href="{{ route('landing.demo') }}" class="btn btn-ghost btn-xl">
                <i class="fas fa-eye"></i> See the Demo
            </a>
        </div>
    </div>
</section>

@endsection
