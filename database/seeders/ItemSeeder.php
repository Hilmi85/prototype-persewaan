<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Kebaya Jawa Putri',
                'description' => 'Pakaian adat wanita untuk acara pernikahan dan wisuda',
                'price' => 250000,
                'category_id' => 1,
                'item_type' => 'baju_adat',
                'adat_category' => 'Jawa',
                'gender' => 'Perempuan',
                'img' => 'kebaya-jawa-putri.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beskap Jawa Putra',
                'description' => 'Pakaian adat pria untuk acara lamaran dan pernikahan',
                'price' => 275000,
                'category_id' => 1,
                'item_type' => 'baju_adat',
                'adat_category' => 'Jawa',
                'gender' => 'Laki-laki',
                'img' => 'beskap-jawa-putra.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siger Pengantin',
                'description' => 'Aksesoris kepala untuk pengantin adat',
                'price' => 100000,
                'category_id' => 2,
                'item_type' => 'aksesoris',
                'adat_category' => 'Jawa',
                'gender' => 'Perempuan',
                'img' => 'siger-pengantin.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paket Rias Pengantin',
                'description' => 'Layanan rias untuk acara pernikahan',
                'price' => 500000,
                'category_id' => 3,
                'item_type' => 'jasa_rias',
                'adat_category' => null,
                'gender' => 'Perempuan',
                'img' => 'rias-pengantin.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paket Rias Wisuda',
                'description' => 'Layanan rias untuk acara wisuda',
                'price' => 300000,
                'category_id' => 3,
                'item_type' => 'jasa_rias',
                'adat_category' => null,
                'gender' => 'Perempuan',
                'img' => 'rias-wisuda.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('items')->insert($items);
    }
}
