<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('restaurant_id')->unsigned()->nullable();
            $table->bigInteger('menu_details_id')->unsigned()->nullable();
            $table->time('fromTime')->nullable();
            $table->time('toTime')->nullable();
            $table->date('fromDate')->nullable();
            $table->date('toDate')->nullable();
            $table->decimal('price',14,2)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

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
        Schema::dropIfExists('offers');
    }
}
