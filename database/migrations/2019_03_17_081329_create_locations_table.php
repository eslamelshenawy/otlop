<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('vendor_id')->unsigned();
            $table->bigInteger('restaurant_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->bigInteger('state_id')->unsigned();
            $table->string('address')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->tinyInteger('status')->default(1);


            $table->foreign('restaurant_id')->references('id')
                ->on('restaurants')->onDelete('cascade');

            $table->foreign('vendor_id')->references('id')
                ->on('admins')->onDelete('cascade');

            $table->foreign('city_id')->references('id')
                ->on('cities')->onDelete('cascade');

            $table->foreign('state_id')->references('id')
                ->on('states')->onDelete('cascade');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

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
        Schema::dropIfExists('locations');
    }
}
