<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'fullname' => 'Administrator Quin Salon',
            'email' => 'admin@quinsalon.com',
            'phone' => '081234567890',
            'address' => 'Jombang',
            'role_id' => 1,
        ]);

        User::factory(5)->create([
            'role_id' => 2,
        ]);
    }
}
