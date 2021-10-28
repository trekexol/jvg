<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnticiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anticipos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_client')->nullable();
            $table->unsignedBigInteger('id_provider')->nullable();
            $table->unsignedBigInteger('id_account');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_quotation')->nullable();

            $table->date('date');
            $table->decimal('amount',64,2);
            $table->decimal('rate',64,2);
            $table->string('coin',20);
            $table->string('reference',20)->nullable();
            
           
            $table->string('status',1);
            
            $table->foreign('id_client')->references('id')->on('clients');
            $table->foreign('id_provider')->references('id')->on('providers');
            $table->foreign('id_account')->references('id')->on('accounts');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_quotation')->references('id')->on('quotations');
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
        Schema::dropIfExists('anticipos');
    }
}
