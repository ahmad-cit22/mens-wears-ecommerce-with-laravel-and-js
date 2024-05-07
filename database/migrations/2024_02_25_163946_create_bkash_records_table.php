<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBkashRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bkash_records', function (Blueprint $table) {
            $table->id();
            $table->integer('bkash_business_id');
            $table->string('tr_type')->comment('CASH IN, CASH OUT, SEND MONEY, PAYMENTS, RECHARGE');
            $table->integer('tr_purpose_id');
            $table->float('amount');
            $table->longText('comments')->nullable();
            $table->string('last_digit')->nullable();
            $table->integer('order_sheet_id')->nullable();
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
        Schema::dropIfExists('bkash_records');
    }
}
