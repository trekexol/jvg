<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatetwosubsegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_subsegments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subsegment_id');
            $table->string('description',100);
            $table->string('status',1);
            
            $table->foreign('subsegment_id')->references('id')->on('subsegments');
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
        Schema::dropIfExists('2subsegments');
    }
}
