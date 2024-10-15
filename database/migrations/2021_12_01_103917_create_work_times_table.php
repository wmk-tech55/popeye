<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkTimesTable extends Migration
{
    public function up()
    {
        Schema::create('work_times', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('en_day_name');
            $table->string('ar_day_name');
            $table->unsignedInteger('day_order')->comment('Used to order days instead of created_at');
            $table->unsignedInteger('raw_order')->comment('Used to order days in OS ');
            $table->boolean('day_is_vacation')->default(false);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->uuid('user_id')->nullable();
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_times');
    }
}
