<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacebookOrdersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('facebook_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->integer('customer_id')->nullable();
            $table->double('price');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp_num')->nullable();
            $table->text('shipping_address')->nullable();
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->foreign('courier_id')->references('id')->on('courier_names')->onDelete('restrict');
            $table->double('delivery_charge')->nullable();
            $table->unsignedBigInteger('order_status_id')->default(1);
            $table->foreign('order_status_id')->references('id')->on('facebook_order_statuses')->onDelete('restrict');
            $table->unsignedBigInteger('special_status_id')->default(1);
            $table->foreign('special_status_id')->references('id')->on('order_special_statuses')->onDelete('restrict');
            $table->string('note')->nullable();
            $table->string('remarks')->nullable();
            $table->double('discount_amount')->nullable();
            $table->unsignedBigInteger('bkash_business_id')->nullable();
            $table->foreign('bkash_business_id')->references('id')->on('bkash_numbers')->onDelete('restrict');
            $table->string('bkash_num')->nullable();
            $table->double('bkash_amount')->nullable();
            $table->double('advance')->default(0);
            $table->string('source')->default('Offline');
            $table->integer('is_return')->default(0);
            $table->integer('is_final')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('facebook_orders');
    }
}
