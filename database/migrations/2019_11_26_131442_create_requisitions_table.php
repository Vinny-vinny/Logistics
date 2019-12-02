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
            $table->string('requested_on');
            $table->string('description');
            $table->string('type');
            $table->string('req_no');
            $table->string('where_to_charge');
            $table->integer('project_id');
            $table->integer('requested_by');
            $table->integer('used')->default(0)->nullable();
            $table->text('inventory_items_internal');
            $table->text('inventory_items_external');
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
