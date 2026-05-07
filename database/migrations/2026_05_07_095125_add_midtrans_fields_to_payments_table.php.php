<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('proof_url');
            }

            if (!Schema::hasColumn('payments', 'redirect_url')) {
                $table->text('redirect_url')->nullable()->after('snap_token');
            }

            if (!Schema::hasColumn('payments', 'response_payload')) {
                $table->json('response_payload')->nullable()->after('redirect_url');
            }

            if (!Schema::hasColumn('payments', 'notification_payload')) {
                $table->json('notification_payload')->nullable()->after('response_payload');
            }

            if (!Schema::hasColumn('payments', 'midtrans_status')) {
                $table->string('midtrans_status')->nullable()->after('notification_payload');
            }

            if (!Schema::hasColumn('payments', 'fraud_status')) {
                $table->string('fraud_status')->nullable()->after('midtrans_status');
            }

            if (!Schema::hasColumn('payments', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('fraud_status');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            foreach ([
                'payment_type',
                'fraud_status',
                'midtrans_status',
                'notification_payload',
                'response_payload',
                'redirect_url',
                'snap_token',
            ] as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
