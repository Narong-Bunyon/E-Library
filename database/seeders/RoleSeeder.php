<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert role values based on existing table structure
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Full system access and user management',
            ],
            [
                'name' => 'author',
                'description' => 'Can create and manage books',
            ],
            [
                'name' => 'user',
                'description' => 'Can read books and manage personal account',
            ],
        ];

        // Clear existing roles
        DB::table('roles')->delete();

        // Insert new roles
        foreach ($roles as $role) {
            DB::table('roles')->insert($role);
        }

        $this->command->info('Roles seeded successfully!');
        $this->command->info('Created roles: admin, author, user');
        
        // Display created roles
        $createdRoles = DB::table('roles')->get();
        foreach ($createdRoles as $role) {
            echo "- {$role->name}: {$role->description}\n";
        }
    }
}
