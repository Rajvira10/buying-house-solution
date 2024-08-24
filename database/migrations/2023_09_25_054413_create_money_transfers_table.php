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
        Schema::create('money_transfers', function (Blueprint $table) {

            $table->increments('id');

            $table->string('transfer_no')->unique()->nullable();

            $table->decimal('amount', 20, 2);


            $table->unsignedInteger('sender_account_id');

            $table->foreign('sender_account_id')->references('id')->on('accounts');   


            $table->unsignedInteger('receiver_account_id');

            $table->foreign('receiver_account_id')->references('id')->on('accounts');


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
        Schema::dropIfExists('money_transfers');
    }
};
