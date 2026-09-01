@extends('layouts.app')
@section('content')
<div class="travel-home">
<section class="hero"><nav><a class="brand" href="/">Kuakata<span>Stay</span></a><div class="nav-links"><a href="#hotels">Hotels</a><a href="#destinations">Destinations</a><a href="#partner">Partners</a></div><div><a class="login" href="/login">Sign in</a><a class="nav-cta" href="/register">List your hotel</a></div></nav>
<div class="hero-content"><p class="eyebrow">KUAKATA • BANGLADESH</p><h1>Find your perfect<br>stay by the sea.</h1><p>Discover hotels, resorts and unique stays with simple, secure booking.</p></div>
<form id="hotelSearchForm" class="search-card"><label>Destination<input id="destination" value="Kuakata" placeholder="Where do you want to go?"></label><label>Check-in<input id="checkIn" type="date" required></label><label>Check-out<input id="checkOut" type="date" required></label><label>Guests<input id="guests" value="2 Guests, 1 Room"></label><button type="submit">Search stays</button></form></section>
<section class="benefits"><div>🛡️ <b>Best Price</b><span>Great stays at fair prices</span></div><div>🕒 <b>24/7 Support</b><span>Help whenever you need it</span></div><div>↩️ <b>Flexible Booking</b><span>Clear cancellation options</span></div><div>🔒 <b>Secure Payments</b><span>Protected booking flow</span></div></section>
<section id="destinations" class="content-section"><div class="section-head"><div><p class="eyebrow">EXPLORE</p><h2>Popular destinations</h2></div></div><div class="destination-grid"><article class="destination kuakata"><span>Kuakata</span><small>Sea beach & resorts</small></article><article class="destination cox"><span>Cox's Bazar</span><small>Beach hotels</small></article><article class="destination sundarban"><span>Sundarbans</span><small>Nature stays</small></article><article class="destination dhaka"><span>Dhaka</span><small>City hotels</small></article></div></section>
<section id="hotels" class="content-section light"><div class="section-head"><div><p class="eyebrow">AVAILABLE STAYS</p><h2>Find your hotel</h2></div></div><div id="searchStatus"></div><div id="hotelResults" class="hotel-grid"></div></section>
<section id="partner" class="host-banner"><div><p class="eyebrow">FOR HOTEL OWNERS</p><h2>Grow your hotel business with us.</h2><p>Manage rooms, bookings, staff and payouts from one powerful platform.</p></div><a class="nav-cta" href="/register">Become a partner →</a></section></div>
<script>
const form=document.getElementById('hotelSearchForm'),results=document.getElementById('hotelResults'),statusEl=document.getElementById('searchStatus');
const today=new Date().toISOString().slice(0,10);checkIn.min=today;checkOut.min=today;
function money(v){return new Intl.NumberFormat('en-BD',{style:'currency',currency:'BDT',maximumFractionDigits:0}).format(v||0)}
async function searchHotels(){
 statusEl.textContent='Searching available hotels…';results.innerHTML='';
 const p=new URLSearchParams({destination:destination.value});
 const r=await fetch('/api/public/hotels?'+p,{headers:{Accept:'application/json'}});
 const d=await r.json();const hotels=d.hotels?.data||[];
 statusEl.textContent=hotels.length?hotels.length+' hotel(s) found':'No hotels found yet.';
 results.innerHTML=hotels.map(h=>{const price=Math.min(...(h.room_types||[]).map(x=>Number(x.base_price||0)).filter(Boolean));return '<article class="hotel-card"><div class="hotel-image h1"></div><div class="hotel-info"><h3>'+h.name+'</h3><p>📍 '+(h.city||'Bangladesh')+'</p><div class="price">From <strong>'+money(price)+'</strong> / night</div><a class="hotel-link" href="/hotels/'+encodeURIComponent(h.slug||h.id)+'?check_in='+checkIn.value+'&check_out='+checkOut.value+'">View rooms →</a></div></article>'}).join('');
}
form.addEventListener('submit',e=>{e.preventDefault();if(checkIn.value&&checkOut.value&&checkOut.value<=checkIn.value){alert('Check-out must be after check-in.');return;}document.getElementById('hotels').scrollIntoView({behavior:'smooth'});searchHotels();});
</script>
@endsection