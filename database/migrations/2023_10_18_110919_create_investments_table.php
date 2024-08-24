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
        Schema::create('investments', function (Blueprint $table) {
            
            $table->increments('id');
            

            $table->unsignedInteger('warehouse_id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses');


            $table->string('investment_no')->unique()->nullable();


            $table->unsignedInteger('investor_id');

            $table->foreign('investor_id')->references('id')->on('investors');

            
            $table->decimal('amount', 20, 2);

            $table->string('type');

            $table->string('return_status');

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
        Schema::dropIfExists('investments');
    }
};
