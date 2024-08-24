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
        Schema::create('settings', function (Blueprint $table) {
            
            $table->increments('id');
            
            $table->string('full_name');

            $table->string('short_name');

            
            $table->unsignedInteger('logo_id');

            $table->foreign('logo_id')->references('id')->on('files');


            $table->unsignedInteger('favicon_id');

            $table->foreign('favicon_id')->references('id')->on('files');


            $table->string('website')->nullable();

            $table->string('email1')->nullable();

            $table->string('email2')->nullable();

            $table->string('contact1')->nullable();

            $table->string('contact2')->nullable();

            $table->string('address')->nullable();

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
        Schema::dropIfExists('settings');
    }
};
