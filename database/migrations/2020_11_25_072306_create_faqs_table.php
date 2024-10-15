<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Faq;

class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('en_question');
            $table->string('ar_question');
            
            $table->text('en_answer');
            $table->text('ar_answer');

            $table->enum('status', [Faq::ACTIVE_STATUS, Faq::INACTIVE_STATUS])->default(Faq::INACTIVE_STATUS);

            $table->uuid('creator_id')->nullable();

            // $table->foreign('creator_id')
            //     ->references('id')
            //     ->on('users')
            //     ->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('faqs');
    }
}
