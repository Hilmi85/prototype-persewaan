<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['cat_name' => 'Baju Adat', 'description' => 'Kategori pakaian adat'],
            ['cat_name' => 'Aksesoris', 'description' => 'Kategori aksesoris pelengkap'],
            ['cat_name' => 'Jasa Rias', 'description' => 'Kategori layanan rias'],
        ];

        DB::table('categories')->insert($categories);
    }
}
