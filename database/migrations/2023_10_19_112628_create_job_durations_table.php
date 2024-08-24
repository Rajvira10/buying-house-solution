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
        Schema::create('job_durations', function (Blueprint $table) {
            
            $table->increments('id');

            
            $table->unsignedInteger('employee_id');

            $table->foreign('employee_id')->references('id')->on('employees');


            $table->dateTime('start_date');

            $table->dateTime('end_date')->nullable();

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
        Schema::dropIfExists('job_durations');
    }
};
