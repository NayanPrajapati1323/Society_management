@extends('society.layouts.super_admin')
@section('title', 'Manage Plans')
@section('page-title', 'Plans')
@section('breadcrumb', 'Super Admin / Manage Plans')

@section('content')
<div class="card">
  <div class="card-header">
    <div class="card-title"><i class="bi bi-layers-fill" style="color:var(--primary);margin-right:.4rem;"></i> All Plans</div>
    <a href="{{ route('super-admin.plans.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-circle-fill"></i> Add Plan
    </a>
  </div>
  <div style="padding:1.5rem;">
    @if($plans->isEmpty())
    <div style="text-align:center;padding:3rem;color:var(--muted);">
      <i class="bi bi-layers" style="font-size:3rem;opacity:.3;display:block;margin-bottom:1rem;"></i>
      No plans yet. <a href="{{ route('super-admin.plans.create') }}" style="color:var(--primary);">Create the first plan</a>
    </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">
      @foreach($plans as $plan)
      <div style="border:2px solid {{ $plan->is_active ? 'rgba(108,99,255,.3)' : 'var(--border)' }};border-radius:16px;padding:1.5rem;background:{{ $plan->is_active ? '#faf9ff' : '#fafafa' }};position:relative;transition:all .2s;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1rem;">
          <div>
            <div style="font-size:1.1rem;font-weight:800;color:var(--dark);">{{ $plan->name }}</div>
            <div style="font-size:.8rem;color:var(--muted);margin-top:.2rem;">Sort: {{ $plan->sort_order }}</div>
          </div>
          <span class="badge {{ $plan->is_active ? 'badge-active' : 'badge-inactive' }}">
            <i class="bi bi-dot"></i> {{ $plan->is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>
        <div style="font-size:.83rem;color:var(--muted);margin-bottom:1rem;line-height:1.6;">{{ $plan->description ?: 'No description.' }}</div>
        <div style="display:flex;gap:1.25rem;margin-bottom:1rem;">
          <div style="text-align:center;flex:1;padding:.65rem;background:#fff;border-radius:10px;border:1px solid var(--border);">
            <div style="font-size:1.2rem;font-weight:800;color:var(--primary);">{{ $plan->max_units == 9999 ? '∞' : $plan->max_units }}</div>
            <div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Max Units</div>
          </div>
          <div style="text-align:center;flex:1;padding:.65rem;background:#fff;border-radius:10px;border:1px solid var(--border);">
            <div style="font-size:1.2rem;font-weight:800;color:#43e97b;">{{ $plan->max_users == 9999 ? '∞' : $plan->max_users }}</div>
            <div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Max Users</div>
          </div>
          <div style="text-align:center;flex:1;padding:.65rem;background:#fff;border-radius:10px;border:1px solid var(--border);">
            <div style="font-size:1.2rem;font-weight:800;color:#f59e0b;">{{ $plan->societies_count ?? $plan->societies()->count() }}</div>
            <div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Societies</div>
          </div>
        </div>
        @if($plan->features->count())
        <ul style="list-style:none;padding:0;margin-bottom:1rem;">
          @foreach($plan->features->take(4) as $feature)
          <li style="display:flex;align-items:center;gap:.5rem;font-size:.8rem;color:{{ $feature->is_included ? 'var(--dark)' : 'var(--muted)' }};padding:.2rem 0;">
            <i class="bi bi-{{ $feature->is_included ? 'check-circle-fill' : 'x-circle' }}" style="color:{{ $feature->is_included ? '#43e97b' : '#d1d5db' }};"></i>
            {{ $feature->feature_text }}
          </li>
          @endforeach
          @if($plan->features->count() > 4)
          <li style="font-size:.75rem;color:var(--muted);padding:.2rem .5rem;">+{{ $plan->features->count() - 4 }} more features</li>
          @endif
        </ul>
        @endif
        <div style="display:flex;gap:.5rem;margin-top:.75rem;">
          <a href="{{ route('super-admin.plans.edit', $plan) }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;"><i class="bi bi-pencil-fill"></i> Edit</a>
          <form action="{{ route('super-admin.plans.toggle', $plan) }}" method="POST" style="display:inline;">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-sm {{ $plan->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }}">
              <i class="bi bi-{{ $plan->is_active ? 'eye-slash' : 'eye' }}-fill"></i>
            </button>
          </form>
          <form action="{{ route('super-admin.plans.delete', $plan) }}" method="POST" style="display:inline;"
                onsubmit="return confirm('Delete plan {{ addslashes($plan->name) }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
          </form>
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</div>
@endsection
