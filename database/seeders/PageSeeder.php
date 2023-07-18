<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
        	'Home',
        	'All Products',
        	'All Category',
        	'About Us',
        	'Privacy Policy',
        	'Term and Conditions'
        ];
        foreach ($pages as $page) {
        	$new_page = new Page;
        	$new_page->name = $page;
        	$new_page->save();
        }
    }
}
