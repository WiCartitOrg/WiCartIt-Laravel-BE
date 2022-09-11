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
        Schema::create('promo_and_bonuses', function (Blueprint $table) {

            $table->id();

            $table->string('promo_bonus_name');
            $table->longText('description');

            $table->longText('purpose')->nullable();
            $table->json('faqs')->nullable();

            $table->enum('currency', ['NGN', 'USD', 'EUR'])->default('NGN');

            $table->boolean('is_discount_involved')->default(false);
            $table->boolean('is_gift_item_involved')->default(false);

            $table->string('amount')->nullable(); //if discount is involved
            $table->json('gift_items_involved')->nullable(); //if gift items are involved

            $table->dateTime('starting_date_time');
            $table->dateTime('ending_date_time');
            $table->longText('terms_conditions');


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
        Schema::dropIfExists('promo_and_bonuses');
    }
};
