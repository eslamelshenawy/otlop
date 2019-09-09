<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripDriverDispatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_driver_dispatches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('driver_id')->nullable();
            $table->bigInteger('trip_id')->nullable();
            $table->tinyInteger('request_sent_status')->default(0);
            $table->dateTime('request_sent_at')->nullable();
            $table->bigInteger('rank')->default(0);
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
        Schema::dropIfExists('trip_driver_dispatches');
    }
}
