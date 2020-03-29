<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_price_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_price_list');
            $table->integer('price_list_name_id');
            $table->integer('stock_id');
            $table->float('exclusive_price');
            $table->float('inclusive_price');
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
        Schema::dropIfExists('customer_price_lists');
    }
}
