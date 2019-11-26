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
            $table->integer('vehicle_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('driver_id')->nullable();
            $table->string('driver_name')->nullable();
            $table->integer('expense_id')->nullable();
            $table->date('fuel_on');
            $table->string('asset_type')->default('company');
            $table->string('invoice_no')->nullable();
            $table->string('customer_type')->nullable();
            $table->text('other_expenses')->nullable();
            $table->float('odometer_readings')->nullable()->default(0);
            $table->float('rate')->nullable()->default(0);
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
