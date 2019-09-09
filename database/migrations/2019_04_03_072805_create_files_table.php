<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('admin_id')->unsigned()->nullable();
            $table->enum('type',['user','delivery','owner'])->nullable();
            $table->string('name')->nullable();


            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->foreign('admin_id')->references('id')
                ->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('files');
    }
}
