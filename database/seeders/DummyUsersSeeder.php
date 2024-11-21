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
                'password' => bcrypt('admin'),
                'role' => 'admin',
            ],
            [
                'name' => 'Stocker',
                'email' => 'Stocker@gmail.com',
                'password' => bcrypt('stocker'),
                'role' => 'stocker',
            ],
            [
                'name' => 'Purchaser',
                'email' => 'purchase@gmail.com',
                'password' => bcrypt('purchase'),
                'role' => 'purchaser',
            ],
            [
                'name' => 'Sales',
                'email' => 'sales@gmail.com',
                'password' => bcrypt('sales'),
                'role' => 'sales',
            ],
        ];

        foreach ($users as $user => $value) {
            User::create($value);
        }
    }
}