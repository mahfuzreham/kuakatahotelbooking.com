<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

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
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function updateRoles(Request $request, User $user)
    {
        $data = $request->validate(['roles' => ['array']]);
        $roleIds = Role::whereIn('slug', $data['roles'] ?? [])->pluck('id');

        UserRole::where('user_id', $user->id)
            ->whereNotIn('role_id', $roleIds)
            ->delete();

        foreach ($roleIds as $roleId) {
            UserRole::firstOrCreate(['user_id' => $user->id, 'role_id' => $roleId]);
        }

        return back()->with('status', 'User roles updated.');
    }
}
