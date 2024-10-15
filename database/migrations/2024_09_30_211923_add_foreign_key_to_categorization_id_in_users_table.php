<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToCategorizationIdInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('categorization_id')
                ->references('id')->on('categorizations')
                ->onDelete('casa');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['categorization_id']);
        });
    }

}
