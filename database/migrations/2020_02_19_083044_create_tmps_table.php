<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmpsTable extends Migration
{
    public function up()
    {
        Schema::create('tmps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('qt');
            $table->uuid('product_id')->index();
            $table->uuid('loading_id')->index();
            $table->timestamps();
        });
        Schema::create('account_detail_tmp', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('tmp_id')->index();
            $table->uuid('account_detail_id')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tmps');
        Schema::dropIfExists('account_detail_tmp');
    }
}
