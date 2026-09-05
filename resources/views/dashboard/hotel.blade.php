@extends('layouts.app')
@section('content')
<div class="dashboard"><aside><h2>Hotel Manager</h2><a>Today</a><a>Bookings</a><a>Check-in</a><a>Rooms</a><a>Housekeeping</a><form method="POST" action="{{ route('logout') }}" class="logout-form">@csrf<button type="submit" class="logout-button">Logout</button></form></aside><main><h1>Hotel Operations</h1><div class="stats"><div>Today's Arrivals<br><strong>—</strong></div><div>Occupied Rooms<br><strong>—</strong></div><div>Check-outs<br><strong>—</strong></div></div><section><h3>Quick Actions</h3><button>Check In Guest</button> <button>Update Room Status</button></section></main></div>
<style>.logout-form{margin-top:20px}.logout-button{width:100%;padding:11px 14px;border:1px solid #d64545;border-radius:8px;background:#d64545;color:#fff;font-weight:700;cursor:pointer}.logout-button:hover{background:#b83232}</style>
@endsection
