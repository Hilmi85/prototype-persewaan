<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contact_settings')->insert([
            'admin_user_id' => 1,
            'contact_name' => 'Admin Quin Salon',
            'whatsapp_number' => '6281234567890',
            'whatsapp_url' => 'https://wa.me/6281234567890',
            'message_template' => 'Halo Admin Quin Salon, saya ingin konfirmasi ketersediaan paket.',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
