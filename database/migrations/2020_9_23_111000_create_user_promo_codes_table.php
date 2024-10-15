<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\UserPromoCode;

class CreateUserPromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_promo_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->integer('available_quantity');

            // promo code Id
            $table->uuid('promo_code_id')->nullable();

            // promo code Id
            $table->foreign('promo_code_id')
                ->references('id')
                ->on('promo_codes')
                ->onDelete('set null');

            // User Id
            $table->uuid('customer_id')->nullable();

            // User Id
            $table->foreign('customer_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

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
        Schema::dropIfExists('user_promo_codes');
    }
}
