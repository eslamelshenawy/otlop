<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherDataMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_data_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('restaurant_id')->unsigned();
            $table->bigInteger('menu_id')->unsigned();
            $table->bigInteger('menu_details_id')->unsigned();
            $table->decimal('price',14,2)->nullable();


            $table->foreign('restaurant_id')->references('id')
                ->on('restaurants')->onDelete('cascade');

            $table->foreign('menu_details_id')->references('id')
                ->on('menu_details')->onDelete('cascade');

            $table->foreign('menu_id')->references('id')
                ->on('menus')->onDelete('cascade');

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
        Schema::dropIfExists('other_data_menus');
    }
}
