@extends('society.layouts.super_admin')
@section('title', 'Manage Users')
@section('page-title', 'Users')
@section('breadcrumb', 'Super Admin / Manage Users')

@section('content')
<div class="card">
  <div class="card-header">
    <div class="card-title"><i class="bi bi-people-fill" style="color:var(--primary);margin-right:.4rem;"></i> All Users</div>
    <button class="btn btn-primary btn-sm" onclick="openModal('addUserModal')">
      <i class="bi bi-plus-circle"></i> Add New User
    </button>
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
            <div style="display:flex; gap:.5rem;">
              <button onclick="editUser({{ json_encode($user) }})" class="btn btn-outline btn-sm" title="Edit">
                <i class="bi bi-pencil"></i>
              </button>
              <form action="{{ route('super-admin.users.toggle', $user) }}" method="POST" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                        title="{{ $user->is_active ? 'Deactivate user' : 'Activate user' }}">
                  <i class="bi bi-{{ $user->is_active ? 'person-dash' : 'person-check' }}-fill"></i>
                </button>
              </form>
              <form action="{{ route('super-admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Delete this user permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline btn-sm" style="color:#dc2626; border-color:#fee2e2;" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
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

<!-- Add User Modal -->
<div id="addUserModal" class="modal-overlay">
  <div class="modal-container">
    <form action="{{ route('super-admin.users.store') }}" method="POST">
      @csrf
      <div class="modal-header">
        <h3 class="card-title">Add New User</h3>
        <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('addUserModal')">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" class="form-control" required />
          </div>
          <div class="form-group">
            <label>Email Address *</label>
            <input type="email" name="email" class="form-control" required />
          </div>
          <div class="form-group">
            <label>Initial Password *</label>
            <div class="input-wrap" style="position:relative;">
              <input type="password" name="password" id="add_user_pass" class="form-control" required />
              <i class="bi bi-eye-slash toggle-password" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); cursor:pointer; color:var(--muted);" onclick="togglePassword('add_user_pass', this)"></i>
            </div>
          </div>
          <div class="form-group">
            <label>Role *</label>
            <select name="role_id" class="form-control" required>
              <option value="2">Society Admin</option>
              <option value="3">Resident User</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Assign to Society</label>
            <select name="society_id" class="form-control">
              <option value="">None (Standalone User)</option>
              @foreach($societies as $s)
                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->city }})</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('addUserModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Create User</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="modal-overlay">
  <div class="modal-container">
    <form id="editUserForm" method="POST">
      @csrf @method('PUT')
      <div class="modal-header">
        <h3 class="card-title">Edit User</h3>
        <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('editUserModal')">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" id="edit_name" class="form-control" required />
          </div>
          <div class="form-group">
            <label>Email Address *</label>
            <input type="email" name="email" id="edit_email" class="form-control" required />
          </div>
          <div class="form-group">
            <label>New Password (Optional)</label>
            <div class="input-wrap" style="position:relative;">
              <input type="password" name="password" id="edit_user_pass" class="form-control" placeholder="Leave blank to keep current" />
              <i class="bi bi-eye-slash toggle-password" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); cursor:pointer; color:var(--muted);" onclick="togglePassword('edit_user_pass', this)"></i>
            </div>
          </div>
          <div class="form-group">
            <label>Role *</label>
            <select name="role_id" id="edit_role_id" class="form-control" required>
              <option value="2">Society Admin</option>
              <option value="3">Resident User</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Assign to Society</label>
            <select name="society_id" id="edit_society_id" class="form-control">
              <option value="">None (Standalone User)</option>
              @foreach($societies as $s)
                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->city }})</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('editUserModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Update User</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
  function openModal(id) { document.getElementById(id).classList.add('show'); }
  function closeModal(id) { document.getElementById(id).classList.remove('show'); }

  function editUser(user) {
    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_role_id').value = user.role_id;
    document.getElementById('edit_society_id').value = user.society_id || '';
    document.getElementById('editUserForm').action = "/super-admin/users/" + user.id;
    openModal('editUserModal');
  }
</script>
@endsection
