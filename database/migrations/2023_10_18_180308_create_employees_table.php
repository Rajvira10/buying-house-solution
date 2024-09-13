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
        Schema::create('employees', function (Blueprint $table) {
            
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users');


            $table->string('unique_id')->unique()->nullable();

            $table->string('contact_no')->unique();

            $table->string('nid')->unique();

            $table->string('present_address');

            $table->string('permanent_address');


            $table->unsignedInteger('department_id');

            $table->foreign('department_id')->references('id')->on('departments');


            $table->string('designation');

            
            $table->unsignedInteger('image_id');

            $table->foreign('image_id')->references('id')->on('files');


            $table->unsignedInteger('cv_id')->nullable();

            $table->foreign('cv_id')->references('id')->on('files');


            $table->string('status');
            
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
        Schema::dropIfExists('employees');
    }
};
