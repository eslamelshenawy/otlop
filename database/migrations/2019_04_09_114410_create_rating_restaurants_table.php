<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating_restaurants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('restaurant_id')->unsigned()->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->integer('rating')->default(5);
            $table->text('comment')->nullable();


            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')
                ->on('restaurants')->onDelete('cascade');

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
        Schema::dropIfExists('rating_restaurants');
    }
}
