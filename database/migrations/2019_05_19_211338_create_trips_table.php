<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('driver_id')->unsigned()->nullable();
            $table->tinyInteger('trips_status_id')->default(1);
            $table->tinyInteger('trips_driver_status')->default(1);
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('trips_status_id')->references('id')->on('trip_statuses')->onDelete('cascade');

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
        Schema::dropIfExists('trips');
    }
}
