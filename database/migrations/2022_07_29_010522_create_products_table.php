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
        Schema::create('products', function (Blueprint $table) 
        {
            $table->id();

            $table->string('unique_product_id')->unique();

            //create foreign keys:
                //For Vendors:
                $table->foreignId('unique_vendor_id');

            //texts:
            $table->string('product_category');
            $table->string('product_title');
            $table->longText('product_summary');
            $table->longText('product_description');
            $table->enum('product_currency_of_payment', ['NGN', 'USD', 'EUR'])->default('NGN');
            $table->string('product_price');
            $table->string('product_shipping_cost');
            $table->longText('product_add_info')->nullable();
            $table->longText('product_ship_guarantee_info')->nullable();

            $table->bigInteger('num_of_times_bought')->nullable()->default(0);
            $table->bigInteger('total_amount_bought')->nullable()->default(0);

            $table->boolean('is_hot_deal')->nullable()->default(false);
            $table->bigInteger('original_price')->nullable()->default(0);
            
            //images:
            $table->binary('main_image_1')->nullable();
            $table->binary('main_image_2')->nullable();
            $table->binary('logo_1')->nullable();
            $table->binary('logo_2')->nullable();

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
        Schema::dropIfExists('products');
    }
};
