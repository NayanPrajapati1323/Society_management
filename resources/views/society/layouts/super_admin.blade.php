<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Dashboard') – SocietyPro Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <style>
    :root {
      --primary: #6C63FF; --primary-dark: #574fd6; --primary-light: #ede9ff;
      --sidebar-w: 260px; 
      --sidebar-bg: linear-gradient(160deg, #0f0e17 0%, #1a1a2e 50%, #16213e 100%);
      --sidebar-hover: rgba(108,99,255,.12);
      --topbar-h: 64px; --dark: #111827; --muted: #6b7280; --border: #e5e7eb;
      --bg: #f4f6fa; --card-bg: #ffffff;
      --gradient: linear-gradient(135deg, #6C63FF 0%, #43e97b 100%);
      --gradient-sidebar: linear-gradient(135deg, #6C63FF 0%, #574fd6 100%);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: var(--bg); display: flex; min-height: 100vh; color: var(--dark); }

    /* ── SIDEBAR ── */
    .sidebar {
      width: var(--sidebar-w); background: var(--sidebar-bg); min-height: 100vh;
      position: fixed; top: 0; left: 0; z-index: 100; display: flex; flex-direction: column;
      transition: transform .3s;
      border-right: 1px solid rgba(255,255,255,0.05);
    }
    .sidebar::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(circle at 0% 0%, rgba(108,99,255,0.15) 0%, transparent 50%);
      pointer-events: none;
    }

    /* ── PREMIUM MODAL REDESIGN ── */
    .modal-overlay { 
      position: fixed; inset: 0; background: rgba(15, 14, 23, 0.7); 
      display: flex; align-items: center; justify-content: center; 
      z-index: 1000; backdrop-filter: blur(12px);
      opacity: 0; visibility: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .modal-overlay.show { opacity: 1; visibility: visible; }
    
    .modal-container { 
      background: #ffffff; border-radius: 28px; width: 95%; max-width: 680px; 
      max-height: 90vh; display: flex; flex-direction: column; 
      box-shadow: 0 40px 100px rgba(0,0,0,0.3);
      border: 1px solid rgba(255,255,255,0.1);
      transform: scale(0.92) translateY(20px); transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal-overlay.show .modal-container { transform: scale(1) translateY(0); }
    
    .modal-header { 
      padding: 1.5rem 2rem; border-bottom: 1px solid #f3f4f6; 
      display: flex; justify-content: space-between; align-items: center;
      flex-shrink: 0;
    }
    .modal-header h3 { font-size: 1.25rem; font-weight: 800; color: var(--dark); margin:0; display: flex; align-items: center; gap: .75rem; }
    
    .modal-container form { display: flex; flex-direction: column; flex: 1; overflow: hidden; }

    .modal-body { 
      padding: 2rem; overflow-y: auto; flex: 1;
      scrollbar-width: thin; 
    }
    .modal-body::-webkit-scrollbar { width: 6px; }
    .modal-body::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    
    .modal-footer { 
      padding: 1.25rem 2rem; border-top: 1px solid #f3f4f6; 
      display: flex; gap: 1rem; background: #fafafa; flex-shrink: 0;
      border-radius: 28px;
    }

    /* Choice Cards (Radio alternative) */
    .choice-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: .5rem; }
    .choice-card { 
      cursor: pointer; border: 2px solid #e5e7eb; border-radius: 16px; padding: 1.25rem;
      display: flex; flex-direction: column; align-items: center; text-align: center; gap: .5rem;
      transition: all .2s; position: relative;
    }
    .choice-card:hover { border-color: var(--primary); background: #f9f9ff; }
    .choice-card input { position: absolute; opacity: 0; }
    .choice-card i { font-size: 1.5rem; color: var(--muted); }
    .choice-card div { font-size: .9rem; font-weight: 700; color: var(--dark); }
    .choice-card input:checked + .choice-inner { color: var(--primary); }
    .choice-card:has(input:checked) { border-color: var(--primary); background: var(--primary-light); }
    .choice-card:has(input:checked) i { color: var(--primary); }
    
    /* Better Inputs */
    .form-control { 
      background: #fdfdfd; border: 1.5px solid #e5e7eb; padding: .75rem 1rem; border-radius: 12px;
      font-size: .92rem; transition: all .2s;
    }
    .form-control:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(108,99,255,0.12); }
    .form-group label { font-size: .85rem; font-weight: 700; color: #4b5563; margin-bottom: .4rem; }

    .sidebar-brand {
      padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: .75rem;
      border-bottom: 1px solid rgba(255,255,255,.07); text-decoration: none;
    }
    .sidebar-brand-icon { width: 38px; height: 38px; border-radius: 10px; background: var(--gradient); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem; flex-shrink: 0; }
    .sidebar-brand-name { font-size: 1.1rem; font-weight: 800; color: #fff; }
    .sidebar-brand-name span { color: #43e97b; }
    .sidebar-section { padding: .5rem 0; }
    .sidebar-label { padding: .5rem 1.5rem .25rem; font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.3); }
    .sidebar-link {
      display: flex; align-items: center; gap: .75rem;
      padding: .65rem 1.5rem; color: rgba(255,255,255,.65); text-decoration: none;
      font-size: .875rem; font-weight: 500; transition: all .2s; position: relative;
    }
    .sidebar-link:hover { background: var(--sidebar-hover); color: #fff; }
    .sidebar-link.active { background: var(--gradient); color: #fff; }
    .sidebar-link.active::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: #43e97b; border-radius: 0 3px 3px 0; }
    .sidebar-link i { font-size: 1.1rem; width: 22px; text-align: center; }
    .sidebar-link .badge { margin-left: auto; background: rgba(255,255,255,.15); color: #fff; font-size: .65rem; padding: .2rem .5rem; border-radius: 20px; font-weight: 600; }
    .sidebar-footer { margin-top: auto; padding: 1.25rem 1.5rem; border-top: 1px solid rgba(255,255,255,.07); }
    .user-mini { display: flex; align-items: center; gap: .75rem; }
    .user-avatar { width: 36px; height: 36px; border-radius: 10px; background: var(--gradient); display: flex; align-items: center; justify-content: center; color: #fff; font-size: .85rem; font-weight: 700; flex-shrink: 0; }
    .user-info strong { display: block; font-size: .82rem; font-weight: 700; color: #fff; }
    .user-info span { font-size: .72rem; color: rgba(255,255,255,.45); }
    .sidebar-logout { margin-top: .75rem; display: flex; align-items: center; gap: .5rem; color: rgba(255,255,255,.5); font-size: .8rem; text-decoration: none; padding: .4rem .5rem; border-radius: 8px; transition: all .2s; }
    .sidebar-logout:hover { color: #fc5c7d; background: rgba(252,92,125,.1); }

    /* ── MAIN ── */
    .main-content { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

    /* ── TOPBAR ── */
    .topbar { height: var(--topbar-h); background: var(--card-bg); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; position: sticky; top: 0; z-index: 50; }
    .topbar-left { display: flex; align-items: center; gap: 1rem; }
    .page-title { font-size: 1.1rem; font-weight: 700; color: var(--dark); }
    .page-breadcrumb { font-size: .78rem; color: var(--muted); }
    .topbar-right { display: flex; align-items: center; gap: 1rem; }
    .topbar-btn { width: 38px; height: 38px; border-radius: 10px; border: 1px solid var(--border); background: #fff; display: flex; align-items: center; justify-content: center; color: var(--muted); cursor: pointer; transition: all .2s; text-decoration: none; }
    .topbar-btn:hover { border-color: var(--primary); color: var(--primary); }
    .topbar-user { display: flex; align-items: center; gap: .6rem; }
    .topbar-user-name { font-size: .85rem; font-weight: 600; color: var(--dark); }
    .topbar-user-role { font-size: .72rem; color: var(--muted); }

    /* ── PAGE CONTENT ── */
    .page-content { padding: 2rem; flex: 1; }

    /* ── STATS CARDS ── */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
    .stat-card { background: var(--card-bg); border-radius: 16px; padding: 1.4rem 1.5rem; border: 1px solid var(--border); display: flex; align-items: center; gap: 1rem; transition: all .3s; }
    .stat-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,.08); transform: translateY(-2px); }
    .stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #fff; flex-shrink: 0; }
    .stat-icon-1 { background: var(--gradient); }
    .stat-icon-2 { background: var(--gradient2); }
    .stat-icon-3 { background: var(--gradient3); }
    .stat-icon-4 { background: var(--gradient4); }
    .stat-icon-5 { background: linear-gradient(135deg,#4facfe,#00f2fe); }
    .stat-icon-6 { background: linear-gradient(135deg,#a18cd1,#fbc2eb); }
    .stat-icon-7 { background: linear-gradient(135deg,#fd746c,#ff9068); }
    .stat-icon-8 { background: linear-gradient(135deg,#16a085,#1abc9c); }
    .stat-body {}
    .stat-value { font-size: 1.7rem; font-weight: 800; color: var(--dark); line-height: 1; margin-bottom: .2rem; }
    .stat-label { font-size: .78rem; font-weight: 500; color: var(--muted); }

    /* ── CARDS ── */
    .card { background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border); overflow: hidden; }
    .card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .card-title { font-size: .95rem; font-weight: 700; color: var(--dark); }
    .card-body { padding: 1.5rem; }
    .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

    /* ── TABLE ── */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: .85rem; }
    thead th { padding: .75rem 1rem; background: #f9fafb; font-weight: 700; font-size: .78rem; color: var(--muted); text-align: left; text-transform: uppercase; letter-spacing: .05em; border-bottom: 1px solid var(--border); white-space: nowrap; }
    tbody td { padding: .85rem 1rem; border-bottom: 1px solid #f3f4f6; color: var(--dark); vertical-align: middle; }
    tbody tr:hover { background: #fafafa; }
    tbody tr:last-child td { border-bottom: none; }
    .table-name { font-weight: 600; }
    .table-sub { font-size: .75rem; color: var(--muted); }

    /* ── BADGES ── */
    .badge { display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .7rem; border-radius: 20px; font-size: .72rem; font-weight: 700; }
    .badge-active { background: #dcfce7; color: #15803d; }
    .badge-pending { background: #fff7ed; color: #c2410c; }
    .badge-inactive { background: #f3f4f6; color: #6b7280; }
    .badge-role-1 { background: #ede9ff; color: #6C63FF; }
    .badge-role-2 { background: #fff7ed; color: #c2410c; }
    .badge-role-3 { background: #f0fdf4; color: #15803d; }

    /* ── BUTTONS ── */
    .btn { display: inline-flex; align-items: center; gap: .35rem; padding: .5rem 1.1rem; border-radius: 8px; font-size: .82rem; font-weight: 600; cursor: pointer; transition: all .2s; border: none; text-decoration: none; font-family: inherit; }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-primary:hover { background: var(--primary-dark); }
    .btn-success { background: #16a34a; color: #fff; }
    .btn-success:hover { background: #15803d; }
    .btn-danger { background: #ef4444; color: #fff; }
    .btn-danger:hover { background: #dc2626; }
    .btn-warning { background: #f59e0b; color: #fff; }
    .btn-warning:hover { background: #d97706; }
    .btn-outline { background: transparent; border: 1.5px solid var(--border); color: var(--dark); }
    .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
    .btn-sm { padding: .35rem .75rem; font-size: .78rem; }

    /* ── ALERTS ── */
    .alert { padding: .85rem 1.1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: .875rem; display: flex; align-items: center; gap: .55rem; }
    .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .alert-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
    .alert-warning { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }

    /* ── SEARCH / FILTER BAR ── */
    .filter-bar { display: flex; gap: .75rem; align-items: center; flex-wrap: wrap; }
    .filter-bar input, .filter-bar select { padding: .5rem .9rem; border-radius: 8px; border: 1.5px solid var(--border); font-size: .85rem; font-family: inherit; color: var(--dark); background: #fff; outline: none; }
    .filter-bar input:focus, .filter-bar select:focus { border-color: var(--primary); }

    /* ── FORMS ── */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    .form-group { margin-bottom: .5rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label { display: block; font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
    .form-control { width: 100%; padding: .65rem .9rem; border-radius: 8px; border: 1.5px solid var(--border); font-size: .88rem; font-family: inherit; color: var(--dark); background: #fff; transition: all .2s; outline: none; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(108,99,255,.1); }
    textarea.form-control { resize: vertical; min-height: 90px; }

    /* ── TOGGLE ── */
    .toggle-switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .toggle-switch input { display: none; }
    .toggle-slider { position: absolute; inset: 0; background: #d1d5db; border-radius: 24px; cursor: pointer; transition: .3s; }
    .toggle-slider::before { content: ''; position: absolute; width: 18px; height: 18px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: .3s; }
    input:checked + .toggle-slider { background: #16a34a; }
    input:checked + .toggle-slider::before { transform: translateX(20px); }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .main-content { margin-left: 0; }
      .two-col, .form-grid { grid-template-columns: 1fr; }
      .stats-grid { grid-template-columns: 1fr 1fr; }
    }
  </style>
  @yield('extra-styles')
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <a href="{{ route('society.landing') }}" class="sidebar-brand">
    <div class="sidebar-brand-icon"><i class="bi bi-buildings-fill"></i></div>
    <span class="sidebar-brand-name">Society<span>Pro</span></span>
  </a>

  <div class="sidebar-section">
    <div class="sidebar-label">Main</div>
    <a href="{{ route('super-admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>
  </div>

  <div class="sidebar-section">
    <div class="sidebar-label">Management</div>
    <a href="{{ route('super-admin.societies') }}" class="sidebar-link {{ request()->routeIs('super-admin.societies*') ? 'active' : '' }}">
      <i class="bi bi-buildings"></i> Societies
      <span class="badge">{{ \App\Models\Society::count() }}</span>
    </a>
    <a href="{{ route('super-admin.users') }}" class="sidebar-link {{ request()->routeIs('super-admin.users*') ? 'active' : '' }}">
      <i class="bi bi-people"></i> Users
      <span class="badge">{{ \App\Models\User::where('role_id','!=',1)->count() }}</span>
    </a>
    <a href="{{ route('super-admin.plans') }}" class="sidebar-link {{ request()->routeIs('super-admin.plans*') ? 'active' : '' }}">
      <i class="bi bi-layers"></i> Plans
    </a>
    <a href="{{ route('super-admin.settings') }}" class="sidebar-link {{ request()->routeIs('super-admin.settings*') ? 'active' : '' }}">
      <i class="bi bi-gear"></i> Settings
    </a>
  </div>

  <div class="sidebar-section">
    <div class="sidebar-label">System</div>
    <a href="{{ route('society.landing') }}" class="sidebar-link" target="_blank">
      <i class="bi bi-globe2"></i> View Landing Page
    </a>
  </div>

  <div class="sidebar-footer">
    <div class="user-mini">
      <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
      <div class="user-info">
        <strong>{{ auth()->user()->name }}</strong>
        <span>Super Admin</span>
      </div>
    </div>
    <form action="{{ route('society.logout') }}" method="POST">
      @csrf
      <button type="submit" class="sidebar-logout" style="background:none;border:none;cursor:pointer;width:100%;text-align:left;">
        <i class="bi bi-box-arrow-left"></i> Sign Out
      </button>
    </form>
  </div>
</aside>

<!-- MAIN -->
<div class="main-content">
  <!-- TOPBAR -->
  <div class="topbar">
    <div class="topbar-left">
      <div>
        <div class="page-title">@yield('page-title', 'Dashboard')</div>
        <div class="page-breadcrumb">@yield('breadcrumb', 'Super Admin')</div>
      </div>
    </div>
    <div class="topbar-right">
      <a href="{{ route('society.landing') }}" class="topbar-btn" title="View Site" target="_blank">
        <i class="bi bi-globe2"></i>
      </a>
      <div class="topbar-user">
        <div class="user-avatar" style="width:36px;height:36px;font-size:.85rem;">{{ substr(auth()->user()->name, 0, 1) }}</div>
        <div>
          <div class="topbar-user-name">{{ auth()->user()->name }}</div>
          <div class="topbar-user-role">Super Administrator</div>
        </div>
      </div>
    </div>
  </div>

  <!-- PAGE CONTENT -->
  <div class="page-content">
    @if(session('success'))
    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif

    @yield('content')
  </div>
</div>

@yield('scripts')
</body>
</html>
