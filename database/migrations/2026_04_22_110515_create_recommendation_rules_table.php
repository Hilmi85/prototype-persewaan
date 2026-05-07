<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_code')->unique()->nullable();
            $table->string('rule_name');

            $table->foreignId('bundle_id')
                ->constrained('bundles')
                ->nullOnDelete();

            $table->string('jenis_acara')->nullable();
            $table->string('kategori_adat')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('butuh_rias')->nullable();
            $table->enum('budget', ['Rendah', 'Sedang', 'Tinggi'])->nullable();

            $table->unsignedInteger('priority')->default(1);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_rules');
    }
};
