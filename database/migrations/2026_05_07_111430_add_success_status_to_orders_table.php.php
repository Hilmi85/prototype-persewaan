<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE orders
            MODIFY status ENUM(
                'pending',
                'confirmed',
                'booked',
                'in_progress',
                'completed',
                'success',
                'cancelled'
            ) DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::table('orders')
            ->where('status', 'success')
            ->update(['status' => 'completed']);

        DB::statement("
            ALTER TABLE orders
            MODIFY status ENUM(
                'pending',
                'confirmed',
                'booked',
                'in_progress',
                'completed',
                'cancelled'
            ) DEFAULT 'pending'
        ");
    }
};
