<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class E2EAdminSeeder extends Seeder
{
    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'admin']);

        $user = User::firstOrCreate(
            ['email' => 'e2e_admin@example.com'],
            ['name' => 'E2E Admin', 'password' => Hash::make('Password123!')]
        );

        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
        }

        $this->command->info('E2E admin seeded: '.$user->email);
    }
}
