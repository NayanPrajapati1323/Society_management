@extends('society.layouts.super_admin')
@section('title', 'System Settings')
@section('page-title', 'Settings')
@section('breadcrumb', 'Super Admin / Settings')

@section('extra-styles')
<style>
  .settings-container { display: grid; grid-template-columns: 240px 1fr; gap: 2rem; }
  .settings-nav { background: #fff; border-radius: 16px; border: 1px solid var(--border); padding: .75rem; height: fit-content; position: sticky; top: 100px; }
  .settings-nav-item { display: flex; align-items: center; gap: .75rem; padding: .85rem 1.25rem; border-radius: 12px; color: var(--muted); text-decoration: none; font-size: .88rem; font-weight: 600; transition: all .2s; margin-bottom: .25rem; border: none; background: none; width: 100%; text-align: left; cursor: pointer; }
  .settings-nav-item:hover { background: #f9fafb; color: var(--primary); }
  .settings-nav-item.active { background: var(--primary-light); color: var(--primary); }
  .settings-nav-item i { font-size: 1.1rem; }

  .settings-card { display: none; }
  .settings-card.active { display: block; }
  
  .plan-mini-card { border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between; transition: all .2s; }
  .plan-mini-card:hover { border-color: var(--primary); background: #fafafa; }
</style>
@endsection

@section('content')
<div class="settings-container">
  {{-- Side Navigation --}}
  <div class="settings-nav">
    <button class="settings-nav-item active" data-target="profile">
      <i class="bi bi-person-circle"></i> Profile Settings
    </button>
    <button class="settings-nav-item" data-target="plans">
      <i class="bi bi-layers-fill"></i> Manage Plans
    </button>
    <button class="settings-nav-item" data-target="security">
      <i class="bi bi-shield-lock-fill"></i> Security
    </button>
  </div>

  {{-- Content Area --}}
  <div class="settings-content">
    
    {{-- PROFILE TAB --}}
    <div class="settings-card active" id="profile">
      <div class="card">
        <div class="card-header">
          <div class="card-title">Personal Information</div>
        </div>
        <div class="card-body">
          <form action="{{ route('super-admin.settings.profile') }}" method="POST">
            @csrf
            <div class="form-grid">
              <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required />
              </div>
              <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required />
              </div>
              <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ $admin->phone }}" />
              </div>
            </div>
            <div style="margin-top:2rem;">
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- PLANS TAB --}}
    <div class="settings-card" id="plans">
      <div class="card">
        <div class="card-header">
          <div class="card-title">Manage Subscription Plans</div>
          <a href="{{ route('super-admin.plans.create') }}" class="btn btn-primary btn-sm">Add New Plan</a>
        </div>
        <div class="card-body">
          <p style="font-size:.85rem; color:var(--muted); margin-bottom:1.5rem;">Configure the plans available for societies on the landing page.</p>
          
          @foreach($plans as $plan)
          <div class="plan-mini-card">
            <div style="display:flex; align-items:center; gap:1rem;">
              <div style="width:42px; height:42px; border-radius:10px; background:var(--primary-light); color:var(--primary); display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                <i class="bi bi-layers"></i>
              </div>
              <div>
                <div style="font-weight:700; color:var(--dark);">{{ $plan->name }}</div>
                <div style="font-size:.78rem; color:var(--muted);">{{ $plan->max_units }} Units • {{ $plan->features->count() }} Features</div>
              </div>
            </div>
            <div style="display:flex; gap:.5rem;">
              <a href="{{ route('super-admin.plans.edit', $plan) }}" class="btn btn-sm btn-outline">Edit Plan</a>
              <form action="{{ route('super-admin.plans.toggle', $plan) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $plan->is_active ? 'btn-warning' : 'btn-success' }}">
                  {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                </button>
              </form>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- SECURITY TAB --}}
    <div class="settings-card" id="security">
      <div class="card">
        <div class="card-header">
          <div class="card-title">Security Settings</div>
        </div>
        <div class="card-body">
          <form action="{{ route('super-admin.settings.password') }}" method="POST">
            @csrf
            <div style="max-width:500px;">
              <div class="form-group" style="margin-bottom:1.25rem;">
                <label>Current Password</label>
                <input type="password" name="current_password" class="form-control" required />
              </div>
              <div class="form-group" style="margin-bottom:1.25rem;">
                <label>New Password</label>
                <input type="password" name="password" class="form-control" required />
              </div>
              <div class="form-group" style="margin-bottom:1.25rem;">
                <label>Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control" required />
              </div>
              <div style="margin-top:2rem;">
                <button type="submit" class="btn btn-primary">Update Password</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
  document.querySelectorAll('.settings-nav-item').forEach(button => {
    button.addEventListener('click', () => {
      // Remove active class from all buttons and cards
      document.querySelectorAll('.settings-nav-item').forEach(btn => btn.classList.remove('active'));
      document.querySelectorAll('.settings-card').forEach(card => card.classList.remove('active'));
      
      // Add active class to clicked button
      button.classList.add('active');
      
      // Show target card
      const target = button.getAttribute('data-target');
      document.getElementById(target).classList.add('active');
    });
  });
</script>
@endsection
