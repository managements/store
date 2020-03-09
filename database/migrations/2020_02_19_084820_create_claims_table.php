<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean("debt")->default(0);
            $table->unsignedBigInteger("partner_id");
            $table->unsignedBigInteger("cheque_id")->nullable();
            $table->unsignedBigInteger("transfer_id")->nullable();
            $table->unsignedBigInteger("cash_id")->nullable();
            $table->unsignedBigInteger("creator_id");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claims');
    }
}
