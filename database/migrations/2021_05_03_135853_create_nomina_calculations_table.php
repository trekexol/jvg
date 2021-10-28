<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominaCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomina_calculations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_nomina');
            $table->unsignedBigInteger('id_nomina_concept');
            $table->unsignedBigInteger('id_employee');

            $table->integer('number_receipt');
            $table->string('type',20);
            $table->decimal('amount',64,2);
            $table->integer('hours')->nullable();
            $table->integer('days')->nullable();
            $table->decimal('cantidad',64,2)->nullable();
            $table->integer('voucher')->nullable();
           
            $table->string('status',1);
            $table->foreign('id_nomina')->references('id')->on('nominas');
            $table->foreign('id_nomina_concept')->references('id')->on('nomina_concepts');
            $table->foreign('id_employee')->references('id')->on('employees');
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
        Schema::dropIfExists('nomina_calculations');
    }
}
