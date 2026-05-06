<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TipCenter — Education Management SaaS')</title>
    <meta name="description" content="@yield('meta_description', 'TipCenter is the all-in-one cloud platform for educational centers — manage students, professors, sessions, attendance and payments effortlessly.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand:        #2563EB;
            --brand-dark:   #1D4ED8;
            --brand-light:  #EFF6FF;
            --accent:       #10B981;
            --accent-dark:  #059669;
            --text:         #111827;
            --muted:        #6B7280;
            --border:       #E5E7EB;
            --bg:           #F9FAFB;
            --white:        #FFFFFF;
            --radius:       12px;
            --shadow-sm:    0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.04);
            --shadow:       0 4px 16px rgba(0,0,0,.10);
            --shadow-lg:    0 12px 40px rgba(0,0,0,.14);
        }

        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; color: var(--text); background: var(--white); line-height: 1.6; }

        /* ── NAVBAR ── */
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%; height: 68px;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-brand-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 18px;
        }
        .nav-brand-name { font-weight: 800; font-size: 1.2rem; color: var(--brand); letter-spacing: -.5px; }
        .nav-links { display: flex; align-items: center; gap: 32px; list-style: none; }
        .nav-links a { text-decoration: none; color: var(--muted); font-size: .9rem; font-weight: 500; transition: color .2s; }
        .nav-links a:hover, .nav-links a.active { color: var(--brand); }
        .nav-cta { display: flex; gap: 10px; align-items: center; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px; border-radius: 8px; font-size: .88rem;
            font-weight: 600; text-decoration: none; transition: all .2s; cursor: pointer; border: none;
        }
        .btn-outline {
            background: transparent; border: 1.5px solid var(--border);
            color: var(--text);
        }
        .btn-outline:hover { border-color: var(--brand); color: var(--brand); }
        .btn-primary { background: var(--brand); color: #fff; }
        .btn-primary:hover { background: var(--brand-dark); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(37,99,235,.35); }
        .btn-accent { background: var(--accent); color: #fff; }
        .btn-accent:hover { background: var(--accent-dark); transform: translateY(-1px); }
        .btn-lg { padding: 14px 32px; font-size: 1rem; border-radius: 10px; }
        .btn-xl { padding: 16px 40px; font-size: 1.05rem; border-radius: 12px; }
        .hamburger { display: none; background: none; border: none; cursor: pointer; padding: 6px; }

        /* ── MAIN ── */
        main { padding-top: 68px; }

        /* ── HERO ── */
        .hero {
            min-height: calc(100vh - 68px);
            display: flex; align-items: center;
            background: linear-gradient(155deg, #EFF6FF 0%, #ffffff 55%, #F0FDF4 100%);
            padding: 80px 5%;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; top: -200px; right: -200px;
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(37,99,235,.07) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-inner { max-width: 1200px; margin: 0 auto; width: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 14px; border-radius: 50px;
            background: var(--brand-light); color: var(--brand);
            font-size: .8rem; font-weight: 600; margin-bottom: 20px;
            border: 1px solid rgba(37,99,235,.2);
        }
        .hero-title { font-size: clamp(2rem, 4vw, 3.2rem); font-weight: 900; line-height: 1.15; letter-spacing: -.5px; margin-bottom: 20px; }
        .hero-title span { color: var(--brand); }
        .hero-sub { font-size: 1.1rem; color: var(--muted); margin-bottom: 36px; max-width: 500px; }
        .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }
        .hero-stats { display: flex; gap: 32px; margin-top: 44px; }
        .stat { }
        .stat-value { font-size: 1.6rem; font-weight: 800; color: var(--brand); }
        .stat-label { font-size: .8rem; color: var(--muted); font-weight: 500; }
        .hero-visual {
            background: var(--white); border-radius: 20px;
            box-shadow: var(--shadow-lg); overflow: hidden;
            border: 1px solid var(--border);
        }
        .hero-visual-header {
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            padding: 16px 20px; display: flex; align-items: center; gap: 8px;
        }
        .dot { width: 12px; height: 12px; border-radius: 50%; }
        .dot-red { background: #FF5F57; }
        .dot-yellow { background: #FFBD2E; }
        .dot-green { background: #28CA41; }
        .hero-visual-title { color: rgba(255,255,255,.8); font-size: .85rem; margin-left: auto; font-weight: 500; }
        .mock-dashboard { padding: 20px; background: #F8FAFC; }
        .mock-cards { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 16px; }
        .mock-card {
            background: var(--white); border-radius: 10px; padding: 14px;
            border: 1px solid var(--border);
        }
        .mock-card-label { font-size: .72rem; color: var(--muted); font-weight: 500; margin-bottom: 4px; }
        .mock-card-value { font-size: 1.3rem; font-weight: 700; }
        .mock-card-value.blue { color: var(--brand); }
        .mock-card-value.green { color: var(--accent); }
        .mock-card-value.orange { color: #F59E0B; }
        .mock-card-value.purple { color: #8B5CF6; }
        .mock-table { background: var(--white); border-radius: 10px; border: 1px solid var(--border); overflow: hidden; }
        .mock-table-header { background: var(--bg); padding: 10px 14px; font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; display: flex; gap: 12px; }
        .mock-table-row { padding: 10px 14px; display: flex; gap: 12px; align-items: center; border-top: 1px solid var(--border); font-size: .78rem; }
        .mock-badge { padding: 3px 8px; border-radius: 20px; font-size: .7rem; font-weight: 600; }
        .mock-badge.present { background: #D1FAE5; color: #059669; }
        .mock-badge.pending { background: #FEF3C7; color: #D97706; }

        /* ── SECTIONS ── */
        section { padding: 96px 5%; }
        .section-inner { max-width: 1200px; margin: 0 auto; }
        .section-tag { font-size: .8rem; font-weight: 700; color: var(--brand); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
        .section-title { font-size: clamp(1.7rem, 3vw, 2.4rem); font-weight: 800; letter-spacing: -.4px; margin-bottom: 16px; }
        .section-sub { font-size: 1.05rem; color: var(--muted); max-width: 580px; }
        .section-center { text-align: center; }
        .section-center .section-sub { margin: 0 auto; }

        /* ── FEATURES ── */
        .features { background: var(--bg); }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 56px; }
        .feature-card {
            background: var(--white); border-radius: var(--radius);
            padding: 28px; border: 1px solid var(--border);
            transition: all .25s; cursor: default;
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.2); }
        .feature-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; margin-bottom: 16px;
        }
        .feature-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 8px; }
        .feature-desc { font-size: .9rem; color: var(--muted); line-height: 1.65; }

        /* ── HOW IT WORKS ── */
        .steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-top: 56px; }
        .step { text-align: center; }
        .step-num {
            width: 52px; height: 52px; border-radius: 50%;
            background: var(--brand); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; font-weight: 800; margin: 0 auto 16px;
        }
        .step-title { font-weight: 700; margin-bottom: 8px; }
        .step-desc { font-size: .88rem; color: var(--muted); }

        /* ── PRICING ── */
        .pricing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 56px; }
        .pricing-card {
            background: var(--white); border-radius: var(--radius);
            padding: 32px; border: 1.5px solid var(--border);
            position: relative; transition: all .25s;
        }
        .pricing-card.popular { border-color: var(--brand); box-shadow: 0 0 0 4px rgba(37,99,235,.08); }
        .popular-badge {
            position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
            background: var(--brand); color: #fff; padding: 4px 16px;
            border-radius: 20px; font-size: .75rem; font-weight: 700;
        }
        .pricing-plan { font-size: .8rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .pricing-price { font-size: 2.6rem; font-weight: 900; margin-bottom: 4px; }
        .pricing-price span { font-size: 1rem; color: var(--muted); font-weight: 400; }
        .pricing-desc { font-size: .88rem; color: var(--muted); margin-bottom: 24px; }
        .pricing-features { list-style: none; margin-bottom: 28px; display: flex; flex-direction: column; gap: 10px; }
        .pricing-features li { font-size: .88rem; display: flex; align-items: flex-start; gap: 10px; }
        .pricing-features li i { color: var(--accent); margin-top: 2px; }
        .pricing-features li.off i { color: var(--border); }
        .pricing-features li.off { color: var(--muted); }

        /* ── TESTIMONIALS ── */
        .testimonials { background: var(--bg); }
        .testimonials-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 56px; }
        .testimonial {
            background: var(--white); border-radius: var(--radius);
            padding: 28px; border: 1px solid var(--border);
        }
        .testimonial-stars { color: #F59E0B; margin-bottom: 14px; font-size: .85rem; }
        .testimonial-text { font-size: .92rem; color: var(--muted); line-height: 1.7; margin-bottom: 18px; font-style: italic; }
        .testimonial-author { display: flex; align-items: center; gap: 12px; }
        .author-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .95rem; color: #fff;
        }
        .author-name { font-weight: 700; font-size: .9rem; }
        .author-role { font-size: .78rem; color: var(--muted); }

        /* ── CTA ── */
        .cta-section {
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #fff; text-align: center; padding: 96px 5%;
        }
        .cta-section .section-title { color: #fff; }
        .cta-section .section-sub { color: rgba(255,255,255,.8); margin: 16px auto 36px; }
        .cta-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
        .btn-white { background: #fff; color: var(--brand); }
        .btn-white:hover { background: #F0F7FF; transform: translateY(-1px); }
        .btn-ghost { background: transparent; border: 1.5px solid rgba(255,255,255,.4); color: #fff; }
        .btn-ghost:hover { background: rgba(255,255,255,.1); }

        /* ── FOOTER ── */
        footer {
            background: var(--text); color: rgba(255,255,255,.7);
            padding: 64px 5% 32px;
        }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 48px; max-width: 1200px; margin-left: auto; margin-right: auto; }
        .footer-brand-name { font-weight: 800; font-size: 1.2rem; color: #fff; margin-bottom: 12px; }
        .footer-desc { font-size: .88rem; line-height: 1.65; max-width: 260px; }
        .footer-heading { font-size: .85rem; font-weight: 700; color: #fff; margin-bottom: 16px; text-transform: uppercase; letter-spacing: .5px; }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer-links a { color: rgba(255,255,255,.6); text-decoration: none; font-size: .88rem; transition: color .2s; }
        .footer-links a:hover { color: #fff; }
        .footer-bottom { display: flex; justify-content: space-between; align-items: center; padding-top: 24px; border-top: 1px solid rgba(255,255,255,.1); font-size: .82rem; max-width: 1200px; margin: 0 auto; }
        .footer-social { display: flex; gap: 14px; }
        .footer-social a { color: rgba(255,255,255,.5); font-size: 1rem; transition: color .2s; }
        .footer-social a:hover { color: #fff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .hero-inner { grid-template-columns: 1fr; }
            .hero-visual { display: none; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .steps { grid-template-columns: repeat(2, 1fr); gap: 32px; }
            .pricing-grid { grid-template-columns: 1fr; max-width: 420px; margin-left: auto; margin-right: auto; }
            .testimonials-grid { grid-template-columns: 1fr; max-width: 600px; margin-left: auto; margin-right: auto; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .nav-links { display: none; }
            .hamburger { display: block; }
            .features-grid { grid-template-columns: 1fr; }
            .steps { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; gap: 16px; text-align: center; }
            .hero-stats { gap: 20px; }
        }
    </style>
    @stack('head')
</head>
<body>

<!-- NAVBAR -->
<nav class="nav">
    <a href="{{ route('landing.index') }}" class="nav-brand">
        <div class="nav-brand-icon"><i class="fas fa-graduation-cap"></i></div>
        <span class="nav-brand-name">TipCenter</span>
    </a>

    <ul class="nav-links">
        <li><a href="{{ route('landing.index') }}#features">Features</a></li>
        <li><a href="{{ route('landing.index') }}#how-it-works">How it works</a></li>
        <li><a href="{{ route('landing.index') }}#pricing">Pricing</a></li>
        <li><a href="{{ route('landing.about') }}" class="{{ request()->routeIs('landing.about') ? 'active' : '' }}">About</a></li>
    </ul>

    <div class="nav-cta">
        <a href="{{ route('landing.demo') }}" class="btn btn-outline">
            <i class="fas fa-play-circle"></i> Live Demo
        </a>
        <a href="{{ route('landing.register') }}" class="btn btn-primary">
            Start Free Trial
        </a>
    </div>

    <button class="hamburger" onclick="document.querySelector('.nav-links').classList.toggle('open')" aria-label="Menu">
        <i class="fas fa-bars fa-lg"></i>
    </button>
</nav>

<!-- MAIN -->
<main>
    @yield('content')
</main>

<!-- FOOTER -->
<footer>
    <div class="footer-grid">
        <div>
            <div class="footer-brand-name">TipCenter</div>
            <p class="footer-desc">The all-in-one cloud platform for educational centers. Manage everything from one place.</p>
        </div>
        <div>
            <p class="footer-heading">Product</p>
            <ul class="footer-links">
                <li><a href="{{ route('landing.index') }}#features">Features</a></li>
                <li><a href="{{ route('landing.index') }}#pricing">Pricing</a></li>
                <li><a href="{{ route('landing.demo') }}">Live Demo</a></li>
                <li><a href="{{ route('landing.register') }}">Get Started</a></li>
            </ul>
        </div>
        <div>
            <p class="footer-heading">Company</p>
            <ul class="footer-links">
                <li><a href="{{ route('landing.about') }}">About Us</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Blog</a></li>
            </ul>
        </div>
        <div>
            <p class="footer-heading">Legal</p>
            <ul class="footer-links">
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} TipCenter. All rights reserved.</span>
        <div class="footer-social">
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
            <a href="#"><i class="fab fa-facebook"></i></a>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
