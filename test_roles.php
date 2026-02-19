<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking Database Structure ===\n";

// Check if role column exists
try {
    $columns = DB::select("SHOW COLUMNS FROM users");
    echo "Users table columns:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
} catch (Exception $e) {
    echo "Error checking columns: " . $e->getMessage() . "\n";
}

echo "\n=== Checking Users ===\n";

// Check users
try {
    $users = DB::table('users')->get();
    echo "Total users: " . $users->count() . "\n\n";
    
    foreach ($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Role: " . ($user->role ?? 'NULL') . "\n";
        echo "---\n";
    }
} catch (Exception $e) {
    echo "Error checking users: " . $e->getMessage() . "\n";
}

echo "\n=== Testing Role Methods ===\n";

// Test role methods
try {
    $users = DB::table('users')->get();
    foreach ($users as $userData) {
        $user = new \App\Models\User();
        $user->id = $userData->id;
        $user->name = $userData->name;
        $user->email = $userData->email;
        $user->role = $userData->role;
        
        echo "User: {$user->name}\n";
        echo "Role: {$user->role}\n";
        echo "Is Admin: " . ($user->isAdmin() ? 'Yes' : 'No') . "\n";
        echo "Is Author: " . ($user->isAuthor() ? 'Yes' : 'No') . "\n";
        echo "Is User: " . ($user->isUser() ? 'Yes' : 'No') . "\n";
        echo "Can Read Books: " . ($user->canReadBooks() ? 'Yes' : 'No') . "\n";
        echo "Can Manage Books: " . ($user->canManageBooks() ? 'Yes' : 'No') . "\n";
        echo "Can Manage Users: " . ($user->canManageUsers() ? 'Yes' : 'No') . "\n";
        echo "---\n";
    }
} catch (Exception $e) {
    echo "Error testing role methods: " . $e->getMessage() . "\n";
}
