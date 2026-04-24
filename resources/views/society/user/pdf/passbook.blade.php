<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10pt; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #6C63FF; padding-bottom: 10px; }
        .title { font-size: 18pt; font-weight: bold; color: #6C63FF; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f2f2f2; padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .status-paid { color: green; font-weight: bold; }
        .status-unpaid { color: red; font-weight: bold; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8pt; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">RESIDENT PASSBOOK</div>
        <div>{{ $user->society->name }} • {{ $user->society->city }}</div>
    </div>

    <div class="info">
        <strong>Resident Name:</strong> {{ $user->name }}<br>
        <strong>Unit Number:</strong> {{ $user->unit_number }}<br>
        <strong>Generated On:</strong> {{ date('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bills as $bill)
            <tr>
                <td>{{ $bill->month }} {{ $bill->year }}</td>
                <td>Maintenance Bill (#MB-{{ $bill->id }})</td>
                <td>₹{{ number_format($bill->total_amount, 2) }}</td>
                <td class="{{ $bill->status == 'paid' ? 'status-paid' : 'status-unpaid' }}">
                    {{ strtoupper($bill->status) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        This is a computer generated document. SocietyPro Management System.
    </div>
</body>
</html>
