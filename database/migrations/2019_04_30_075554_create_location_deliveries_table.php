<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('delivery_id')->unsigned();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->enum('active',['on','off'])->default('off');

            $table->foreign('delivery_id')->references('id')->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('location_deliveries');
    }
}
