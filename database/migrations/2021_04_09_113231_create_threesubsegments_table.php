<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatethreesubsegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('three_subsegments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('twosubsegment_id');
            $table->string('description',100);
            $table->string('status',1);
            
            $table->foreign('twosubsegment_id')->references('id')->on('two_subsegments');
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
        Schema::dropIfExists('3subsegments');
    }
}
