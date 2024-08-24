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
        Schema::create('account_statements', function (Blueprint $table) {

            $table->increments('id');

            $table->string('type');

            $table->unsignedInteger('reference_id')->nullable();


            $table->unsignedInteger('account_id');

            $table->foreign('account_id')->references('id')->on('accounts');


            $table->decimal('amount', 20, 2);

            $table->string('cash_flow_type')->nullable();

            $table->dateTime('statement_date');

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
        Schema::dropIfExists('account_statements');
    }
};
