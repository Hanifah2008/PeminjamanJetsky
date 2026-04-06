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
        Schema::table('keranjangs', function (Blueprint $table) {
            // Tambah kolom untuk jam peminjaman
            $table->time('jam_mulai')->nullable()->after('harga');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
            $table->date('tanggal_mulai')->nullable()->after('jam_selesai');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai', 'tanggal_mulai', 'tanggal_selesai']);
        });
    }
};
