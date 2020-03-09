<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nbr');
            $table->date('from');
            $table->date('to');
            $table->timestamps();
        });
        Schema::create('invoice_trade',function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('invoice_id')->index();
            $table->uuid('trade_id')->index();
        });
        Schema::create('invoice_payment',function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('invoice_id')->index();
            $table->uuid('payment_id')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_trade');
        Schema::dropIfExists('invoice_payment');
    }
}
