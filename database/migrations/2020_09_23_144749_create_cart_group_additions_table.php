<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartGroupAdditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_group_additions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_id')->nullable();
            $table->uuid('cart_id')->nullable();
            $table->timestamps();
            $table->index('cart_id');
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
        Schema::dropIfExists('cart_group_additions');
    }
}
