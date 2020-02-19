<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargeTruckDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('charge_truck_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->unsignedDecimal('price');
            $table->unsignedBigInteger('charge_id');
            $table->unsignedBigInteger('charge_truck_id');
            $table->unsignedBigInteger('account_detail_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charge_truck_details');
    }
}
