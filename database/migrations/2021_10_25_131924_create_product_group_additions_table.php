<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\ProductGroupAddition;

class CreateProductGroupAdditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_group_additions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('en_name');
            $table->string('ar_name');
            $table->enum('type', ProductGroupAddition::GROUP_TYPES)->default(ProductGroupAddition::ONE_OPTION_GROUP_TYPE);
            $table->uuid('product_id')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('product_group_additions');
    }
}
