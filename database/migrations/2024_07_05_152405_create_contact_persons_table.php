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
        Schema::create('contact_persons', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->unsignedInteger('client_id');

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $table->string('designation');

            $table->string('email')->nullable();

            $table->string('phone');

            $table->dateTime('dob')->nullable();
            
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
        Schema::dropIfExists('contact_persons');
    }
};
