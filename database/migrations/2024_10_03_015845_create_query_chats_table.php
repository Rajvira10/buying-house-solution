<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('query_chats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('query_id');
            $table->foreign('query_id')->references('id')->on('queries')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('message');
            $table->string('attachment')->nullable();
            $table->enum('status', ['sent', 'delivered', 'read'])->default('sent');
            $table->enum('type', ['text', 'attachment'])->default('text');
            $table->dateTime('read_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->dateTime('sent_at')->nullable();
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
        Schema::dropIfExists('query_chats');
    }
};
