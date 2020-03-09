<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargeStoresTable extends Migration
{
    public function up()
    {
        Schema::create('charge_stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->default(1);
            $table->unsignedBigInteger('creator_id');
            $table->timestamps();
        });
        Schema::create('charge_store_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('charge_store_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('charge_stores');
        Schema::dropIfExists('charge_store_payment');
    }
}
