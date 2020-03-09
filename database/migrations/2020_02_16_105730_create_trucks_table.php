<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrucksTable extends Migration
{
    public function up()
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("registered")->unique();
            $table->boolean('transporter')->default(0);
            $table->string('lat')->nullable();
            $table->string('lang')->nullable();
            $table->unsignedDecimal('cash')->default(0);
            $table->unsignedDecimal('cheque')->default(0);
            $table->date('assurance')->nullable();
            $table->date('visit_technique')->nullable();
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('account_caisse_id');
            $table->unsignedBigInteger('account_charge_id');
            $table->unsignedBigInteger('account_stock_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trucks');
    }
}
