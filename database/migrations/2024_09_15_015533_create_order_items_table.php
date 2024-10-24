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
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->string('style_no')->nullable();
            $table->string('image')->nullable();
            $table->string('item')->nullable();
            $table->decimal('factory_cost', 10, 2)->default(0);
            $table->decimal('final_cost', 10, 2)->default(0);
            $table->string('sizes')->nullable();
            $table->json('colors')->nullable();
            $table->bigInteger('pieces')->default(0);
            $table->dateTime('shipment_date')->default(now());
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
        Schema::dropIfExists('order_items');
    }
};
