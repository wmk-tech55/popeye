<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Address;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('block_number')->nullable();
            $table->string('floor_number')->nullable();
            $table->string('flat_number')->nullable();
            $table->string('street_name')->nullable();
            $table->string('address')->nullable();
            $table->string('address_description')->nullable();
            $table->string('country')->nullable();
            $table->string('area')->nullable();
            $table->string('country_code')->nullable();
            $table->string('city')->nullable();
            $table->uuid('city_id')->nullable();
            $table->uuid('country_id')->nullable();
            $table->boolean('is_default')->default(0);
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('zoom')->nullable();
            $table->uuid('owner_id')->nullable();
            $table->timestamps();
            $table->index('owner_id');
            $table->index('country_id');
            $table->index('city_id');
      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
