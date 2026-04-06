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
            // Tambah kolom untuk diskon
            $table->decimal('diskon_persen', 5, 2)->default(0)->after('harga')->comment('Diskon dalam persen');
            $table->text('deskripsi_promo')->nullable()->after('diskon_persen')->comment('Deskripsi promo');
        });
        
        Schema::table('keranjangs', function (Blueprint $table) {
            // Update untuk menyimpan durasi rental
            $table->decimal('durasi_jam', 4, 2)->default(1)->after('qty')->comment('Durasi dalam jam (bisa 0.5, 1, 1.5, dll)');
            $table->bigInteger('harga_original')->nullable()->after('durasi_jam')->comment('Harga sebelum diskon');
            $table->decimal('diskon_persen', 5, 2)->default(0)->after('harga_original')->comment('Diskon persen dari alat');
            $table->bigInteger('harga_setelah_diskon')->nullable()->after('diskon_persen')->comment('Harga setelah diskon');
        });

        Schema::table('transaksi_details', function (Blueprint $table) {
            // Update untuk menyimpan durasi rental
            $table->decimal('durasi_jam', 4, 2)->default(1)->after('alat_name')->comment('Durasi dalam jam');
            $table->bigInteger('harga_original')->nullable()->after('durasi_jam')->comment('Harga sebelum diskon');
            $table->decimal('diskon_persen', 5, 2)->default(0)->after('harga_original')->comment('Diskon persen');
            $table->bigInteger('harga_setelah_diskon')->nullable()->after('diskon_persen')->comment('Harga setelah diskon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alats', function (Blueprint $table) {
            $table->dropColumn(['diskon_persen', 'deskripsi_promo']);
        });

        Schema::table('keranjangs', function (Blueprint $table) {
            $table->dropColumn(['durasi_jam', 'harga_original', 'diskon_persen', 'harga_setelah_diskon']);
        });

        Schema::table('transaksi_details', function (Blueprint $table) {
            $table->dropColumn(['durasi_jam', 'harga_original', 'diskon_persen', 'harga_setelah_diskon']);
        });
    }
};
