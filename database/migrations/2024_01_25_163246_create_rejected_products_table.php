<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRejectedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // product_reject_entries

        Schema::create('rejected_products', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('size_id');
            $table->double('qty');
            $table->string('note')->nullable();
            $table->date('date')->nullable();
            $table->boolean('is_transfer')->default(0);
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
        Schema::dropIfExists('rejected_products');
    }
}
