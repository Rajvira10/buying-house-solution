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
        Schema::create('query_measurements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('query_item_id');
            $table->foreign('query_item_id')->references('id')->on('query_items');
            $table->unsignedInteger('file_id');
            $table->foreign('file_id')->references('id')->on('files');
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
        Schema::dropIfExists('query_measurements');
    }
};
