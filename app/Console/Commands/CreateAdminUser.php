<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'kuakata:create-admin {email} {--name=} {--phone=}';
    protected $description = 'Create or update a user and assign administrator access';

    public function handle(): int
    {
        $email = $this->argument('email');
        $name = $this->option('name') ?: $this->ask('Name', 'Administrator');
        $phone = $this->option('phone') ?: $this->ask('Phone');
        $password = $this->secret('Password');

        if (! $password) {
            $this->error('Password is required.');
            return self::FAILURE;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'phone' => $phone, 'password' => Hash::make($password)]
        );

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

        $this->info("Administrator account is ready: {$email}");
        return self::SUCCESS;
    }
}
