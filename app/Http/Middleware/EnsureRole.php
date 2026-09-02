<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class EnsureRole {
 public function handle(Request $request, Closure $next, ...$roles): Response {
  $user=$request->user();
  abort_unless($user,401);
  $allowed=$user->roles()->whereHas('role',fn($q)=>$q->whereIn('slug',$roles))->exists();
  abort_unless($allowed,403,'You do not have permission to access this resource.');
  return $next($request);
 }
}