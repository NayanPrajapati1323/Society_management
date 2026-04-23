@extends('society.layouts.society_admin')

@section('page-title', 'Society Settings')

@section('content')
<div class="card mb-4" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color:#fff; border:none; border-radius:24px;">
    <div class="card-body" style="padding: 2.5rem; display:flex; align-items:center; gap:2rem;">
        <div style="width:80px; height:80px; border-radius:20px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:2.5rem; color:#fff; border:1px solid rgba(255,255,255,0.3);">
            {{ substr($society->name, 0, 1) }}
        </div>
        <div>
            <h2 style="font-weight:800; margin-bottom:.3rem;">{{ $society->name }}</h2>
            <div style="display:flex; align-items:center; gap:1rem; opacity:.9; font-size:.9rem;">
                <span><i class="bi bi-geo-alt"></i> {{ $society->city }}, {{ $society->state }}</span>
                <span>•</span>
                <span>Current Plan: <strong>{{ $society->plan->name ?? 'Enterprise' }}</strong></span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="background:#fff;">
        <div style="display:flex; gap:1.5rem;">
            <button onclick="showTab('profileTab')" id="profileBtn" class="tab-btn active">Society Profile</button>
            <button onclick="showTab('planTab')" id="planBtn" class="tab-btn">Subscription Plans</button>
        </div>
    </div>
    <div class="card-body">
        
        {{-- Profile Tab --}}
        <div id="profileTab" class="tab-content">
            <form action="{{ route('society-admin.settings.profile') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Society Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ $society->name }}" required />
                    </div>
                    <div class="form-group">
                        <label>Contact Email *</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ $society->contact_email }}" required />
                    </div>
                    <div class="form-group">
                        <label>Contact Phone *</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ $society->contact_phone }}" required />
                    </div>
                    <div class="form-group full">
                        <label>Address *</label>
                        <textarea name="address" class="form-control" required>{{ $society->address }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>City *</label>
                        <input type="text" name="city" class="form-control" value="{{ $society->city }}" required />
                    </div>
                    <div class="form-group">
                        <label>State *</label>
                        <input type="text" name="state" class="form-control" value="{{ $society->state }}" required />
                    </div>
                </div>
                <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #f3f4f6;">
                    <button type="submit" class="btn btn-primary" style="padding:.75rem 2rem;">Save Changes</button>
                </div>
            </form>
        </div>

        {{-- Plans Tab --}}
        <div id="planTab" class="tab-content" style="display:none;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
                @foreach($plans as $plan)
                <div style="border:2px solid {{ $society->plan_id == $plan->id ? 'var(--primary)' : '#f3f4f6' }}; border-radius:20px; padding:2rem; position:relative;">
                    @if($society->plan_id == $plan->id)
                    <span style="position:absolute; top:-12px; left:50%; transform:translateX(-50%); background:var(--primary); color:#fff; font-size:.7rem; font-weight:800; padding:.3rem 1rem; border-radius:50px;">CURRENT PLAN</span>
                    @endif
                    <div style="font-size:1.25rem; font-weight:800; margin-bottom:.5rem;">{{ $plan->name }}</div>
                    <div style="color:var(--muted); font-size:.85rem; margin-bottom:1.5rem;">{{ $plan->description }}</div>
                    <div style="font-size:1.5rem; font-weight:800; color:var(--primary); margin-bottom:1.5rem;">Contact for Pricing</div>
                    <ul style="list-style:none; font-size:.85rem; color:var(--text); margin-bottom:2rem;">
                        @foreach($plan->features as $feat)
                        <li style="display:flex; align-items:center; gap:.5rem; margin-bottom:.5rem;">
                            <i class="bi bi-check2-circle" style="color:#16a34a;"></i> {{ $feat->feature_text }}
                        </li>
                        @endforeach
                    </ul>
                    <button class="btn {{ $society->plan_id == $plan->id ? 'btn-outline' : 'btn-primary' }}" style="width:100%; justify-content:center;" {{ $society->plan_id == $plan->id ? 'disabled' : '' }}>
                        {{ $society->plan_id == $plan->id ? 'Stay with current' : 'Switch Plan' }}
                    </button>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<style>
    .tab-btn { background:none; border:none; padding:1rem 0; font-size:.9rem; font-weight:700; color:var(--muted); cursor:pointer; position:relative; }
    .tab-btn.active { color:var(--primary); }
    .tab-btn.active::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; background:var(--primary); border-radius:3px; }
    .tab-btn:hover { color:var(--primary); }
</style>

<script>
    function showTab(id) {
        document.querySelectorAll('.tab-content').forEach(t => t.style.display = 'none');
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById(id).style.display = 'block';
        document.getElementById(id.replace('Tab', 'Btn')).classList.add('active');
    }
</script>
@endsection
