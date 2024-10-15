<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Order;

class CreateCanceledOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canceled_orders', function (Blueprint $table) {

            $table->uuid('id')->primary();
            $table->uuid('driver_id')->nullable();
            $table->uuid('order_id')->nullable();
            $table->timestamps();
            $table->index('driver_id');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('canceled_orders');
    }
}
