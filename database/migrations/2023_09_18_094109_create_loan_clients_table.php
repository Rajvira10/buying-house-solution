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
        Schema::create('loan_clients', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('warehouse_id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses');

            $table->string('name');

            $table->string('unique_id')->unique()->nullable();

            $table->string('email')->unique();

            $table->string('contact_no')->unique();

            $table->string('address');

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
        Schema::dropIfExists('loan_clients');
    }
};
