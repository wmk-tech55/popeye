<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\PromoCode ;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->integer('discount');
            $table->integer('number_of_uses');
            $table->date('expiry_date');
            $table->enum('status', [PromoCode::ACTIVE_STATUS, PromoCode::INACTIVE_STATUS])->default(PromoCode::ACTIVE_STATUS);
  
            // Creator Info
            $table->uuid('creator_id')->nullable();

            // Relations Info
            $table->foreign('creator_id')
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
        Schema::dropIfExists('promo_codes');
    }
}
