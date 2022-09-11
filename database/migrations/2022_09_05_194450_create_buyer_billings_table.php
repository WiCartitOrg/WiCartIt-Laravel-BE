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
        Schema::create('buyer_billings', function (Blueprint $table) {
            $table->id();

            $table->string('billing_username');
            $table->string('billing_user_company')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_state');
            $table->string('billing_city_or_town');
            $table->longText('billing_street_or_close');
            $table->longText('billing_home_apartment_suite_or_unit')->nullable();
            $table->string('billing_phone_number')->unique();
            $table->string('billing_email')->unique();

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
        Schema::dropIfExists('buyer_billings');
    }
};
