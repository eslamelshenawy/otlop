<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_shares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('send_by')->unsigned(); // user id send share
            $table->bigInteger('receive_by')->unsigned();
            $table->tinyInteger('accept')->default(0);
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
        Schema::dropIfExists('send_shares');
    }
}
