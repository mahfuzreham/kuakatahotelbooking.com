<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    protected $signature = 'kuakata:make-admin {email : User email address}';
    protected $description = 'Assign the administrator role to an existing user';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User not found: {$email}");
            return self::FAILURE;
        }

        $role = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Administrator', 'description' => 'Full platform administration']
        );

        UserRole::firstOrCreate([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'vendor_id' => null,
            'property_id' => null,
        ]);

        $this->info("Admin role assigned to {$user->email}.");
        return self::SUCCESS;
    }
}
