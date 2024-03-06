<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatEntriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('vat_entries', function (Blueprint $table) {
            $table->id();
            $table->date('date_of_sell')->nullable();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            // $table->string('status')->default('OUT STANDING')->comment('OUT STANDING, PAID');
            $table->integer('is_paid')->nullable()->comment('0 = OUT STANDING, 1 = PAID');
            $table->float('vat_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('vat_entries');
    }
}
