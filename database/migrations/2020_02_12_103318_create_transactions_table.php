<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->integer('user_card_id')->unsigned();
            $table->string('payed_for')->nullable();
            $table->integer('amount')->unsigned();
            $table->boolean('status')->default(0);
            $table->string('uniques')->nullable();
            $table->string('transacID')->nullable();
            $table->string('systemsTraceAuditNumber')->nullable();
            $table->boolean('reversal')->default(0);
            $table->string('code')->nullable();
            $table->string('message')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
