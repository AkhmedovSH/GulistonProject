<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('car_info')->nullable();
            $table->string('car_number')->nullable();
            $table->string('additional_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('image')->nullable();
            $table->text('firebase_token')->nullable();
            $table->double('taxi_balance', 8,0)->default(0);
            $table->double('balance', 8,0)->default(0);
            $table->integer('type')->unsigned()->default(0);
            $table->string('last_login')->nullable();
            $table->rememberToken()->nullable();
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
        Schema::dropIfExists('users');
    }
}
