<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            ItemSeeder::class,
            ItemVariantSeeder::class,
            BundleSeeder::class,
            RecommendationRuleSeeder::class,
            ContactSettingSeeder::class,
        ]);
    }
}
