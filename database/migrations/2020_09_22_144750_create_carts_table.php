<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Cart;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedInteger('quantity');
            $table->float('price')->default(0);
            $table->float('total_price')->default(0);
            $table->float('total_price_before_additions')->default(0);
            $table->float('total_additions_price')->default(0);
            $table->uuid('product_id')->nullable();
            $table->uuid('customer_id')->nullable();
            $table->uuid('store_id')->nullable();
            $table->timestamps();
            $table->index('product_id');
            $table->index('customer_id');
            $table->index('store_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
