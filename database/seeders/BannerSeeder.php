<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

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
        Banner::create([
            'title' => 'Slider 3',
            'image' => 'banners/slider3.jpg',
            'link' => '#',
            'position' => 'slider',
            'sort_order' => 3,
            'is_active' => true,
        ]);


        Banner::create([
            'title' => 'Banner Bottom',
            'image' => 'banners/banner_bottom.jpeg',
            'link' => '#',
            'position' => 'banner_bottom',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}
