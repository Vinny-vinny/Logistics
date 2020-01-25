<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dc_link')->nullable();
            $table->string('account');
            $table->string('name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('box_no')->nullable();
            $table->string('fax')->nullable();
            $table->string('tax_no')->nullable();
            $table->string('telephone')->nullable();
            $table->integer('customer_type_id')->nullable();
            $table->integer('price_list_name_id')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
