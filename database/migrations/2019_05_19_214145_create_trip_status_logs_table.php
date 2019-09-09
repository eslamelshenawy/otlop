<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripStatusLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_status_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('trip_id')->unsigned()->nullable();
            $table->bigInteger('trip_statuses_id')->unsigned()->nullable();
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->foreign('trip_statuses_id')->references('id')->on('trip_statuses')->onDelete('cascade');
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
        Schema::dropIfExists('trip_status_logs');
    }
}
