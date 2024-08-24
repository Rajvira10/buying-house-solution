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
        Schema::create('investment_returns', function (Blueprint $table) {
           
            $table->increments('id');

            $table->string('return_no')->unique()->nullable();

            
            $table->unsignedInteger('investment_id');

            $table->foreign('investment_id')->references('id')->on('investments');


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
        Schema::dropIfExists('investment_returns');
    }
};
