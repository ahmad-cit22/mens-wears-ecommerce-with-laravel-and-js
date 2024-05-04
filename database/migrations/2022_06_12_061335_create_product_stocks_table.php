<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('size_id')->nullable();
            $table->string('code')->nullable();
            $table->string('barcode')->nullable();
            $table->double('production_cost');
            $table->double('price');
            $table->double('discount_price')->nullable();
            $table->double('wholesale_price')->nullable();
            $table->double('qty')->default(0);
            $table->double('sold_qty')->default(0);
            $table->double('is_sale')->default(0);
            $table->string('unit')->nullable();
            $table->string('image')->nullable();
            $table->integer('is_active')->default(1);
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('product_stocks');
    }
}
