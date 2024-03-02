<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('assets', function (Blueprint $table) {
        //     $table->id();
        //     $table->integer('bank_id')->nullable();
        //     $table->string('name')->nullable();
        //     $table->double('amount')->nullable();
        //     $table->double('reduction_amount')->nullable();
        //     $table->double('reduction_period')->nullable();
        //     $table->double('depreciation_value')->nullable();
        //     $table->text('note')->nullable();
        //     $table->date('purchase_date')->nullable();
        //     $table->timestamps();
        // });
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_id');
            $table->string('name');
            $table->double('amount');
            $table->double('depreciation_value')->nullable();
            $table->double('estimated_life');
            $table->double('net_value');
            // $table->double('reduction_amount')->nullable();
            // $table->double('reduction_period')->nullable();
            $table->text('note')->nullable();
            $table->date('purchase_date');
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
        Schema::dropIfExists('assets');
    }
}
