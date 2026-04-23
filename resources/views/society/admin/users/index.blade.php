@extends('society.layouts.society_admin')

@section('title', 'User Approvals')
@section('page-title', 'Resident Management')

@section('content')
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
              <button class="btn btn-outline btn-sm">Manage</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
