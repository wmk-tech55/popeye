<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAdditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_additions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('price')->default(0);
            $table->string('en_name');
            $table->string('ar_name');
            $table->uuid('product_id')->nullable();
            $table->uuid('group_id')->nullable();
            $table->timestamps();
            $table->index('group_id');
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
        Schema::dropIfExists('product_additions');
    }
}
