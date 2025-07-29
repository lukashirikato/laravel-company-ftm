<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('goals')->nullable()->after('birth_date');
            $table->string('kondisi_khusus')->nullable()->after('goals');
            $table->string('referensi')->nullable()->after('kondisi_khusus');
            $table->string('pengalaman')->nullable()->after('referensi');
            $table->string('is_muslim')->nullable()->after('pengalaman');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['goals', 'kondisi_khusus', 'referensi', 'pengalaman', 'is_muslim']);
        });
    }
};
