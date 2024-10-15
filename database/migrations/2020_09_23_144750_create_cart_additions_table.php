<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartAdditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_additions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('quantity')->default(1);
            $table->float('price')->default(0);
            $table->float('total_price')->default(0);
            $table->uuid('cart_id')->nullable();
            $table->uuid('addition_id')->nullable();
            $table->uuid('group_id')->nullable();
            $table->uuid('cart_group_addition_id')->nullable();
            $table->timestamps();
            $table->index('cart_id');
            $table->index('addition_id');
            $table->index('cart_group_addition_id');
            $table->index('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_additions');
    }
}
