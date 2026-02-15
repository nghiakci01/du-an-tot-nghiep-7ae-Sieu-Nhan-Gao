<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sliders
        Banner::create([
            'title' => 'Slider 1',
            'image' => 'banners/slider1.jpg',
            'link' => '#',
            'position' => 'slider',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Slider 2',
            'image' => 'banners/slider2.jpg',
            'link' => '#',
            'position' => 'slider',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Side Banners (if any, based on common layouts, but error mentioned slider)
        Banner::create([
            'title' => 'Banner Top',
            'image' => 'banners/banner-top.jpg',
            'link' => '#',
            'position' => 'banner_top',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        
         Banner::create([
            'title' => 'Banner Bottom',
            'image' => 'banners/banner-bottom.jpg',
            'link' => '#',
            'position' => 'banner_bottom',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}
