<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number')->nullable()->unique();
            $table->integer('quantity')->default(1);
            $table->integer('time_id')->nullable()->default(NULL);
            $table->integer('status')->default(0)->unsigned();
            $table->boolean('is_read')->default(0)->unsigned();
            // Payed is for dedicate from  cash or card payed if card 1
            $table->boolean('payed')->nullable()->default(NULL);
            //$table->string('status_text')->nullable();
            $table->integer('product_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('address_id')->unsigned()->nullable();
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
        Schema::dropIfExists('orders');
    }
}
