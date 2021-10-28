<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_account');
            $table->unsignedBigInteger('id_header_voucher')->nullable();
            $table->unsignedBigInteger('id_invoice')->nullable();
            $table->unsignedBigInteger('id_expense')->nullable();
            $table->unsignedBigInteger('user_id');

            $table->decimal('tasa',64,2);
            $table->decimal('debe',64,2);
            $table->decimal('haber',64,2);

            $table->string('status',1);
            $table->date('date_end')->nullable();


            $table->foreign('id_account')->references('id')->on('accounts');
            $table->foreign('id_header_voucher')->references('id')->on('header_vouchers');
            $table->foreign('id_invoice')->references('id')->on('quotations');
            $table->foreign('id_expense')->references('id')->on('expenses_and_purchases');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('detail_vouchers');

    }
}
