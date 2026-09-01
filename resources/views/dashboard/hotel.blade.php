@extends('layouts.app')
@section('content')
<div class="dashboard"><aside><h2>Hotel Manager</h2><a>Today</a><a>Bookings</a><a>Check-in</a><a>Rooms</a><a>Housekeeping</a></aside><main><h1>Hotel Operations</h1><div class="stats"><div>Today's Arrivals<br><strong>—</strong></div><div>Occupied Rooms<br><strong>—</strong></div><div>Check-outs<br><strong>—</strong></div></div><section><h3>Quick Actions</h3><button>Check In Guest</button> <button>Update Room Status</button></section></main></div>
@endsection