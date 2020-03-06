<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTaxisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_taxis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number')->nullable();
            $table->string('fromLongitude')->nullable();
            $table->string('fromLatitude')->nullable();
            $table->string('toLongitude')->nullable();
            $table->string('toLatitude')->nullable();
	    $table->string('startAddress')->nullable();
            $table->string('destinationAddress')->nullable();
            $table->integer('taxi_user_id')->nullable();
            $table->integer('user_id');
            $table->integer('status')->default(0);
            $table->double('price', 8,0)->default(0);
            $table->string('order_accept_time')->nullable();
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
        Schema::dropIfExists('order_taxis');
    }
}
