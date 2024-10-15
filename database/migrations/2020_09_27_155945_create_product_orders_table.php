<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('product_orders', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->unsignedInteger('quantity')->default('0');
      $table->float('price')->unsigned()->default('0');
      $table->float('final_price')->unsigned()->default('0');

      $table->uuid('order_id')->nullable();

      $table->uuid('creator_id')->nullable();

      $table->uuid('product_id')->nullable();
      $table->uuid('variation_id')->nullable();

      $table->timestamps();
      $table->index('variation_id');
      $table->index('creator_id');
      $table->index('product_id');
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
    Schema::dropIfExists('product_orders');
  }
}
