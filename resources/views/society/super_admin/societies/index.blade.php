@extends('society.layouts.super_admin')
@section('title', 'Manage Societies')
@section('page-title', 'Societies')
@section('breadcrumb', 'Super Admin / Manage Societies')

@section('extra-styles')
<style>
  /* Premium Modal Styles */
  .modal-close { cursor: pointer; border: none; background: #f3f4f6; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: var(--muted); transition: all .2s; }
  .modal-close:hover { background: #fee2e2; color: #ef4444; }
</style>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div class="card-title"><i class="bi bi-buildings" style="color:var(--primary);margin-right:.4rem;"></i> All Societies</div>
    <button onclick="openModal('addSocietyModal')" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-circle-fill"></i> Add Society
    </button>
  </div>
  <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);">
    <form method="GET" class="filter-bar">
      <input type="text" name="search" placeholder="Search society name..." value="{{ request('search') }}" style="flex:1;min-width:200px;" />
      <select name="status">
        <option value="">All Status</option>
        <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
        <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive / Pending</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Search</button>
      <a href="{{ route('super-admin.societies') }}" class="btn btn-outline btn-sm">Reset</a>
    </form>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Society Name</th>
          <th>Type</th>
          <th>Location</th>
          <th>Plan</th>
          <th>Admin Auth</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($societies as $society)
        <tr>
          <td style="color:var(--muted);font-size:.8rem;">{{ $loop->iteration }}</td>
          <td>
            <div class="table-name">{{ $society->name }}</div>
            <div class="table-sub">{{ $society->contact_email ?? 'No email' }}</div>
          </td>
          <td>
            @if($society->type == 'flat')
              <span class="badge" style="background:#e0f2fe; color:#0369a1;"><i class="bi bi-building"></i> Flat</span>
            @else
              <span class="badge" style="background:#fef2f2; color:#b91c1c;"><i class="bi bi-house-door"></i> Row House</span>
            @endif
          </td>
          <td>
            <div class="table-name" style="font-size:.8rem;">{{ $society->city }}, {{ $society->state }}</div>
            <div class="table-sub">{{ $society->country }}</div>
          </td>
          <td>
            @if($society->plan)
              <div class="table-name" style="font-size:.85rem; font-weight:600;">{{ $society->plan->name }}</div>
              <div style="font-size:.7rem; color:var(--muted); margin-top:.2rem; display:flex; gap:.3rem; flex-wrap:wrap;">
                @if($society->plan_duration == 12)
                  <span class="badge" style="background:#dcfce7; color:#166534; font-size:.65rem; padding:.1rem .3rem;">13 Month Active</span>
                @else
                  <span class="badge" style="background:#f3f4f6; color:#374151; font-size:.65rem; padding:.1rem .3rem;">{{ $society->plan_duration }} Month Active</span>
                @endif
                
                @if($society->has_website)
                  <span class="badge" style="background:#e0e7ff; color:#4338ca; font-size:.65rem; padding:.1rem .3rem;"><i class="bi bi-globe"></i> Website</span>
                @endif
              </div>
              <div style="font-size:.7rem; margin-top:.3rem;">
                @if($society->is_plan_expired)
                  <span style="color:#ef4444; font-weight:700;"><i class="bi bi-exclamation-circle"></i> Expired</span>
                @else
                  <span style="color:var(--muted);">Expires: {{ \Carbon\Carbon::parse($society->plan_expiry_date)->format('d M Y') }}</span>
                @endif
              </div>
            @else
              <span style="color:var(--muted);font-size:.8rem;">No Plan</span>
            @endif
          </td>
          <td>
            @if($society->admin)
              <div class="table-name" style="font-size:.8rem;">{{ $society->admin->email }}</div>
              <div class="table-sub">Admin ID: {{ $society->admin->id }}</div>
            @else
              <button onclick="openAdminModal({{ $society->id }}, '{{ addslashes($society->name) }}')" class="btn btn-sm btn-outline" style="padding:.2rem .5rem; font-size:.7rem;">
                <i class="bi bi-person-plus"></i> Create Admin
              </button>
            @endif
          </td>
          <td>
            @if($society->is_active)
              <span class="badge badge-active"><i class="bi bi-dot"></i> Active</span>
            @else
              <span class="badge badge-pending"><i class="bi bi-dot"></i> Pending</span>
            @endif
          </td>
          <td>
            <div style="display:flex;gap:.3rem;flex-wrap:wrap;">
              @if($society->is_plan_expired)
                <button onclick="openRenewModal({{ json_encode($society) }})" class="btn btn-sm btn-danger" title="Renew Plan">
                  <i class="bi bi-arrow-repeat"></i> Renew
                </button>
              @endif
              <button onclick="openEditModal({{ json_encode($society) }})" class="btn btn-sm btn-outline" title="Edit Society"><i class="bi bi-pencil-fill"></i></button>
              <form action="{{ route('super-admin.societies.toggle', $society) }}" method="POST" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $society->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $society->is_active ? 'Deactivate' : 'Activate' }}">
                  <i class="bi bi-{{ $society->is_active ? 'pause-circle' : 'play-circle' }}-fill"></i>
                </button>
              </form>
              <form action="{{ route('super-admin.societies.delete', $society) }}" method="POST" style="display:inline;"
                    onsubmit="return confirm('Delete {{ addslashes($society->name) }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align:center;color:var(--muted);padding:3rem;">
            <i class="bi bi-buildings" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:.75rem;"></i>
            No societies found.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Modal: Add Society --}}
<div id="addSocietyModal" class="modal-overlay">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="card-title" style="margin:0; font-size:1.2rem;"><i class="bi bi-plus-circle-fill" style="color:var(--primary);"></i> Add New Society</h3>
      <button onclick="closeModal('addSocietyModal')" class="modal-close">&times;</button>
    </div>
    <form action="{{ route('super-admin.societies.store') }}" method="POST">
      @csrf
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group full">
            <label>Society Name *</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Green Valley Society" required />
          </div>
          <div class="form-group full">
            <label>Society Type *</label>
            <div class="choice-grid">
              <label class="choice-card">
                <input type="radio" name="type" value="flat" checked>
                <i class="bi bi-building"></i>
                <div>Flat Society</div>
              </label>
              <label class="choice-card">
                <input type="radio" name="type" value="row_house">
                <i class="bi bi-house-door"></i>
                <div>Row House</div>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label>Contact Email *</label>
            <input type="email" name="contact_email" class="form-control" placeholder="e.g. info@society.com" required />
          </div>
          <div class="form-group">
            <label>Contact Phone</label>
            <input type="text" name="contact_phone" class="form-control" placeholder="e.g. +91 9876543210" />
          </div>
          <div class="form-group full">
            <label>Address *</label>
            <textarea name="address" class="form-control" placeholder="Society location address..." rows="2" required></textarea>
          </div>
          <div class="form-group">
            <label>Country</label>
            <input type="text" value="India" class="form-control" disabled />
            <input type="hidden" name="country" value="India" />
          </div>
          <div class="form-group">
            <label>State *</label>
            <select name="state" id="stateSelect" class="form-control" required onchange="updateCities('stateSelect', 'citySelect')">
              <option value="">Select State</option>
            </select>
          </div>
          <div class="form-group">
            <label>City *</label>
            <select name="city" id="citySelect" class="form-control" required>
              <option value="">Select City</option>
            </select>
          </div>
          <div class="form-group">
            <label>Pincode</label>
            <input type="text" name="pincode" class="form-control" placeholder="380015" />
          </div>
          <div class="form-group">
            <label>Select Plan *</label>
            <select name="plan_id" id="add_plan_id" class="form-control" required onchange="calculatePrice('add')">
              <option value="">Select Plan</option>
              @foreach($plans as $p)
                <option value="{{ $p->id }}" data-price="{{ $p->monthly_price }}" data-website-price="{{ $p->website_price }}" data-maintenance-price="{{ $p->website_maintenance_price }}">{{ $p->name }} (₹{{ $p->monthly_price }}/mo)</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Plan Duration *</label>
            <select name="plan_duration" id="add_plan_duration" class="form-control" required onchange="calculatePrice('add')">
              <option value="6">6 Months</option>
              <option value="12">12 Months (+1 Month Extra)</option>
            </select>
          </div>
          <div class="form-group full" style="display:flex; align-items:center; gap:.5rem;">
            <label class="toggle-switch">
              <input type="checkbox" name="has_website" id="add_has_website" value="1" onchange="calculatePrice('add')" />
              <span class="toggle-slider"></span>
            </label>
            <label style="margin:0;">Include Society Website?</label>
          </div>
          <div class="form-group full" id="add_price_display" style="display:none; background:var(--primary-light); padding:.75rem; border-radius:.5rem; border:1px solid var(--primary); margin-top:.5rem;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-weight:600; color:var(--primary);">Total Price:</span>
              <span id="add_total_price" style="font-weight:700; color:var(--primary); font-size:1.1rem;">₹0.00</span>
            </div>
            <div id="add_plan_info" style="font-size:.75rem; color:var(--primary); margin-top:.25rem;"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" style="flex:1;">Create Society</button>
        <button type="button" onclick="closeModal('addSocietyModal')" class="btn btn-outline" style="flex:1;">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal: Edit Society --}}
<div id="editSocietyModal" class="modal-overlay">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="card-title" style="margin:0; font-size:1.2rem;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Society</h3>
      <button onclick="closeModal('editSocietyModal')" class="modal-close">&times;</button>
    </div>
    <form id="editSocietyForm" method="POST">
      @csrf @method('PUT')
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group full">
            <label>Society Name *</label>
            <input type="text" name="name" id="edit_name" class="form-control" required />
          </div>
          <div class="form-group full">
            <label>Society Type (Fixed)</label>
            <div class="choice-grid" style="opacity:.7; pointer-events:none;">
              <label class="choice-card">
                <input type="radio" name="type_static" id="edit_type_flat" value="flat">
                <i class="bi bi-building"></i>
                <div>Flat Society</div>
              </label>
              <label class="choice-card">
                <input type="radio" name="type_static" id="edit_type_row" value="row_house">
                <i class="bi bi-house-door"></i>
                <div>Row House</div>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label>Contact Email *</label>
            <input type="email" name="contact_email" id="edit_email" class="form-control" required />
          </div>
          <div class="form-group">
            <label>Contact Phone</label>
            <input type="text" name="contact_phone" id="edit_phone" class="form-control" />
          </div>
          <div class="form-group full">
            <label>Address *</label>
            <textarea name="address" id="edit_address" class="form-control" rows="2" required></textarea>
          </div>
          <div class="form-group">
            <label>Country</label>
            <input type="text" value="India" class="form-control" disabled />
          </div>
          <div class="form-group">
            <label>State *</label>
            <select name="state" id="editStateSelect" class="form-control" required onchange="updateCities('editStateSelect', 'editCitySelect')">
              <option value="">Select State</option>
            </select>
          </div>
          <div class="form-group">
            <label>City *</label>
            <select name="city" id="editCitySelect" class="form-control" required>
              <option value="">Select City</option>
            </select>
          </div>
          <div class="form-group">
            <label>Pincode</label>
            <input type="text" name="pincode" id="edit_pincode" class="form-control" />
          </div>
          <div class="form-group">
            <label>Select Plan *</label>
            <select name="plan_id" id="edit_plan_id" class="form-control" required onchange="calculatePrice('edit')">
              <option value="">No Plan</option>
              @foreach($plans as $plan)
                <option value="{{ $plan->id }}" data-price="{{ $plan->monthly_price }}" data-website-price="{{ $plan->website_price }}" data-maintenance-price="{{ $plan->website_maintenance_price }}">{{ $plan->name }} (₹{{ $plan->monthly_price }}/mo)</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Plan Duration *</label>
            <select name="plan_duration" id="edit_plan_duration" class="form-control" required onchange="calculatePrice('edit')">
              <option value="6">6 Months</option>
              <option value="12">12 Months (+1 Month Extra)</option>
            </select>
          </div>
          <div class="form-group full" style="display:flex; align-items:center; gap:.5rem;">
            <label class="toggle-switch">
              <input type="checkbox" name="has_website" id="edit_has_website" value="1" onchange="calculatePrice('edit')" />
              <span class="toggle-slider"></span>
            </label>
            <label style="margin:0;" id="edit_website_label">Include Society Website?</label>
          </div>
          <div class="form-group full" id="edit_price_display" style="display:none; background:var(--primary-light); padding:.75rem; border-radius:.5rem; border:1px solid var(--primary); margin-top:.5rem;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-weight:600; color:var(--primary);">Total Price:</span>
              <span id="edit_total_price" style="font-weight:700; color:var(--primary); font-size:1.1rem;">₹0.00</span>
            </div>
            <div id="edit_plan_info" style="font-size:.75rem; color:var(--primary); margin-top:.25rem;"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" style="flex:1;">Save Changes</button>
        <button type="button" onclick="closeModal('editSocietyModal')" class="btn btn-outline" style="flex:1;">Cancel</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal: Renew Plan --}}
<div id="renewPlanModal" class="modal-overlay">
  <div class="modal-container" style="max-width:450px;">
    <div class="modal-header">
      <h3 class="card-title" style="margin:0; font-size:1.2rem;"><i class="bi bi-arrow-repeat" style="color:var(--primary);"></i> Renew Plan</h3>
      <button onclick="closeModal('renewPlanModal')" class="modal-close">&times;</button>
    </div>
    <form id="renewPlanForm" method="POST">
      @csrf @method('PUT')
      <div class="modal-body">
        <input type="hidden" name="name" id="renew_hidden_name" />
        <div id="renew_society_name" style="font-weight:700; color:var(--dark); margin-bottom:1rem; font-size:1rem; border-bottom:1px solid var(--border); padding-bottom:.5rem;"></div>
        <div class="form-grid" style="display:block;">
          <div class="form-group" style="margin-bottom:1rem;">
            <label>Select Plan *</label>
            <select name="plan_id" id="renew_plan_id" class="form-control" required onchange="calculatePrice('renew')">
              <option value="">No Plan</option>
              @foreach($plans as $plan)
                <option value="{{ $plan->id }}" data-price="{{ $plan->monthly_price }}" data-website-price="{{ $plan->website_price }}" data-maintenance-price="{{ $plan->website_maintenance_price }}">{{ $plan->name }} (₹{{ $plan->monthly_price }}/mo)</option>
              @endforeach
            </select>
          </div>
          <div class="form-group" style="margin-bottom:1rem;">
            <label>Plan Duration *</label>
            <select name="plan_duration" id="renew_plan_duration" class="form-control" required onchange="calculatePrice('renew')">
              <option value="6">6 Months</option>
              <option value="12">12 Months (+1 Month Extra)</option>
            </select>
          </div>
          <div class="form-group" style="display:flex; align-items:center; gap:.5rem; margin-bottom:1rem;">
            <label class="toggle-switch">
              <input type="checkbox" name="has_website" id="renew_has_website" value="1" onchange="calculatePrice('renew')" />
              <span class="toggle-slider"></span>
            </label>
            <label style="margin:0;" id="renew_website_label">Include Society Website?</label>
          </div>
          <div class="form-group" id="renew_price_display" style="display:none; background:var(--primary-light); padding:.75rem; border-radius:.5rem; border:1px solid var(--primary); margin-top:.5rem;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-weight:600; color:var(--primary);">Total Renewal Price:</span>
              <span id="renew_total_price" style="font-weight:700; color:var(--primary); font-size:1.1rem;">₹0.00</span>
            </div>
            <div id="renew_plan_info" style="font-size:.75rem; color:var(--primary); margin-top:.25rem;"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" style="flex:1;">Renew Plan Now</button>
        <button type="button" onclick="closeModal('renewPlanModal')" class="btn btn-outline" style="flex:1;">Cancel</button>
      </div>
    </form>
  </div>
</div>
<div id="createAdminModal" class="modal-overlay">
  <div class="modal-container" style="max-width:400px;">
    <div class="modal-header">
      <h3 class="card-title" style="margin:0; font-size:1.1rem;"><i class="bi bi-person-badge-fill" style="color:var(--primary);"></i> Create Society Admin</h3>
      <button onclick="closeModal('createAdminModal')" class="modal-close">&times;</button>
    </div>
    <div class="modal-body" style="overflow:visible;">
      <p id="adminModalSub" style="font-size:.85rem; color:var(--muted); margin-bottom:1.5rem;"></p>
      <form id="adminForm" method="POST">
        @csrf
        <div class="form-group">
          <label>Admin Name *</label>
          <input type="text" name="admin_name" class="form-control" placeholder="Full Name" required />
        </div>
        <div class="form-group">
          <label>Admin Email (Login ID) *</label>
          <input type="email" name="admin_email" class="form-control" placeholder="admin@email.com" required />
        </div>
        <div class="form-group">
          <label>Password *</label>
          <input type="password" name="admin_password" class="form-control" placeholder="Minimum 6 characters" required />
        </div>
        <div style="margin-top:1.5rem; display:flex; gap:.75rem;">
          <button type="submit" class="btn btn-primary" style="flex:1;">Create Account</button>
          <button type="button" onclick="closeModal('createAdminModal')" class="btn btn-outline" style="flex:1;">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  const stateCityData = {
    "Gujarat": ["Ahmedabad", "Surat", "Vadodara", "Rajkot", "Bhavnagar"],
    "Maharashtra": ["Mumbai", "Pune", "Nagpur", "Thane", "Nashik"],
    "Rajasthan": ["Jaipur", "Jodhpur", "Udaipur", "Kota", "Ajmer"],
    "Delhi": ["New Delhi", "North Delhi", "South Delhi"],
    "Karnataka": ["Bengaluru", "Mysuru", "Hubballi", "Belagavi"],
    "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai", "Salem"]
  };

  function openModal(id) { document.getElementById(id).classList.add('show'); }
  function closeModal(id) { document.getElementById(id).classList.remove('show'); }

  function openAdminModal(societyId, societyName) {
    document.getElementById('adminModalSub').innerText = "Generating credentials for " + societyName;
    document.getElementById('adminForm').action = "/super-admin/societies/" + societyId + "/admin";
    openModal('createAdminModal');
  }

  function calculatePrice(prefix) {
    const planSelect = document.getElementById(prefix + '_plan_id');
    const durationSelect = document.getElementById(prefix + '_plan_duration');
    const websiteCheckbox = document.getElementById(prefix + '_has_website');
    const priceDisplay = document.getElementById(prefix + '_price_display');
    const totalPriceEl = document.getElementById(prefix + '_total_price');
    const planInfoEl = document.getElementById(prefix + '_plan_info');

    const selectedOption = planSelect.options[planSelect.selectedIndex];
    if (!selectedOption.value) {
      priceDisplay.style.display = 'none';
      return;
    }

    const monthlyPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    const websitePrice = parseFloat(selectedOption.getAttribute('data-website-price')) || 0;
    const maintenancePrice = parseFloat(selectedOption.getAttribute('data-maintenance-price')) || 0;
    const duration = parseInt(durationSelect.value) || 6;
    
    let total = (monthlyPrice * duration);
    let info = duration + " Months Plan";
    
    if (websiteCheckbox.checked) {
      if ((prefix === 'edit' || prefix === 'renew') && currentSocietyHasWebsite) {
        total += maintenancePrice;
        info += " + Website Maintenance (₹" + maintenancePrice.toFixed(2) + ")";
      } else {
        total += websitePrice;
        info += " + Website Activation (₹" + websitePrice.toFixed(2) + ")";
      }
    }

    if (duration === 12) {
      info += " | 13 Months Total";
    }

    totalPriceEl.innerText = "₹" + total.toFixed(2);
    planInfoEl.innerText = info;
    priceDisplay.style.display = 'block';
  }

  let currentSocietyHasWebsite = false;
  function openEditModal(society) {
    currentSocietyHasWebsite = !!society.has_website;
    const label = document.getElementById('edit_website_label');
    if (currentSocietyHasWebsite) {
      label.innerText = "Website Maintenance (Annual)";
    } else {
      label.innerText = "Include Society Website?";
    }

    document.getElementById('editSocietyForm').action = "/super-admin/societies/" + society.id;
    document.getElementById('edit_name').value = society.name;
    document.getElementById('edit_email').value = society.contact_email || '';
    document.getElementById('edit_phone').value = society.contact_phone || '';
    document.getElementById('edit_address').value = society.address || '';
    document.getElementById('edit_pincode').value = society.pincode || '';
    document.getElementById('edit_plan_id').value = society.plan_id || '';
    document.getElementById('edit_plan_duration').value = society.plan_duration || '6';
    document.getElementById('edit_has_website').checked = !!society.has_website;
    
    calculatePrice('edit');
    
    if(society.type === 'flat') document.getElementById('edit_type_flat').checked = true;
    else document.getElementById('edit_type_row').checked = true;

    // Set state and city
    document.getElementById('editStateSelect').value = society.state || '';
    updateCities('editStateSelect', 'editCitySelect');
    document.getElementById('editCitySelect').value = society.city || '';

    openModal('editSocietyModal');
  }

  function openRenewModal(society) {
    currentSocietyHasWebsite = !!society.has_website;
    const label = document.getElementById('renew_website_label');
    if (currentSocietyHasWebsite) {
      label.innerText = "Website Maintenance (Annual)";
    } else {
      label.innerText = "Include Society Website?";
    }

    document.getElementById('renewPlanForm').action = "/super-admin/societies/" + society.id;
    document.getElementById('renew_society_name').innerText = "Renewal for: " + society.name;
    document.getElementById('renew_hidden_name').value = society.name;
    document.getElementById('renew_plan_id').value = society.plan_id || '';
    document.getElementById('renew_plan_duration').value = society.plan_duration || '6';
    document.getElementById('renew_has_website').checked = !!society.has_website;
    
    calculatePrice('renew');
    
    openModal('renewPlanModal');
  }

  function initStates(selectId) {
    const sel = document.getElementById(selectId);
    Object.keys(stateCityData).forEach(state => {
      let opt = document.createElement('option');
      opt.value = state;
      opt.innerHTML = state;
      sel.appendChild(opt);
    });
  }

  function updateCities(stateId, cityId) {
    const state = document.getElementById(stateId).value;
    const sel = document.getElementById(cityId);
    sel.innerHTML = '<option value="">Select City</option>';
    if (state && stateCityData[state]) {
      stateCityData[state].forEach(city => {
        let opt = document.createElement('option');
        opt.value = city;
        opt.innerHTML = city;
        sel.appendChild(opt);
      });
    }
  }

  initStates('stateSelect');
  initStates('editStateSelect');

  // Close modals on overlay click
  window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
      event.target.classList.remove('show');
    }
  }
</script>
@endsection
