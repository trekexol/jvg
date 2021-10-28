<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesAndPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses_and_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('id_provider');
            $table->unsignedBigInteger('id_user');

            $table->string('invoice',30)->nullable();
            $table->string('serie',30)->nullable();
            $table->string('observation',150)->nullable();

            $table->date('date');
            $table->date('date_payment')->nullable();
         
            $table->decimal('anticipo',64,2)->nullable();
            $table->integer('iva_percentage')->nullable();
            $table->integer('credit_days')->nullable();
           
            $table->decimal('base_imponible',64,2)->nullable();
            $table->decimal('amount',64,2)->nullable();
            $table->decimal('amount_iva',64,2)->nullable();
            $table->decimal('amount_with_iva',64,2)->nullable();

            $table->string('coin',15)->nullable();

            $table->decimal('rate',64,2)->nullable()->comment = 'Tasa';

           
            $table->string('status',1);
            $table->foreign('id_provider')->references('id')->on('providers');
            $table->foreign('id_user')->references('id')->on('users');

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
        Schema::dropIfExists('expenses_and_purchases');
    }
}
