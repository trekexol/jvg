<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_payments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('id_expense');
            $table->unsignedBigInteger('id_account')->nullable();


            $table->integer('payment_type');
            $table->decimal('amount',64,2);
            $table->string('reference',30)->nullable();

            $table->string('status',1);

            $table->foreign('id_expense')->references('id')->on('expenses_and_purchases');
            $table->foreign('id_account')->references('id')->on('accounts');

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
        Schema::dropIfExists('expense_payments');
    }
}
