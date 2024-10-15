<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOrderAdditionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  { 

    Schema::create('product_order_additions', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->unsignedInteger('quantity')->default('0');
      $table->float('price')->unsigned()->default('0');
      $table->float('final_price')->unsigned()->default('0');
      $table->uuid('product_order_id')->nullable();
      $table->uuid('addition_id')->nullable();
      $table->timestamps();
      $table->index('product_order_id');
      $table->index('addition_id');
   
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('product_order_additions');
  }
}
