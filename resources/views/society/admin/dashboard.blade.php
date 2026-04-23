@extends('society.layouts.society_admin')

@section('title', 'Admin Dashboard')
@section('page-title')
  Dashboard Overview
  <span class="badge {{ $society->type == 'flat' ? 'badge-role-1' : 'badge-role-2' }}" style="font-size: .65rem; padding: .15rem .6rem; margin-left: .5rem; vertical-align: middle;">
    <i class="bi {{ $society->type == 'flat' ? 'bi-building' : 'bi-house' }}"></i>
    {{ strtoupper($society->type) }} SOCIETY
  </span>
@endsection

@section('content')
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background: linear-gradient(135deg, #6C63FF, #574fd6);"><i class="bi bi-building"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['total_units'] }}</div>
      <div class="stat-label">Total Units</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38d39f);"><i class="bi bi-people"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['occupied_units'] }}</div>
      <div class="stat-label">Occupied Units</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: linear-gradient(135deg, #ff9a9e, #fecfef);"><i class="bi bi-person-check"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['total_users'] }}</div>
      <div class="stat-label">Total Residents</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: linear-gradient(135deg, #fad0c4, #ffd1ff);"><i class="bi bi-hourglass-split"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['pending_users'] }}</div>
      <div class="stat-label">Pending Approval</div>
    </div>
  </div>
</div>

<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
  <div class="stat-card">
    <div class="stat-icon" style="background: #10b981;"><i class="bi bi-wallet2"></i></div>
    <div class="stat-body">
      <div class="stat-value">₹{{ number_format($stats['total_funds'], 2) }}</div>
      <div class="stat-label">Total Society Funds (Credits)</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: #ef4444;"><i class="bi bi-cash-stack"></i></div>
    <div class="stat-body">
      <div class="stat-value">₹{{ number_format($stats['total_expenses'], 2) }}</div>
      <div class="stat-label">Total Expenses (Debits)</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: #f59e0b;"><i class="bi bi-receipt-cutoff"></i></div>
    <div class="stat-body">
      <div class="stat-value">{{ $stats['unpaid_bills'] }}</div>
      <div class="stat-label">Unpaid Bills</div>
    </div>
  </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Recent Passbook Entries</h3>
      <a href="{{ route('society-admin.passbook') }}" class="btn btn-outline btn-sm">View All</a>
    </div>
    <div class="card-body" style="padding:0;">
      <table style="width:100%;">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Category</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recent_activities as $entry)
          <tr>
            <td style="font-size:.8rem;">{{ $entry->entry_date }}</td>
            <td>
              <span class="badge {{ $entry->type == 'credit' ? 'badge-active' : 'badge-inactive' }}" style="background: {{ $entry->type == 'credit' ? '#dcfce7' : '#fee2e2' }}; color: {{ $entry->type == 'credit' ? '#15803d' : '#dc2626' }};">
                {{ ucfirst($entry->type) }}
              </span>
            </td>
            <td><strong>{{ $entry->category }}</strong></td>
            <td><strong>₹{{ number_format($entry->amount, 2) }}</strong></td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center; padding:2rem; color:var(--muted);">No recent transactions</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Society Info</h3>
    </div>
    <div class="card-body p-all">
      <div style="display:flex; flex-direction:column; gap:1rem;">
        <div>
          <div style="font-size:.7rem; font-weight:700; color:var(--muted); text-transform:uppercase;">Society Name</div>
          <div style="font-weight:700;">{{ $society->name }}</div>
        </div>
        <div>
          <div style="font-size:.7rem; font-weight:700; color:var(--muted); text-transform:uppercase;">Address</div>
          <div style="font-size:.85rem;">{{ $society->address }}, {{ $society->city }}, {{ $society->state }}</div>
        </div>
        <div>
          <div style="font-size:.7rem; font-weight:700; color:var(--muted); text-transform:uppercase;">Property Type</div>
          <div class="badge badge-role-1">{{ ucfirst($society->type) }} Society</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
