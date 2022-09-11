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
        Schema::create('vendor_bank_details', function (Blueprint $table) {
            $table->id();

            $table->string('bank_first_name')->nullable();
            $table->string('bank_middle_name')->nullable();
            $table->string('bank_last_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_type')->nullable();
            $table->string('account_number')->nullable();

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
        Schema::dropIfExists('vendor_bank_details');
    }
};
