<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobcardCategoryTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobcard_category_transaction', function (Blueprint $table) {
            $table->bigInteger('jobcard_category_id')->unsigned();
            $table->bigInteger('transaction_id')->unsigned();
            $table->foreign('jobcard_category_id')->references('id')->on('jobcard_categories')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
             });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobcard_category_transaction');
    }
}
