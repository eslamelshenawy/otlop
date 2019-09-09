<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManageShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_shifts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('total_price_morning',14,2)->default(0);
            $table->decimal('total_price_night',14,2)->default(0);
            $table->decimal('delivery_visa_morning',14,2)->default(0);
            $table->decimal('delivery_visa_night',14,2)->default(0);
            $table->decimal('organization_visa_morning',14,2)->default(0);
            $table->decimal('organization_visa_night',14,2)->default(0);
            $table->time('fromTime')->nullable();
            $table->time('toTime')->nullable();
            $table->decimal('percentageOrder',14,1)->default(0)->nullable();
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
        Schema::dropIfExists('manage_shifts');
    }
}
