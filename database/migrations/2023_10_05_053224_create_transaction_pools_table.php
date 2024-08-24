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
        Schema::create('transaction_pools', function (Blueprint $table) {
            
            $table->increments('id');

            $table->string('poolable_type')->nullable();

            $table->unsignedInteger('poolable_id')->nullable();

            $table->string('action_type');

            $table->text('data');


            $table->unsignedInteger('checked_by')->nullable();

            $table->foreign('checked_by')->references('id')->on('users');

            
            $table->dateTime('checked_at')->nullable();


            $table->unsignedInteger('approved_by')->nullable();

            $table->foreign('approved_by')->references('id')->on('users');
            

            $table->dateTime('approved_at')->nullable();

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
        Schema::dropIfExists('transaction_pools');
    }
};
