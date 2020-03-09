<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadingsTable extends Migration
{
    public function up()
    {
        Schema::create('loadings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('unloading')->default(0);
            $table->boolean('valid')->default(0);
            $table->uuid('truck_id')->index();
            $table->uuid('partner_id')->index();
            $table->timestamps();
        });
        Schema::create('loading_payment',function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('payment_id')->index();
            $table->uuid('loading_id')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loadings');
        Schema::dropIfExists('loading_payment');
    }
}
