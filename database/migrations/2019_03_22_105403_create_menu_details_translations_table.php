<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuDetailsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_details_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('menu_details_id')->unsigned();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('locale')->index();

            $table->unique(['menu_details_id','locale']);
            $table->foreign('menu_details_id')->references('id')->on('menu_details')->onDelete('cascade');
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
        Schema::dropIfExists('menu_details_translations');
    }
}
