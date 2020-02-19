<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargeTrucksTable extends Migration
{
    public function up()
    {
        Schema::create('charge_trucks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('truck_id');
            $table->unsignedBigInteger('creator_id');
            $table->timestamps();
        });
        Schema::create('charge_truck_payment', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('charge_truck_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('charge_trucks');
        Schema::dropIfExists('charge_truck_payment');
    }
}
