<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('type')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('sub_category_id')->nullable();
            $table->double('is_sale')->default(0);
            $table->string('code')->nullable();
            $table->string('barcode')->nullable();
            $table->string('unit')->nullable();
            $table->string('image')->nullable();
            $table->string('size_chart')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->integer('is_featured')->default(0);
            $table->integer('is_trending')->default(0);
            $table->integer('is_offer')->default(0);
            $table->integer('is_active')->default(1);
            $table->integer('slod')->default(0);
            $table->longText('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('products');
    }
}
