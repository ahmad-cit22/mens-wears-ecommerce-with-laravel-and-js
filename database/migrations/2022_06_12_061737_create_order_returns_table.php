<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->unsigned();
            $table->string('order_code')->nullable();
            $table->string('code')->nullable();
            $table->integer('product_id')->unsigned();
            $table->integer('size_id')->unsigned()->nullable();
            $table->double('production_price')->nullable();
            $table->double('price');
            $table->double('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_returns');
    }
}
