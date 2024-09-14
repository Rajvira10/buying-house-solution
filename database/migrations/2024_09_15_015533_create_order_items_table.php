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
            $table->string('disculpe')->nullable();
            $table->string('brand')->nullable();
            $table->string('code')->nullable();
            $table->string('function')->nullable();
            $table->string('model')->nullable();
            $table->string('details')->nullable();
            $table->string('fit')->nullable();
            $table->string('fabric')->nullable();
            $table->string('weight')->nullable();
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
