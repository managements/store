<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargeStoreDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('charge_store_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('label');
            $table->unsignedDecimal('price');
            $table->uuid('charge_id')->index();
            $table->uuid('charge_store_id')->index();
            $table->uuid('account_detail_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charge_store_details');
    }
}
