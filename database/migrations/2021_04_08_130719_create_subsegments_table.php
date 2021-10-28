<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubsegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subsegments', function (Blueprint $table) {
            $table->bigIncrements('id');  
            $table->unsignedBigInteger('segment_id');
            $table->string('description',100);
            $table->string('status',1);
            
            $table->foreign('segment_id')->references('id')->on('segments');
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
        Schema::dropIfExists('subsegments');
    }
}
