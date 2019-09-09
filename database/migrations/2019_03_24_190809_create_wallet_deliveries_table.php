<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('delivery_id')->unsigned();
            $table->decimal('account',14,2)->default(0);
           /* $table->dateTime('date')->nullable();
            $table->string('shift')->nullable();*/
            $table->tinyInteger('status')->default(1);

            $table->foreign('delivery_id')->references('id')
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
        Schema::dropIfExists('wallet_deliveries');
    }
}
