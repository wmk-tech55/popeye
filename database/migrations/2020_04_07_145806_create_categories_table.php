<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Category;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('en_name');
            $table->string('ar_name');

            $table->string('ar_slug')->nullable();
            $table->string('en_slug')->nullable();

            $table->uuid('parent_id')->nullable();
            $table->uuid('categorization_id')->nullable();

            $table->enum('status', [Category::ACTIVE_STATUS, Category::INACTIVE_STATUS])->default(Category::INACTIVE_STATUS);

            $table->uuid('creator_id')->nullable();

            $table->softDeletes();
            $table->timestamps();
            $table->index('creator_id');
            $table->index('categorization_id');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
