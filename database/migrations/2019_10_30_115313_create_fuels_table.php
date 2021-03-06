<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('litres');
            $table->integer('fuel_type_id');
            $table->string('fuel_no');
            $table->integer('asset_category_id')->nullable();
            $table->integer('vehicle_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('requested_by')->nullable();
            $table->integer('authorized_by')->nullable();
            $table->integer('expense_id')->nullable();
            $table->date('fuel_on');
            $table->string('asset_type')->default('company');      
            $table->integer('customer_type_id')->nullable();
            $table->integer('job_card_id')->nullable();
            $table->string('store_man')->nullable();
            $table->float('odometer_readings')->nullable()->default(0);
            $table->float('previous_odometer')->nullable()->default(0);
            $table->float('rate')->nullable()->default(0);
            $table->string('external_reference')->nullable();
            $table->string('fuel_category_id')->nullable();
            $table->integer('status')->nullable()->default(1);
            $table->integer('credit_account_id')->nullable();
            $table->integer('where_to_charge')->nullable();
            $table->string('reversal_ref')->nullable();
            $table->float('reversal_litres')->nullable();
            $table->float('reversal_rate')->nullable();
            $table->integer('invoiced')->default(0)->nullable();
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
        Schema::dropIfExists('fuels');
    }
}
