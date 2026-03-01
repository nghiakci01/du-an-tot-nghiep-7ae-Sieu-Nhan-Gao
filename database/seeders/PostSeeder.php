<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = PostCategory::all();

        if ($categories->isEmpty()) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            $title = "Tin tức mẫu số $i - Bản tin thời trang elite " . date('Y');
            Post::create([
                'post_category_id' => $categories->random()->id,
                'title' => $title,
                'slug' => Str::slug($title) . '-' . uniqid(),
                'content' => 'Nội dung mẫu cho bài viết số ' . $i . '. Tổng hợp các xu hướng thời trang mới nhất năm 2026. Đội ngũ Elite cam kết mang lại sản phẩm chất lượng cao nhất.',
                'image' => 'posts/sample-' . $i . '.jpg',
                'is_active' => true,
                'views' => rand(100, 1000),
            ]);
        }
    }
}
