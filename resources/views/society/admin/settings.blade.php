@extends('society.layouts.society_admin')

@section('title', 'Settings')
@section('page-title', 'Society & Profile Settings')

@section('content')
<div class="card" style="max-width: 800px;">
  <div class="card-header">
    <h3 class="card-title">Society Information</h3>
  </div>
  <div class="card-body p-all">
    <form action="{{ route('society-admin.settings.profile') }}" method="POST">
      @csrf
      <div class="form-group">
        <label>Society Name</label>
        <input type="text" name="name" class="form-control" value="{{ $society->name }}" required />
      </div>
      <div class="form-group">
        <label>Full Address</label>
        <textarea name="address" class="form-control" rows="3" required>{{ $society->address }}</textarea>
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div class="form-group">
          <label>City</label>
          <input type="text" class="form-control" value="{{ $society->city }}" disabled />
        </div>
        <div class="form-group">
          <label>State</label>
          <input type="text" class="form-control" value="{{ $society->state }}" disabled />
        </div>
      </div>
      <div style="margin-top: 1.5rem;">
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<div class="card" style="max-width: 800px; margin-top: 2rem; border-color: #fca5a5;">
  <div class="card-header" style="background: #fef2f2;">
    <h3 class="card-title" style="color: #991b1b;">Danger Zone</h3>
  </div>
  <div class="card-body p-all">
    <div style="display:flex; justify-content: space-between; align-items: center;">
      <div>
        <div style="font-weight:700; font-size:.9rem;">Deactivate Society Account</div>
        <div style="font-size:.8rem; color:var(--muted);">Temporarily disable all resident access.</div>
      </div>
      <button class="btn btn-danger btn-sm">Deactivate</button>
    </div>
  </div>
</div>
@endsection
