<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom 'password' dan 'is_verified' ke tabel 'customers'.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'password')) {
                $table->string('password')->nullable(); // tanpa after
            }

            if (!Schema::hasColumn('customers', 'is_verified')) {
                $table->boolean('is_verified')->default(false); // tanpa after
            }
        });
    }

    /**
     * Hapus kolom jika perlu di-rollback.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'is_verified')) {
                $table->dropColumn('is_verified');
            }

            if (Schema::hasColumn('customers', 'password')) {
                $table->dropColumn('password');
            }
        });
    }
};
