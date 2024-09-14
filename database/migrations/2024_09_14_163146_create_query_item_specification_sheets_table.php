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
        Schema::create('query_item_specification_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('query_item_id');
            $table->foreign('query_item_id')->references('id')->on('query_items')->onDelete('cascade');
            $table->unsignedInteger('factory_id');
            $table->foreign('factory_id')->references('id')->on('factories');
            $table->dateTime('date')->nullable();
            $table->dateTime('approximate_delivery_date')->nullable();
            $table->string('express_courier')->nullable();
            $table->string('AWB')->nullable();
            $table->dateTime('AWB_date')->nullable();
            $table->string('required_size')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->string('fitting')->nullable();
            $table->string('styling')->nullable();
            $table->text('required_fabric_composition')->nullable();
            $table->string('GSM')->nullable();
            $table->string('fabric_color')->nullable();
            $table->string('main_label')->nullable();
            $table->string('care_label')->nullable();
            $table->string('hang_tag')->nullable();
            $table->text('print_instructions')->nullable();
            $table->text('embroidery_instructions')->nullable();
            $table->string('button_type')->nullable();
            $table->string('button_size')->nullable();
            $table->string('button_color')->nullable();
            $table->string('button_thread')->nullable();
            $table->string('button_hole')->nullable();
            $table->string('zipper_type')->nullable();
            $table->string('zipper_size')->nullable();
            $table->string('zipper_color')->nullable();
            $table->string('zipper_tape')->nullable();
            $table->string('zipper_puller')->nullable();
            $table->text('other_instructions')->nullable();

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
        Schema::dropIfExists('query_item_specification_sheets');
    }
};
