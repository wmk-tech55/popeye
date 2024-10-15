<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Contact;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('message');
            $table->uuid('country_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('contacts');
    }
}
