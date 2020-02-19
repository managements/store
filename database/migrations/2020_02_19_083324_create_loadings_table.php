<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadingsTable extends Migration
{
    public function up()
    {
        Schema::create('loadings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('unloading')->default(0);
            $table->boolean('valid')->default(0);
            $table->unsignedBigInteger('truck_id');
            $table->unsignedBigInteger('partner_id');
            $table->timestamps();
        });
        Schema::create('loading_payment',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('loading_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('loadings');
    }
}
