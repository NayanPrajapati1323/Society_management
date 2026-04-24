@extends('society.layouts.user')

@section('title', 'Resident Passbook')
@section('page-title', 'Resident Passbook')

@section('content')
<div class="card p-all" style="margin-bottom: 2rem; background: #f8fafc;">
  <form action="{{ route('society.user.passbook') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
    <div class="form-group" style="margin-bottom: 0;">
      <label>Filter Month</label>
      <select name="month" class="form-control" style="min-width: 150px;">
        <option value="">All Months</option>
        @foreach(range(1, 12) as $m)
          <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="form-group" style="margin-bottom: 0;">
      <label>Filter Year</label>
      <select name="year" class="form-control" style="min-width: 120px;">
        <option value="">All Years</option>
        @foreach(range(date('Y'), date('Y')-5) as $y)
          <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endforeach
      </select>
    </div>
    <div style="display: flex; gap: .5rem;">
      <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Apply Filter</button>
      <a href="{{ route('society.user.passbook') }}" class="btn btn-outline">Reset</a>
    </div>
    <div style="margin-left: auto;">
      <button type="submit" name="export" value="pdf" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444;">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
      </button>
    </div>
  </form>
</div>

<div class="card p-all">
  <table class="table">
    <thead style="background: #f1f5f9;">
      <tr>
        <th style="border-radius: 10px 0 0 10px;">Bill ID</th>
        <th>Billing Date</th>
        <th>Amount</th>
        <th>Due Date</th>
        <th>Status</th>
        <th style="border-radius: 0 10px 10px 0;">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($bills as $bill)
      <tr>
        <td>#MB-{{ $bill->id }}</td>
        <td>{{ $bill->month }} {{ $bill->year }}</td>
        <td style="font-weight: 700;">₹{{ number_format($bill->total_amount, 2) }}</td>
        <td>N/A</td>
        <td>
          <span class="badge {{ $bill->status == 'paid' ? 'badge-success' : 'badge-danger' }}">
            {{ ucfirst($bill->status) }}
          </span>
        </td>
        <td>
          @if($bill->status == 'paid')
            <span style="color: var(--success); font-weight: 600;"><i class="bi bi-check-circle"></i> Confirmed</span>
          @else
            <button class="btn" style="padding: .2rem .6rem; font-size: .75rem; background: #fff1f2; color: #e11d48; border: 1px solid #fecaca;">
              Pay Now
            </button>
          @endif
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 4rem;">
          <div style="font-size: 2.5rem; opacity: .2; margin-bottom: 1rem;"><i class="bi bi-journal-x"></i></div>
          No transaction history matching your filters.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
