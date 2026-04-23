@extends('society.layouts.super_admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Super Admin / Overview')

@section('content')

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon stat-icon-1"><i class="bi bi-buildings-fill"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['total_societies'] }}</div>
      <div class="stat-label">Total Societies</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-2"><i class="bi bi-check-circle-fill"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['active_societies'] }}</div>
      <div class="stat-label">Active Societies</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-3"><i class="bi bi-hourglass-split"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['pending_societies'] }}</div>
      <div class="stat-label">Pending Approval</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-4"><i class="bi bi-people-fill"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['total_users'] }}</div>
      <div class="stat-label">Total Users</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-5"><i class="bi bi-person-gear"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['society_admins'] }}</div>
      <div class="stat-label">Society Admins</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-6"><i class="bi bi-person-fill"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['regular_users'] }}</div>
      <div class="stat-label">Regular Users</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-7"><i class="bi bi-layers-fill"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['total_plans'] }}</div>
      <div class="stat-label">Total Plans</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-8"><i class="bi bi-lightning-fill"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['active_plans'] }}</div>
      <div class="stat-label">Active Plans</div>
    </div>
  </div>
</div>

{{-- Quick Actions --}}
<div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:2rem;">
  <a href="{{ route('super-admin.societies.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle-fill"></i> Add Society</a>
  <a href="{{ route('super-admin.plans.create') }}" class="btn btn-primary" style="background:linear-gradient(135deg,#43e97b,#38f9d7);"><i class="bi bi-plus-circle-fill"></i> Add Plan</a>
  <a href="{{ route('super-admin.societies') }}" class="btn btn-outline"><i class="bi bi-buildings"></i> Manage Societies</a>
  <a href="{{ route('super-admin.users') }}" class="btn btn-outline"><i class="bi bi-people"></i> Manage Users</a>
</div>

<div class="two-col">
  {{-- Recent Societies --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-buildings" style="color:var(--primary);margin-right:.4rem;"></i> Recent Societies</div>
      <a href="{{ route('super-admin.societies') }}" class="btn btn-outline btn-sm">View All</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Society</th>
            <th>Plan</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recent_societies as $society)
          <tr>
            <td>
              <div class="table-name">{{ $society->name }}</div>
              <div class="table-sub">{{ $society->city ?? 'N/A' }}</div>
            </td>
            <td>{{ $society->plan ? $society->plan->name : '—' }}</td>
            <td>
              @if($society->is_active)
                <span class="badge badge-active"><i class="bi bi-dot"></i> Active</span>
              @else
                <span class="badge badge-pending"><i class="bi bi-dot"></i> Pending</span>
              @endif
            </td>
            <td>
              <form action="{{ route('super-admin.societies.toggle', $society) }}" method="POST" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $society->is_active ? 'btn-warning' : 'btn-success' }}">
                  {{ $society->is_active ? 'Deactivate' : 'Activate' }}
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:2rem;">No societies yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Recent Users --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-people" style="color:var(--primary);margin-right:.4rem;"></i> Recent Users</div>
      <a href="{{ route('super-admin.users') }}" class="btn btn-outline btn-sm">View All</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>User</th>
            <th>Role</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recent_users as $user)
          <tr>
            <td>
              <div class="table-name">{{ $user->name }}</div>
              <div class="table-sub">{{ $user->email }}</div>
            </td>
            <td>
              <span class="badge badge-role-{{ $user->role_id }}">{{ $user->role->display_name ?? 'N/A' }}</span>
            </td>
            <td>
              @if($user->is_active)
                <span class="badge badge-active">Active</span>
              @else
                <span class="badge badge-inactive">Inactive</span>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="3" style="text-align:center;color:var(--muted);padding:2rem;">No users yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
