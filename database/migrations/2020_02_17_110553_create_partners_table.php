<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('name of Partner');
            $table->string('speaker')->comment("Le nom du Gérant");
            $table->string('rc');
            $table->string('patent');
            $table->string('ice');

            $table->string('account')->unique()
                ->comment("
                verifier pour la synchronisation; 
                provider 900 000 increments by 100; 
                client 100 000 increments by 1;
                ");

            $table->tinyInteger('provider')
                ->comment("
                0 => Client, 
                1 => Provider, 
                ");
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('account_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('partners');
    }
}
