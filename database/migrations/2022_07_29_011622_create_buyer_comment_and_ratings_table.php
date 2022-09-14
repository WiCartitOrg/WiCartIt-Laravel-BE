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
        Schema::create('buyer_comment_and_ratings', function (Blueprint $table) {
            $table->id();

            $table->string('unique_comment_rate_id')->unique();
            $table->longText('comment');
            $table->integer('rating');
            $table->boolean('is_approved_for_view')->default(false);

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
        Schema::dropIfExists('buyer_comment_and_ratings');
    }
};
