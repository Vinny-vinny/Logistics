<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('requested_on')->nullable();
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->string('req_no')->nullable();
            $table->string('where_to_charge')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('requested_by')->nullable();
            $table->string('group_name')->nullable();
            $table->integer('used')->default(0)->nullable();
            $table->text('inventory_items_internal')->nullable();
            $table->text('inventory_items_external')->nullable();
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
        Schema::dropIfExists('requisitions');
    }
}
