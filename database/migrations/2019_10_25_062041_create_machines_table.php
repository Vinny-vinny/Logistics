<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('asset_no')->nullable();
            $table->string('code');
            $table->string('description');
            $table->string('make')->nullable();
            $table->integer('track_by_id')->nullable();
            $table->date('warranty')->nullable();
            $table->string('plate_no')->nullable();
            $table->float('next_readings')->nullable()->default(0);
            $table->float('current_readings')->nullable()->default(0);
            $table->integer('assign_to')->nullable();
            $table->string('year_of_make')->nullable()->default(date('Y'));
            $table->string('engine_no')->nullable();
            $table->string('year_of_reg')->nullable()->default(date('Y'));
            $table->string('chasis_no')->nullable();
            $table->string('code_type')->nullable()->default(0);
            $table->integer('status')->nullable()->default(0);
            $table->integer('fuel_balance_id')->nullable();
            $table->integer('fuel_type_id')->nullable();
            $table->float('odometer_readings')->nullable()->default(0);
            $table->date('next_service_date')->nullable();
            $table->string('insurance_file')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('reminder_before')->nullable()->default(0);
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
        Schema::dropIfExists('machines');
    }
}
