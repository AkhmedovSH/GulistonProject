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
            $table->text('additional_info')->nullable();
            $table->string('email')->nullable();
            $table->string('image')->nullable();
            $table->double('balance', 8, 2)->default(0.00);
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
