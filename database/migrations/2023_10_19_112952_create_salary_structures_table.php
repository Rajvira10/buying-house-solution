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
        Schema::create('salary_structures', function (Blueprint $table) {
            
            $table->increments('id');

            
            $table->unsignedInteger('employee_id');

            $table->foreign('employee_id')->references('id')->on('employees');


            $table->decimal('gross', 10, 2);

            $table->decimal('h_rent_percent', 5, 2);
            
            $table->decimal('med_percent', 5, 2);

            $table->decimal('conv_percent', 5, 2);

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
        Schema::dropIfExists('salary_structures');
    }
};
