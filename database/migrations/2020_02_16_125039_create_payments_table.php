<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedDecimal('price');
            $table->unsignedBigInteger('operation')->nullable();
            $table->unsignedBigInteger('mode_id');
            $table->timestamps();
        });
        Schema::create('account_detail_payment',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('account_detail_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
