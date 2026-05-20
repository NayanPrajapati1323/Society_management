@extends('society.layouts.society_admin')

@section('title', 'Visitor Log')
@section('page-title', 'Visitor Log')

@section('content')
<style>
    .badge-info { background: #e0f2fe; color: #0369a1; }
    .badge-success { background: #dcfce7; color: #15803d; }
    .badge-danger { background: #fee2e2; color: #b91c1c; }
    .badge-muted { background: #f1f5f9; color: #475569; }
    
    .btn-sm { padding: .4rem .75rem; font-size: .8rem; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; transition: all 0.2s; }
    .btn-success { background: #10b981; color: #fff; }
    .btn-success:hover { background: #059669; }
    .btn-danger { background: #ef4444; color: #fff; }
    .btn-danger:hover { background: #dc2626; }
    
    .table th { font-weight: 600; color: #475569; font-size: 0.85rem; }
    .table td { vertical-align: middle; }
</style>

<div class="card p-all">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="card-title">Daily Visitor Log</h2>
        <div style="display: flex; gap: .5rem;">
            <button class="btn btn-outline" onclick="alert('Export feature coming soon!')">
                <i class="bi bi-download"></i> Export
            </button>
            <button class="btn btn-primary" onclick="openModal('addVisitorModal')">
                <i class="bi bi-plus-lg"></i> Add Visitor
            </button>
        </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="table-wrap">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 1rem; border-bottom: 1px solid var(--border);">Visitor</th>
                    <th style="text-align: left; padding: 1rem; border-bottom: 1px solid var(--border);">Visiting</th>
                    <th style="text-align: left; padding: 1rem; border-bottom: 1px solid var(--border);">Type</th>
                    <th style="text-align: left; padding: 1rem; border-bottom: 1px solid var(--border);">In Time</th>
                    <th style="text-align: left; padding: 1rem; border-bottom: 1px solid var(--border);">Out Time</th>
                    <th style="text-align: left; padding: 1rem; border-bottom: 1px solid var(--border);">Status</th>
                    <th style="text-align: left; padding: 1rem; border-bottom: 1px solid var(--border);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                <tr>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border);">
                        <strong>{{ $entry->visitor->name }}</strong><br>
                        <small style="color: var(--text-muted);">{{ $entry->visitor->mobile }}</small>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border);">
                        {{ $entry->unit ? 'Unit ' . $entry->unit->unit_number : 'N/A' }}
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border);">{{ $entry->visitorType->name }}</td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border);">
                        {{ $entry->entry_time ? \Carbon\Carbon::parse($entry->entry_time)->format('h:i A') : 'N/A' }}
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border);">
                        {{ $entry->exit_time ? \Carbon\Carbon::parse($entry->exit_time)->format('h:i A') : 'N/A' }}
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border);">
                        @php
                            $badgeClass = 'muted';
                            if ($entry->status == 'In Society') $badgeClass = 'success';
                            if ($entry->status == 'Pre-Approved') $badgeClass = 'info';
                            if ($entry->status == 'Completed') $badgeClass = 'muted';
                        @endphp
                        <span class="badge badge-{{ $badgeClass }}">
                            {{ $entry->status }}
                        </span>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border);">
                        @if($entry->status == 'Pre-Approved')
                        <form action="{{ route('society-admin.visitors.update-status', $entry->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="In Society">
                            <button type="submit" class="btn-sm btn-success">Check In</button>
                        </form>
                        @elseif($entry->status == 'In Society')
                        <form action="{{ route('society-admin.visitors.update-status', $entry->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Completed">
                            <button type="submit" class="btn-sm btn-danger">Check Out</button>
                        </form>
                        @else
                        <span style="color: var(--text-muted); font-size: .85rem;">No actions</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                        No entries found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Visitor Modal -->
<div class="modal-overlay" id="addVisitorModal">
  <form action="{{ route('society-admin.visitors.store') }}" method="POST" class="modal-container">
    @csrf
    <div class="modal-header">
      <h3 class="card-title">Add Visitor</h3>
      <button type="button" class="btn btn-sm" onclick="closeModal('addVisitorModal')">&times;</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label>Visitor Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Mobile Number</label>
        <input type="text" name="mobile" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Vehicle Number</label>
        <input type="text" name="vehicle_number" class="form-control">
      </div>
      <div class="form-group">
        <label>Visitor Type</label>
        <select name="visitor_type_id" class="form-select" required style="width: 100%; padding: .75rem 1rem; border-radius: 12px; border: 1px solid var(--border); outline: none;">
          @foreach($visitor_types as $type)
            <option value="{{ $type->id }}">{{ $type->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="margin-top: 1.25rem;">
        <label>Flat (Unit)</label>
        <select name="society_unit_id" class="form-select" required style="width: 100%; padding: .75rem 1rem; border-radius: 12px; border: 1px solid var(--border); outline: none;">
          @foreach($units as $unit)
            <option value="{{ $unit->id }}">Unit {{ $unit->unit_number }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="margin-top: 1.25rem;">
        <label>Purpose</label>
        <input type="text" name="purpose" class="form-control">
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline" onclick="closeModal('addVisitorModal')">Cancel</button>
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </form>
</div>
@endsection
