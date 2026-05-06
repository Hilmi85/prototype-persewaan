<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemVariantSeeder extends Seeder
{
    public function run(): void
    {
        $variants = [
            [
                'item_id' => 1,
                'sku_code' => 'KJP-S',
                'size' => 'S',
                'color' => 'Merah',
                'stock' => 3,
                'available_stock' => 3,
                'daily_price' => 250000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item_id' => 1,
                'sku_code' => 'KJP-M',
                'size' => 'M',
                'color' => 'Merah',
                'stock' => 5,
                'available_stock' => 5,
                'daily_price' => 250000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item_id' => 2,
                'sku_code' => 'BJP-L',
                'size' => 'L',
                'color' => 'Hitam',
                'stock' => 4,
                'available_stock' => 4,
                'daily_price' => 275000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('item_variants')->insert($variants);
    }
}
