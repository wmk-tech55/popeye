<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderFieldToCategorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categorizations', function (Blueprint $table) {
            $table->unsignedInteger('order_field')->nullable()->comment('Customer Say make it static');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorizations', function (Blueprint $table) {
            $table->dropColumn('order_field');
        });
    }
}
