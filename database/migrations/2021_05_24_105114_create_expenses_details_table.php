<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_expense');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_inventory')->nullable();
            $table->unsignedBigInteger('id_account')->nullable();
            $table->unsignedBigInteger('id_branch')->nullable();

            $table->string('description',80)->nullable();
            $table->boolean('exento');
            $table->boolean('islr');
            $table->integer('amount');
            $table->decimal('price',64,2);
            $table->decimal('rate',64,2)->comment = 'Tasa';
            
            $table->string('status',1);

            $table->foreign('id_expense')->references('id')->on('expenses_and_purchases');
            $table->foreign('id_inventory')->references('id')->on('inventories');
            $table->foreign('id_account')->references('id')->on('accounts');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_branch')->references('id')->on('branches');
            
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
        Schema::dropIfExists('expenses_details');
    }
}
