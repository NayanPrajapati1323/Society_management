@extends('society.layouts.society_admin')

@section('page-title', 'Society Expenses')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Expense Records</h3>
        <button onclick="openModal('addExpenseModal')" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Record Expense</button>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount (₹)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense->date->format('d M Y') }}</td>
                        <td><span class="badge badge-role-2">{{ $expense->category }}</span></td>
                        <td>{{ $expense->description }}</td>
                        <td style="font-weight:800; color:#ef4444;">-₹{{ number_format($expense->amount) }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline text-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Expense Modal --}}
<div id="addExpenseModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="bi bi-cart-dash"></i> Record New Expense</h3>
            <button onclick="closeModal('addExpenseModal')" class="modal-close">&times;</button>
        </div>
        <form action="{{ route('society-admin.expenses.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Date *</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required />
                    </div>
                    <div class="form-group">
                        <label>Category *</label>
                        <select name="category" class="form-control" required>
                            <option value="Electricity">Electricity</option>
                            <option value="Water">Water Supply</option>
                            <option value="Security">Security Services</option>
                            <option value="Repair">Maintenance/Repair</option>
                            <option value="Staff Salary">Staff Salary</option>
                            <option value="Garden">Gardening</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Amount (₹) *</label>
                        <input type="number" name="amount" class="form-control" placeholder="0.00" required />
                    </div>
                    <div class="form-group full">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Short description of expense..."></textarea>
                    </div>
                </div>
                <input type="hidden" name="society_id" value="{{ Auth::user()->society_id }}">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" style="flex:1;">Submit Expense</button>
                <button type="button" onclick="closeModal('addExpenseModal')" class="btn btn-outline" style="flex:1;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }
</script>
@endsection
