@extends('layouts.app')

@section('content')
<style>
.customer-page{max-width:1280px;margin:0 auto;padding:32px 20px;font-family:Arial,sans-serif;color:#1f2937}
.customer-page *{box-sizing:border-box}.customer-head{display:flex;justify-content:space-between;align-items:flex-end;gap:16px;margin-bottom:24px}.customer-kicker{margin:0 0 6px;color:#0f766e;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.customer-page h2{margin:0;font-size:28px}.customer-sub{margin:7px 0 0;color:#64748b}.customer-panel{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 4px 16px rgba(15,23,42,.05);overflow:hidden}.customer-toolbar{padding:18px;border-bottom:1px solid #e5e7eb}.customer-search{display:flex;gap:10px;max-width:620px}.customer-search input{flex:1;height:42px;padding:0 13px;border:1px solid #cbd5e1;border-radius:8px}.customer-search button{height:42px;padding:0 18px;border:0;border-radius:8px;background:#0f766e;color:#fff;font-weight:600}.customer-table-wrap{overflow-x:auto}.customer-table{width:100%;min-width:760px;border-collapse:collapse}.customer-table th{background:#f8fafc;text-align:left;color:#475569;font-size:12px;text-transform:uppercase;letter-spacing:.04em}.customer-table th,.customer-table td{padding:15px 18px;border-bottom:1px solid #e5e7eb}.customer-table tr:last-child td{border-bottom:0}.customer-name{font-weight:700}.customer-email{color:#64748b;font-size:13px;margin-top:4px}.customer-count{display:inline-block;padding:5px 10px;border-radius:999px;background:#e0f2fe;color:#075985;font-weight:700;font-size:12px}.customer-empty{text-align:center;color:#94a3b8;padding:32px!important}.customer-footer{padding:16px 18px;border-top:1px solid #e5e7eb}@media(max-width:650px){.customer-page{padding:20px 12px}.customer-head{align-items:flex-start;flex-direction:column}.customer-search{max-width:none}}
</style>

<div class="customer-page">
    <div class="customer-head">
        <div>
            <p class="customer-kicker">Administration</p>
            <h2>Customer Data</h2>
            <p class="customer-sub">Customer accounts are shown separately from admin, vendor and hotel manager accounts.</p>
        </div>
    </div>

    <div class="customer-panel">
        <div class="customer-toolbar">
            <form method="GET" class="customer-search">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by name, email or phone">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="customer-table-wrap">
            <table class="customer-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Bookings</th>
                        <th>Email Verified</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>
                                <div class="customer-name">{{ $customer->name }}</div>
                                <div class="customer-email">{{ $customer->email }}</div>
                            </td>
                            <td>{{ $customer->phone ?: '—' }}</td>
                            <td><span class="customer-count">{{ $customer->bookings_count }}</span></td>
                            <td>{{ $customer->email_verified_at ? 'Verified' : 'Not verified' }}</td>
                            <td>{{ $customer->created_at?->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="customer-empty">No customers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="customer-footer">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection