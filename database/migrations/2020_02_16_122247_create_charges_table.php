<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargesTable extends Migration
{
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->string('charge');
        });
    }

    public function down()
    {
        Schema::dropIfExists('charges');
    }
}
