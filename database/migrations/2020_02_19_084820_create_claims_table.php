<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean("debt")->default(0);
            $table->uuid("partner_id")->index();
            $table->uuid("cheque_id")->nullable()->index();
            $table->uuid("transfer_id")->nullable()->index();
            $table->uuid("cash_id")->nullable()->index();
            $table->uuid("creator_id")->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claims');
    }
}
