<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('qt');
            $table->uuid('product_id')->index();
            $table->uuid('truck_id')->nullable()->index();
            $table->uuid('partner_id')->nullable()->index();
            $table->uuid('store_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
