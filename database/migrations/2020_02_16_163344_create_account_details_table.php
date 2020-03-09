<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('account_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('label');
            $table->string('detail');
            $table->unsignedBigInteger('qt_enter')->nullable();
            $table->unsignedBigInteger('qt_out')->nullable();
            $table->unsignedDecimal('db')->nullable();
            $table->unsignedDecimal('cr')->nullable();
            $table->uuid('account_id')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_details');
    }
}
