<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->comment('name of Partner');
            $table->string('speaker')->comment("Le nom du Gérant");
            $table->string('rc')->nullable();
            $table->string('patent')->nullable();
            $table->string('ice')->nullable();

            $table->unsignedDecimal('gain')->default(0);
            $table->unsignedDecimal('loss')->default(0);

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
                ")->default(0);
            $table->uuid('creator_id')->index();
            $table->uuid('account_id')->nullable()->index();
            $table->timestamps();
        });
        Schema::create('account_detail_partner', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('partner_id')->index();
            $table->uuid('account_detail_id')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('partners');
        Schema::dropIfExists('account_detail_partner');
    }
}
