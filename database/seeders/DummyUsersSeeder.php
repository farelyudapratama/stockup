<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'role' => 'admin',
            ],
            [
                'name' => 'Stocker',
                'email' => 'Stocker@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('stocker'),
                'role' => 'stocker',
            ],
            [
                'name' => 'Purchaser',
                'email' => 'purchase@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('purchase'),
                'role' => 'purchaser',
            ],
            [
                'name' => 'Seller',
                'email' => 'seller@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('seller'),
                'role' => 'seller',
            ],
        ];

        foreach ($users as $user => $value) {
            User::create($value);
        }
    }
}