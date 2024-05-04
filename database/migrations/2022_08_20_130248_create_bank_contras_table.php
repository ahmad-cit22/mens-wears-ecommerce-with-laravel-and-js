<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankContrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_contras', function (Blueprint $table) {
            $table->id();
            $table->integer('from_id');
            $table->integer('to_id');
            $table->double('amount')->nullable();
            $table->text('note')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('bank_contras');
    }
}
