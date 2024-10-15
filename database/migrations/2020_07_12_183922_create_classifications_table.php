<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('en_name');
            $table->string('ar_name');
            $table->uuid('creator_id')->nullable();
            $table->uuid('categorization_id')->nullable();
            $table->softDeletes();        
            $table->timestamps();
            $table->index('creator_id');
            $table->index('categorization_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classifications');
    }
}
