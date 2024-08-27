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
        Schema::create('query_trims', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('query_item_id');
            $table->foreign('query_item_id')->references('id')->on('query_items');
            $table->unsignedInteger('trim_id');
            $table->foreign('trim_id')->references('id')->on('trims');
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
        Schema::dropIfExists('query_trims');
    }
};
