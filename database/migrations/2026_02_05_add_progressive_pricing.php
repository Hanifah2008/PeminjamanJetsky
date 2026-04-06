<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alats', function (Blueprint $table) {
            // Tambah kolom untuk menandai apakah harga bisa progresif
            $table->boolean('use_progressive_pricing')->default(true)->after('deskripsi_promo')->comment('Gunakan tarif progresif (1jam normal, 2jam+20%, 3+jam -10%)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alats', function (Blueprint $table) {
            $table->dropColumn('use_progressive_pricing');
        });
    }
};
