<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherDataMenuTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_data_menu_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('other_data_menu_id')->unsigned();
            $table->string('title')->nullable();
            $table->string('locale')->index();

            $table->unique(['other_data_menu_id','locale']);
            $table->foreign('other_data_menu_id')->references('id')->on('other_data_menus')->onDelete('cascade');

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
        Schema::dropIfExists('other_data_menu_translations');
    }
}
