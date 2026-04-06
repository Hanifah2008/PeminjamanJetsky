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
        Schema::table('transaksis', function (Blueprint $table) {
            // Kolom untuk tracking pengembalian
            $table->date('due_date')->nullable()->after('status');
            $table->enum('return_status', ['pending', 'returned', 'overdue'])->default('pending')->after('due_date');
            $table->timestamp('returned_at')->nullable()->after('return_status');
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik')->after('returned_at');
            $table->text('return_notes')->nullable()->after('kondisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['due_date', 'return_status', 'returned_at', 'kondisi', 'return_notes']);
        });
    }
};
