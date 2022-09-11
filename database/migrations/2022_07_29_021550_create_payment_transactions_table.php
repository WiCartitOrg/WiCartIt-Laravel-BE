<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();

            $table->string('unique_trans_id')->unique();
            $table->enum('owner', ['buyer', 'vendor', 'admin'])->default('buyer');
            $table->string('paid_amount');
            //$table->dateTime('payment_date_time');

            $table->timestamps();//use createdAt and updatedAt to instantiate a PHP date time 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
};
