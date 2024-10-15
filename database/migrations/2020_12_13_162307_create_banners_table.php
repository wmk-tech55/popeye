<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Banner;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Basic Info
            $table->string('en_name');
            $table->string('ar_name');
            $table->string('url')->nullable();
            $table->enum('position',   Banner::WEBSITE_SECTIONS)->default(Banner::HOME_MAIN_SLIDER);
            $table->enum('show_title', [Banner::ACTIVE_STATUS, Banner::INACTIVE_STATUS])->default(Banner::INACTIVE_STATUS);
            $table->uuid('creator_id')->nullable();
            $table->uuid('data_country_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index('data_country_id');
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
        Schema::dropIfExists('banners');
    }
}
