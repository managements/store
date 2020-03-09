<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('qt');
            $table->unsignedBigInteger('ht');
            $table->unsignedBigInteger('tva');
            $table->unsignedBigInteger('ttc');
            $table->uuid('product_id')->index();
            $table->uuid('bl_id')->nullable()->index();
            $table->uuid('bc_id')->nullable()->index();
            $table->uuid('trade_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('account_detail_order',function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('order_id')->index();
            $table->uuid('account_detail_id')->index();
        });

    }

    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('account_detail_order');
    }
}
