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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();

            $table->string('unique_wishlist_id')->unique();
            $table->string('unique_buyer_id');
            //owning vendors:
            //$table->string('unique_vendor_ids');

            //already inside the Wishlist details:
            $table->enum('wishlist_payment_currency', ['NGN', 'USD', 'EUR'])->default('NGN');
            $table->integer('wishlist_product_count')->default(0);
            $table->string('wishlist_products_cost');
            $table->string('wishlist_shipping_cost');
            $table->string('wishlist_total_cost');
    
            //associated ids attached to this wishlist:
            $table->json('wishlist_attached_products_ids_quantities');
            
            //$table->enum('wishlist_payment_status', ['pending', 'cleared'])->default('pending');

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
        Schema::dropIfExists('wishlists');
    }
};
