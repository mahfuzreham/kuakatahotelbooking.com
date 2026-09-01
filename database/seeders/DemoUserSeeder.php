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
        $accounts = [
            [
                'name' => 'Demo Administrator',
                'email' => 'demo@gmail.com',
                'phone' => '01790614055',
                'roles' => ['admin'],
            ],
            [
                'name' => 'Demo Vendor',
                'email' => 'vendor@demo.com',
                'phone' => '01790614056',
                'roles' => ['vendor'],
            ],
            [
                'name' => 'Demo Hotel Manager',
                'email' => 'manager@demo.com',
                'phone' => '01790614057',
                'roles' => ['hotel_manager'],
            ],
            [
                'name' => 'Demo Customer',
                'email' => 'customar@demo.com',
                'phone' => '01790614058',
                'roles' => ['customer'],
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'phone' => $account['phone'],
                    'password' => Hash::make('01790614055'),
                    'email_verified_at' => now(),
                ]
            );

            foreach ($account['roles'] as $slug) {
                $role = Role::where('slug', $slug)->firstOrFail();

                UserRole::firstOrCreate([
                    'user_id' => $user->id,
                    'role_id' => $role->id,
                ]);
            }
        }
    }
}
