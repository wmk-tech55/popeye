<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('full_name');
            $table->string('username')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->unique()->nullable();
            $table->string('phone_code')->nullable();
            $table->string('national_id')->nullable();
            $table->string('verification_code')->nullable();
            $table->float('shipping_fee')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('address')->nullable();
            $table->string('country_code')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->string('lang')->nullable();
            $table->float('total_current_wallet_amount')->default(0);
            $table->enum('type',  User::USER_TYPES)->default(User::CUSTOMER_TYPE);
            $table->enum('status', User::USER_STATUS)->default(User::PENDING_STATUS);
            $table->boolean('is_availability')->default(1);
            $table->boolean('on_trip')->default(0);
            $table->longText('bio')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->uuid('active_country_id')->nullable();
            $table->uuid('country_id')->nullable();
            $table->uuid('city_id')->nullable();
            $table->uuid('categorization_id')->nullable();
            $table->string('zoom')->nullable();
            $table->string('firebase_cloud_messaging_token')->nullable();
            $table->string('account_social_provider')->default('normal_account');
            $table->string('account_social_provider_id')->nullable();
            // Socials
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('snapchat')->nullable();
            $table->string('youtube')->nullable();

            $table->rememberToken();

            $table->softDeletes();
            $table->timestamps();

            $table->index('categorization_id');
            $table->index('active_country_id');
            $table->index('country_id');
            $table->index('full_name');
            $table->index('type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
