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
        Schema::create('accounts', function (Blueprint $table) {

            $table->increments('id');

            $table->string('name')->unique();

            $table->unsignedInteger('warehouse_id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses');

            $table->unsignedInteger('account_category_id');

            $table->foreign('account_category_id')->references('id')->on('account_categories');


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
        Schema::dropIfExists('accounts');
    }
};
