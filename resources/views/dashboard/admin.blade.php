@extends('layouts.app')

@section('content')
<div class="admin-shell">
    <aside class="admin-sidebar">
        <h2>KuakataStay</h2>
        <a href="{{ route('dashboard.admin') }}">Overview</a>
        <a href="{{ route('admin.vendors.index') }}">Vendors @if($stats['pending_vendors']) <b>{{ $stats['pending_vendors'] }}</b> @endif</a>
        <a href="{{ route('admin.properties.index') }}">Properties @if($stats['pending_properties']) <b>{{ $stats['pending_properties'] }}</b> @endif</a>
        <a href="#bookings">Bookings</a>
        <a href="#finance">Finance</a>
    </aside>
    <main class="admin-main">
        <p class="eyebrow">ADMIN CONTROL CENTER</p>
        <h1>Dashboard Overview</h1>

        <div class="admin-stats">
            <div><span>Total Users</span><strong>{{ number_format($stats['users']) }}</strong></div>
            <div><span>Vendors</span><strong>{{ number_format($stats['vendors']) }}</strong><small>{{ $stats['pending_vendors'] }} pending</small></div>
            <div><span>Active Hotels</span><strong>{{ number_format($stats['active_properties']) }}</strong><small>{{ $stats['pending_properties'] }} pending</small></div>
            <div><span>Total Bookings</span><strong>{{ number_format($stats['bookings']) }}</strong><small>{{ $stats['pending_bookings'] }} awaiting payment</small></div>
            <div><span>Paid Revenue</span><strong>৳{{ number_format((float)$stats['revenue'], 2) }}</strong></div>
            <div><span>Pending Refunds</span><strong>{{ number_format($stats['pending_refunds']) }}</strong></div>
        </div>

        <section class="admin-actions">
            <a href="{{ route('admin.vendors.index') }}">Review Vendors</a>
            <a href="{{ route('admin.properties.index') }}">Review Properties</a>
        </section>

        <section id="bookings" class="admin-card">
            <div class="section-head"><h2>Recent Bookings</h2><span>{{ $recentBookings->count() }} latest</span></div>
            <div class="admin-table-wrap">
                <table>
                    <thead><tr><th>Booking</th><th>Hotel</th><th>Stay</th><th>Status</th><th>Total</th></tr></thead>
                    <tbody>
                    @forelse($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_number }}</td>
                            <td>{{ $booking->property?->name ?? '—' }}</td>
                            <td>{{ optional($booking->check_in)->format('d M Y') }} – {{ optional($booking->check_out)->format('d M Y') }}</td>
                            <td><span class="status">{{ $booking->status }}</span></td>
                            <td>৳{{ number_format((float)$booking->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No bookings yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
<style>
.admin-shell{min-height:100vh;background:#f5f7fb;display:flex;font-family:Arial,sans-serif}.admin-sidebar{width:240px;background:#102a43;color:#fff;padding:28px 18px}.admin-sidebar h2{margin:0 0 32px}.admin-sidebar a{display:block;color:#d9e2ec;text-decoration:none;padding:12px;border-radius:8px;margin:4px 0}.admin-sidebar a:hover{background:#243b53}.admin-sidebar b{float:right;background:#d64545;border-radius:20px;padding:2px 7px;color:#fff}.admin-main{flex:1;padding:38px;min-width:0}.eyebrow{color:#627d98;font-weight:700;font-size:12px;letter-spacing:1px}.admin-main h1{margin-top:0;color:#102a43}.admin-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;margin:24px 0}.admin-stats>div,.admin-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.05)}.admin-stats span,.admin-stats small{display:block;color:#627d98}.admin-stats strong{display:block;font-size:28px;color:#102a43;margin:8px 0}.admin-actions{display:flex;gap:12px;margin-bottom:22px}.admin-actions a{background:#176b87;color:#fff;padding:12px 18px;border-radius:8px;text-decoration:none;font-weight:700}.section-head{display:flex;justify-content:space-between;align-items:center}.admin-table-wrap{overflow:auto}table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:14px;border-bottom:1px solid #e9edf2}th{color:#627d98;font-size:12px}.status{background:#eef4ff;padding:5px 9px;border-radius:20px;font-size:12px}@media(max-width:800px){.admin-shell{display:block}.admin-sidebar{width:auto;display:flex;gap:6px;overflow:auto;padding:15px}.admin-sidebar h2{display:none}.admin-sidebar a{white-space:nowrap}.admin-main{padding:20px}.admin-stats{grid-template-columns:repeat(2,1fr)}}@media(max-width:480px){.admin-stats{grid-template-columns:1fr}.admin-actions{flex-direction:column}}
</style>
@endsection
