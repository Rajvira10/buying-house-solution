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
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->string('project_id')->nullable();
            $table->unsignedInteger('project_type_id');
            $table->foreign('project_type_id')->references('id')->on('project_types');
            $table->unsignedInteger('project_status_id');
            $table->foreign('project_status_id')->references('id')->on('project_statuses');
            $table->unsignedInteger('client_id');
            $table->enum('payment_status', ['Due', 'Partial', 'Paid'])->default('Due');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->string('name');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
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
        Schema::dropIfExists('projects');
    }
};
