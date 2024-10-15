<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Favorite Id
            // Will Create
            // favorited_id and favorited_type with it's index
            $table->uuidMorphs('favorited');

            
            // User Id
            $table->uuid('user_id');

            $table->timestamps();

            // Error In Server that key is too long
            // $table->unique(['user_id', 'favorited_id', 'favorited_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
