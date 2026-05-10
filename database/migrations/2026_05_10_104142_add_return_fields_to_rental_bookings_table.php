<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental_bookings', function (Blueprint $table) {
            $table->timestamp('returned_at')->nullable()->after('booking_status');
            $table->unsignedBigInteger('returned_by')->nullable()->after('returned_at');

            $table->string('return_condition')->nullable()->after('returned_by');
            $table->string('return_stock_action')->nullable()->after('return_condition');
            $table->text('return_notes')->nullable()->after('return_stock_action');

            $table->unsignedInteger('late_days')->default(0)->after('return_notes');
            $table->decimal('late_fee', 12, 2)->default(0)->after('late_days');
            $table->decimal('damage_fee', 12, 2)->default(0)->after('late_fee');
            $table->decimal('total_return_fee', 12, 2)->default(0)->after('damage_fee');

            $table->foreign('returned_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rental_bookings', function (Blueprint $table) {
            $table->dropForeign(['returned_by']);

            $table->dropColumn([
                'returned_at',
                'returned_by',
                'return_condition',
                'return_stock_action',
                'return_notes',
                'late_days',
                'late_fee',
                'damage_fee',
                'total_return_fee',
            ]);
        });
    }
};
