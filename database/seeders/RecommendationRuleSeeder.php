<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecommendationRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'rule_code' => 'RULE-001',
                'rule_name' => 'Rule Pengantin Jawa',
                'bundle_id' => 1,
                'jenis_acara' => 'Pernikahan',
                'kategori_adat' => 'Jawa',
                'gender' => null,
                'butuh_rias' => true,
                'budget' => null,
                'size' => null,
                'priority' => 1,
                'is_active' => true,
                'notes' => 'Jika acara pernikahan, adat Jawa, dan butuh rias',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rule_code' => 'RULE-002',
                'rule_name' => 'Rule Wisuda Putri',
                'bundle_id' => 2,
                'jenis_acara' => 'Wisuda',
                'kategori_adat' => null,
                'gender' => 'Perempuan',
                'butuh_rias' => true,
                'budget' => null,
                'size' => null,
                'priority' => 2,
                'is_active' => true,
                'notes' => 'Jika acara wisuda, perempuan, dan butuh rias',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rule_code' => 'RULE-003',
                'rule_name' => 'Rule Paket Hemat',
                'bundle_id' => 3,
                'jenis_acara' => null,
                'kategori_adat' => null,
                'gender' => null,
                'butuh_rias' => false,
                'budget' => 'Rendah',
                'size' => null,
                'priority' => 3,
                'is_active' => true,
                'notes' => 'Jika budget rendah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('recommendation_rules')->insert($rules);
    }
}
