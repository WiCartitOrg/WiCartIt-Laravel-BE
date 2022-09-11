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
        Schema::create('carts', function (Blueprint $table) 
        {
            $table->id();

            $table->string('unique_cart_id')->unique();
            $table->string('unique_buyer_id');
            //owning vendors:
            //$table->string('unique_vendor_ids');

            //already inside the Cart details:
            $table->enum('cart_payment_currency', ['NGN', 'USD', 'EUR'])->default('NGN');
            $table->integer('cart_product_count')->default(0);
            $table->string('cart_products_cost');
            $table->string('cart_shipping_cost');
            $table->string('cart_total_cost');
    
            //associated ids attached to this cart:
            $table->json('cart_attached_products_ids_quantities');
            
            $table->enum('cart_payment_status', ['pending', 'cleared'])->default('pending');

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
        Schema::dropIfExists('carts');
    }
};
