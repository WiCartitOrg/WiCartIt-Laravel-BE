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
        Schema::create('product_location_and_trackings', function (Blueprint $table) {
            $table->id();

            $table->string('unique_buyer_id')->unique();
            $table->string('unique_cart_id')->unique();
            $table->enum('cart_status', ['Processing', 'PickUp', 'In-Transit', 'On-hold'])->default('Processing');
            $table->string('current_country')->nullable();
            $table->string('current_state')->nullable();
            $table->string('current_city_or_town')->nullable();
            $table->string('current_street')->nullable();
            $table->string('shipped_date')->nullable();
            $table->string('expected_delivery_date')->nullable();
            $table->string('expected_delivery_time')->nullable();
            
            //very important:
            $table->boolean('is_product_delivered')->nullable();

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
        Schema::dropIfExists('product_location_and_trackings');
    }
};
