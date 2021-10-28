<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralNominaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_nomina', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->date('date_begin');
            $table->date('date_end');

            $table->decimal('tax_unit',8,2);
            $table->integer('days_utilities_minimun');
            $table->integer('days_utilities_maximun');
            $table->integer('vacation_bonus_days');

            $table->decimal('sso',8,2);
            $table->decimal('faov',8,2);
            $table->decimal('pie',8,2);
            $table->decimal('sso_company',8,2);
            $table->decimal('faov_company',8,2);
            $table->decimal('pie_company',8,2);
            
            $table->integer('days_benefits');

            $table->integer('cestaticket');
            $table->decimal('cestaticket_amount',64,2);

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
        Schema::dropIfExists('general_nomina');
    }
}
