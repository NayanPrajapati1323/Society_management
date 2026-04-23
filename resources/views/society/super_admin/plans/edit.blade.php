@extends('society.layouts.super_admin')
@section('title', 'Edit Plan')
@section('page-title', 'Edit Plan')
@section('breadcrumb', 'Super Admin / Plans / Edit')

@section('content')
<div style="max-width:760px;">
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-pencil-fill" style="color:var(--primary);margin-right:.4rem;"></i> Edit Plan: {{ $plan->name }}</div>
      <a href="{{ route('super-admin.plans') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      @if($errors->any())
      <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
      </div>
      @endif
      <form action="{{ route('super-admin.plans.update', $plan) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-grid">
          <div class="form-group">
            <label>Plan Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $plan->name) }}" required />
          </div>
          <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $plan->sort_order) }}" min="0" />
          </div>
          <div class="form-group full">
            <label>Short Description</label>
            <input type="text" name="description" class="form-control" value="{{ old('description', $plan->description) }}" />
          </div>
          <div class="form-group full">
            <label>Features Summary</label>
            <input type="text" name="features_summary" class="form-control" value="{{ old('features_summary', $plan->features_summary) }}" />
          </div>
          <div class="form-group">
            <label>Max Units *</label>
            <input type="number" name="max_units" class="form-control" value="{{ old('max_units', $plan->max_units) }}" min="1" required />
            <div style="font-size:.75rem;color:var(--muted);margin-top:.25rem;">Enter 9999 for unlimited</div>
          </div>
          <div class="form-group">
            <label>Max Users *</label>
            <input type="number" name="max_users" class="form-control" value="{{ old('max_users', $plan->max_users) }}" min="1" required />
          </div>
          <div class="form-group" style="display:flex;align-items:center;gap:1rem;padding-top:.5rem;">
            <label style="margin-bottom:0;">Active (visible on landing page)</label>
            <label class="toggle-switch">
              <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} />
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>

        {{-- Plan Features --}}
        <div style="margin-top:1.5rem;">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
            <label style="font-weight:700;font-size:.9rem;color:var(--dark);">Plan Features <span style="color:var(--muted);font-weight:400;font-size:.8rem;">(existing features will be replaced)</span></label>
            <button type="button" class="btn btn-outline btn-sm" onclick="addFeatureRow()">
              <i class="bi bi-plus-circle"></i> Add Row
            </button>
          </div>
          <div id="featuresContainer">
            @forelse($plan->features as $feature)
            <div class="feature-input-row" style="display:flex;gap:.5rem;margin-bottom:.5rem;">
              <input type="text" name="features[]" class="form-control" value="{{ old('features.'.$loop->index, $feature->feature_text) }}" placeholder="Feature description..." />
              <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
            </div>
            @empty
            <div class="feature-input-row" style="display:flex;gap:.5rem;margin-bottom:.5rem;">
              <input type="text" name="features[]" class="form-control" placeholder="e.g. Up to 50 Units" />
              <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
            </div>
            @endforelse
          </div>
        </div>

        <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle-fill"></i> Save Changes</button>
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
  div.innerHTML = `<input type="text" name="features[]" class="form-control" placeholder="Feature description..." />
    <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>`;
  container.appendChild(div);
  div.querySelector('input').focus();
}
</script>
@endsection
