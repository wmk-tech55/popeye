<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\ShippingFeePerDistance;

class CreateShippingFeePerDistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_fee_per_distances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('distance_from')->default('0');
            $table->float('distance_to')->default('0')->nullable();
            $table->boolean('is_default_distance')->default('0');
            $table->float('shipping_fee')->default('0');
            $table->string('unit')->nullable();
            $table->uuid('country_id')->nullable();
            $table->timestamps();
            $table->index('country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_fee_per_distances');
    }
}
