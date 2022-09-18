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
        Schema::create('admins', function (Blueprint $table) 
        {
            $table->id();

            $table->boolean('is_logged_in')->default(false);

            $table->string('unique_admin_id')->unique()->nullable();
            $table->string('admin_first_name');
            $table->string('admin_middle_name')->nullable();
            $table->string('admin_last_name');
            $table->string('admin_email')->unique();

            //because it cannot be filled by mass assignment:
            $table->string('admin_password')->unique();
            $table->string('admin_phone_number')->unique();

            //role of the admin here: boss or hired:
            $table->enum('admin_role', ['boss', 'hired'])->default('boss');

            $table->string('admin_country_of_operation')->nullable();
            $table->string('admin_city_or_town')->nullable();
            $table->string('admin_street_or_close')->nullable();
            $table->string('admin_apartment_suite_or_unit')->nullable();

            $table->longText('admin_about')->nullable();
            $table->longText('admin_description')->nullable();
            
            $table->string('admin_industry')->nullable();
            $table->json('admin_social_media_handles')->nullable();

            //referral program:
            $table->string('is_referral_prog_activated')->default(false);
            $table->string('referral_bonus');
            $table->enum('referral_bonus_currency', ['NGN','USD','EUR']);//->default('NGN');

            //$table->boolean('is_email_verified')->default(false);
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
        Schema::dropIfExists('admins');
    }
};
