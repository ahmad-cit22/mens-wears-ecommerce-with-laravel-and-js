<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_suppliers', function (Blueprint $table) {
            $table->id();
            $table->integer('production_id')->nullable();
            $table->integer('supplier_id');
            $table->string('qty')->nullable();
            $table->double('amount')->nullable();
            $table->string('type')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('production_suppliers');
    }
}
