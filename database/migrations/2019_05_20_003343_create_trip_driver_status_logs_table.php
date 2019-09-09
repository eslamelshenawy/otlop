<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripDriverStatusLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_driver_status_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('trip_id');
            $table->unsignedInteger('trip_driver_statuses_id');
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
        Schema::dropIfExists('trip_driver_status_logs');
    }
}
