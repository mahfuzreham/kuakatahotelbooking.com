@extends('layouts.app')

@section('content')
<div class="vendor-app">
    <div class="vendor-shell">
        <aside class="vendor-sidebar">
            <a class="vendor-brand" href="{{ route('vendor.dashboard') }}">Kuakata<span>Stay</span></a>
            <nav class="vendor-nav">
                <a href="{{ route('vendor.dashboard') }}">Dashboard</a>
                <a class="active" href="{{ route('vendor.properties.index') }}">Properties</a>
                <a href="{{ route('vendor.bookings.index') }}">Bookings</a>
                <a href="{{ route('vendor.payouts.index') }}">Wallet & Payouts</a>
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
                    <strong>Properties</strong>
                    <div class="vendor-muted" style="font-size:13px">Manage your hotels and resorts</div>
                </div>
                <a class="vendor-btn" href="{{ route('vendor.properties.create') }}">+ Add Property</a>
            </header>

            <main class="vendor-main">
                <p class="vendor-eyebrow">PROPERTY MANAGEMENT</p>
                <h1 class="vendor-title">My hotels & properties</h1>

                @if (session('success'))
                    <div class="vendor-alert" style="margin-top:16px">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="vendor-errors" style="margin-top:16px">{{ $errors->first() }}</div>
                @endif

                <div class="vendor-section">
                    @forelse ($properties as $property)
                        <div class="vendor-row">
                            <div class="vendor-row-main">
                                <strong>{{ $property->name }}</strong>
                                <p>
                                    {{ $property->city }} ·
                                    {{ ucfirst(str_replace('_', ' ', $property->type)) }} ·
                                    {{ $property->room_types_count }} room types ·
                                    {{ $property->rooms_count }} rooms
                                </p>
                            </div>

                            <div class="vendor-actions">
                                <span class="vendor-badge">{{ strtoupper($property->status) }}</span>
                                <a class="vendor-btn secondary" href="{{ route('vendor.rooms.index', $property) }}">Rooms & Types</a>
                                <a class="vendor-btn secondary" href="{{ route('vendor.bookings.index', ['property_id' => $property->id]) }}">Bookings</a>
                                <form method="POST" action="{{ route('vendor.properties.destroy', $property) }}" onsubmit="return confirm('Delete this property? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="vendor-btn secondary" type="submit">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="vendor-card">
                            <strong style="font-size:18px">No properties yet</strong>
                            <p class="vendor-muted">Add your first hotel or resort to begin.</p>
                            <a class="vendor-btn" href="{{ route('vendor.properties.create') }}">Add Property</a>
                        </div>
                    @endforelse
                </div>
            </main>
        </section>
    </div>
</div>
@endsection
