<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'admin', 'description' => 'Administrator sistem'],
            ['role_name' => 'customer', 'description' => 'Pelanggan website'],
        ];

        DB::table('roles')->insert($roles);
    }
}
