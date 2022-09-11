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
        Schema::create('wishlist_metadata', function (Blueprint $table) {
            $table->id();

             //To show that $unique_buyer_id has bought products worth $total_product_price from $unique_vendor_id:
			//This will make future promos, discount and referrals possible...
            $table->string('unique_buyer_id');
            $table->string('unique_vendor_id');
            $table->string('unique_wishlist_id');
            $table->string('unique_product_id');
            $table->bigInteger('product_cost');

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
        Schema::dropIfExists('wishlist_metadata');
    }
};
