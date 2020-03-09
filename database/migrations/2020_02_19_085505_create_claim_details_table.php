<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('claim_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('claim_id')->index();
            $table->uuid('trade_id')->index();
            $table->unsignedBigInteger("bl_nbr")->nullable();
            $table->unsignedDecimal('term');
            $table->string('inv')->nullable();
            $table->timestamps();
        });
        Schema::create('account_detail_claim_detail', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('claim_detail_id')->index();
            $table->uuid('account_detail_id')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claim_details');
    }
}
