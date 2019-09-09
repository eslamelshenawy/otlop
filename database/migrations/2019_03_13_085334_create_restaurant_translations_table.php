<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('restaurant_id')->unsigned();
            $table->string('name');
            $table->string('address');
            $table->string('locale')->index();

            $table->unique(['restaurant_id','locale']);
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->string('crated_by')->nullable();
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
        Schema::dropIfExists('restaurant_translations');
    }
}
