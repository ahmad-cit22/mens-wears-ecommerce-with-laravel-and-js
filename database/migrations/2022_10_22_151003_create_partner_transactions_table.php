<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('partner_id');
            $table->integer('bank_id')->nullable();
            $table->double('credit')->nullable();
            $table->double('debit')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('partner_transactions');
    }
}
