<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStockHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stock_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('size_id')->nullable();
            $table->double('qty')->default(0);
            $table->string('note')->nullable();
            $table->string('reference_code')->nullable();
            $table->longText('remarks')->nullable();
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('receiver_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_approved')->default(1);
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
        Schema::dropIfExists('product_stock_histories');
    }
}
