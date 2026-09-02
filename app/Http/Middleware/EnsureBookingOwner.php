<?php
namespace App\Http\Middleware;
use App\Models\Booking;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class EnsureBookingOwner {
 public function handle(Request $request, Closure $next): Response {
  $booking=$request->route('booking');
  if($booking instanceof Booking) abort_unless($request->user() && ($booking->user_id===$request->user()->id || $request->user()->isAdmin()),403);
  return $next($request);
 }
}