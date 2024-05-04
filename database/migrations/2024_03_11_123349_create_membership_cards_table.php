<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_status');
            $table->float('discount_rate');
            $table->double('min_purchase')->nullable();
            $table->float('point_percentage')->default(1);
            $table->integer('min_point')->nullable();
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
        Schema::dropIfExists('membership_cards');
    }
}
