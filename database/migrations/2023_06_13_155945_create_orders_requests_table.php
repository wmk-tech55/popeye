<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\OrderRequest;

class CreateOrdersRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', OrderRequest::STATUSES)->default(OrderRequest::NOT_ACCEPTED_STATUS);
            $table->uuid('order_id')->nullable();
            $table->uuid('driver_id')->nullable();
            $table->timestamps();
            $table->index('order_id');
            $table->index('driver_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_requests');
    }
}
