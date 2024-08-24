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
        Schema::create('loan_paybacks', function (Blueprint $table) {
            
            $table->increments('id');

            $table->string('payback_no')->unique()->nullable();

            
            $table->unsignedInteger('loan_id');

            $table->foreign('loan_id')->references('id')->on('loans');


            $table->decimal('amount', 20, 2);

            $table->dateTime('date');


            $table->unsignedInteger('account_id');

            $table->foreign('account_id')->references('id')->on('accounts');


            $table->text('note')->nullable();

            $table->string('status');


            $table->unsignedInteger('finalized_by');

            $table->foreign('finalized_by')->references('id')->on('users');


            $table->dateTime('finalized_at');

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
        Schema::dropIfExists('loan_paybacks');
    }
};
