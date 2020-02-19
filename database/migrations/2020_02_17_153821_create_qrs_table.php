<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrsTable extends Migration
{
    public function up()
    {
        Schema::create('qrs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('img');
            $table->string('code')->unique();
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->string('min_lat')->nullable();
            $table->string('max_lat')->nullable();
            $table->string('min_lang')->nullable();
            $table->string('max_lang')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('qrs');
    }
}
