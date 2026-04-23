<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Dashboard – SocietyPro</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <style>
    :root { --primary:#6C63FF; --gradient:linear-gradient(135deg,#6C63FF,#43e97b); }
    * { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:'Inter',sans-serif; min-height:100vh; background:linear-gradient(160deg,#0f0e17,#1a1a2e,#16213e); display:flex; align-items:center; justify-content:center; }
    .card { background:rgba(255,255,255,.06); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,.1); border-radius:24px; padding:3rem; max-width:500px; width:90%; text-align:center; }
    .icon { width:72px; height:72px; border-radius:20px; background:var(--gradient); display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; font-size:2rem; color:#fff; }
    h2 { color:#fff; font-size:1.6rem; font-weight:800; margin-bottom:.5rem; }
    p { color:rgba(255,255,255,.6); font-size:.9rem; line-height:1.7; }
    .badge { display:inline-block; background:rgba(108,99,255,.25); color:#a5a0ff; border:1px solid rgba(108,99,255,.4); padding:.3rem .9rem; border-radius:20px; font-size:.78rem; font-weight:600; margin:1.25rem 0; }
    .btn { display:inline-flex; align-items:center; gap:.4rem; padding:.7rem 1.5rem; border-radius:12px; font-size:.9rem; font-weight:700; cursor:pointer; text-decoration:none; border:none; font-family:inherit; transition:all .2s; }
    .btn-primary { background:var(--gradient); color:#fff; }
    .btn-outline { background:transparent; border:1.5px solid rgba(255,255,255,.25); color:rgba(255,255,255,.7); margin-top:.5rem; }
    .actions { display:flex; flex-direction:column; align-items:center; gap:.5rem; margin-top:1.5rem; }
  </style>
</head>
<body>
  <div class="card">
    <div class="icon"><i class="bi bi-person-check-fill"></i></div>
    <div class="badge">Logged in as <strong>{{ auth()->user()->role->display_name ?? 'User' }}</strong></div>
    <h2>Welcome, {{ auth()->user()->name }}! 👋</h2>
    <p>You're successfully logged in to SocietyPro. Your society dashboard is being set up. Please contact your Society Admin for more details.</p>
    @if(auth()->user()->society)
    <p style="margin-top:1rem;color:rgba(255,255,255,.8);">
      <i class="bi bi-buildings-fill" style="color:#43e97b;"></i>
      <strong style="color:#fff;">{{ auth()->user()->society->name }}</strong>
    </p>
    @endif
    <div class="actions">
      <form action="{{ route('society.logout') }}" method="POST" style="width:100%;text-align:center;">
        @csrf
        <button type="submit" class="btn btn-outline" style="width:100%;justify-content:center;">
          <i class="bi bi-box-arrow-left"></i> Sign Out
        </button>
      </form>
      <a href="{{ route('society.landing') }}" class="btn" style="color:rgba(255,255,255,.5);font-size:.82rem;text-decoration:underline;">Back to Home</a>
    </div>
  </div>
</body>
</html>
