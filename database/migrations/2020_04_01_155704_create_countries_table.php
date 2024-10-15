<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Country;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('en_name');
            $table->string('ar_name');
            $table->string('country_code')->nullable();
            $table->string('ar_currency')->nullable();
            $table->string('en_currency')->nullable();
            $table->enum('status', Country::STATUSES)->default(Country::INACTIVE_STATUS);
            $table->uuid('creator_id')->nullable();
            $table->softDeletes();
            $table->index('creator_id');
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
        Schema::dropIfExists('countries');
    }
}
