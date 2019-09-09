<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('restaurant_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->decimal('amount',14,2)->nullable();
            $table->dateTime('dateTime')->nullable();
            $table->decimal('tex',14,2)->nullable();
            $table->decimal('amount_delivery',14,2)->nullable();
            $table->enum('type_shift',['morning','night'])->nullable();
            $table->enum('payment_type',['cash','wallet','cart'])->nullable();

            $table->enum('status',['pending','complete','cancel','delivery'])->nullable();

            $table->foreign('restaurant_id')->references('id')
                ->on('restaurants')->onDelete('cascade');
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
}
