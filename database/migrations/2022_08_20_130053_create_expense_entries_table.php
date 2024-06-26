<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('expense_id');
            $table->double('amount');
            $table->integer('bank_id')->nullable();
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
        Schema::dropIfExists('expense_entries');
    }
}
