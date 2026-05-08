@extends('society.layouts.super_admin')
@section('title', 'Create Plan')
@section('page-title', 'Create Plan')
@section('breadcrumb', 'Super Admin / Plans / Create New')

@section('content')
<div style="max-width:760px;">
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-plus-circle-fill" style="color:var(--primary);margin-right:.4rem;"></i> Create New Plan</div>
      <a href="{{ route('super-admin.plans') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      @if($errors->any())
      <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
      </div>
      @endif
      <form action="{{ route('super-admin.plans.store') }}" method="POST" id="planForm">
        @csrf
        <div class="form-grid">
          <div class="form-group">
            <label>Plan Name *</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Basic, Standard, Premium" value="{{ old('name') }}" required />
          </div>
          <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" class="form-control" placeholder="1" value="{{ old('sort_order', 0) }}" min="0" />
          </div>
          <div class="form-group full">
            <label>Short Description</label>
            <input type="text" name="description" class="form-control" placeholder="e.g. Perfect for small housing societies" value="{{ old('description') }}" />
          </div>
          <div class="form-group full">
            <label>Features Summary <span style="color:var(--muted);font-weight:400;">(shown on landing page cards)</span></label>
            <input type="text" name="features_summary" class="form-control" placeholder="e.g. Manage up to 50 units with essential features" value="{{ old('features_summary') }}" />
          </div>
          <div class="form-group">
            <label>Unit Range (Min - Max) *</label>
            <div style="display:flex; gap:.5rem; align-items:center;">
              <input type="number" name="min_units" class="form-control" placeholder="0" value="{{ old('min_units', 0) }}" min="0" required />
              <span>to</span>
              <input type="number" name="max_units" class="form-control" placeholder="50" value="{{ old('max_units', 50) }}" min="1" required />
            </div>
            <div style="font-size:.75rem;color:var(--muted);margin-top:.25rem;">e.g. 0 to 50, 51 to 100</div>
          </div>
          <div class="form-group">
            <label>Monthly Price (₹) *</label>
            <input type="number" name="monthly_price" id="monthly_price" class="form-control" placeholder="500" value="{{ old('monthly_price') }}" min="0" step="0.01" required />
          </div>
          <div class="form-group">
            <label>Website Activation Price (One-time ₹) *</label>
            <input type="number" name="website_price" id="website_price" class="form-control" placeholder="1500" value="{{ old('website_price', 0) }}" min="0" step="0.01" required />
          </div>
          <div class="form-group">
            <label>Website Maintenance Price (Annual ₹) *</label>
            <input type="number" name="website_maintenance_price" id="website_maintenance_price" class="form-control" placeholder="500" value="{{ old('website_maintenance_price', 0) }}" min="0" step="0.01" required />
          </div>
          <div class="form-group">
            <label>6 Month Price</label>
            <input type="text" id="six_month_price" class="form-control" readonly style="background:var(--bg-light);" />
          </div>
          <div class="form-group">
            <label>12 Month Price</label>
            <input type="text" id="twelve_month_price" class="form-control" readonly style="background:var(--bg-light);" />
          </div>
          <div class="form-group" style="display:flex;align-items:center;gap:1rem;padding-top:.5rem;">
            <label style="margin-bottom:0;">Active (visible on landing page)</label>
            <label class="toggle-switch">
              <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} />
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>

        {{-- Plan Features --}}
        <div style="margin-top:1.5rem;">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
            <label style="font-weight:700;font-size:.9rem;color:var(--dark);">Plan Features</label>
            <button type="button" class="btn btn-outline btn-sm" onclick="addFeatureRow()">
              <i class="bi bi-plus-circle"></i> Add Feature
            </button>
          </div>
          <div id="featuresContainer">
            @if(old('features'))
              @foreach(old('features') as $i => $f)
              <div class="feature-input-row" style="display:flex;gap:.5rem;margin-bottom:.5rem;">
                <input type="text" name="features[]" class="form-control" value="{{ $f }}" placeholder="Feature description..." />
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
              </div>
              @endforeach
            @else
              <div class="feature-input-row" style="display:flex;gap:.5rem;margin-bottom:.5rem;">
                <input type="text" name="features[]" class="form-control" placeholder="e.g. Up to 50 Units" />
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
              </div>
            @endif
          </div>
          <div style="font-size:.78rem;color:var(--muted);margin-top:.5rem;"><i class="bi bi-info-circle"></i> All added features will be marked as "Included". Leave blank rows will be ignored.</div>
        </div>

        <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle-fill"></i> Create Plan</button>
          <a href="{{ route('super-admin.plans') }}" class="btn btn-outline">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('monthly_price').addEventListener('input', function() {
  const monthly = parseFloat(this.value) || 0;
  document.getElementById('six_month_price').value = (monthly * 6).toFixed(2);
  document.getElementById('twelve_month_price').value = (monthly * 12).toFixed(2);
});

function addFeatureRow() {
  const container = document.getElementById('featuresContainer');
  const div = document.createElement('div');
  div.className = 'feature-input-row';
  div.style.cssText = 'display:flex;gap:.5rem;margin-bottom:.5rem;';
  div.innerHTML = `<input type="text" name="features[]" class="form-control" placeholder="e.g. Member Management" />
    <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>`;
  container.appendChild(div);
  div.querySelector('input').focus();
}
</script>
@endsection
