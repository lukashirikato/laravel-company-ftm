<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom preferred_membership ke tabel customers.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('preferred_membership')->nullable()->after('membership');
        });
    }

    /**
     * Hapus kolom preferred_membership dari tabel customers.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('preferred_membership');
        });
    }
};
