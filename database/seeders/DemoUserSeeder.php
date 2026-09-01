<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'demo@gmail.com'],
            [
                'name' => 'Demo Administrator',
                'phone' => '01790614055',
                'password' => Hash::make('01790614055'),
                'email_verified_at' => now(),
            ]
        );

        $roles = Role::whereIn('slug', ['admin', 'vendor', 'hotel_manager', 'customer'])->get();

        foreach ($roles as $role) {
            UserRole::firstOrCreate([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        }
    }
}
