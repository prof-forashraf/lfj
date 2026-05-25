<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@latestfashionjewellery.com',
                'password' => Hash::make('admin123'),
            ],
            [
                'name' => 'Editor User',
                'email' => 'editor@latestfashionjewellery.com',
                'password' => Hash::make('editor123'),
            ],
            [
                'name' => 'Test User',
                'email' => 'user@latestfashionjewellery.com',
                'password' => Hash::make('user123'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
