<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Administrator', 'slug' => 'admin', 'description' => 'Full platform administration'],
            ['name' => 'Vendor', 'slug' => 'vendor', 'description' => 'Hotel business owner'],
            ['name' => 'Hotel Manager', 'slug' => 'hotel_manager', 'description' => 'Property management access'],
            ['name' => 'Customer', 'slug' => 'customer', 'description' => 'Booking customer'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
