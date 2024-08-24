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
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');

            $table->string('unique_id')->nullable();

            $table->string('company_name');

            $table->string('contact_no');

            $table->string('email')->nullable();

            $table->unsignedInteger('country_id')->nullable();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');

            $table->unsignedInteger('state_id')->nullable();

            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');

            $table->unsignedInteger('city_id')->nullable();

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');

            $table->string('address')->nullable();
            
            $table->unsignedInteger('client_source_id');

            $table->foreign('client_source_id')->references('id')->on('client_sources')->onDelete('cascade');
            
            $table->unsignedInteger('business_category_id');

            $table->foreign('business_category_id')->references('id')->on('business_categories')->onDelete('cascade');

            $table->unsignedInteger('interested_in_id');

            $table->foreign('interested_in_id')->references('id')->on('interested_ins')->onDelete('cascade');
            
            $table->unsignedInteger('client_status_id');

            $table->foreign('client_status_id')->references('id')->on('client_statuses')->onDelete('cascade');
            
            $table->text('note')->nullable();

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
        Schema::dropIfExists('clients');
    }
};
