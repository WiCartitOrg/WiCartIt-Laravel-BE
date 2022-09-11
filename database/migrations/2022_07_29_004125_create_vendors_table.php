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
        Schema::create('vendors', function (Blueprint $table) 
        {
            $table->id();

            $table->boolean('is_logged_in')->default(false);

            $table->string('unique_vendor_id')->unique()->nullable();

            //create foreign keys:
                //For Products:
                $table->foreignId('unique_product_id');

            $table->string('vendor_first_name');
            $table->string('vendor_middle_name')->nullable();
            $table->string('vendor_last_name');
            $table->string('vendor_email');
            //because it cannot be filled by mass assignment:
            $table->string('vendor_password')->unique()->nullable();
            $table->string('vendor_phone_number');

            $table->string('vendor_country_of_operation')->nullable();
            $table->string('vendor_city_or_town')->nullable();
            $table->string('vendor_street_or_close')->nullable();
            $table->string('vendor_apartment_suite_or_unit')->nullable();
            
            $table->string('vendor_industry')->nullable();
            $table->string('vendor_industry_subgroups')->nullable();
            $table->json('vendor_social_media_handles')->nullable();

            $table->longText('vendor_about_us')->nullable();
            $table->longText('vendor_description')->nullable();

            $table->boolean('is_email_verified')->default(false);
            //$table->timestamp('email_verified_at')->nullable();
            //$table->rememberToken();

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
        Schema::dropIfExists('vendors');
    }
};
