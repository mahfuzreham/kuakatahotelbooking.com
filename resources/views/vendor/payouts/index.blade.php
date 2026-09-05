@extends('layouts.app')

@section('content')
<div class="vendor-app">
    <div class="vendor-shell">
        <aside class="vendor-sidebar">
            <a class="vendor-brand" href="{{ route('vendor.dashboard') }}">Kuakata<span>Stay</span></a>
            <nav class="vendor-nav">
                <a href="{{ route('vendor.dashboard') }}">Dashboard</a>
                <a href="{{ route('vendor.properties.index') }}">Properties</a>
                <a href="{{ route('vendor.bookings.index') }}">Bookings</a>
                <a class="active" href="{{ route('vendor.payouts.index') }}">Wallet &amp; Payouts</a>
                <a href="{{ route('customer.dashboard') }}">Customer Account</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="vendor-btn secondary" style="width:100%;margin-top:8px" type="submit">Sign out</button>
                </form>
            </nav>
        </aside>

        <section class="vendor-content">
            <header class="vendor-topbar">
                <div>
                    <strong>Wallet &amp; Payouts</strong>
                    <div class="vendor-muted" style="font-size:13px">Manage your vendor balance and payout requests</div>
                </div>
                <a class="vendor-btn secondary" href="{{ route('vendor.dashboard') }}">Dashboard</a>
            </header>

            <main class="vendor-main">
                <p class="vendor-eyebrow">FINANCE</p>
                <h1 class="vendor-title">Wallet &amp; payouts</h1>

                @if (session('success'))
                    <div class="vendor-alert" style="margin-top:16px">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="vendor-errors" style="margin-top:16px">{{ $errors->first() }}</div>
                @endif

                <div class="vendor-grid">
                    <div class="vendor-card">
                        <small>Available</small>
                        <strong>৳{{ number_format((float) $wallet->available_balance, 2) }}</strong>
                    </div>
                    <div class="vendor-card">
                        <small>Pending</small>
                        <strong>৳{{ number_format((float) $wallet->pending_balance, 2) }}</strong>
                    </div>
                    <div class="vendor-card">
                        <small>Paid</small>
                        <strong>৳{{ number_format((float) $wallet->paid_balance, 2) }}</strong>
                    </div>
                    <div class="vendor-card">
                        <small>Currency</small>
                        <strong>{{ $wallet->currency }}</strong>
                    </div>
                </div>

                <div class="vendor-card vendor-section">
                    <h2>Request payout</h2>
                    <p class="vendor-muted">The requested amount will move from available balance to pending until admin processes it.</p>

                    <form class="vendor-form" method="POST" action="{{ route('vendor.payouts.request') }}">
                        @csrf
                        <div class="vendor-form-grid">
                            <div class="vendor-field">
                                <label for="amount">Amount</label>
                                <input id="amount" type="number" name="amount" min="1" max="{{ (float) $wallet->available_balance }}" step="0.01" required>
                            </div>
                            <div class="vendor-field">
                                <label for="method">Payment method</label>
                                <input id="method" name="method" value="{{ old('method') }}" placeholder="Bank / bKash / Nagad" maxlength="50" required>
                            </div>
                        </div>
                        <button class="vendor-btn" type="submit">Request payout</button>
                    </form>
                </div>

                <div class="vendor-section">
                    <div class="vendor-section-head">
                        <h2>Payout history</h2>
                    </div>

                    <div class="vendor-table-wrap">
                        <table class="vendor-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payouts as $payout)
                                    <tr>
                                        <td>{{ $payout->requested_at?->format('d M Y, h:i A') ?? '—' }}</td>
                                        <td>৳{{ number_format((float) $payout->amount, 2) }}</td>
                                        <td>{{ $payout->method }}</td>
                                        <td><span class="vendor-badge">{{ strtoupper($payout->status) }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="vendor-muted">No payout requests yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($payouts->hasPages())
                        <div style="margin-top:16px">{{ $payouts->links() }}</div>
                    @endif
                </div>
            </main>
        </section>
    </div>
</div>
@endsection
