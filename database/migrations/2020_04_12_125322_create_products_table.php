<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Product;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('en_name');
            $table->string('ar_name');
            $table->string('ar_slug')->nullable();
            $table->string('en_slug')->nullable();
            $table->longText('en_overview');
            $table->longText('ar_overview');
            $table->float('price');
            $table->uuid('category_id')->nullable();
            $table->uuid('categorization_id')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('average_rate')->default(0);
            $table->enum('type', Product::TYPE_OPTIONS)->default(Product::ONE_OPTION);
            $table->enum('status', [Product::ACTIVE_STATUS, Product::INACTIVE_STATUS])->default(Product::ACTIVE_STATUS);
            $table->uuid('discount_id')->nullable();
            $table->uuid('creator_id')->nullable();
            $table->uuid('data_country_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index('data_country_id');
            $table->index('discount_id');
            $table->index('creator_id');
            $table->index('category_id');
            $table->index('categorization_id');
            $table->index('views_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
