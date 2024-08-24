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
        Schema::create('calls', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->unsignedInteger('contact_person_id')->nullable();
            $table->foreign('contact_person_id')->references('id')->on('contact_persons')->onDelete(NULL);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('call_type_id')->nullable();
            $table->foreign('call_type_id')->references('id')->on('call_types')->onDelete(NULL);
            $table->unsignedInteger('call_status_id')->nullable();
            $table->foreign('call_status_id')->references('id')->on('call_statuses')->onDelete(NULL);
            $table->dateTime('call_date')->default(now());
            $table->string('call_summary')->nullable();
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
        Schema::dropIfExists('calls');
    }
};
