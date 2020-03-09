<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedDecimal('price');
            $table->unsignedBigInteger('operation')->nullable();
            $table->uuid('mode_id')->index();
            $table->timestamps();
        });
        Schema::create('account_detail_payment',function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('payment_id')->index();
            $table->uuid('account_detail_id')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('account_detail_payment');
    }
}
