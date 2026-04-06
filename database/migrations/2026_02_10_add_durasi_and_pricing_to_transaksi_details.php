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
        Schema::table('transaksi_details', function (Blueprint $table) {
            // Tambah kolom durasi_jam jika belum ada
            if (!Schema::hasColumn('transaksi_details', 'durasi_jam')) {
                $table->float('durasi_jam')->default(1)->after('qty');
            }
            
            // Tambah kolom harga_original jika belum ada
            if (!Schema::hasColumn('transaksi_details', 'harga_original')) {
                $table->bigInteger('harga_original')->nullable()->after('harga');
            }
            
            // Tambah kolom harga_setelah_diskon jika belum ada
            if (!Schema::hasColumn('transaksi_details', 'harga_setelah_diskon')) {
                $table->bigInteger('harga_setelah_diskon')->nullable()->after('harga_original');
            }
            
            // Tambah kolom diskon_persen jika belum ada
            if (!Schema::hasColumn('transaksi_details', 'diskon_persen')) {
                $table->integer('diskon_persen')->default(0)->after('harga_setelah_diskon');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            $table->dropColumn(['durasi_jam', 'harga_original', 'harga_setelah_diskon', 'diskon_persen']);
        });
    }
};
