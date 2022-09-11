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
        Schema::create('buyer_shippings', function (Blueprint $table) {
            $table->id();

            $table->string('shipping_username')->unique();
            $table->string('shipping_user_company')->nullable();
            $table->string('shipping_country');
            $table->string('shipping_state');
            $table->string('shipping_city_or_town');
            $table->longText('shipping_street_or_close');
            $table->longText('shipping_home_apartment_suite_or_unit');
            $table->string('shipping_phone_number')->unique();
            $table->string('shipping_email')->unique();

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
        Schema::dropIfExists('buyer_shippings');
    }
};
