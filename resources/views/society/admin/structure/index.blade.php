@extends('society.layouts.society_admin')

@section('page-title', 'Society Structure')

@section('extra-styles')
<style>
    .structure-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 1rem; margin-top: 1.5rem; }
    .unit-box { 
        height: 100px; border: 2px solid var(--border); border-radius: 12px; 
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .3rem;
        transition: all .2s; cursor: pointer;
    }
    .unit-box:hover { border-color: var(--primary); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    .unit-box.occupied { background: #f0fdf4; border-color: #16a34a; }
    .unit-box.occupied i { color: #16a34a; }
    .unit-box .unit-num { font-weight: 800; font-size: 1rem; color: var(--dark); }
    .unit-box .unit-status { font-size: .65rem; font-weight: 700; text-transform: uppercase; color: var(--muted); }
    .tower-section { margin-bottom: 3rem; background: #fff; padding: 2rem; border-radius: 20px; border: 1px solid var(--border); }
    .tower-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px dashed #f3f4f6; }
</style>
@endsection

@section('content')
<div class="card mb-4" style="background: var(--gradient); color:#fff; border:none;">
    <div class="card-body" style="padding: 2rem; display:flex; align-items:center; justify-content:space-between;">
        <div>
            <h2 style="font-weight:800; margin-bottom:.5rem;">Design Your Community</h2>
            <p style="opacity: .8; font-size:.9rem;">Define your towers, floors, and units to visualize your society layout.</p>
        </div>
        <button onclick="openModal('setupModal')" class="btn btn-white btn-lg" style="color:var(--primary); background:#fff;">
            <i class="bi bi-plus-lg"></i> {{ $society->towers->count() > 0 || $society->blocks->count() > 0 ? 'Add More' : 'Start Designing' }}
        </button>
    </div>
</div>

@foreach($towers as $tower)
<div class="tower-section">
    <div class="tower-header">
        <h3 style="font-weight:800; color:var(--dark); display:flex; align-items:center; gap:.75rem;">
            <i class="bi bi-building"></i> Tower: {{ $tower->name }}
        </h3>
        <span class="badge badge-role-1">{{ $tower->floors->count() }} Floors</span>
    </div>
    
    @foreach($tower->floors as $floor)
    <div style="margin-bottom: 1.5rem;">
        <div style="font-size:.78rem; font-weight:700; color:var(--muted); margin-bottom:.75rem; text-transform:uppercase;">Floor {{ $floor->floor_number }}</div>
        <div class="structure-grid">
            @foreach($floor->units as $unit)
            <div class="unit-box {{ $unit->status == 'occupied' ? 'occupied' : '' }}">
                <i class="bi {{ $unit->status == 'occupied' ? 'bi-person-fill' : 'bi-house' }}"></i>
                <div class="unit-num">{{ $unit->unit_number }}</div>
                <div class="unit-status">{{ $unit->status }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endforeach

@foreach($blocks as $block)
<div class="tower-section">
    <div class="tower-header">
        <h3 style="font-weight:800; color:var(--dark); display:flex; align-items:center; gap:.75rem;">
            <i class="bi bi-houses"></i> Block: {{ $block->name }}
        </h3>
        <span class="badge badge-role-2">{{ $block->units->count() }} Units</span>
    </div>
    <div class="structure-grid">
        @foreach($block->units as $unit)
        <div class="unit-box {{ $unit->status == 'occupied' ? 'occupied' : '' }}">
            <i class="bi {{ $unit->status == 'occupied' ? 'bi-person-fill' : 'bi-house' }}"></i>
            <div class="unit-num">{{ $unit->unit_number }}</div>
            <div class="unit-status">{{ $unit->status }}</div>
        </div>
        @endforeach
    </div>
</div>
@endforeach

{{-- Setup Modal --}}
<div id="setupModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="bi bi-tools"></i> Structure Setup</h3>
            <button onclick="closeModal('setupModal')" class="modal-close">&times;</button>
        </div>
        <form action="{{ route('society-admin.structure.save') }}" method="POST">
            @csrf
            <div class="modal-body">
                @if($society->type === 'flat')
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Tower Name/Letter *</label>
                        <input type="text" name="tower_name" class="form-control" placeholder="e.g. Tower A" required />
                    </div>
                    <div class="form-group">
                        <label>Number of Floors *</label>
                        <input type="number" name="floors" class="form-control" min="1" max="50" required />
                    </div>
                    <div class="form-group">
                        <label>Flats Per Floor *</label>
                        <input type="number" name="flats_per_floor" class="form-control" min="1" max="20" required />
                    </div>
                </div>
                @else
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Block Name/Letter *</label>
                        <input type="text" name="block_name" class="form-control" placeholder="e.g. Block A" required />
                    </div>
                    <div class="form-group full">
                        <label>Number of Houses *</label>
                        <input type="number" name="houses" class="form-control" min="1" max="100" required />
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" style="flex:1;">Generate Structure</button>
                <button type="button" onclick="closeModal('setupModal')" class="btn btn-outline" style="flex:1;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }
</script>
@endsection
