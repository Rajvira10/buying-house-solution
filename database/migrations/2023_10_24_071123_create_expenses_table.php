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
        Schema::create('expenses', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('warehouse_id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses');

            $table->string('expense_no')->unique()->nullable();


            $table->unsignedInteger('expense_category_id');

            $table->foreign('expense_category_id')->references('id')->on('expense_categories');


            $table->decimal('amount', 20, 2);

            $table->string('payment_status');

            $table->dateTime('date');

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
        Schema::dropIfExists('expenses');
    }
};
