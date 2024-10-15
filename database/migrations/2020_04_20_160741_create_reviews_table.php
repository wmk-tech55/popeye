<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('email');
            $table->text('review');
            $table->integer('rate');

            $table->uuid('customer_id')->nullable();

            $table->uuid('product_id')->nullable();


            $table->softDeletes();
            $table->timestamps();
            $table->index('customer_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
