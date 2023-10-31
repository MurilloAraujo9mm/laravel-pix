<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToBalanceHistory extends Migration
{
    public function up()
    {
        Schema::table('balance_history', function (Blueprint $table) {
            if (!Schema::hasColumn('balance_history', 'description')) {
                $table->text('description')->nullable()->after('balance');
            }
        });
    }

    public function down()
    {
        Schema::table('balance_history', function (Blueprint $table) {
            if (Schema::hasColumn('balance_history', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
}
