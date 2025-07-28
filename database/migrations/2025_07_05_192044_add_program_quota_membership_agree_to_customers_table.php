<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProgramQuotaMembershipAgreeToCustomersTable extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'program')) {
                $table->string('program')->nullable()->after('phone_number');
            }

            if (!Schema::hasColumn('customers', 'quota')) {
                $table->integer('quota')->default(0)->after('program');
            }

            if (!Schema::hasColumn('customers', 'membership')) {
                $table->string('membership')->default('Not sure')->after('quota');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'program')) {
                $table->dropColumn('program');
            }

            if (Schema::hasColumn('customers', 'quota')) {
                $table->dropColumn('quota');
            }

            if (Schema::hasColumn('customers', 'membership')) {
                $table->dropColumn('membership');
            }
        });
    }
}
