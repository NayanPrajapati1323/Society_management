<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SocietyPro – Smart Society Management Software</title>
  <meta name="description" content="SocietyPro is a complete society management platform for housing societies, apartments, and townships. Manage members, billing, visitors, and more." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <style>
    :root {
      --primary: #6C63FF;
      --primary-dark: #574fd6;
      --primary-light: #ede9ff;
      --secondary: #FF6584;
      --accent: #43e97b;
      --dark: #0f0e17;
      --dark2: #1a1a2e;
      --card-bg: #ffffff;
      --text: #2d2d44;
      --muted: #6b7280;
      --border: #e5e7eb;
      --gradient: linear-gradient(135deg, #6C63FF 0%, #574fd6 50%, #43e97b 100%);
      --gradient2: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Inter', sans-serif; color: var(--text); background: #fff; }

    /* ── NAVBAR ── */
    .navbar {
      position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
      padding: 1rem 0;
      background: rgba(255,255,255,0.95);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(108,99,255,0.1);
      transition: all .3s;
    }
    .navbar.scrolled { box-shadow: 0 4px 30px rgba(108,99,255,0.15); }
    .navbar-inner { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .navbar-brand { display: flex; align-items: center; gap: .6rem; text-decoration: none; }
    .brand-icon { width: 38px; height: 38px; border-radius: 10px; background: var(--gradient); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.2rem; }
    .brand-name { font-size: 1.25rem; font-weight: 800; color: var(--dark); }
    .brand-name span { color: var(--primary); }
    .nav-links { display: flex; align-items: center; gap: 2rem; list-style: none; }
    .nav-links a { text-decoration: none; color: var(--text); font-weight: 500; font-size: .9rem; transition: color .2s; }
    .nav-links a:hover { color: var(--primary); }
    .nav-actions { display: flex; align-items: center; gap: .75rem; }
    .btn { display: inline-flex; align-items: center; gap: .4rem; padding: .55rem 1.3rem; border-radius: 50px; font-size: .9rem; font-weight: 600; cursor: pointer; transition: all .25s; border: 2px solid transparent; text-decoration: none; }
    .btn-outline { border-color: var(--primary); color: var(--primary); background: transparent; }
    .btn-outline:hover { background: var(--primary); color: #fff; }
    .btn-primary { background: var(--primary); color: #fff; border-color: var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(108,99,255,0.35); }
    .btn-lg { padding: .8rem 2rem; font-size: 1rem; }
    .btn-white { background: #fff; color: var(--primary); }
    .btn-white:hover { background: var(--primary-light); }

    /* ── HERO ── */
    .hero {
      min-height: 100vh;
      background: linear-gradient(160deg, #0f0e17 0%, #1a1a2e 40%, #16213e 100%);
      display: flex; align-items: center;
      padding: 8rem 0 5rem;
      position: relative; overflow: hidden;
    }
    .hero::before {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at 20% 50%, rgba(108,99,255,0.3) 0%, transparent 60%),
                  radial-gradient(ellipse at 80% 20%, rgba(67,233,123,0.15) 0%, transparent 50%);
    }
    .hero-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; position: relative; z-index: 1; }
    .hero-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
    .hero-badge { display: inline-flex; align-items: center; gap: .5rem; background: rgba(108,99,255,0.2); border: 1px solid rgba(108,99,255,0.4); color: #a5a0ff; padding: .4rem 1rem; border-radius: 50px; font-size: .8rem; font-weight: 600; margin-bottom: 1.5rem; }
    .hero-title { font-size: clamp(2.2rem, 5vw, 3.5rem); font-weight: 800; color: #fff; line-height: 1.15; margin-bottom: 1.25rem; }
    .hero-title span { background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .hero-desc { color: rgba(255,255,255,0.65); font-size: 1.1rem; line-height: 1.7; margin-bottom: 2rem; }
    .hero-btns { display: flex; gap: 1rem; flex-wrap: wrap; }
    .hero-stats { display: flex; gap: 2rem; margin-top: 3rem; }
    .stat { text-align: center; }
    .stat-num { font-size: 1.5rem; font-weight: 800; color: #fff; }
    .stat-label { font-size: .75rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: .05em; }
    .hero-visual { position: relative; }
    .hero-card-stack { position: relative; }
    .hero-card {
      background: rgba(255,255,255,0.07);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 20px;
      padding: 1.5rem;
      color: #fff;
    }
    .hero-card-main { margin-bottom: 1rem; }
    .hero-card-small {
      position: absolute;
      width: 200px;
      background: linear-gradient(135deg, rgba(108,99,255,0.95), rgba(67,233,123,0.9));
      border: none;
      right: -1rem; bottom: -2rem;
      animation: float 3s ease-in-out infinite;
    }
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
    .metric-row { display: flex; align-items: center; justify-content: space-between; padding: .6rem 0; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .metric-row:last-child { border-bottom: none; }
    .metric-label { font-size: .8rem; color: rgba(255,255,255,0.6); }
    .metric-val { font-size: .95rem; font-weight: 700; }
    .metric-badge { padding: .2rem .6rem; border-radius: 20px; font-size: .7rem; font-weight: 600; }
    .badge-green { background: rgba(67,233,123,0.2); color: #43e97b; }
    .badge-blue { background: rgba(108,99,255,0.3); color: #a5a0ff; }
    .small-stat { font-size: .75rem; opacity: .8; }

    /* ── SECTION COMMONS ── */
    section { padding: 5rem 0; }
    .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
    .section-badge { display: inline-block; background: var(--primary-light); color: var(--primary); padding: .35rem .9rem; border-radius: 50px; font-size: .78rem; font-weight: 700; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: .06em; }
    .section-title { font-size: clamp(1.7rem, 3.5vw, 2.5rem); font-weight: 800; color: var(--dark); margin-bottom: .75rem; line-height: 1.2; }
    .section-title span { color: var(--primary); }
    .section-desc { color: var(--muted); font-size: 1rem; line-height: 1.7; max-width: 580px; margin: 0 auto; }
    .text-center { text-align: center; }

    /* ── FEATURES ── */
    #features { background: #fafafa; }
    .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 3rem; }
    .feature-card {
      background: #fff;
      border-radius: 16px;
      padding: 1.75rem;
      border: 1px solid var(--border);
      transition: all .3s;
      position: relative; overflow: hidden;
    }
    .feature-card::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0; height: 3px;
      background: var(--gradient);
      transform: scaleX(0); transform-origin: left;
      transition: transform .3s;
    }
    .feature-card:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(108,99,255,0.12); border-color: rgba(108,99,255,0.2); }
    .feature-card:hover::before { transform: scaleX(1); }
    .feature-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-bottom: 1.1rem; }
    .icon-purple { background: var(--primary-light); color: var(--primary); }
    .icon-green { background: #dcfce7; color: #16a34a; }
    .icon-orange { background: #fff7ed; color: #ea580c; }
    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-red { background: #fff1f2; color: #e11d48; }
    .icon-teal { background: #f0fdfa; color: #0d9488; }
    .feature-card h4 { font-size: 1rem; font-weight: 700; color: var(--dark); margin-bottom: .5rem; }
    .feature-card p { color: var(--muted); font-size: .88rem; line-height: 1.6; }

    /* ── HOW IT WORKS ── */
    #how-it-works { background: #fff; }
    .steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2rem; margin-top: 3rem; position: relative; }
    .steps-grid::before { content: ''; position: absolute; top: 2.5rem; left: 10%; right: 10%; height: 2px; background: linear-gradient(to right, var(--primary), var(--accent)); opacity: .3; }
    .step { text-align: center; position: relative; }
    .step-num { width: 56px; height: 56px; border-radius: 50%; background: var(--gradient); color: #fff; font-size: 1.3rem; font-weight: 800; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; box-shadow: 0 8px 25px rgba(108,99,255,0.35); }
    .step h4 { font-size: .95rem; font-weight: 700; color: var(--dark); margin-bottom: .4rem; }
    .step p { color: var(--muted); font-size: .83rem; line-height: 1.6; }

    /* ── PLANS ── */
    #plans { background: linear-gradient(180deg, #f8f7ff 0%, #fff 100%); }
    .plans-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.75rem; margin-top: 3rem; }
    .plan-card { background: #fff; border-radius: 20px; padding: 2rem; border: 2px solid var(--border); transition: all .3s; position: relative; }
    .plan-card.popular { border-color: var(--primary); box-shadow: 0 20px 60px rgba(108,99,255,0.2); }
    .popular-badge { position: absolute; top: -1px; left: 50%; transform: translateX(-50%); background: var(--gradient); color: #fff; font-size: .72rem; font-weight: 700; padding: .3rem 1.25rem; border-radius: 0 0 12px 12px; white-space: nowrap; text-transform: uppercase; letter-spacing: .05em; }
    .plan-card:not(.popular):hover { border-color: rgba(108,99,255,0.3); transform: translateY(-4px); box-shadow: 0 15px 40px rgba(108,99,255,0.1); }
    .plan-name { font-size: 1.15rem; font-weight: 800; color: var(--dark); margin-bottom: .25rem; }
    .plan-desc { color: var(--muted); font-size: .83rem; margin-bottom: 1.5rem; }
    .plan-contact { display: flex; align-items: baseline; gap: .4rem; margin: 1.25rem 0 1.5rem; }
    .plan-contact-text { font-size: 1.3rem; font-weight: 800; color: var(--primary); }
    .plan-contact-sub { font-size: .78rem; color: var(--muted); }
    .plan-features { list-style: none; space-y: .5rem; }
    .plan-features li { display: flex; align-items: center; gap: .6rem; padding: .4rem 0; font-size: .875rem; color: var(--text); }
    .plan-features li .check { color: var(--accent); font-size: 1rem; flex-shrink: 0; }
    .plan-features li .cross { color: #d1d5db; font-size: 1rem; flex-shrink: 0; }
    .plan-cta { display: block; width: 100%; padding: .75rem; border-radius: 12px; font-size: .9rem; font-weight: 700; text-align: center; text-decoration: none; margin-top: 1.5rem; transition: all .25s; }
    .plan-cta-outline { border: 2px solid var(--primary); color: var(--primary); background: transparent; }
    .plan-cta-outline:hover { background: var(--primary); color: #fff; }
    .plan-cta-filled { background: var(--gradient); color: #fff; border: none; cursor: pointer; }
    .plan-cta-filled:hover { opacity: .9; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(108,99,255,.35); }

    /* ── CONTACT ── */
    #contact { background: #fff; }
    .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-top: 3rem; }
    .contact-info h3 { font-size: 1.5rem; font-weight: 800; color: var(--dark); margin-bottom: 1rem; }
    .contact-info p { color: var(--muted); line-height: 1.7; margin-bottom: 1.5rem; }
    .contact-item { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: #fafafa; border-radius: 12px; border: 1px solid var(--border); margin-bottom: .75rem; text-decoration: none; color: var(--text); transition: all .2s; }
    .contact-item:hover { border-color: var(--primary); background: var(--primary-light); }
    .contact-item-icon { width: 42px; height: 42px; border-radius: 10px; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .contact-item-text strong { display: block; font-size: .83rem; color: var(--muted); font-weight: 500; }
    .contact-item-text span { font-size: .95rem; font-weight: 600; color: var(--dark); }
    .contact-form-card { background: #fafafa; border-radius: 20px; padding: 2rem; border: 1px solid var(--border); }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: .83rem; font-weight: 600; color: var(--dark); margin-bottom: .35rem; }
    .form-control { width: 100%; padding: .65rem 1rem; border-radius: 10px; border: 1.5px solid var(--border); font-size: .9rem; font-family: inherit; color: var(--text); background: #fff; transition: border-color .2s; outline: none; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(108,99,255,0.1); }
    textarea.form-control { resize: vertical; min-height: 100px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }

    /* ── FOOTER ── */
    footer { background: var(--dark); color: rgba(255,255,255,.7); padding: 3rem 0 1.5rem; }
    .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 3rem; margin-bottom: 2rem; }
    .footer-brand .brand-name { color: #fff; margin-bottom: .75rem; font-size: 1.1rem; }
    .footer-brand p { font-size: .85rem; line-height: 1.7; max-width: 280px; }
    .footer-links h5 { color: #fff; font-size: .85rem; font-weight: 700; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: .07em; }
    .footer-links ul { list-style: none; }
    .footer-links li { margin-bottom: .5rem; }
    .footer-links a { color: rgba(255,255,255,.6); text-decoration: none; font-size: .85rem; transition: color .2s; }
    .footer-links a:hover { color: var(--primary); }
    .footer-bottom { border-top: 1px solid rgba(255,255,255,.08); padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
    .footer-bottom p { font-size: .82rem; }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      .nav-links { display: none; }
      .hero-grid, .contact-grid, .footer-grid { grid-template-columns: 1fr; }
      .hero-card-small { display: none; }
      .steps-grid::before { display: none; }
      .form-row { grid-template-columns: 1fr; }
      .hero-stats { gap: 1.25rem; }
    }

    /* ── ALERTS ── */
    .alert { padding: .8rem 1.25rem; border-radius: 10px; margin-bottom: 1rem; font-size: .88rem; }
    .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .alert-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="mainNav">
  <div class="navbar-inner">
    <a href="{{ route('society.landing') }}" class="navbar-brand">
      <div class="brand-icon"><i class="bi bi-buildings-fill"></i></div>
      <span class="brand-name">Society<span>Pro</span></span>
    </a>
    <ul class="nav-links">
      <li><a href="#features">Features</a></li>
      <li><a href="#how-it-works">How It Works</a></li>
      <li><a href="#plans">Plans</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-actions">
      <a href="{{ route('society.login') }}" class="btn btn-outline">Sign In</a>
      <a href="{{ route('society.register') }}" class="btn btn-primary">Get Started</a>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero" id="home">
  <div class="hero-container">
    <div class="hero-grid">
      <div class="hero-content">
        <div class="hero-badge"><i class="bi bi-stars"></i> Trusted by 500+ Societies</div>
        <h1 class="hero-title">Smart <span>Society Management</span> Made Simple</h1>
        <p class="hero-desc">
          A complete digital platform to manage housing societies, apartments, and townships.
          From maintenance billing to visitor tracking — everything in one place.
        </p>
        <div class="hero-btns">
          <a href="{{ route('society.register') }}" class="btn btn-primary btn-lg">Start Free <i class="bi bi-arrow-right"></i></a>
          <a href="#features" class="btn btn-outline btn-lg" style="color:#fff; border-color:rgba(255,255,255,0.4);">Explore Features</a>
        </div>
        <div class="hero-stats">
          <div class="stat"><div class="stat-num">500+</div><div class="stat-label">Societies</div></div>
          <div class="stat"><div class="stat-num">50k+</div><div class="stat-label">Members</div></div>
          <div class="stat"><div class="stat-num">99.9%</div><div class="stat-label">Uptime</div></div>
          <div class="stat"><div class="stat-num">4.9★</div><div class="stat-label">Rating</div></div>
        </div>
      </div>
      <div class="hero-visual">
        <div class="hero-card-stack">
          <div class="hero-card hero-card-main">
            <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1.25rem;">
              <div style="width:36px;height:36px;border-radius:10px;background:var(--gradient);display:flex;align-items:center;justify-content:center;"><i class="bi bi-buildings" style="color:#fff;font-size:1rem;"></i></div>
              <div><div style="font-weight:700;font-size:.9rem;">Sunrise Apartments</div><div style="font-size:.72rem;color:rgba(255,255,255,0.5);">Active • 240 Units</div></div>
            </div>
            <div class="metric-row"><span class="metric-label">Maintenance Collected</span><span class="metric-val">₹4.2L <span class="metric-badge badge-green">+12%</span></span></div>
            <div class="metric-row"><span class="metric-label">Pending Bills</span><span class="metric-val">8 <span class="metric-badge badge-blue">Units</span></span></div>
            <div class="metric-row"><span class="metric-label">Visitors Today</span><span class="metric-val">34</span></div>
            <div class="metric-row"><span class="metric-label">Open Complaints</span><span class="metric-val">3</span></div>
          </div>
          <div class="hero-card hero-card-small">
            <div style="font-size:.75rem;font-weight:700;margin-bottom:.4rem;">📢 Notice Published</div>
            <div class="small-stat">Annual AGM on 25th April — All members are requested to attend</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section id="features">
  <div class="container">
    <div class="text-center">
      <span class="section-badge">Features</span>
      <h2 class="section-title">Everything Your Society <span>Needs</span></h2>
      <p class="section-desc">A complete suite of tools to digitize and streamline every aspect of society management.</p>
    </div>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon icon-purple"><i class="bi bi-people-fill"></i></div>
        <h4>Member Management</h4>
        <p>Maintain complete profiles of all residents, owners, and tenants with unit-wise allocation and history.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon icon-green"><i class="bi bi-cash-coin"></i></div>
        <h4>Maintenance Billing</h4>
        <p>Automate monthly maintenance bills, track payments, send reminders, and generate receipts instantly.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon icon-orange"><i class="bi bi-person-badge-fill"></i></div>
        <h4>Visitor Management</h4>
        <p>Log all visitors with entry/exit times, photo capture, host approval, and digital gate pass generation.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon icon-blue"><i class="bi bi-megaphone-fill"></i></div>
        <h4>Notice Board</h4>
        <p>Publish society announcements, meeting notices, and event updates to all residents digitally.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon icon-red"><i class="bi bi-chat-left-dots-fill"></i></div>
        <h4>Complaint Management</h4>
        <p>Allow residents to raise complaints, track resolution status, and improve community satisfaction.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon icon-teal"><i class="bi bi-bar-chart-fill"></i></div>
        <h4>Reports & Analytics</h4>
        <p>Get detailed financial reports, occupancy data, and compliance summaries with one-click exports.</p>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section id="how-it-works">
  <div class="container">
    <div class="text-center">
      <span class="section-badge">Process</span>
      <h2 class="section-title">Get Started in <span>4 Easy Steps</span></h2>
      <p class="section-desc">Onboard your society in minutes and take complete control of your community management.</p>
    </div>
    <div class="steps-grid">
      <div class="step"><div class="step-num">1</div><h4>Register Society</h4><p>Sign up and submit your society details for super admin approval.</p></div>
      <div class="step"><div class="step-num">2</div><h4>Choose a Plan</h4><p>Contact us to select the plan that best fits your society's size and needs.</p></div>
      <div class="step"><div class="step-num">3</div><h4>Onboard Members</h4><p>Add all your residents, units, and admins to the platform.</p></div>
      <div class="step"><div class="step-num">4</div><h4>Go Digital</h4><p>Start managing billing, visitors, complaints, and more — fully online.</p></div>
    </div>
  </div>
</section>

<!-- PLANS -->
<section id="plans">
  <div class="container">
    <div class="text-center">
      <span class="section-badge">Pricing Plans</span>
      <h2 class="section-title">Plans <span>Designed for You</span></h2>
      <p class="section-desc">All plans include our full support team. No hidden charges, no per-unit fees. Contact us to get started.</p>
    </div>
    <div class="plans-grid">
      @forelse($plans as $index => $plan)
      <div class="plan-card {{ $index == 1 ? 'popular' : '' }}">
        @if($index == 1)
        <div class="popular-badge">Most Popular</div>
        @endif
        <div class="plan-name">{{ $plan->name }}</div>
        <div class="plan-desc">{{ $plan->description }}</div>
        <div class="plan-contact">
          <span class="plan-contact-text">Contact Us</span>
          <span class="plan-contact-sub">for pricing</span>
        </div>
        <ul class="plan-features">
          @foreach($plan->features as $feature)
          <li>
            <i class="bi {{ $feature->is_included ? 'bi-check-circle-fill check' : 'bi-x-circle cross' }}"></i>
            {{ $feature->feature_text }}
          </li>
          @endforeach
        </ul>
        <a href="#contact" class="{{ $index == 1 ? 'plan-cta plan-cta-filled' : 'plan-cta plan-cta-outline' }}">
          Contact Sales <i class="bi bi-arrow-right"></i>
        </a>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;color:var(--muted);padding:3rem;">Plans coming soon. Please contact us.</div>
      @endforelse
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact">
  <div class="container">
    <div class="text-center">
      <span class="section-badge">Contact Us</span>
      <h2 class="section-title">Let's Talk About <span>Your Society</span></h2>
      <p class="section-desc">Have questions about which plan suits you? Our team is happy to help you get started.</p>
    </div>
    <div class="contact-grid">
      <div class="contact-info">
        <h3>Get in Touch</h3>
        <p>Reach out to our team for a free consultation. We'll understand your society's needs and recommend the best plan for you — with no obligations.</p>

        <a href="mailto:info@societypro.in" class="contact-item">
          <div class="contact-item-icon"><i class="bi bi-envelope-fill"></i></div>
          <div class="contact-item-text">
            <strong>Email Us</strong>
            <span>info@societypro.in</span>
          </div>
        </a>
        <a href="tel:+919876543210" class="contact-item">
          <div class="contact-item-icon"><i class="bi bi-telephone-fill"></i></div>
          <div class="contact-item-text">
            <strong>Call Us</strong>
            <span>+91 98765 43210</span>
          </div>
        </a>
        <a href="https://wa.me/919876543210" class="contact-item" target="_blank">
          <div class="contact-item-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-whatsapp"></i></div>
          <div class="contact-item-text">
            <strong>WhatsApp</strong>
            <span>+91 98765 43210</span>
          </div>
        </a>
        <div class="contact-item" style="cursor:default;">
          <div class="contact-item-icon" style="background:#fff7ed;color:#ea580c;"><i class="bi bi-geo-alt-fill"></i></div>
          <div class="contact-item-text">
            <strong>Office</strong>
            <span>Ahmedabad, Gujarat, India</span>
          </div>
        </div>
      </div>
      <div>
        @if(session('contact_success'))
        <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('contact_success') }}</div>
        @endif
        <div class="contact-form-card">
          <h4 style="font-weight:700;color:var(--dark);margin-bottom:1.5rem;">Send us a Message</h4>
          <form action="{{ route('society.contact.send') }}" method="POST">
            @csrf
            <div class="form-row">
              <div class="form-group">
                <label>Your Name *</label>
                <input type="text" name="name" class="form-control" placeholder="Rajesh Kumar" required />
              </div>
              <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" />
              </div>
            </div>
            <div class="form-group">
              <label>Email Address *</label>
              <input type="email" name="email" class="form-control" placeholder="rajesh@email.com" required />
            </div>
            <div class="form-group">
              <label>Society Name</label>
              <input type="text" name="society_name" class="form-control" placeholder="Sunrise Apartments" />
            </div>
            <div class="form-group">
              <label>Message *</label>
              <textarea name="message" class="form-control" placeholder="Tell us about your society and what you're looking for..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.8rem;">
              Send Message <i class="bi bi-send-fill"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="brand-name">Society<span>Pro</span></div>
        <p>A complete digital platform for modern housing societies. Trusted by 500+ communities across India.</p>
      </div>
      <div class="footer-links">
        <h5>Product</h5>
        <ul>
          <li><a href="#features">Features</a></li>
          <li><a href="#plans">Plans</a></li>
          <li><a href="#how-it-works">How It Works</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>
      <div class="footer-links">
        <h5>Account</h5>
        <ul>
          <li><a href="{{ route('society.login') }}">Sign In</a></li>
          <li><a href="{{ route('society.register') }}">Register Society</a></li>
          <li><a href="#contact">Get a Demo</a></li>
          <li><a href="mailto:info@societypro.in">Support</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© {{ date('Y') }} SocietyPro. All rights reserved.</p>
      <p>Made with <span style="color:#FF6584;">♥</span> for Indian Communities</p>
    </div>
  </div>
</footer>

<script>
  // Navbar scroll effect
  window.addEventListener('scroll', () => {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 30);
  });
</script>
</body>
</html>
