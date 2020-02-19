<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmpsTable extends Migration
{
    public function up()
    {
        Schema::create('tmps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('qt');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('loading_id');
            $table->timestamps();
        });
        Schema::create('account_detail_tmp', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tmp_id');
            $table->unsignedBigInteger('account_detail_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tmps');
    }
}
