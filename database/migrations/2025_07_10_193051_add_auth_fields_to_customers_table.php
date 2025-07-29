<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'password')) {
                $table->string('password')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('customers', 'is_verified')) {
                $table->boolean('is_verified')->default(0)->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'password')) {
                $table->dropColumn('password');
            }

            if (Schema::hasColumn('customers', 'is_verified')) {
                $table->dropColumn('is_verified');
            }
        });
    }
};
