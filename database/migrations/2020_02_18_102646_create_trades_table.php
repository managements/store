<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug_inv')->nullable();
            $table->string('inv')->nullable();
            $table->unsignedBigInteger('ht')->nullable();
            $table->unsignedBigInteger('tva')->nullable();
            $table->unsignedBigInteger('ttc')->nullable();
            $table->unsignedBigInteger('partner_id');
            $table->unsignedBigInteger('intermediate_id')->nullable();
            $table->unsignedBigInteger('truck_id')->nullable();
            $table->unsignedBigInteger('creator_id');
            $table->timestamps();
        });

        Schema::create('payment_trade',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trade_id');
            $table->unsignedBigInteger('payment_id');
        });

        Schema::create('account_detail_trade', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trade_id');
            $table->unsignedBigInteger('account_detail_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trades');
    }
}
