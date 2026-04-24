@extends('society.layouts.user')

@section('title', 'Resident Dashboard')
@section('page-title', 'Resident Dashboard')

@section('content')
<div class="stats-grid">
  <div class="card stat-card">
    <div class="stat-label">Total Contribution</div>
    <div class="stat-val">₹{{ number_format($totalPaid, 2) }}</div>
    <div style="font-size: .8rem; color: var(--success); font-weight: 700;">
      <i class="bi bi-graph-up-arrow"></i> Society Funds
    </div>
  </div>
  <div class="card stat-card border-danger" style="border-left: 4px solid var(--danger);">
    <div class="stat-label">Unpaid Dues</div>
    <div class="stat-val" style="color: var(--danger);">₹{{ number_format($pendingAmount, 2) }}</div>
    <div style="font-size: .8rem; color: var(--text-muted);">
      {{ $pendingAmount > 0 ? 'Action Required' : 'All Clear' }}
    </div>
  </div>
  <div class="card stat-card">
    <div class="stat-label">Unit Profile</div>
    <div class="stat-val" style="font-size: 1.4rem;">{{ Auth::user()->unit_number }}</div>
    <div style="font-size: .8rem; color: var(--text-muted); text-transform: capitalize;">
      Society: {{ Auth::user()->society->name }}
    </div>
  </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
  <!-- Recent Bills -->
  <div class="card">
    <div class="p-all" style="border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
      <h3 style="font-size: 1.1rem; font-weight: 800;">Recent Maintenance Bills</h3>
      <a href="{{ route('society.user.passbook') }}" class="btn btn-outline" style="padding: .4rem .8rem; font-size: .8rem;">View All</a>
    </div>
    <div class="card-body">
      <table class="table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentTransactions as $bill)
          <tr>
            <td>{{ $bill->month }} {{ $bill->year }}</td>
            <td>Maintenance Bill</td>
            <td style="font-weight: 700;">₹{{ number_format($bill->total_amount, 2) }}</td>
            <td>
              <span class="badge {{ $bill->status == 'paid' ? 'badge-success' : 'badge-danger' }}">
                {{ ucfirst($bill->status) }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 3rem;">No recent bills found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Quick Info -->
  <div class="card">
    <div class="p-all" style="border-bottom: 1px solid var(--border);">
      <h3 style="font-size: 1.1rem; font-weight: 800;">Resident Notice</h3>
    </div>
    <div class="p-all">
      <div style="padding: 1rem; background: var(--primary-light); border-radius: 12px; border-left: 4px solid var(--primary); margin-bottom: 1rem;">
        <p style="font-size: .85rem; color: var(--primary); font-weight: 600; line-height: 1.6;">
          Welcome to the new SocietyPro Resident portal. You can now track your maintenance and update profile here.
        </p>
      </div>
      <div style="font-size: .85rem; color: var(--text-muted);">
        <strong>Support:</strong> For any billing discrepancies, please contact your society admin directly.
      </div>
    </div>
  </div>
</div>
@endsection
