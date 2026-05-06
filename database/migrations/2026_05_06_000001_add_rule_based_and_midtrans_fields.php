<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recommendation_rules', function (Blueprint $table) {
            if (!Schema::hasColumn('recommendation_rules', 'kategori_item')) {
                $table->string('kategori_item')->nullable()->after('jenis_acara');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'kategori_item')) {
                $table->string('kategori_item')->nullable()->after('jenis_acara');
            }

            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('note');
            }
        });

        Schema::table('bundle_items', function (Blueprint $table) {
            if (!Schema::hasColumn('bundle_items', 'item_variant_id')) {
                $table->foreignId('item_variant_id')
                    ->nullable()
                    ->after('item_id')
                    ->constrained('item_variants')
                    ->nullOnDelete();
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('transaction_ref');
            }

            if (!Schema::hasColumn('payments', 'snap_redirect_url')) {
                $table->text('snap_redirect_url')->nullable()->after('snap_token');
            }

            if (!Schema::hasColumn('payments', 'qr_code_url')) {
                $table->text('qr_code_url')->nullable()->after('snap_redirect_url');
            }

            if (!Schema::hasColumn('payments', 'raw_response')) {
                $table->json('raw_response')->nullable()->after('proof_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            foreach (['raw_response', 'qr_code_url', 'snap_redirect_url', 'snap_token'] as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('bundle_items', function (Blueprint $table) {
            if (Schema::hasColumn('bundle_items', 'item_variant_id')) {
                $table->dropConstrainedForeignId('item_variant_id');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'notes')) {
                $table->dropColumn('notes');
            }

            if (Schema::hasColumn('orders', 'kategori_item')) {
                $table->dropColumn('kategori_item');
            }
        });

        Schema::table('recommendation_rules', function (Blueprint $table) {
            if (Schema::hasColumn('recommendation_rules', 'kategori_item')) {
                $table->dropColumn('kategori_item');
            }
        });
    }
};
