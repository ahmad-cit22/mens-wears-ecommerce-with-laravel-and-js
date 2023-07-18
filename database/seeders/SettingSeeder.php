<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\Slider;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Setting;
        $setting->name = "Go By Fabrifest";
        $setting->phone = "01763100517";
        $setting->additional_phone = "01763100517";
        $setting->email = "sadeknurul5@gmail.com";
        $setting->address = "Mirpur, Dhaka";
        $setting->combine_address = "Mirpur, Dhaka";

        $setting->save();

        $images = ['slider-1.jpg', 'slider-2.jpg', 'slider-3.jpg'];
        foreach ($images as $image) {
            $slider = new Slider;
            $slider->image = $image;
            $slider->link = '#';
            $slider->save();
        }
    }
}
