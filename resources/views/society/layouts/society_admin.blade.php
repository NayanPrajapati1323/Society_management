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
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: var(--bg); display: flex; min-height: 100vh; color: var(--dark); }

    /* ── SIDEBAR ── */
    .sidebar { width: var(--sidebar-w); background: var(--sidebar-bg); min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 100; display: flex; flex-direction: column; border-right: 1px solid rgba(255,255,255,0.05); }
    .sidebar-brand { padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: .75rem; border-bottom: 1px solid rgba(255,255,255,.07); text-decoration: none; }
    .sidebar-brand-icon { width: 38px; height: 38px; border-radius: 10px; background: var(--gradient); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem; flex-shrink: 0; }
    .sidebar-brand-name { font-size: 1.1rem; font-weight: 800; color: #fff; }
    .sidebar-brand-name span { color: #43e97b; }
    .sidebar-section { padding: .5rem 0; }
    .sidebar-label { padding: .5rem 1.5rem .25rem; font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.3); }
    .sidebar-link { display: flex; align-items: center; gap: .75rem; padding: .65rem 1.5rem; color: rgba(255,255,255,.65); text-decoration: none; font-size: .875rem; font-weight: 500; transition: all .2s; }
    .sidebar-link:hover { background: var(--sidebar-hover); color: #fff; }
    .sidebar-link.active { background: var(--primary); color: #fff; }
    .sidebar-link i { font-size: 1.1rem; width: 22px; text-align: center; }

    /* ── MAIN ── */
    .main-content { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; width: calc(100% - var(--sidebar-w)); }
    .topbar { height: var(--topbar-h); background: var(--card-bg); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 50; }
    .page-title { font-size: 1.1rem; font-weight: 800; color: var(--dark); display: flex; align-items: center; gap: .75rem; }
    .page-content { padding: 1.5rem; flex: 1; overflow-x: hidden; }

    /* ── CARS & UI ── */
    .card { background: #fff; border-radius: 20px; border: 1px solid var(--border); overflow: hidden; margin-bottom: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
    .card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .card-title { font-size: 1rem; font-weight: 800; color: var(--dark); margin:0; }
    .card-body { padding: 0; } /* Set default padding to 0 for tables, can be overriden */
    .card-body.p-all { padding: 1.5rem; }
    
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
    .stat-card { background: #fff; border-radius: 16px; padding: 1.5rem; border: 1px solid var(--border); display: flex; align-items: center; gap: 1rem; }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: #fff; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--dark); }
    .stat-label { font-size: .78rem; font-weight: 500; color: var(--muted); }

    .btn { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1.1rem; border-radius: 10px; font-size: .82rem; font-weight: 700; cursor: pointer; transition: all .2s; border: none; text-decoration: none; font-family: inherit; }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-outline { background: #fff; border: 1.5px solid var(--border); color: var(--dark); }
    .btn-sm { padding: .35rem .75rem; font-size: .78rem; }

    .alert { padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: .88rem; display: flex; align-items: center; gap: .5rem; }
    .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }

    /* ── MODALS ── */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15, 14, 23, 0.6); display: flex; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(8px); opacity: 0; visibility: hidden; transition: all 0.3s; }
    .modal-overlay.show { opacity: 1; visibility: visible; }
    .modal-container { background: #fff; border-radius: 24px; width: 95%; max-width: 600px; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column; transform: translateY(20px); transition: all 0.3s; }
    .modal-overlay.show .modal-container { transform: translateY(0); }
    .modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
    .modal-body { padding: 1.5rem; overflow-y: auto; }
    .modal-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--border); background: #f9fafb; display: flex; justify-content: flex-end; gap: .75rem; }

    .form-group { margin-bottom: 1.25rem; }
    .form-group label { display: block; font-size: .82rem; font-weight: 700; color: #374151; margin-bottom: .4rem; }
    .form-control, .form-select { width: 100%; padding: .65rem .9rem; border-radius: 10px; border: 1.5px solid var(--border); font-size: .88rem; font-family: inherit; outline: none; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(108,99,255,.1); }

    .sidebar-footer { margin-top: auto; padding: 1.25rem 1.5rem; border-top: 1px solid rgba(255,255,255,.07); }
    .user-mini { display: flex; align-items: center; gap: .75rem; color: #fff; margin-bottom: 1rem; }
    .user-avatar { width: 36px; height: 36px; border-radius: 10px; background: var(--gradient); display: flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 800; }

    /* ── TABLE ── */
    .table-wrap { overflow-x: auto; width: 100%; }
    table { width: 100%; border-collapse: collapse; font-size: .85rem; background: #fff; }
    thead th { padding: 1rem; background: #f8fafc; font-weight: 700; font-size: .75rem; color: #64748b; text-align: left; text-transform: uppercase; letter-spacing: .05em; border-bottom: 1px solid var(--border); white-space: nowrap; }
    tbody td { padding: 1rem; border-bottom: 1px solid #f1f5f9; color: var(--dark); vertical-align: middle; }
    tbody tr:hover { background: #f8fafc; }
    tbody tr:last-child td { border-bottom: none; }

    /* ── BADGES ── */
    .badge { display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .7rem; border-radius: 20px; font-size: .72rem; font-weight: 700; }
    .badge-active { background: #dcfce7; color: #15803d; }
    .badge-pending { background: #fff7ed; color: #c2410c; }
    .badge-inactive { background: #f3f4f6; color: #6b7280; }
    .badge-role-1 { background: #ede9ff; color: #6C63FF; }
    .badge-role-2 { background: #fff7ed; color: #c2410c; }
    .badge-role-3 { background: #f0fdf4; color: #15803d; }
  </style>
  @yield('extra-styles')
</head>
<body>

<aside class="sidebar">
  <a href="{{ route('society.landing') }}" class="sidebar-brand">
    <div class="sidebar-brand-icon"><i class="bi bi-buildings-fill"></i></div>
    <span class="sidebar-brand-name">Society<span>Pro</span></span>
  </a>

  <div class="sidebar-section">
    <div class="sidebar-label">PANEL</div>
    <a href="{{ route('society-admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('society-admin.dashboard') ? 'active' : '' }}">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>
  </div>

  <div class="sidebar-section">
    <div class="sidebar-label">MANAGEMENT</div>
    <a href="{{ route('society-admin.structure') }}" class="sidebar-link {{ request()->routeIs('society-admin.structure*') ? 'active' : '' }}">
      <i class="bi bi-diagram-3"></i> Structure Design
    </a>
    <a href="{{ route('society-admin.users') }}" class="sidebar-link {{ request()->routeIs('society-admin.users*') ? 'active' : '' }}">
      <i class="bi bi-people"></i> User Approvals
      @php $pending = \App\Models\User::where('society_id', auth()->user()->society_id)->where('is_approved', false)->count(); @endphp
      @if($pending > 0)<span style="margin-left:auto; background:#ef4444; color:#fff; font-size:.65rem; padding:.1rem .4rem; border-radius:10px;">{{ $pending }}</span>@endif
    </a>
    <a href="{{ route('society-admin.maintenance') }}" class="sidebar-link {{ request()->routeIs('society-admin.maintenance*') ? 'active' : '' }}">
      <i class="bi bi-receipt"></i> Maintenance
    </a>
    <a href="{{ route('society-admin.passbook') }}" class="sidebar-link {{ request()->routeIs('society-admin.passbook*') ? 'active' : '' }}">
      <i class="bi bi-book"></i> Society Passbook
    </a>
    <a href="{{ route('society-admin.settings') }}" class="sidebar-link {{ request()->routeIs('society-admin.settings*') ? 'active' : '' }}">
      <i class="bi bi-gear"></i> Settings
    </a>
  </div>

  <div class="sidebar-footer">
    <div class="user-mini">
      <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
      <div class="user-info">
        <div style="font-size:.82rem;font-weight:700;">{{ auth()->user()->name }}</div>
        <div style="font-size:.7rem;opacity:.6;">Admin: {{ auth()->user()->society->name }}</div>
      </div>
    </div>
    <form action="{{ route('society.logout') }}" method="POST" style="margin-top: .5rem;">
      @csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.5);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.75rem;padding: .5rem 0; width: 100%; transition: color .2s;">
        <i class="bi bi-box-arrow-left" style="font-size: 1.1rem;"></i> <span>Sign Out</span>
      </button>
    </form>
  </div>
</aside>

<div class="main-content">
  <div class="topbar">
    <div class="page-title">@yield('page-title', 'Dashboard')</div>
    <div style="font-size:.8rem; color:var(--muted); font-weight:600;">Society: {{ auth()->user()->society->name }} ({{ ucfirst(auth()->user()->society->type) }})</div>
  </div>

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

<script>
function openModal(id) { document.getElementById(id).classList.add('show'); }
function closeModal(id) { document.getElementById(id).classList.remove('show'); }
window.onclick = function(event) { if (event.target.classList.contains('modal-overlay')) { event.target.classList.remove('show'); } }
</script>
@yield('scripts')
</body>
</html>
