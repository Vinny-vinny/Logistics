<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobcards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('machine_id')->nullable();
            $table->integer('track_by_id')->nullable();
            $table->integer('service_type_id')->nullable();
            $table->string('service_type')->nullable();
            $table->float('next_readings')->nullable()->default(0);
            $table->float('current_readings')->nullable()->default(0);
            $table->date('next_service_date')->nullable();        
            $table->date('actual_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('service_type_description')->nullable();
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->text('maintenance')->nullable();
            $table->string('card_no');
            $table->string('cost_code')->nullable();
            $table->integer('asset_category_id')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->string('status')->nullable()->default(1);
            $table->integer('jobcard_category_id')->nullable();
            $table->integer('mechanic_id')->nullable();
            $table->integer('job_type_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('customer_type_id')->nullable();
            $table->integer('checklist_assigned')->nullable()->default(0);
            $table->integer('requisition_id')->nullable();         
            $table->bigInteger('labour_cost')->nullable();
            $table->bigInteger('standing_fee')->nullable();
            $table->float('hours_spent')->nullable(); 
            $table->text('inventory_items_reversal')->nullable();
            $table->string('reversal_ref')->nullable();  
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
        Schema::dropIfExists('jobcards');
    }
}
