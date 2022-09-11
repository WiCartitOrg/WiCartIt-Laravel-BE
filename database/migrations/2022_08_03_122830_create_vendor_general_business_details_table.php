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
        Schema::create('vendor_general_business_details', function (Blueprint $table) {
            $table->id();

            $table->string('unique_vendor_id');//this belongs to the vendor...
            $table->string('business_name');
            $table->string('company_name')->nullable();
            $table->string('business_country')->nullable();
            $table->string('business_state')->nullable();
            $table->string('business_city_or_town')->nullable();
            $table->string('business_street_or_close')->nullable();
            $table->string('business_apartment_suite_or_unit')->nullable();
            $table->string('business_phone_number');
            $table->string('business_email');

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
        Schema::dropIfExists('vendor_general_business_details');
    }
};
