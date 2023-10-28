<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('balance_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->decimal('amount', 8, 2); 
            $table->decimal('balance', 15, 2)->default(0);
            $table->text('description');
            $table->timestamps();
    
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('balance_history');
    }
}
