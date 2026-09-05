@extends('layouts.app')
@section('content')
<div class="vendor-app"><div class="vendor-shell">
<aside class="vendor-sidebar">
<a class="vendor-brand" href="{{ route('vendor.dashboard') }}">Kuakata<span>Stay</span></a>
<nav class="vendor-nav">
<a class="active" href="{{ route('vendor.dashboard') }}">Dashboard</a>
<a href="{{ route('vendor.properties.index') }}">Properties</a>
<a href="{{ route('vendor.payouts.index') }}">Wallet & Payouts</a>
<a href="{{ route('customer.dashboard') }}">Customer Account</a>
<form method="POST" action="{{ route('logout') }}">@csrf<button class="vendor-btn secondary" style="width:100%;margin-top:8px">Sign out</button></form>
</nav></aside>
<section class="vendor-content"><header class="vendor-topbar"><div><strong>Vendor Center</strong><div class="vendor-muted" style="font-size:13px">Manage your hospitality business</div></div>@if($vendor?->status==='approved')<a class="vendor-btn" href="{{ route('vendor.properties.create') }}">+ Add Property</a>@endif</header>
<main class="vendor-main"><p class="vendor-eyebrow">VENDOR DASHBOARD</p><h1 class="vendor-title">{{ $vendor?->business_name ?? 'Become a partner' }}</h1>
@if(session('success'))<div class="vendor-alert" style="margin-top:16px">{{ session('success') }}</div>@endif
@if(!$vendor)
<div class="vendor-card vendor-section"><h2>Start your vendor journey</h2><p class="vendor-muted">Submit your business details to start managing properties on KuakataStay.</p><a class="vendor-btn" href="{{ route('vendor.register') }}">Register as vendor</a></div>
@else
<div class="vendor-grid"><div class="vendor-card"><small>Application</small><strong>{{ strtoupper($vendor->status) }}</strong></div><div class="vendor-card"><small>Verification</small><strong>{{ strtoupper($vendor->verification_status) }}</strong></div><div class="vendor-card"><small>Properties</small><strong>{{ $propertyCount ?? $vendor->properties()->count() }}</strong></div><div class="vendor-card"><small>Booking value</small><strong>৳{{ number_format($bookingValue ?? 0,2) }}</strong></div></div>
@if($vendor->status==='approved')
<div class="vendor-section"><div class="vendor-section-head"><h2>Quick actions</h2></div><div class="vendor-actions"><a class="vendor-btn" href="{{ route('vendor.properties.index') }}">Manage Properties</a><a class="vendor-btn secondary" href="{{ route('vendor.payouts.index') }}">Wallet & Payouts</a></div></div>
@else
<div class="vendor-card vendor-section"><strong style="font-size:18px">Approval pending</strong><p class="vendor-muted">Property management will unlock after admin approval.</p></div>
@endif
@endif
</main></section></div></div>
@endsection