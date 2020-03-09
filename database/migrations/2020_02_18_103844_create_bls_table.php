<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlsTable extends Migration
{
    public function up()
    {
        Schema::create('bls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nbr');
            $table->uuid('trade_id')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bls');
    }
}
