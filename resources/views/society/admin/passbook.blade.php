@extends('society.layouts.society_admin')

@section('title', 'Passbook')
@section('page-title', 'Financial Ledger')

@section('content')
<div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
  <div class="filter-bar">
    <form method="GET" style="display:flex; gap:.5rem;">
      <select name="month" class="form-select" style="width: auto;">
        <option value="">All Months</option>
        @for($i=1; $i<=12; $i++)
        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i,1)) }}</option>
        @endfor
      </select>
      <input type="number" name="year" class="form-control" placeholder="Year" value="{{ request('year', date('Y')) }}" style="width: 100px;" />
      <button type="submit" class="btn btn-outline">Filter</button>
    </form>
  </div>
  <button class="btn btn-primary" onclick="openModal('addEntryModal')">
    <i class="bi bi-plus-lg"></i> Add New Entry
  </button>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Transaction History</h3>
    <div style="display:flex; gap:1.5rem;">
      <div style="font-size:.8rem;">
        <span style="color:var(--muted);">Credits:</span> <span style="color:#10b981; font-weight:700;">₹{{ number_format($entries->where('type','credit')->sum('amount'), 2) }}</span>
      </div>
      <div style="font-size:.8rem;">
        <span style="color:var(--muted);">Debits:</span> <span style="color:#ef4444; font-weight:700;">₹{{ number_format($entries->where('type','debit')->sum('amount'), 2) }}</span>
      </div>
    </div>
  </div>
  <div class="card-body" style="padding: 0;">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Category</th>
            <th>Description</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>
          @forelse($entries as $entry)
          <tr>
            <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($entry->entry_date)->format('d M, Y') }}</td>
            <td>
              <span class="badge {{ $entry->type == 'credit' ? 'badge-active' : 'badge-inactive' }}" style="background: {{ $entry->type == 'credit' ? '#dcfce7' : '#fee2e2' }}; color: {{ $entry->type == 'credit' ? '#15803d' : '#dc2626' }};">
                {{ ucfirst($entry->type) }}
              </span>
            </td>
            <td><strong>{{ $entry->category }}</strong></td>
            <td style="max-width: 300px; color: var(--muted); font-size: .8rem;">{{ $entry->description }}</td>
            <td><strong style="color: {{ $entry->type == 'credit' ? '#10b981' : '#ef4444' }};">₹{{ number_format($entry->amount, 2) }}</strong></td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center; padding:3rem; color:var(--muted);">No financial records found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Entry Modal -->
<div id="addEntryModal" class="modal-overlay">
  <div class="modal-container">
    <form action="{{ route('society-admin.passbook.entry.store') }}" method="POST">
      @csrf
      <div class="modal-header">
        <h3 class="card-title">Add Passbook Entry</h3>
        <button type="button" style="background:none; border:none; font-size:1.5rem; cursor:pointer;" onclick="closeModal('addEntryModal')">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Transaction Type *</label>
          <select name="type" class="form-select" required>
            <option value="credit">Credit (Inflow/Fund)</option>
            <option value="debit">Debit (Outflow/Expense)</option>
          </select>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="form-group">
            <label>Amount (₹) *</label>
            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required />
          </div>
          <div class="form-group">
            <label>Date *</label>
            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required />
          </div>
        </div>
        <div class="form-group">
          <label>Category *</label>
          <select name="category" class="form-select" required>
            <option value="Maintenance">Maintenance Collection</option>
            <option value="Utility Bill">Utility Bill Payment</option>
            <option value="Salary">Staff Salary</option>
            <option value="Repair">Repairs & Maintenance</option>
            <option value="Event">Event/Festival Expense</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label>Description</label>
          <textarea name="description" class="form-control" placeholder="Brief details about the transaction..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('addEntryModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Entry</button>
      </div>
    </form>
  </div>
</div>
@endsection
