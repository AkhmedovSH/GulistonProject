<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->double('price', 8, 2)->default(0.00);
            $table->integer('discount')->default(0);
            $table->string('image')->nullable();
            $table->text('images')->nullable();
            $table->boolean('available')->default(1);
            $table->boolean('famous')->default(1);
            $table->boolean('hasAttributes')->default(0);
            $table->text('parameters')->nullable()->comment('JSON array of parameters');
            $table->string('keywords')->nullable();
            $table->string('quantity_type')->default('piece');
            $table->integer('company_id')->nullable()->unsigned();
            $table->integer('company_category_id')->nullable()->unsigned();
            $table->integer('category_id')->nullable()->unsigned();
            //$table->foreign('company_id')->references('id')->on('companies');
            //$table->foreign('category_id')->references('id')->on('categories');
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
        Schema::dropIfExists('products');
    }
}
