<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('seo_id')->unsigned();
            $table->text('keyword')->nullable();
            $table->text('description')->nullable();
            $table->string('locale')->index();

            $table->unique(['seo_id','locale']);
            $table->foreign('seo_id')->references('id')->on('seos')->onDelete('cascade');
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
        Schema::dropIfExists('seo_translations');
    }
}
