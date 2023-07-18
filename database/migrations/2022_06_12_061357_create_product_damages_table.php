<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDamagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_damages', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('size_id')->nullable();
            $table->string('code')->nullable();
            $table->double('qty');
            $table->double('production_cost');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('product_damages');
    }
}
