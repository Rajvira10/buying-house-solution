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
        Schema::create('query_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('query_id');
            $table->foreign('query_id')->references('id')->on('queries')->onDelete('cascade');
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->bigInteger('target_price')->default(0);
            $table->dateTime('price_submission_date')->default(now());
            $table->dateTime('sample_submission_date')->nullable();
            $table->string('product_model');
            $table->text('details');
            $table->bigInteger('approximate_quantity');
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
        Schema::dropIfExists('query_items');
    }
};
