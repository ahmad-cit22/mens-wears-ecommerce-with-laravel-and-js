<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkTrackingEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_tracking_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_sheet_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('product_stock_history_id')->nullable();
            $table->unsignedBigInteger('rejected_product_id')->nullable();
            $table->unsignedBigInteger('damaged_product_id')->nullable();
            $table->unsignedBigInteger('expense_entry_id')->nullable();
            $table->unsignedBigInteger('cash_flow_id')->nullable();
            $table->unsignedBigInteger('bkash_record_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('work_name');
            $table->timestamps();

            // foreign key relationships
            $table->foreign('order_sheet_id')->references('id')->on('facebook_orders')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_stock_history_id')->references('id')->on('product_stock_histories')->onDelete('cascade');
            $table->foreign('rejected_product_id')->references('id')->on('rejected_products')->onDelete('cascade');
            $table->foreign('damaged_product_id')->references('id')->on('product_damages')->onDelete('cascade');
            $table->foreign('expense_entry_id')->references('id')->on('expense_entries')->onDelete('cascade');
            $table->foreign('cash_flow_id')->references('id')->on('bank_contras')->onDelete('cascade');
            $table->foreign('bkash_record_id')->references('id')->on('bkash_records')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_tracking_entries');
    }
}
