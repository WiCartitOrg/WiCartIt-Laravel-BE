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
        Schema::create('buyers', function (Blueprint $table) {
        
            $table->id();

            $table->boolean('is_logged_in')->default(false);
            //because it cannot be filled by mass assignment:
            $table->string('unique_buyer_id')->unique()->nullable();
            $table->string('buyer_first_name');
            $table->string('buyer_middle_name')->nullable();
            $table->string('buyer_last_name');

            /*$table->string('buyer_country')->nullable();
            $table->string('buyer_state')->nullable();
            $table->string('buyer_city_or_town')->nullable();
            $table->string('buyer_street_or_close')->nullable();
            $table->string('buyer_home_apartment_suite_unit')->nullable();*/

            $table->string('buyer_email')->unique();
            $table->string('buyer_phone_number')->unique()->nullable();

            //$table->timestamp('email_verified_at')->nullable();
            //$table->rememberToken()->nullable();
            $table->boolean('is_email_verified')->default(false);

            //because it cannot be filled by mass assignment:
            $table->string('buyer_password')->unique()->nullable();

            $table->string('buyer_referral_link')->unique()->nullable();
            $table->string('buyer_total_referral_bonus')->nullable();

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
        Schema::dropIfExists('buyers');
    }
};
