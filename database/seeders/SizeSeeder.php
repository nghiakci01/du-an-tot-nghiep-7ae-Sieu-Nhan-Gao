<?php
 
 namespace Database\Seeders;
 
 use App\Models\Size;
 use Illuminate\Database\Seeder;
 
 class SizeSeeder extends Seeder
 {
     /**
      * Run the database seeds.
      */
     public function run(): void
     {
        $sizes = ['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '38', '39', '40', '41', '42', '43', '44', 'Free Size'];
 
         foreach ($sizes as $index => $size) {
             Size::firstOrCreate(
                 ['name' => $size],
                 ['is_active' => true, 'display_order' => $index]
             );
         }
     }
 }
