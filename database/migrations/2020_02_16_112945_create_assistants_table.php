<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssistantsTable extends Migration
{
    public function up()
    {
        Schema::create('assistants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('staff_id')->index();
            $table->uuid('truck_id')->index();
            $table->dateTime('from');
            $table->dateTime('to')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assistants');
    }
}
