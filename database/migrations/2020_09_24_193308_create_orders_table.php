<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Order;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->uuid('id')->primary();
            $table->float('total')->unsigned()->default(0);
            $table->float('total_before_fees')->unsigned()->default(0);
            $table->string('invoice_id')->nullable();
            $table->unsignedInteger('promo_code')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->text('comment')->nullable();
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('shipping_fee')->default(0);
            $table->string('commission_percentage')->nullable();
            $table->string('tax_fee')->default(0);
            $table->string('block_number')->nullable();
            $table->string('floor_number')->nullable();
            $table->string('flat_number')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('area')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('city')->nullable();
            $table->uuid('country_id')->nullable();
            $table->uuid('data_country_id')->nullable();
            $table->uuid('city_id')->nullable();
            $table->timestamp('accept_time')->nullable();
            $table->boolean('is_paid')->default(Order::NOT_PAID_STATUS);
            $table->boolean('is_cancelled')->default(Order::NOT_CANCELLED_STATUS);
            $table->boolean('is_approved')->default(Order::NOT_APPROVED_STATUS);
            $table->boolean('in_shipping')->default(Order::NOT_SHIPPED_STATUS);
            $table->boolean('is_delivered')->default(Order::NOT_DELIVERED_STATUS);
            $table->boolean('is_accepted')->default(Order::NOT_ACCEPTED_STATUS);
            $table->boolean('marked_as_deleted_for_driver')->default(0);
            $table->boolean('marked_as_deleted_for_customer')->default(0);
            $table->boolean('marked_as_deleted_for_provider')->default(0);
            $table->uuid('customer_id')->nullable();
            $table->uuid('driver_id')->nullable();
            $table->uuid('provider_id')->nullable();
            $table->uuid('address_id')->nullable();
            $table->enum('platform', Order::PLATFORMS)->nullable();
            $table->enum('payment_method', Order::PAYMENT_METHODS)->nullable();
            $table->string('payment_id')->nullable();
            $table->string('refund_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('customer_id');
            $table->index('driver_id');
            $table->index('provider_id');
            $table->index('address_id');
            $table->index('payment_method');
            $table->index('platform');
            $table->index('payment_id');
            $table->index('refund_id');
            $table->index('country_id');
            $table->index('city_id');
            $table->index('data_country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
