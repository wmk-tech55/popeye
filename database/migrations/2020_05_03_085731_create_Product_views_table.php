<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_views', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // product Id
            $table->uuid('product_id')->nullable();

            $table->uuid('user_id')->nullable();

            $table->timestamps();
            $table->index('product_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_views');
    }
}
