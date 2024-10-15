<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\Wallet;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('balance');
            $table->boolean('status')->default(Wallet::BALANCE_NOT_PAID);
            $table->uuid('company_id');
            $table->uuid('order_id');
            $table->softDeletes();
            $table->timestamps();
            $table->index('company_id');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}
