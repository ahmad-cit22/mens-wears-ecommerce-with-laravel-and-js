<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('additional_phone')->nullable();
            $table->text('address')->nullable();
            $table->text('combine_address')->nullable();
            $table->string('vat')->default(0);
            $table->double('shipping_charge')->default(0);
            $table->double('shipping_charge_dhaka')->default(0);
            $table->double('shipping_charge_dhaka_metro')->default(0);
            $table->double('maximum_wallet')->default(0);
            $table->double('minimum_cart')->default(0);
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_tag')->nullable();
            $table->string('meta_image')->nullable();
            $table->string('slider_option')->default('image')->comments('image|video');
            $table->string('video')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
