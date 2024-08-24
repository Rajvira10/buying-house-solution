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
        Schema::create('payroll_details', function (Blueprint $table) {
            
            $table->increments('id');
            

            $table->unsignedInteger('payroll_id');

            $table->foreign('payroll_id')->references('id')->on('payrolls');


            $table->unsignedInteger('employee_id');

            $table->foreign('employee_id')->references('id')->on('employees');


            $table->decimal('bonus', 10, 2)->default(0.00);

            $table->tinyInteger('days_absent')->default(0);

            $table->string('payment_status');

            $table->dateTime('payment_date')->nullable();

            $table->string('payment_method')->nullable();


            $table->unsignedInteger('disbursed_by')->nullable();

            $table->foreign('disbursed_by')->references('id')->on('users');
            

            $table->dateTime('disbursed_at')->nullable();
            
            
            $table->unsignedInteger('printed_by')->nullable();

            $table->foreign('printed_by')->references('id')->on('users');
            

            $table->dateTime('printed_at')->nullable();
            
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
        Schema::dropIfExists('payroll_details');
    }
};
