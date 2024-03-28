<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->integer('customer_id')->nullable();
            $table->double('price');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('area_id')->nullable();
            $table->text('shipping_address')->nullable();
            $table->integer('delivery_boy_id')->nullable();
            $table->double('delivery_charge')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('refer_code')->nullable();
            $table->double('vat')->nullable();
            $table->double('cod')->default(0);
            $table->integer('order_status_id')->default(1);
            $table->string('payment_status')->default(0);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('sender_phone')->nullable();
            $table->string('sender_amount')->nullable();
            $table->string('note')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('wallet_amount')->nullable();
            $table->double('advance')->default(0);
            $table->string('source')->nullable();
            $table->integer('is_return')->default(0);
            $table->integer('is_final')->default(0);
            $table->integer('add_loss')->default(0);
            $table->integer('points_used')->nullable();
            $table->integer('discount_rate')->nullable();
            $table->double('membership_discount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('orders');
    }
}
