<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->string('bundle_code')->unique();
            $table->string('bundle_name');
            $table->text('description')->nullable();

            $table->string('jenis_acara')->nullable();
            $table->string('kategori_adat')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('butuh_rias')->default(false);
            $table->enum('budget_category', ['Rendah', 'Sedang', 'Tinggi'])->nullable();

            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_custom')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundles');
    }
};
