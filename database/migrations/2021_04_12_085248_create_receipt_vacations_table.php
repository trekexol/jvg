<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptVacationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_vacations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->date('date_begin');
            $table->date('date_end');
            $table->integer('days_vacations');
            $table->integer('bono_vacations');
            $table->integer('days_feriados');
            $table->decimal('lph',64,2);
            $table->decimal('sso',64,2);
            $table->decimal('seguro_paro_forzoso',64,2);
            $table->decimal('ultimo_sueldo',64,2);
            $table->decimal('total_pagar',64,2);
            
            $table->string('status',1);
            $table->foreign('employee_id')->references('id')->on('employees');
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
        Schema::dropIfExists('receipt_vacations');
    }
}
