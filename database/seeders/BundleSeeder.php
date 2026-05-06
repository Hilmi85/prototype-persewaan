<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BundleSeeder extends Seeder
{
    public function run(): void
    {
        $bundles = [
            [
                'bundle_code' => 'BND-001',
                'bundle_name' => 'Paket Pengantin Jawa Lengkap',
                'description' => 'Paket lengkap untuk acara pernikahan adat Jawa',
                'jenis_acara' => 'Pernikahan',
                'kategori_adat' => 'Jawa',
                'gender' => 'Perempuan',
                'butuh_rias' => true,
                'budget_category' => 'Tinggi',
                'price' => 850000,
                'is_custom' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bundle_code' => 'BND-002',
                'bundle_name' => 'Paket Wisuda Putri',
                'description' => 'Paket untuk wisuda wanita dengan layanan rias',
                'jenis_acara' => 'Wisuda',
                'kategori_adat' => null,
                'gender' => 'Perempuan',
                'butuh_rias' => true,
                'budget_category' => 'Sedang',
                'price' => 550000,
                'is_custom' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bundle_code' => 'BND-003',
                'bundle_name' => 'Paket Hemat',
                'description' => 'Paket bundling dengan harga terjangkau',
                'jenis_acara' => null,
                'kategori_adat' => null,
                'gender' => null,
                'butuh_rias' => false,
                'budget_category' => 'Rendah',
                'price' => 300000,
                'is_custom' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('bundles')->insert($bundles);
    }
}
