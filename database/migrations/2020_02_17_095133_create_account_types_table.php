<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTypesTable extends Migration
{
    public function up()
    {
        Schema::create('account_types', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_types');
    }
}
