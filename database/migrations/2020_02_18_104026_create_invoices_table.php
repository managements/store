<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nbr');
            $table->date('from');
            $table->date('to');
            $table->timestamps();
        });
        Schema::create('invoice_trade',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('trade_id');
        });
        Schema::create('invoice_payment',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('payment_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
