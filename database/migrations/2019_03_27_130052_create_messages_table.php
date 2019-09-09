<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('emailTo')->nullable();
            $table->string('emailSend')->nullable();
            $table->string('subject')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->enum('type',['vendor','customer','delivery','others'])->default('others');
            $table->text('message')->nullable();
            $table->tinyInteger('read')->default(0);
            $table->tinyInteger('send')->nullable();
            $table->tinyInteger('receive')->nullable();
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
        Schema::dropIfExists('messages');
    }
}
