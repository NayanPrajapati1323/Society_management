@extends('society.layouts.society_admin')

@section('title', 'Resident Management')
@section('page-title', 'Resident Management')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
  <div class="page-title" style="margin:0;">Resident Management</div>
  <button class="btn btn-primary" onclick="openModal('addResidentModal')">
    <i class="bi bi-plus-lg"></i> Add Resident
  </button>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Pending Residency Requests</h3>
    <span class="badge badge-pending">{{ $users->where('is_approved', false)->count() }} Pending</span>
  </div>
  <div class="card-body" style="padding: 0;">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width: 30%;">Resident Info</th>
            <th style="width: 15%;">Unit / Floor</th>
            <th style="width: 25%;">Contact</th>
            <th style="width: 15%;">ID Proof</th>
            <th style="width: 15%;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users->where('is_approved', false) as $user)
          <tr>
            <td>
              <div style="display:flex; align-items:center; gap:.75rem;">
                <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
                <div>
                  <div style="font-weight:700;">{{ $user->name }}</div>
                  <div style="font-size:.75rem; color:var(--muted);">Registered: {{ $user->created_at->format('d M, Y') }}</div>
                </div>
              </div>
            </td>
            <td>
              <div style="font-weight:700; color:var(--primary);">{{ $user->unit_number ?? 'Pending Info' }}</div>
            </td>
            <td>
              <div style="font-size:.85rem;">{{ $user->email }}</div>
              <div style="font-size:.8rem; color:var(--muted);">{{ $user->phone }}</div>
            </td>
            <td>
              @if($user->document_path)
              <a href="{{ Storage::url($user->document_path) }}" target="_blank" class="btn btn-outline btn-sm">
                <i class="bi bi-eye"></i> View Doc
              </a>
              @else
              <span style="color:var(--muted); font-size:.8rem;">No Doc</span>
              @endif
            </td>
            <td>
              <div style="display:flex; gap:.5rem;">
                <form action="{{ route('society-admin.users.approve', $user->id) }}" method="POST">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn btn-success btn-sm">Approve</button>
                </form>
                <form action="{{ route('society-admin.users.reject', $user->id) }}" method="POST" onsubmit="return confirm('Reject this user? This will delete their account.')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center; padding:3rem; color:var(--muted);">No pending requests at the moment.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card" style="margin-top: 2rem;">
  <div class="card-header">
    <h3 class="card-title">Approved Residents</h3>
    <span class="badge badge-active">{{ $users->where('is_approved', true)->count() }} Residents</span>
  </div>
  <div class="card-body" style="padding:0;">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width: 40%;">Resident Info</th>
            <th style="width: 20%;">Unit</th>
            <th style="width: 20%;">Status</th>
            <th style="width: 20%;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users->where('is_approved', true) as $user)
          <tr>
            <td>
              <div style="display:flex; align-items:center; gap:.75rem;">
                <div class="user-avatar" style="background:#f3f4f6; color:var(--primary);">{{ substr($user->name, 0, 1) }}</div>
                <div>
                  <div style="font-weight:600;">{{ $user->name }}</div>
                  <div style="font-size:.7rem; color:var(--muted);">{{ $user->email }}</div>
                </div>
              </div>
            </td>
            <td><strong style="color:var(--primary);">{{ $user->unit_number ?? 'N/A' }}</strong></td>
            <td><span class="badge badge-active">Active</span></td>
            <td>
              <div style="display:flex; gap:.5rem;">
                <button onclick="editResident({{ json_encode($user) }})" class="btn btn-outline btn-sm">Edit</button>
                <form action="{{ route('society-admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Remove this resident? This will also mark their unit as vacant.')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-outline btn-sm" style="color:#dc2626; border-color:#fee2e2;">Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Resident Modal -->
<div id="addResidentModal" class="modal-overlay">
  <div class="modal-container" style="max-width: 500px;">
    <form action="{{ route('society-admin.users.store') }}" method="POST">
      @csrf
      <div class="modal-header">
        <h3 class="card-title">Add New Resident</h3>
        <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('addResidentModal')">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Full Name *</label>
          <input type="text" name="name" class="form-control" required />
        </div>
        <div class="form-group">
          <label>Email Address *</label>
          <input type="email" name="email" class="form-control" required />
        </div>
        <div class="form-group">
          <label>Unit Number</label>
          <input type="text" name="unit_number" class="form-control" placeholder="e.g. 101 or A-1" />
        </div>
        <div class="form-group">
          <label>Initial Password *</label>
          <div style="position:relative;">
            <input type="password" name="password" id="add_res_pass" class="form-control" value="resident123" required />
            <i class="bi bi-eye-slash toggle-password" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); cursor:pointer; color:var(--muted);" onclick="togglePassword('add_res_pass', this)"></i>
          </div>
          <small style="color:var(--muted);">Default: resident123</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('addResidentModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Resident</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Resident Modal -->
<div id="editResidentModal" class="modal-overlay">
  <div class="modal-container" style="max-width: 500px;">
    <form id="editResidentForm" method="POST">
      @csrf @method('PUT')
      <div class="modal-header">
        <h3 class="card-title">Edit Resident</h3>
        <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('editResidentModal')">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Full Name *</label>
          <input type="text" name="name" id="res_name" class="form-control" required />
        </div>
        <div class="form-group">
          <label>Email Address *</label>
          <input type="email" name="email" id="res_email" class="form-control" required />
        </div>
        <div class="form-group">
          <label>Unit Number</label>
          <input type="text" name="unit_number" id="res_unit_number" class="form-control" />
        </div>
        <div class="form-group">
          <label>Reset Password (Optional)</label>
          <div style="position:relative;">
            <input type="password" name="password" id="edit_res_pass" class="form-control" placeholder="Leave blank to keep current" />
            <i class="bi bi-eye-slash toggle-password" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); cursor:pointer; color:var(--muted);" onclick="togglePassword('edit_res_pass', this)"></i>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('editResidentModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Details</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
  function openModal(id) { document.getElementById(id).classList.add('show'); }
  function closeModal(id) { document.getElementById(id).classList.remove('show'); }

  function editResident(user) {
    document.getElementById('res_name').value = user.name;
    document.getElementById('res_email').value = user.email;
    document.getElementById('res_unit_number').value = user.unit_number || '';
    document.getElementById('editResidentForm').action = "/society-admin/users/" + user.id;
    openModal('editResidentModal');
  }
</script>
@endsection
