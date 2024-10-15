<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Brand;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Basic Info
            $table->string('en_name');
            $table->string('ar_name');
            $table->uuid('creator_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index('creator_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
}
