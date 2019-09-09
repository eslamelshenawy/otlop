<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDayTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('day_id')->unsigned();
            $table->string('name')->nullable()->unique();
            $table->string('locale')->index();

            $table->unique(['day_id','locale']);
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
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
        Schema::dropIfExists('day_translations');
    }
}
