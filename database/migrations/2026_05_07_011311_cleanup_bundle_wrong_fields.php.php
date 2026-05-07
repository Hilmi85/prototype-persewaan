<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bundles')) {
            Schema::table('bundles', function (Blueprint $table) {
                foreach (['kategori_item', 'recommended_variant_ids'] as $column) {
                    if (Schema::hasColumn('bundles', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('bundle_items')) {
            if (Schema::hasColumn('bundle_items', 'item_variant_id')) {
                $foreignKey = $this->foreignKeyName('bundle_items', 'item_variant_id');

                Schema::table('bundle_items', function (Blueprint $table) use ($foreignKey) {
                    if ($foreignKey) {
                        $table->dropForeign($foreignKey);
                    }

                    $table->dropColumn('item_variant_id');
                });
            }

            Schema::table('bundle_items', function (Blueprint $table) {
                foreach (['unit_price', 'subtotal_price'] as $column) {
                    if (Schema::hasColumn('bundle_items', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        // Cleanup ini hanya untuk meluruskan struktur bundle agar sesuai rancangan sistem.
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
