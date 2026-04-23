@extends('society.layouts.super_admin')
@section('title', 'Manage Users')
@section('page-title', 'Users')
@section('breadcrumb', 'Super Admin / Manage Users')

@section('content')
<div class="card">
  <div class="card-header">
    <div class="card-title"><i class="bi bi-people-fill" style="color:var(--primary);margin-right:.4rem;"></i> All Users</div>
  </div>
  <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);">
    <form method="GET" class="filter-bar">
      <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}" style="flex:1;min-width:200px;" />
      <select name="role">
        <option value="">All Roles</option>
        <option value="2" {{ request('role')=='2'?'selected':'' }}>Society Admin</option>
        <option value="3" {{ request('role')=='3'?'selected':'' }}>User</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Filter</button>
      <a href="{{ route('super-admin.users') }}" class="btn btn-outline btn-sm">Reset</a>
    </form>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Role</th>
          <th>Society</th>
          <th>Phone</th>
          <th>Registered</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td style="color:var(--muted);font-size:.78rem;">{{ $loop->iteration }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:.65rem;">
              <div style="width:34px;height:34px;border-radius:9px;background:var(--gradient);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.8rem;font-weight:700;flex-shrink:0;">
                {{ substr($user->name, 0, 1) }}
              </div>
              <div>
                <div class="table-name">{{ $user->name }}</div>
                <div class="table-sub">{{ $user->email }}</div>
              </div>
            </div>
          </td>
          <td>
            <span class="badge badge-role-{{ $user->role_id }}">
              {{ $user->role->display_name ?? 'N/A' }}
            </span>
          </td>
          <td>{{ $user->society ? $user->society->name : '—' }}</td>
          <td style="font-size:.83rem;">{{ $user->phone ?: '—' }}</td>
          <td style="font-size:.78rem;color:var(--muted);">{{ $user->created_at->format('d M Y') }}</td>
          <td>
            @if($user->is_active)
              <span class="badge badge-active"><i class="bi bi-dot"></i> Active</span>
            @else
              <span class="badge badge-inactive"><i class="bi bi-dot"></i> Inactive</span>
            @endif
          </td>
          <td>
            <form action="{{ route('super-admin.users.toggle', $user) }}" method="POST" style="display:inline;">
              @csrf @method('PATCH')
              <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                      title="{{ $user->is_active ? 'Deactivate user' : 'Activate user' }}">
                <i class="bi bi-{{ $user->is_active ? 'person-dash' : 'person-check' }}-fill"></i>
                {{ $user->is_active ? 'Disable' : 'Enable' }}
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align:center;color:var(--muted);padding:3rem;">
            <i class="bi bi-people" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:.75rem;"></i>
            No users found.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
  <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);">
    {{ $users->links() }}
  </div>
  @endif
</div>
@endsection
