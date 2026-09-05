@extends('layouts.app')
@section('content')
<div class="logout-page">
    <div class="logout-card">
        <div class="logout-icon">✓</div>
        <p class="logout-eyebrow">KUAKATASTAY</p>
        <h1>Successfully Logged Out</h1>
        <p>You have been safely signed out of your account.</p>
        <a href="{{ route('home') }}" class="logout-home">Go to Home</a>
    </div>
</div>
@endsection
