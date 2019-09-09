<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestWorkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_workings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type',['owner','delivery'])->nullable();
            $table->string('name')->nullable();
            $table->string('res_name')->nullable();
            $table->bigInteger('city_id')->nullable()->unsigned();
            $table->bigInteger('state_id')->nullable()->unsigned();
            $table->bigInteger('type_id')->nullable()->unsigned();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('file')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
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
        Schema::dropIfExists('request_workings');
    }
}
