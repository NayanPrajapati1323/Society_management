@extends('society.layouts.user')

@section('title', 'Account Settings')
@section('page-title', 'Security & Profile')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
  <!-- Profile Management -->
  <div class="card p-all">
    <div style="margin-bottom: 1.5rem;">
      <h3 style="font-size: 1.2rem; font-weight: 800;">Profile Information</h3>
      <p style="font-size: .85rem; color: var(--text-muted);">Manage your personal contact details.</p>
    </div>
    
    <form action="{{ route('society.user.profile.update') }}" method="POST">
      @csrf
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required />
      </div>
      <div class="form-group">
        <label>Email Address (read-only)</label>
        <input type="email" class="form-control" value="{{ Auth::user()->email }}" style="background: #f1f5f9;" readonly />
      </div>
      <div class="form-group">
        <label>Phone Number</label>
        <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone }}" required />
      </div>
      <div class="form-group">
        <label>Assigned Unit</label>
        <input type="text" class="form-control" value="Unit {{ Auth::user()->unit_number }} ({{ Auth::user()->society->name }})" style="background: #f1f5f9;" readonly />
      </div>
      <button type="submit" class="btn btn-primary" style="width: 100%;">Update Profile Details</button>
    </form>
  </div>

  <!-- Security Management -->
  <div class="card p-all">
    <div style="margin-bottom: 1.5rem;">
      <h3 style="font-size: 1.2rem; font-weight: 800;">Security Center</h3>
      <p style="font-size: .85rem; color: var(--text-muted);">Rotate your password to keep your account safe.</p>
    </div>

    @if($errors->any())
      <div class="alert alert-danger" style="background:#fee2e2; color:#b91c1c; border-color:#fecaca;">
        <ul style="list-style:none; padding:0; font-size:.85rem;">
          @foreach($errors->all() as $error)
            <li><i class="bi bi-exclamation-triangle"></i> {{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    
    <form action="{{ route('society.user.password.update') }}" method="POST">
      @csrf
      <div class="form-group">
        <label>Current Password</label>
        <input type="password" name="current_password" class="form-control" placeholder="••••••••" required />
      </div>
      <hr style="border: 0; border-top: 1px solid var(--border); margin: 1.5rem 0;" />
      <div class="form-group">
        <label>New Password</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required />
      </div>
      <div class="form-group">
        <label>Confirm New Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required />
      </div>
      <button type="submit" class="btn btn-outline" style="width: 100%; border-color: var(--primary); color: var(--primary);">
        Change Password & Log Out All Sessions
      </button>
    </form>
  </div>
</div>
@endsection
