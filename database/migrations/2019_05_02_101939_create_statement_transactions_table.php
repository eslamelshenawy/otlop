<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatementTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statement_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->bigInteger('from_id')->nullable();
            $table->bigInteger('to_id')->nullable();
            $table->enum('from_user_type',['user','delivery','vendor','admin']);
            $table->enum('to_user_type',['user','delivery','vendor','admin']);
            $table->decimal('amount',14,2)->default(0);
            $table->dateTime('due_date')->nullable();
            $table->enum('status',['paid','due']); // paid مدفوع  due مستحق
            $table->enum('payment_method',['wallet','card','cash']); // paid مدفوع  due مستحق

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

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
        Schema::dropIfExists('statement_transactions');
    }
}
