<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorizations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Basic Info
            $table->string('en_name');
            $table->string('ar_name');

            // Creator Info
            $table->uuid('creator_id')->nullable();

            // Relations Info
            $table->foreign('creator_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorizations');
    }
}
