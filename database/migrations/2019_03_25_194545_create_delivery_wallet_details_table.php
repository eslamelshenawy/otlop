<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryWalletDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_wallet_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('delivery_id')->unsigned();
            $table->decimal('account',14,2)->default(0);
            $table->dateTime('date')->nullable();


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
        Schema::dropIfExists('delivery_wallet_details');
    }
}
