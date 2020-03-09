<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('claim_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('claim_id');
            $table->unsignedBigInteger('trade_id');
            $table->unsignedBigInteger("bl_nbr")->nullable();
            $table->unsignedDecimal('term');
            $table->string('inv')->nullable();
            $table->timestamps();
        });
        Schema::create('account_detail_claim_detail', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('claim_detail_id');
            $table->unsignedBigInteger('account_detail_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('claim_details');
    }
}
