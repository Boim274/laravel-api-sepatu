<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh data user
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // Password terenkripsi
            ],
            [
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
