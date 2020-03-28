<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('description');
            $table->float('cost');
            $table->integer('stock_link')->nullable();
            $table->string('item_group')->nullable();
            $table->integer('uom_stock_id')->nullable();
            $table->integer('uom_purchase_id')->nullable();
            $table->integer('uom_sellunit_id')->nullable();
            $table->float('qty_on_hand')->nullable();
            $table->string('service_item')->nullable();
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
        Schema::dropIfExists('parts');
    }
}
