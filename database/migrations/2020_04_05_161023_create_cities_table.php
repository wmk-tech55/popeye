<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\City;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('en_name');
            $table->string('ar_name');

            $table->enum('status', [City::ACTIVE_STATUS, City::INACTIVE_STATUS])->default(City::INACTIVE_STATUS);

            $table->uuid('country_id')->nullable();

            $table->uuid('creator_id')->nullable();

            
            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('set null');

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
        Schema::dropIfExists('cities');
    }
}
