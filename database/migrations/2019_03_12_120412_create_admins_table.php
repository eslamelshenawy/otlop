<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->unique()->nullable();
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->enum('userType',['super_admin','admin','vendor','delivery','user_vendor'])->default('admin');
            $table->string('user_token')->nullable();

            $table->bigInteger('parent_id')->unsigned()->nullable();

            $table->foreign('parent_id')->references('id')
                ->on('admins')->onDelete('cascade');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('admins');
    }
}
