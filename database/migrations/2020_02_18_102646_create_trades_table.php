<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug_inv')->nullable();
            $table->string('inv')->nullable();
            $table->unsignedBigInteger('ht')->nullable();
            $table->unsignedBigInteger('tva')->nullable();
            $table->unsignedBigInteger('ttc')->nullable();
            $table->uuid('partner_id')->index();
            $table->uuid('intermediate_id')->nullable()->index();
            $table->uuid('truck_id')->nullable()->index();
            $table->uuid('creator_id')->index();
            $table->timestamps();
        });

        Schema::create('payment_trade',function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('trade_id')->index();
            $table->uuid('payment_id')->index();
        });

        Schema::create('account_detail_trade', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('trade_id')->index();
            $table->uuid('account_detail_id')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trades');
        Schema::dropIfExists('payment_trade');
        Schema::dropIfExists('account_detail_trade');
    }
}
