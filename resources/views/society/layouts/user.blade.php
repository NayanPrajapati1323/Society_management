<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Dashboard') – SocietyPro Resident</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <style>
    :root {
      --primary: #6C63FF;
      --primary-light: #ede9ff;
      --bg: #f8fafc;
      --sidebar-bg: #0f172a;
      --card-bg: #ffffff;
      --text-main: #1e293b;
      --text-muted: #64748b;
      --border: #e2e8f0;
      --success: #10b981;
      --danger: #ef4444;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); display: flex; min-height: 100vh; }

    /* Sidebar */
    .sidebar { width: 260px; background: var(--sidebar-bg); color: #fff; display: flex; flex-direction: column; position: fixed; height: 100vh; }
    .sidebar-header { padding: 2rem 1.5rem; display: flex; align-items: center; gap: .75rem; }
    .brand-icon { width: 32px; height: 32px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .nav-list { list-style: none; padding: 0 1rem; flex: 1; }
    .nav-item { margin-bottom: .5rem; }
    .nav-link { 
      display: flex; align-items: center; gap: .75rem; padding: .8rem 1rem; 
      text-decoration: none; color: #94a3b8; border-radius: 10px; transition: all .2s; 
      font-size: .9rem; font-weight: 500;
    }
    .nav-link:hover, .nav-link.active { background: #1e293b; color: #fff; }
    .nav-link.active { background: var(--primary); color: #fff; }

    /* Main Content */
    .main-wrap { flex: 1; margin-left: 260px; padding: 2rem; }
    .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .page-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; }

    /* Cards & UI */
    .card { background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .p-all { padding: 1.5rem; }
    .btn { padding: .6rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all .2s; border: none; font-size: .9rem; display: inline-flex; align-items: center; gap: .5rem; text-decoration: none; }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-primary:hover { opacity: .9; transform: translateY(-1px); }
    .btn-outline { border: 1px solid var(--border); background: #fff; color: var(--text-main); }
    .btn-outline:hover { background: #f1f5f9; }

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    .stat-card { padding: 1.5rem; }
    .stat-val { font-size: 1.75rem; font-weight: 800; margin: .5rem 0; color: #0f172a; }
    .stat-label { font-size: .85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: .025em; }

    .table { width: 100%; border-collapse: collapse; }
    .table th { text-align: left; padding: 1rem; font-size: .75rem; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--border); }
    .table td { padding: 1rem; font-size: .9rem; border-bottom: 1px solid var(--border); }
    .badge { padding: .25rem .75rem; border-radius: 20px; font-size: .75rem; font-weight: 700; }
    .badge-success { background: #dcfce7; color: #15803d; }
    .badge-danger { background: #fee2e2; color: #b91c1c; }

    /* Modal */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 2000; }
    .modal-container { background: #fff; border-radius: 20px; width: 90%; max-width: 500px; padding: 2rem; }
    .form-group { margin-bottom: 1.25rem; }
    .form-group label { display: block; font-size: .85rem; font-weight: 700; color: #0f172a; margin-bottom: .5rem; }
    .form-control { width: 100%; padding: .75rem 1rem; border-radius: 12px; border: 1px solid var(--border); outline: none; font-family: inherit; }
    .form-control:focus { border-color: var(--primary); }

    .alert { padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: .9rem; }
    .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
  </style>
</head>
<body id="user-body">
  <div class="sidebar">
    <div class="sidebar-header">
      <div class="brand-icon"><i class="bi bi-person-fill"></i></div>
      <span style="font-weight: 800; font-size: 1.2rem; letter-spacing: -0.5px;">Resident<span>Pro</span></span>
    </div>
    <ul class="nav-list">
      <li class="nav-item">
        <a href="{{ route('society.user.dashboard') }}" class="nav-link {{ Route::is('society.user.dashboard') ? 'active' : '' }}">
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('society.user.passbook') }}" class="nav-link {{ Route::is('society.user.passbook') ? 'active' : '' }}">
          <i class="bi bi-journal-text"></i> Resident Passbook
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('society.user.settings') }}" class="nav-link {{ Route::is('society.user.settings') ? 'active' : '' }}">
          <i class="bi bi-shield-lock"></i> Security Settings
        </a>
      </li>
    </ul>
    <div style="padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);">
      <div style="display: flex; align-items: center; gap: .75rem; margin-bottom: 1.5rem;">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: #334155; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #94a3b8;">
          {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div style="font-size: .85rem;">
          <div style="font-weight: 700; color: #fff;">{{ Auth::user()->name }}</div>
          <div style="color: #64748b; font-size: .75rem;">Unit {{ Auth::user()->unit_number }}</div>
        </div>
      </div>
      <form action="{{ route('society.logout') }}" method="POST">
        @csrf
        <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; cursor: pointer;">
          <i class="bi bi-box-arrow-left"></i> Sign Out
        </button>
      </form>
    </div>
  </div>

  <div class="main-wrap">
    <div class="top-bar">
      <div>
        <h1 class="page-title">@yield('page-title', 'Welcome back')</h1>
        <p style="color: var(--text-muted); font-size: .9rem;">{{ Auth::user()->society->name }} • {{ Auth::user()->society->city }}</p>
      </div>
      <div style="display: flex; gap: 1rem; align-items: center;">
        <div class="badge badge-success">Approved Resident</div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    @yield('content')
  </div>

  <script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
  </script>
  @yield('scripts')
</body>
</html>
