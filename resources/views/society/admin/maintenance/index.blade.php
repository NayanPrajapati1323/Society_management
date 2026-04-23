@extends('society.layouts.society_admin')

@section('title', 'Maintenance')
@section('page-title', 'Maintenance Billing')

@section('content')
<div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
  <button class="btn btn-primary" onclick="openModal('addBillModal')">
    <i class="bi bi-plus-lg"></i> Generate New Bill
  </button>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">All Maintenance Bills</h3>
  </div>
  <div class="card-body" style="padding: 0;">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Unit</th>
            <th>Owner</th>
            <th>Period</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date Generated</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bills as $bill)
          <tr>
            <td><strong>{{ $bill->unit->unit_number ?? 'N/A' }}</strong></td>
            <td>{{ $bill->unit->owner->name ?? 'Vacant' }}</td>
            <td>{{ $bill->month }} {{ $bill->year }}</td>
            <td><strong>₹{{ number_format($bill->total_amount, 2) }}</strong></td>
            <td>
              <span class="badge {{ $bill->status == 'paid' ? 'badge-active' : 'badge-pending' }}">
                {{ ucfirst($bill->status) }}
              </span>
            </td>
            <td>{{ $bill->created_at->format('d M, Y') }}</td>
          </tr>
          @empty
          <tr><td colspan="6" style="text-align:center; padding:3rem; color:var(--muted);">No bills generated yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Bill Modal -->
<div id="addBillModal" class="modal-overlay">
  <div class="modal-container">
    <form action="{{ route('society-admin.maintenance.bill.store') }}" method="POST">
      @csrf
      <div class="modal-header">
        <h3 class="card-title">Generate Maintenance Bill</h3>
        <button type="button" style="background:none; border:none; font-size:1.5rem; cursor:pointer;" onclick="closeModal('addBillModal')">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Select Unit *</label>
          <select name="unit_id" class="form-select" required>
            <option value="">Choose Unit</option>
            @foreach($units as $unit)
            <option value="{{ $unit->id }}">Unit {{ $unit->unit_number }} ({{ $unit->owner->name ?? 'Vacant' }})</option>
            @endforeach
          </select>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="form-group">
            <label>Month *</label>
            <select name="month" class="form-select" required>
              @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
              <option value="{{ $m }}" {{ date('F') == $m ? 'selected' : '' }}>{{ $m }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Year *</label>
            <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required />
          </div>
        </div>
        <div class="form-group">
          <label>Total Amount (₹) *</label>
          <input type="number" step="0.01" name="amount" class="form-control" placeholder="e.g. 2500" required />
        </div>
        <div class="form-group">
          <label>Notes/Details</label>
          <textarea name="description" class="form-control" placeholder="Breakdown of charges..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('addBillModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Generate Bill</button>
      </div>
    </form>
  </div>
</div>
@endsection
