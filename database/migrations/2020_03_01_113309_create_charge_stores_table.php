<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargeStoresTable extends Migration
{
    public function up()
    {
        Schema::create('charge_stores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('store_id')->default(1)->index();
            $table->uuid('creator_id')->index();
            $table->timestamps();
        });
        Schema::create('charge_store_payment', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('payment_id')->index();
            $table->uuid('charge_store_id')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charge_stores');
        Schema::dropIfExists('charge_store_payment');
    }
}
