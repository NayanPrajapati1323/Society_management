@extends('society.layouts.society_admin')

@section('page-title', 'Society Passbook')

@section('content')
<div class="card mb-4">
    <div class="card-body" style="padding: 1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <form class="filter-bar" method="GET">
            <select name="month">
                @for($i=1; $i<=12; $i++)
                <option value="{{ $i }}" {{ request('month', date('n')) == $i ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i,1)) }}</option>
                @endfor
            </select>
            <select name="year">
                @for($i=2024; $i<=2030; $i++)
                <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        <a href="{{ route('society-admin.passbook.export') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-pdf"></i> Export to PDF
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Transaction History</h3>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table style="margin:0;">
                <thead style="position: sticky; top:0; z-index:10;">
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Unit/Category</th>
                        <th style="text-align:right;">Credit (₹)</th>
                        <th style="text-align:right;">Debit (₹)</th>
                        <th style="text-align:right;">Balance (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $runningBalance = 0; 
                        $transactions = collect();
                        foreach($credits as $c) {
                            $transactions->push([
                                'date' => $c->paid_at ?? $c->created_at,
                                'desc' => 'Maintenance: ' . date('F Y', mktime(0,0,0,$c->month,1,$c->year)),
                                'ref' => $c->user->house_number ?? '-',
                                'credit' => $c->total_amount,
                                'debit' => 0
                            ]);
                        }
                        foreach($debits as $d) {
                            $transactions->push([
                                'date' => $d->date,
                                'desc' => $d->description ?: 'Expense: ' . $d->category,
                                'ref' => $d->category,
                                'credit' => 0,
                                'debit' => $d->amount
                            ]);
                        }
                        $transactions = $transactions->sortBy('date');
                    @endphp

                    @foreach($transactions as $trx)
                    @php $runningBalance += ($trx['credit'] - $trx['debit']); @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($trx['date'])->format('d M Y') }}</td>
                        <td class="table-name">{{ $trx['desc'] }}</td>
                        <td><span class="badge badge-inactive">{{ $trx['ref'] }}</span></td>
                        <td style="text-align:right; font-weight:700; color:#16a34a;">{{ $trx['credit'] > 0 ? '+₹'.number_format($trx['credit']) : '-' }}</td>
                        <td style="text-align:right; font-weight:700; color:#ef4444;">{{ $trx['debit'] > 0 ? '-₹'.number_format($trx['debit']) : '-' }}</td>
                        <td style="text-align:right; font-weight:800;">₹{{ number_format($runningBalance) }}</td>
                    </tr>
                    @endforeach
                    @if($transactions->isEmpty())
                    <tr>
                        <td colspan="6" style="text-align:center; padding:3rem; color:var(--muted);">No transactions found for the selected period.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
