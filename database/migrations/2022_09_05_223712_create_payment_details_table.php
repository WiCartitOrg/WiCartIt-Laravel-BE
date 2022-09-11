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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();

            $table->enum('owner', ['buyer', 'vendor', 'admin'])->default('buyer');
            $table->string('unique_owner_id');

            $table->string('bank_account_first_name')->nullable();
            $table->string('bank_account_middle_name')->nullable();
            $table->string('bank_account_last_name')->nullable();
            //savings or current:
            $table->string('bank_account_type')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();

            //On the frontend: (MasterCard - Debit, Visa - Debit)They will all be encrypted...
            $table->longText('bank_card_type')->nullable();
            $table->longText('bank_card_number')->unique()->nullable();
            $table->longText('bank_card_cvv')->unique()->nullable();
            $table->longText('bank_card_expiry_month')->nullable();
            $table->longText('bank_card_expiry_year')->nullable();
             
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
        Schema::dropIfExists('payment_details');
    }
};
