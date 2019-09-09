<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('state_id')->unsigned();
            $table->string('name');
            $table->string('locale')->index();

            $table->unique(['state_id','locale']);
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
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
        Schema::dropIfExists('state_translations');
    }
}
