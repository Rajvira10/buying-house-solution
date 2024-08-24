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
        Schema::create('meetings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('meeting_id')->nullable();
            $table->unsignedInteger('meeting_type_id');
            $table->unsignedInteger('meeting_status_id');
            $table->unsignedInteger('meeting_title_id');
            $table->unsignedInteger('client_id');
            $table->foreign('meeting_type_id')->references('id')->on('meeting_types');
            $table->foreign('meeting_status_id')->references('id')->on('meeting_statuses');
            $table->foreign('meeting_title_id')->references('id')->on('meeting_titles');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->dateTime('date');
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
        Schema::dropIfExists('meetings');
    }
};
