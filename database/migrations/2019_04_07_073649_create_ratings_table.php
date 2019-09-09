<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('menu_details_id')->unsigned()->nullable();
            $table->bigInteger('restaurant_id')->unsigned()->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->enum('type',['site','meal','restaurant'])->nullable();
            $table->integer('rating')->nullable();
            $table->text('comment')->nullable();


            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')
                ->on('restaurants')->onDelete('cascade');
            $table->foreign('menu_details_id')->references('id')
                ->on('menu_details')->onDelete('cascade');
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
        Schema::dropIfExists('ratings');
    }
}
