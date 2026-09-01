@extends('layouts.app')
@section('content')
<div class="search-page">
<header class="search-nav">
<a class="brand dark-brand" href="{{ route('home') }}">Kuakata<span>Stay</span></a>
<nav><a href="{{ route('home') }}">Home</a><a href="{{ route('home') }}#destinations">Destinations</a></nav>
<div>@auth<a class="login dark-login" href="{{ route('customer.dashboard') }}">My account</a>@else<a class="login dark-login" href="{{ route('login') }}">Sign in</a><a class="nav-cta teal-cta" href="{{ route('register') }}">List your hotel</a>@endauth</div>
</header>
<section class="search-hero">
<div><p class="eyebrow">HOTELS & RESORTS</p><h1>Find the right stay for your trip</h1><p>Compare available hotels and choose the room that suits you.</p></div>
<form class="results-search" method="GET" action="{{ route('hotels.search') }}">
<label>Destination<input name="destination" value="{{ $filters['destination'] ?? '' }}" placeholder="Kuakata, Cox's Bazar…"></label>
<label>Check-in<input type="date" name="check_in" value="{{ $filters['check_in'] ?? '' }}"></label>
<label>Check-out<input type="date" name="check_out" value="{{ $filters['check_out'] ?? '' }}"></label>
<label>Guests<input name="guests" value="{{ $filters['guests'] ?? '2 Guests, 1 Room' }}"></label>
<button>Search</button>
</form>
</section>
<main class="results-main">
<div class="results-top"><div><p class="eyebrow">SEARCH RESULTS</p><h2>{{ $hotels->total() }} stays found</h2></div><span>Prices shown per night where available</span></div>
@if($hotels->count())
<div class="results-grid">
@foreach($hotels as $hotel)
@php($prices=$hotel->roomTypes->pluck('base_price')->filter())
<article class="result-card">
<div class="result-photo"><span>{{ strtoupper(substr($hotel->name,0,1)) }}</span></div>
<div class="result-body">
<div class="result-meta">{{ $hotel->type ?: 'Hotel' }} · {{ $hotel->city ?: 'Bangladesh' }}</div>
<h3>{{ $hotel->name }}</h3>
<p>{{ $hotel->address ?: 'Comfortable accommodation in a convenient location.' }}</p>
<div class="result-footer"><div>@if($prices->count())<small>From</small><strong>৳{{ number_format($prices->min()) }}</strong><small>/ night</small>@else<small>Contact for pricing</small>@endif</div>
<a href="{{ route('hotel.details',$hotel) }}?{{ http_build_query(['check_in'=>$filters['check_in'] ?? null,'check_out'=>$filters['check_out'] ?? null]) }}">View stay →</a></div>
</div>
</article>
@endforeach
</div>
<div class="pagination-wrap">{{ $hotels->links() }}</div>
@else
<div class="empty-results"><h2>No stays found</h2><p>Try another destination or browse all available hotels.</p><a href="{{ route('hotels.search') }}">Clear search</a></div>
@endif
</main>
</div>
@endsection