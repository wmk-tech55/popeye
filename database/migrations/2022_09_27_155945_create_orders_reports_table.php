<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\OrderReport;

class CreateOrdersReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('commission_percentage')->default('0');
            $table->float('total_before_commission')->unsigned()->default('0');
            $table->float('total_after_commission')->unsigned()->default('0');
            $table->enum('status', OrderReport::STATUSES)->default(OrderReport::NOT_PAID_STATUS);
            $table->uuid('order_id')->nullable();
            $table->uuid('company_id')->nullable();
            $table->timestamps();
            $table->index('order_id');
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_reports');
    }
}
