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
        Schema::create('loans', function (Blueprint $table) {
            
            $table->increments('id');

            
            $table->unsignedInteger('warehouse_id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses');


            $table->string('loan_no')->unique()->nullable();


            $table->unsignedInteger('loan_client_id');

            $table->foreign('loan_client_id')->references('id')->on('loan_clients');

            
            $table->decimal('amount', 20, 2);

            $table->decimal('interest_rate', 5, 2)->nullable();

            $table->string('type');

            $table->string('payback_status');

            $table->dateTime('date');

            
            $table->unsignedInteger('account_id');

            $table->foreign('account_id')->references('id')->on('accounts');

            
            $table->text('note')->nullable();
            

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
        Schema::dropIfExists('loans');
    }
};
