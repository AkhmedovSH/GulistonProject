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
            $table->string('order_number')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('time_id')->nullable();
            $table->integer('status')->default(0);
            $table->boolean('is_read')->default(0);
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('image')->nullable();
            // Payed is for dedicate from  cash or card payed if card 1
            $table->string('payment_type')->default(0);
            //$table->string('status_text')->nullable();
            $table->integer('product_id');
            $table->integer('user_id');
            $table->integer('address_id')->nullable();
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
