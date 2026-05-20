@extends('society.layouts.user')

@section('title', 'My Visitors')
@section('page-title', 'My Visitors')

@section('content')
<style>
    .modal-container {
        border-radius: 16px !important;
        padding: 0 !important; /* Override layout padding */
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }
    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }
    .modal-body {
        padding: 1.5rem;
        overflow-y: auto;
        max-height: 60vh;
    }
    .modal-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        background: #f8fafc;
    }
    .close-btn {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.2s;
    }
    .close-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .form-group {
        margin-bottom: 1.25rem;
    }
</style>

<div class="card p-all">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="card-title">Expected Visitors</h2>
        <button class="btn btn-primary" onclick="openModal('addVisitorModal')">
            <i class="bi bi-plus-lg"></i> Pre-Schedule Visit
        </button>
    </div>

    @if(session('success'))
      <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Visitor</th>
                    <th>Type</th>
                    <th>Date/Time</th>
                    <th>Status</th>
                    <th>Pass Code</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                <tr>
                    <td>{{ $entry->visitor->name }}<br><small>{{ $entry->visitor->mobile }}</small></td>
                    <td>{{ $entry->visitorType->name }}</td>
                    <td>{{ $entry->entry_time ? \Carbon\Carbon::parse($entry->entry_time)->format('d M Y, h:i A') : 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($entry->status) == 'approved' ? 'success' : 'pending' }}">
                            {{ $entry->status }}
                        </span>
                    </td>
                    <td>
                        <strong>OTP: {{ $entry->otp }}</strong><br>
                        <small>QR: {{ substr($entry->qr_code, 0, 8) }}...</small>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                        No visitors found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Visitor Modal -->
<div class="modal-overlay" id="addVisitorModal">
  <form action="{{ route('society.user.visitors.store') }}" method="POST" class="modal-container">
    @csrf
    <div class="modal-header">
      <h3>Pre-Schedule Visit</h3>
      <button type="button" class="close-btn" onclick="closeModal('addVisitorModal')">&times;</button>
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
        <label>Visit Date & Time</label>
        <input type="datetime-local" name="visit_date_time" class="form-control">
      </div>
      <div class="form-group">
        <label>Purpose</label>
        <input type="text" name="purpose" class="form-control">
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline" onclick="closeModal('addVisitorModal')">Cancel</button>
      <button type="submit" class="btn btn-primary">Schedule</button>
    </div>
  </form>
</div>
@endsection
