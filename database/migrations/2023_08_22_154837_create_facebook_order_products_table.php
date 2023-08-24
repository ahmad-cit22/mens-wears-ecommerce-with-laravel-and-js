<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacebookOrderProductsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('facebook_order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('facebook_orders')->onDelete('cascade');
            $table->integer('product_id')->unsigned();
            $table->integer('size_id')->unsigned()->nullable();
            $table->double('production_cost')->nullable();
            $table->double('price');
            $table->double('qty');
            $table->double('return_qty')->nullable();
            $table->double('discount_amount')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('facebook_order_products');
    }
}
