<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('qt');
            $table->unsignedBigInteger('ht');
            $table->unsignedBigInteger('tva');
            $table->unsignedBigInteger('ttc');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('bl_id')->nullable();
            $table->unsignedBigInteger('bc_id')->nullable();
            $table->unsignedBigInteger('trade_id')->nullable();
            $table->timestamps();
        });

        Schema::create('account_detail_order',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('account_detail_id');
        });

    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
