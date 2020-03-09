<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemisesTable extends Migration
{
    public function up()
    {
        Schema::create('remises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedDecimal('remise');
            $table->uuid('product_id')->index();
            $table->uuid('partner_id')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('remises');
    }
}
