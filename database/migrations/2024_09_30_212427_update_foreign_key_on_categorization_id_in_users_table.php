<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForeignKeyOnCategorizationIdInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['categorization_id']);

            // Re-add the foreign key with onDelete('cascade')
            $table->foreign('categorization_id')
                ->references('id')->on('categorizations')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key
            $table->dropForeign(['categorization_id']);

            // Re-add it with the original onDelete behavior
            $table->foreign('categorization_id')
                ->references('id')->on('categorizations')
                ->onDelete('set null');
        });
    }
}
