<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargeTrucksTable extends Migration
{
    public function up()
    {
        Schema::create('charge_trucks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('truck_id')->index();
            $table->uuid('creator_id')->index();
            $table->timestamps();
        });
        Schema::create('charge_truck_payment', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('payment_id')->index();
            $table->uuid('charge_truck_id')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charge_trucks');
        Schema::dropIfExists('charge_truck_payment');
    }
}
