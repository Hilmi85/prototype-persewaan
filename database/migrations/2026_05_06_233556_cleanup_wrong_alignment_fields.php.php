<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('recommendation_rules')) {
            Schema::table('recommendation_rules', function (Blueprint $table) {
                foreach (['kategori_item', 'size'] as $column) {
                    if (Schema::hasColumn('recommendation_rules', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                foreach (['kategori_item', 'notes'] as $column) {
                    if (Schema::hasColumn('orders', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('bundle_items') && Schema::hasColumn('bundle_items', 'item_variant_id')) {
            $foreignKey = $this->foreignKeyName('bundle_items', 'item_variant_id');

            Schema::table('bundle_items', function (Blueprint $table) use ($foreignKey) {
                if ($foreignKey) {
                    $table->dropForeign($foreignKey);
                }

                $table->dropColumn('item_variant_id');
            });
        }

        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                foreach (['raw_response', 'qr_code_url', 'snap_redirect_url'] as $column) {
                    if (Schema::hasColumn('payments', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        // Migration ini hanya membersihkan kolom yang tidak sesuai rancangan proposal.
    }

    private function foreignKeyName(string $table, string $column): ?string
    {
        $database = DB::getDatabaseName();

        $result = DB::selectOne(
            'SELECT CONSTRAINT_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ?
             AND TABLE_NAME = ?
             AND COLUMN_NAME = ?
             AND REFERENCED_TABLE_NAME IS NOT NULL
             LIMIT 1',
            [$database, $table, $column]
        );

        return $result?->CONSTRAINT_NAME;
    }
};
