<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('parroquia_id');
            $table->string('description',150);
            $table->string('direction',150);
            $table->string('phone',25);
            $table->string('phone2',25);
            $table->string('person_contact',160);
            $table->string('phone_contact',30);
            $table->string('observation',150);
            $table->string('status',1);
            
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('parroquia_id')->references('id')->on('parroquias');
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
        Schema::dropIfExists('branches');
    }
}
