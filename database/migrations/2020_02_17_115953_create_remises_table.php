<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemisesTable extends Migration
{
    public function up()
    {
        Schema::create('remises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedDecimal('remise');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('partner_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('remises');
    }
}
