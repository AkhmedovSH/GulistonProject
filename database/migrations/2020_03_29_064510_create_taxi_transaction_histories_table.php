<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxiTransactionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxi_transaction_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('taxi_id');
            $table->integer('user_id');
            $table->integer('amount');
            $table->boolean('status')->default(0); // status 0 okey 1 canceled
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
        Schema::dropIfExists('taxi_transaction_histories');
    }
}
