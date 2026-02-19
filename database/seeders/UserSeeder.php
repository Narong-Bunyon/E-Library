<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@elibrary.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create author user
        User::create([
            'name' => 'John Author',
            'email' => 'author@elibrary.com',
            'password' => Hash::make('password123'),
            'role' => 'author',
            'email_verified_at' => now(),
        ]);

        // Create regular users
        User::create([
            'name' => 'Jane Reader',
            'email' => 'reader@elibrary.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Bob Reader',
            'email' => 'bob@elibrary.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Alice Reader',
            'email' => 'alice@elibrary.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Users created successfully!');
        $this->command->info('Admin: admin@elibrary.com / password123');
        $this->command->info('Author: author@elibrary.com / password123');
        $this->command->info('Users: reader@elibrary.com, bob@elibrary.com, alice@elibrary.com / password123');
    }
}
