@extends('layouts.app')

@section('content')
<style>
.cd-page{max-width:1280px;margin:0 auto;padding:32px 20px;font-family:Arial,sans-serif;color:#1f2937}.cd-page *{box-sizing:border-box}.cd-back{display:inline-block;margin-bottom:18px;color:#0f766e;text-decoration:none;font-weight:700}.cd-head{display:flex;justify-content:space-between;gap:20px;align-items:flex-start;margin-bottom:24px}.cd-kicker{margin:0 0 6px;color:#0f766e;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.cd-page h2{margin:0;font-size:28px}.cd-sub{color:#64748b;margin:7px 0 0}.cd-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}.cd-card,.cd-panel{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 4px 16px rgba(15,23,42,.05)}.cd-card{padding:20px}.cd-label{font-size:13px;color:#64748b;margin-bottom:8px}.cd-value{font-size:18px;font-weight:700;word-break:break-word}.cd-panel{margin-bottom:24px;overflow:hidden}.cd-panel-head{padding:17px 20px;border-bottom:1px solid #e5e7eb;font-size:18px;font-weight:700}.cd-table-wrap{overflow-x:auto}.cd-table{width:100%;min-width:850px;border-collapse:collapse}.cd-table th{background:#f8fafc;text-align:left;font-size:12px;text-transform:uppercase;color:#475569}.cd-table th,.cd-table td{padding:14px 18px;border-bottom:1px solid #e5e7eb}.cd-table tr:last-child td{border-bottom:0}.cd-badge{display:inline-block;padding:5px 10px;border-radius:999px;background:#e5e7eb;font-size:12px;font-weight:700}.cd-money{font-weight:700}.cd-empty{text-align:center;color:#94a3b8;padding:28px!important}.cd-footer{padding:14px 18px;border-top:1px solid #e5e7eb}@media(max-width:900px){.cd-grid{grid-template-columns:1fr 1fr}}@media(max-width:560px){.cd-page{padding:20px 12px}.cd-grid{grid-template-columns:1fr}.cd-page h2{font-size:23px}}
</style>

<div class="cd-page">
    <a class="cd-back" href="{{ route('admin.customers.index') }}">← Back to Customers</a>
    <div class="cd-head">
        <div>
            <p class="cd-kicker">Customer Profile</p>
            <h2>{{ $user->name }}</h2>
            <p class="cd-sub">Booking, payment and refund history for this customer.</p>
        </div>
    </div>

    <div class="cd-grid">
        <div class="cd-card"><div class="cd-label">Email</div><div class="cd-value">{{ $user->email }}</div></div>
        <div class="cd-card"><div class="cd-label">Phone</div><div class="cd-value">{{ $user->phone ?: '—' }}</div></div>
        <div class="cd-card"><div class="cd-label">Total Bookings</div><div class="cd-value">{{ $bookings->total() }}</div></div>
        <div class="cd-card"><div class="cd-label">Joined</div><div class="cd-value">{{ $user->created_at?->format('d M Y') }}</div></div>
    </div>

    <div class="cd-panel">
        <div class="cd-panel-head">Booking History</div>
        <div class="cd-table-wrap">
            <table class="cd-table">
                <thead><tr><th>Booking</th><th>Hotel</th><th>Stay</th><th>Status</th><th>Total</th><th>Payments</th></tr></thead>
                <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>#{{ $booking->booking_number ?: $booking->id }}</td>
                        <td>{{ $booking->property?->name ?: '—' }}</td>
                        <td>{{ $booking->check_in?->format('d M Y') }} → {{ $booking->check_out?->format('d M Y') }}</td>
                        <td><span class="cd-badge">{{ ucfirst($booking->status) }}</span></td>
                        <td class="cd-money">৳ {{ number_format($booking->total, 2) }}</td>
                        <td>{{ $booking->payments->count() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="cd-empty">No booking history found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="cd-footer">{{ $bookings->withQueryString()->links() }}</div>
    </div>

    <div class="cd-panel">
        <div class="cd-panel-head">Refund History</div>
        <div class="cd-table-wrap">
            <table class="cd-table">
                <thead><tr><th>Refund ID</th><th>Booking ID</th><th>Amount</th><th>Reason</th><th>Status</th><th>Processed</th></tr></thead>
                <tbody>
                @forelse($refunds as $refund)
                    <tr>
                        <td>#{{ $refund->id }}</td>
                        <td>#{{ $refund->booking_id }}</td>
                        <td class="cd-money">৳ {{ number_format($refund->amount, 2) }}</td>
                        <td>{{ $refund->reason ?: '—' }}</td>
                        <td><span class="cd-badge">{{ ucfirst($refund->status) }}</span></td>
                        <td>{{ $refund->processed_at?->format('d M Y H:i') ?: 'Pending' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="cd-empty">No refund history found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="cd-footer">{{ $refunds->withQueryString()->links() }}</div>
    </div>
</div>
@endsection