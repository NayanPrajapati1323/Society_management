@extends('society.layouts.society_admin')

@section('title', 'Society Structure')
@section('page-title', 'Design Society Structure')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
  <div style="display: flex; gap: 1.5rem; background: #fff; padding: .75rem 1.25rem; border-radius: 12px; border: 1px solid var(--border);">
    <div>
      <div style="font-size: .7rem; color: var(--muted); text-transform: uppercase; font-weight: 700; letter-spacing: .05em;">Plan Limit</div>
      <div style="font-size: 1.1rem; font-weight: 800; color: var(--primary);">{{ $society->plan->max_units ?? '∞' }} Units</div>
    </div>
    <div style="width: 1px; background: var(--border);"></div>
    <div>
      <div style="font-size: .7rem; color: var(--muted); text-transform: uppercase; font-weight: 700; letter-spacing: .05em;">Currently Used</div>
      <div style="font-size: 1.1rem; font-weight: 800; color: {{ ($buildings->sum(fn($b) => $b->units->count()) >= ($society->plan->max_units ?? 9999)) ? '#dc2626' : '#10b981' }};">
        {{ $buildings->sum(fn($b) => $b->units->count()) }} Units
      </div>
    </div>
  </div>
  
  <button class="btn btn-primary" onclick="openModal('addBuildingModal')">
    <i class="bi bi-plus-lg"></i> Add New {{ $society->type == 'flat' ? 'Tower' : 'Block' }}
  </button>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
  @forelse($buildings as $building)
  <div class="card">
    <div class="card-header" style="background: #f8fafc;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
        <div>
          <h3 class="card-title">{{ $building->name }}</h3>
          <span style="font-size: .75rem; color: var(--muted);">{{ $building->units->count() }} Units {{ $society->type == 'flat' ? '• '.$building->floors.' Floors' : '' }}</span>
        </div>
        <div style="display: flex; gap: .5rem;">
          <button onclick="openAddUnitsModal({{ $building->id }}, '{{ $building->name }}')" class="btn btn-sm btn-outline" style="padding: .2rem .4rem;" title="Add Units">
            <i class="bi bi-plus-circle" style="font-size: .8rem;"></i>
          </button>
          <button onclick="openEditBuildingModal({{ $building->id }}, '{{ $building->name }}')" class="btn btn-sm btn-outline" style="padding: .2rem .4rem;" title="Rename">
            <i class="bi bi-pencil" style="font-size: .8rem;"></i>
          </button>
          <form action="{{ route('society-admin.structure.building.delete', $building) }}" method="POST" onsubmit="return confirm('Delete this building and all its units?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline" style="padding: .2rem .4rem; color: #dc2626;" title="Delete">
              <i class="bi bi-trash" style="font-size: .8rem;"></i>
            </button>
          </form>
        </div>
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
              @php $is_o = $unit->status == 'occupied' && $unit->owner; @endphp
              <div title="Unit: {{ $unit->unit_number }} ({{ ucfirst($unit->status) }})" 
                   onclick="manageUnit({{ json_encode($unit) }}, {{ $is_o ? 'true' : 'false' }}, '{{ $is_o ? $unit->owner->name : '' }}', '{{ $is_o ? $unit->owner->email : '' }}', '{{ $is_o ? $unit->owner->phone : '' }}', '{{ $is_o ? asset('storage/'.$unit->owner->document_path) : '' }}')"
                   style="height: 48px; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: .75rem; font-weight: 800; background: {{ $is_o ? '#dcfce7' : '#fff' }}; color: {{ $is_o ? '#15803d' : '#64748b' }}; border: 2px solid {{ $is_o ? '#bbf7d0' : '#f1f5f9' }}; transition: all .2s; cursor: pointer;">
                <span style="font-size: .65rem; opacity: .7; font-weight: 600;">{{ $unit->unit_number }}</span>
              </div>
              @endforeach
            </div>
          </div>
        @endforeach
      @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px;">
          @foreach($building->units->sortBy('unit_number') as $unit)
          @php $is_o = $unit->status == 'occupied' && $unit->owner; @endphp
          <div title="Unit: {{ $unit->unit_number }} ({{ ucfirst($unit->status) }})" 
               onclick="manageUnit({{ json_encode($unit) }}, {{ $is_o ? 'true' : 'false' }}, '{{ $is_o ? $unit->owner->name : '' }}', '{{ $is_o ? $unit->owner->email : '' }}', '{{ $is_o ? $unit->owner->phone : '' }}', '{{ $is_o ? asset('storage/'.$unit->owner->document_path) : '' }}')"
               style="height: 54px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: .8rem; font-weight: 800; background: {{ $is_o ? '#dcfce7' : '#fff' }}; color: {{ $is_o ? '#15803d' : '#64748b' }}; border: 2px solid {{ $is_o ? '#bbf7d0' : '#f1f5f9' }}; cursor: pointer;">
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
<!-- Add Units to Building Modal -->
<div id="addUnitsToBuildingModal" class="modal-overlay">
  <div class="modal-container" style="max-width: 400px;">
    <form action="{{ route('society-admin.structure.units.store') }}" method="POST">
      @csrf
      <div class="modal-header">
        <h3 class="card-title">Add Units to <span id="add_units_building_name"></span></h3>
        <button type="button" class="modal-close" onclick="closeModal('addUnitsToBuildingModal')">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="building_id" id="add_units_building_id" />
        <div class="form-group">
          <label>Start From Number *</label>
          <input type="number" name="start_number" class="form-control" placeholder="e.g. 101" required />
        </div>
        <div class="form-group">
          <label>How many units to add? *</label>
          <input type="number" name="count" class="form-control" min="1" max="100" placeholder="e.g. 5" required />
        </div>
        @if($society->type == 'flat')
          <div class="form-group">
            <label>Floor Number</label>
            <input type="number" name="floor" class="form-control" placeholder="e.g. 1" />
          </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('addUnitsToBuildingModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Units</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Building Modal -->
<div id="editBuildingModal" class="modal-overlay">
  <div class="modal-container" style="max-width: 400px;">
    <form id="editBuildingForm" method="POST">
      @csrf @method('PUT')
      <div class="modal-header">
        <h3 class="card-title">Rename {{ $society->type == 'flat' ? 'Tower' : 'Block' }}</h3>
        <button type="button" class="modal-close" onclick="closeModal('editBuildingModal')">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Name *</label>
          <input type="text" name="name" id="edit_building_name" class="form-control" required />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('editBuildingModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Name</button>
      </div>
    </form>
  </div>
</div>

<!-- Manage Unit Modal -->
<div id="manageUnitModal" class="modal-overlay">
  <div class="modal-container" style="max-width: 400px;">
    <div class="modal-header">
      <h3 class="card-title" id="manage_unit_title">Manage Unit</h3>
      <button type="button" class="modal-close" onclick="closeModal('manageUnitModal')">&times;</button>
    </div>
    <div class="modal-body">
      <!-- Resident Info Section (Visible if occupied) -->
      <div id="resident_info_section" style="display:none; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
        <div class="text-center">
          <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; margin: 0 auto 1rem; border: 3px solid var(--primary-light);">
            <img id="m_res_image" src="" style="width: 100%; height: 100%; object-fit: cover;" />
          </div>
          <h4 id="m_res_name" style="margin:0; color:var(--dark);"></h4>
          <p id="m_res_email" style="font-size: .8rem; color:var(--muted); margin-top:.2rem;"></p>
        </div>
      </div>

      <!-- Edit Unit Form -->
      <form id="editUnitForm" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
          <label>Unit Number *</label>
          <input type="text" name="unit_number" id="m_unit_number" class="form-control" required />
        </div>
        <div class="form-group">
          <label>Status *</label>
          <select name="status" id="m_unit_status" class="form-control" required>
            <option value="vacant">Vacant</option>
            <option value="occupied">Occupied</option>
            <option value="maintenance">Maintenance</option>
          </select>
        </div>
        <div style="display: flex; gap: .75rem; margin-top: 1.5rem;">
          <button type="submit" class="btn btn-primary" style="flex: 1;">Update Unit</button>
          <button type="button" id="deleteUnitBtn" class="btn btn-outline" style="color: #dc2626; border-color: #dc2626;">Delete</button>
        </div>
      </form>
      <form id="deleteUnitForm" method="POST" style="display:none;">
        @csrf @method('DELETE')
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  function openModal(id) { document.getElementById(id).classList.add('show'); }
  function closeModal(id) { document.getElementById(id).classList.remove('show'); }

  function openEditBuildingModal(id, name) {
    document.getElementById('edit_building_name').value = name;
    document.getElementById('editBuildingForm').action = "/society-admin/structure/building/" + id;
    openModal('editBuildingModal');
  }

  function openAddUnitsModal(id, name) {
    document.getElementById('add_units_building_id').value = id;
    document.getElementById('add_units_building_name').innerText = name;
    openModal('addUnitsToBuildingModal');
  }

  function manageUnit(unit, isOccupied, name, email, phone, image) {
    document.getElementById('manage_unit_title').innerText = 'Manage Unit ' + unit.unit_number;
    document.getElementById('m_unit_number').value = unit.unit_number;
    document.getElementById('m_unit_status').value = unit.status;
    document.getElementById('editUnitForm').action = "/society-admin/structure/unit/" + unit.id;
    
    const deleteForm = document.getElementById('deleteUnitForm');
    deleteForm.action = "/society-admin/structure/unit/" + unit.id;
    document.getElementById('deleteUnitBtn').onclick = () => {
      if(confirm('Are you sure you want to delete this unit?')) deleteForm.submit();
    };

    const resSection = document.getElementById('resident_info_section');
    if (isOccupied) {
      resSection.style.display = 'block';
      document.getElementById('m_res_name').innerText = name;
      document.getElementById('m_res_email').innerText = email;
      document.getElementById('m_res_image').src = image;
    } else {
      resSection.style.display = 'none';
    }

    openModal('manageUnitModal');
  }
</script>
@endsection
