<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('modelo_id');
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('user_id');
            
            $table->string('type',20);
            $table->string('placa',20);
            $table->string('photo_transport',150)->nullable();
            $table->string('status',1);

            $table->foreign('modelo_id')->references('id')->on('modelos');
            $table->foreign('color_id')->references('id')->on('colors');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transports');
    }
}
