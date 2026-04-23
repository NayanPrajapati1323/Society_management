<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In – SocietyPro</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <style>
    :root {
      --primary: #6C63FF; --primary-dark: #574fd6; --primary-light: #ede9ff;
      --dark: #0f0e17; --dark2: #1a1a2e; --muted: #6b7280;
      --border: #e5e7eb; --gradient: linear-gradient(135deg,#6C63FF,#43e97b);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; background: #f8f7ff; }

    .auth-left {
      width: 45%;
      background: linear-gradient(160deg, #0f0e17 0%, #1a1a2e 50%, #16213e 100%);
      display: flex; flex-direction: column; justify-content: center; align-items: center;
      padding: 3rem; position: relative; overflow: hidden;
    }
    .auth-left::before {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at 30% 50%, rgba(108,99,255,0.35) 0%, transparent 60%),
                  radial-gradient(ellipse at 80% 80%, rgba(67,233,123,0.15) 0%, transparent 50%);
    }
    .auth-left-content { position: relative; z-index: 1; text-align: center; max-width: 380px; }
    .auth-brand { display: inline-flex; align-items: center; gap: .6rem; text-decoration: none; margin-bottom: 2.5rem; }
    .auth-brand-icon { width: 44px; height: 44px; border-radius: 12px; background: var(--gradient); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.3rem; }
    .auth-brand-name { font-size: 1.4rem; font-weight: 800; color: #fff; }
    .auth-brand-name span { color: #43e97b; }
    .auth-left h2 { color: #fff; font-size: 1.8rem; font-weight: 800; margin-bottom: 1rem; line-height: 1.3; }
    .auth-left p { color: rgba(255,255,255,.6); font-size: .9rem; line-height: 1.7; margin-bottom: 2rem; }
    .auth-feature { display: flex; align-items: center; gap: .75rem; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1); border-radius: 12px; padding: .85rem 1rem; margin-bottom: .6rem; text-align: left; }
    .auth-feature-icon { width: 32px; height: 32px; border-radius: 8px; background: rgba(108,99,255,.4); display: flex; align-items: center; justify-content: center; color: #a5a0ff; font-size: .9rem; flex-shrink: 0; }
    .auth-feature-text { color: rgba(255,255,255,.8); font-size: .82rem; font-weight: 500; }

    .auth-right {
      flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center;
      padding: 3rem 2rem; background: #fff;
    }
    .auth-form-wrapper { width: 100%; max-width: 420px; }
    .auth-form-wrapper h3 { font-size: 1.7rem; font-weight: 800; color: var(--dark); margin-bottom: .35rem; }
    .auth-form-wrapper p.subtitle { color: var(--muted); font-size: .9rem; margin-bottom: 2rem; }
    .form-group { margin-bottom: 1.1rem; }
    .form-group label { display: block; font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
    .input-wrap { position: relative; }
    .input-wrap .input-icon { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 1rem; }
    .form-control { width: 100%; padding: .7rem 1rem .7rem 2.5rem; border-radius: 10px; border: 1.5px solid var(--border); font-size: .9rem; font-family: inherit; color: #1f2937; background: #fafafa; transition: all .2s; outline: none; }
    .form-control:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(108,99,255,.1); }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback { color: #ef4444; font-size: .78rem; margin-top: .25rem; }
    .form-check { display: flex; align-items: center; gap: .5rem; }
    .form-check input { accent-color: var(--primary); width: 15px; height: 15px; }
    .form-check label { font-size: .83rem; color: var(--muted); cursor: pointer; }
    .forgot { font-size: .83rem; color: var(--primary); text-decoration: none; font-weight: 600; }
    .forgot:hover { text-decoration: underline; }
    .btn-submit { width: 100%; padding: .8rem; background: var(--gradient); color: #fff; border: none; border-radius: 10px; font-size: .95rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: all .25s; margin-top: .75rem; }
    .btn-submit:hover { opacity: .9; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(108,99,255,.35); }
    .divider { display: flex; align-items: center; gap: .75rem; margin: 1.5rem 0; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
    .divider span { color: var(--muted); font-size: .8rem; }
    .auth-link { text-align: center; font-size: .88rem; color: var(--muted); margin-top: 1.25rem; }
    .auth-link a { color: var(--primary); font-weight: 700; text-decoration: none; }
    .auth-link a:hover { text-decoration: underline; }
    .alert { padding: .8rem 1rem; border-radius: 10px; margin-bottom: 1rem; font-size: .85rem; display: flex; align-items: center; gap: .5rem; }
    .alert-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
    @media (max-width: 768px) { .auth-left { display: none; } .auth-right { padding: 2rem 1.5rem; } }
  </style>
</head>
<body>
<div class="auth-left">
  <div class="auth-left-content">
    <a href="{{ route('society.landing') }}" class="auth-brand">
      <div class="auth-brand-icon"><i class="bi bi-buildings-fill"></i></div>
      <span class="auth-brand-name">Society<span>Pro</span></span>
    </a>
    <h2>Manage Your Society Smarter</h2>
    <p>Log in to access your society dashboard and manage everything from one place.</p>
    <div class="auth-feature">
      <div class="auth-feature-icon"><i class="bi bi-cash-coin"></i></div>
      <div class="auth-feature-text">Automated maintenance billing & payment tracking</div>
    </div>
    <div class="auth-feature">
      <div class="auth-feature-icon"><i class="bi bi-people-fill"></i></div>
      <div class="auth-feature-text">Member & flat management with full history</div>
    </div>
    <div class="auth-feature">
      <div class="auth-feature-icon"><i class="bi bi-bar-chart-fill"></i></div>
      <div class="auth-feature-text">Real-time reports and analytics dashboard</div>
    </div>
  </div>
</div>

<div class="auth-right">
  <div class="auth-form-wrapper">
    <h3>Welcome Back 👋</h3>
    <p class="subtitle">Sign in to your SocietyPro account</p>

    @if($errors->any())
    <div class="alert alert-danger">
      <i class="bi bi-exclamation-triangle-fill"></i>
      {{ $errors->first() }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
      <i class="bi bi-exclamation-triangle-fill"></i>
      {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('society.login.post') }}">
      @csrf
      <div class="form-group">
        <label for="email">Email Address</label>
        <div class="input-wrap">
          <i class="bi bi-envelope-fill input-icon"></i>
          <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                 placeholder="you@example.com" value="{{ old('email') }}" required autofocus />
        </div>
      </div>
      <div class="form-group">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.35rem;">
          <label for="password" style="margin-bottom:0;">Password</label>
          <a href="#" class="forgot">Forgot password?</a>
        </div>
        <div class="input-wrap">
          <i class="bi bi-lock-fill input-icon"></i>
          <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required />
        </div>
      </div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.25rem;">
        <div class="form-check">
          <input type="checkbox" id="remember" name="remember" />
          <label for="remember">Remember me</label>
        </div>
      </div>
      <button type="submit" class="btn-submit">
        <i class="bi bi-box-arrow-in-right"></i> Sign In
      </button>
    </form>

    <div class="auth-link">
      Don't have an account? <a href="{{ route('society.register') }}">Create one</a>
    </div>
    <div class="auth-link" style="margin-top:.5rem;">
      <a href="{{ route('society.landing') }}"><i class="bi bi-arrow-left"></i> Back to Home</a>
    </div>
  </div>
</div>
</body>
</html>
