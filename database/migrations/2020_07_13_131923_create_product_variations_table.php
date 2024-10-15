<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\ProductVariation;

class CreateProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('price');
            $table->string('en_name')->nullable();
            $table->string('ar_name')->nullable();
            $table->enum('type', ProductVariation::TYPE_OPTIONS)->default(ProductVariation::ONE_OPTION);
            $table->boolean('is_primary')->default(1);
            $table->unsignedInteger('quantity')->default(1);
            $table->uuid('product_id')->nullable();
            $table->uuid('classification_id')->nullable();

            $table->timestamps();
            $table->index('classification_id');
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
        Schema::dropIfExists('product_variations');
    }
}
