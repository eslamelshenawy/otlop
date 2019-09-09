<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('restaurant_id')->unsigned()->nullable();
            $table->bigInteger('day_id')->unsigned()->nullable();

            $table->time('from')->nullable();
            $table->time('to')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->foreign('restaurant_id')->references('id')
                ->on('restaurants')->onDelete('cascade');
            $table->foreign('day_id')->references('id')
                ->on('days')->onDelete('cascade');
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
        Schema::dropIfExists('working_hours');
    }
}
