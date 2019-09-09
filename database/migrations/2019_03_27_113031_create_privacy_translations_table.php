<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivacyTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privacy_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('privacy_id')->unsigned();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('locale')->index();

            $table->unique(['privacy_id','locale']);
            $table->foreign('privacy_id')->references('id')->on('privacies')->onDelete('cascade');
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
        Schema::dropIfExists('privacy_translations');
    }
}
