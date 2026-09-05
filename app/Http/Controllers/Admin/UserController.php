<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Booking;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('roles.role');
        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        if ($role = $request->query('role')) {
            $query->whereHas('roles.role', fn ($q) => $q->where('slug', $role));
        }
        return view('admin.users.index', [
            'users' => $query->latest()->paginate(20)->withQueryString(),
            'roles' => Role::orderBy('name')->get()->unique('slug')->values(),
        ]);
    }

    public function customers(Request $request)
    {
        $query = User::query()->withCount('bookings')->whereDoesntHave('roles.role', function ($q) {
            $q->whereIn('slug', ['admin', 'vendor', 'hotel_manager']);
        });
        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%");
            });
        }
        return view('admin.customers.index', ['customers' => $query->latest()->paginate(20)->withQueryString()]);
    }

    public function customerShow(User $user)
    {
        abort_if($user->roles()->whereHas('role', fn ($q) => $q->whereIn('slug', ['admin', 'vendor', 'hotel_manager']))->exists(), 404);
        $bookings = Booking::where('user_id', $user->id)->with(['property', 'payments'])->latest()->paginate(15, ['*'], 'bookings');
        $bookingIds = Booking::where('user_id', $user->id)->pluck('id');
        $refunds = Refund::whereIn('booking_id', $bookingIds)->latest()->paginate(10, ['*'], 'refunds');
        return view('admin.customers.show', compact('user', 'bookings', 'refunds'));
    }

    public function updateRoles(Request $request, User $user)
    {
        $data = $request->validate(['roles' => ['array']]);
        $requested = array_values(array_unique($data['roles'] ?? []));
        $selectedRoles = Role::orderBy('id')->get()->unique('slug')->whereIn('slug', $requested)->values();

        // Prevent an administrator from accidentally removing their own admin access.
        if ($user->is($request->user()) && $user->isAdmin() && !$selectedRoles->contains(fn ($role) => $role->slug === 'admin')) {
            abort(422, 'You cannot remove your own administrator role.');
        }

        DB::transaction(function () use ($user, $selectedRoles) {
            $existing = $user->roles()->get();
            $keep = collect();
            foreach ($selectedRoles as $role) {
                $match = $existing->first(fn ($ur) => $ur->role_id === $role->id);
                if ($match) $keep->push($match->id);
                else $keep->push(UserRole::create(['user_id' => $user->id, 'role_id' => $role->id])->id);
            }
            UserRole::where('user_id', $user->id)->whereNotIn('id', $keep->all())->delete();
        });

        return back()->with('status', 'User roles updated.');
    }
}
