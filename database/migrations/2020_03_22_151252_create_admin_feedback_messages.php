<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminFeedbackMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_feedback_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_feedback_id');
            $table->integer('user_id')->default(0);
            $table->integer('admin_id')->default(0);
            $table->string('message');
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
        Schema::dropIfExists('admin_feedback_messages');
    }
}
