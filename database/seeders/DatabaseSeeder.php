<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Fixed Admin Account
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'email' => 'admin@lemonhaus.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Fixed Kitchen Account
        User::updateOrCreate(
            ['username' => 'kitchen1'],
            [
                'name' => 'Kitchen1',
                'email' => 'kitchen@lemonhaus.com',
                'password' => Hash::make('password123'), // Secure password
                'role' => 'kitchen',
            ]
        );
        
        // Optional: Fixed Cashier Account
        User::updateOrCreate(
            ['username' => 'cashier_one'],
            [
                'name' => 'Cashier1',
                'email' => 'Cashier@lemonhaus.com',
                'password' => Hash::make('cashier123'),
                'role' => 'cashier',
            ]
        );
    }
}