@extends('society.layouts.society_admin')

@section('title', 'Society Structure')
@section('page-title', 'Design Society Structure')

@section('content')
<div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem; gap: 1rem;">
  <button class="btn btn-primary" onclick="openModal('addBuildingModal')">
    <i class="bi bi-plus-lg"></i> Add New {{ $society->type == 'flat' ? 'Tower' : 'Block' }}
  </button>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
  @forelse($buildings as $building)
  <div class="card">
    <div class="card-header" style="background: #f8fafc;">
      <div>
        <h3 class="card-title">{{ $building->name }}</h3>
        <span style="font-size: .75rem; color: var(--muted);">{{ $building->units->count() }} Units {{ $society->type == 'flat' ? '• '.$building->floors.' Floors' : '' }}</span>
      </div>
    </div>
    <div class="card-body p-all">
      @if($society->type == 'flat')
        @foreach($building->units->groupBy('floor')->sortKeysDesc() as $floor => $units)
          <div style="margin-bottom: 1.25rem;">
            <div style="font-size: .7rem; font-weight: 800; color: var(--muted); text-transform: uppercase; margin-bottom: .5rem; display: flex; align-items: center; gap: .5rem;">
              <i class="bi bi-layers"></i> {{ $floor }}{{ in_array($floor % 100, [11,12,13]) ? 'th' : match($floor % 10) {1=>'st', 2=>'nd', 3=>'rd', default=>'th'} }} Floor
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(64px, 1fr)); gap: 8px;">
              @foreach($units->sortBy('unit_number') as $unit)
              <div title="Unit: {{ $unit->unit_number }} ({{ ucfirst($unit->status) }})" 
                   style="height: 48px; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: .75rem; font-weight: 800; background: {{ $unit->status == 'occupied' ? '#dcfce7' : '#fff' }}; color: {{ $unit->status == 'occupied' ? '#15803d' : '#64748b' }}; border: 2px solid {{ $unit->status == 'occupied' ? '#bbf7d0' : '#f1f5f9' }}; transition: all .2s;">
                <span style="font-size: .65rem; opacity: .7; font-weight: 600;">{{ $unit->unit_number }}</span>
              </div>
              @endforeach
            </div>
          </div>
        @endforeach
      @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px;">
          @foreach($building->units->sortBy('unit_number') as $unit)
          <div title="Unit: {{ $unit->unit_number }} ({{ ucfirst($unit->status) }})" 
               style="height: 54px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: .8rem; font-weight: 800; background: {{ $unit->status == 'occupied' ? '#dcfce7' : '#fff' }}; color: {{ $unit->status == 'occupied' ? '#15803d' : '#64748b' }}; border: 2px solid {{ $unit->status == 'occupied' ? '#bbf7d0' : '#f1f5f9' }};">
            {{ $unit->unit_number }}
          </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
  @empty
  <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
    <div style="font-size: 3rem; color: #e5e7eb; margin-bottom: 1rem;"><i class="bi bi-diagram-3"></i></div>
    <h3 style="color: var(--muted);">No structure designed yet</h3>
    <p style="color: #94a3b8; font-size: .9rem;">Start by adding your first {{ $society->type == 'flat' ? 'tower' : 'block' }}.</p>
  </div>
  @endforelse
</div>

<!-- Add Building Modal -->
<div id="addBuildingModal" class="modal-overlay">
  <div class="modal-container">
    <form action="{{ route('society-admin.structure.building.store') }}" method="POST">
      @csrf
      <div class="modal-header">
        <h3 class="card-title">Add New {{ $society->type == 'flat' ? 'Tower' : 'Block' }}</h3>
        <button type="button" style="background:none; border:none; font-size:1.5rem; cursor:pointer;" onclick="closeModal('addBuildingModal')">&times;</button>
      </div>
      <div class="modal-body">
        @if($society->type == 'flat')
          <div class="form-group">
            <label>Tower Name *</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Tower A" required />
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
              <label>Total Floors *</label>
              <input type="number" name="floors" class="form-control" placeholder="e.g. 10" required />
            </div>
            <div class="form-group">
              <label>Flats Per Floor *</label>
              <input type="number" name="flats_per_floor" class="form-control" placeholder="e.g. 4" required />
            </div>
          </div>
          <p style="font-size: .75rem; color: var(--muted); margin-top: .5rem;">Units will be auto-generated as 101, 102, 201, 202 etc.</p>
        @else
          <div class="form-group">
            <label>Block Name *</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Block A" required />
          </div>
          <div class="form-group">
            <label>Total Houses in Block *</label>
            <input type="number" name="total_houses" class="form-control" placeholder="e.g. 20" required />
          </div>
          <p style="font-size: .75rem; color: var(--muted); margin-top: .5rem;">Units will be auto-generated as Block-1, Block-2 etc.</p>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('addBuildingModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Generate Structure</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
</script>
@endsection
