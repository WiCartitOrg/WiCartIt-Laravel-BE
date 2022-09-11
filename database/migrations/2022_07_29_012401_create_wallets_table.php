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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();

            $table->string('unique_wallet_id')->unique();
            $table->enum('owner', ['buyer', 'vendor', 'admin'])->default('buyer');
            $table->enum('wallet_currency', ['NGN', 'USD', 'EUR'])->default('NGN');
            $table->string('wallet_balance')->default('00.00');
            $table->string('last_received')->default('00.00')->nullable();
            $table->string('last_deducted')->default('00.00')->nullable();
            
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
        Schema::dropIfExists('wallets');
    }
};
