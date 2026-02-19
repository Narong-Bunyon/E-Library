<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create author account if not exists
        User::firstOrCreate(
            ['email' => 'author@elibrary.com'],
            [
                'name' => 'Test Author',
                'password' => Hash::make('password123'),
                'role' => 'author',
                'status' => 1, // 1 = active
            ]
        );

        // Create admin account if not exists
        User::firstOrCreate(
            ['email' => 'admin@elibrary.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 1, // 1 = active
            ]
        );

        // Create regular user account if not exists
        User::firstOrCreate(
            ['email' => 'user@elibrary.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 1, // 1 = active
            ]
        );
    }
}
