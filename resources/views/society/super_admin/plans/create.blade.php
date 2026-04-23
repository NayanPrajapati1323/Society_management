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
            <label>Max Units *</label>
            <input type="number" name="max_units" class="form-control" placeholder="50" value="{{ old('max_units', 50) }}" min="1" required />
            <div style="font-size:.75rem;color:var(--muted);margin-top:.25rem;">Enter 9999 for unlimited</div>
          </div>
          <div class="form-group">
            <label>Max Users *</label>
            <input type="number" name="max_users" class="form-control" placeholder="100" value="{{ old('max_users', 100) }}" min="1" required />
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
