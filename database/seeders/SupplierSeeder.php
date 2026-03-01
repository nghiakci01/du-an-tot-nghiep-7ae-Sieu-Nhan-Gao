<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            ['name' => 'Công ty May Mặc Việt Tiến', 'email' => 'viettien@example.com', 'phone' => '02838640800', 'address' => '7 Lê Minh Xuân, Quận Tân Bình, TP.HCM'],
            ['name' => 'Tổng Công ty 28', 'email' => 'agtex28@example.com', 'phone' => '02838942238', 'address' => '3 Nguyễn Oanh, Quận Gò Vấp, TP.HCM'],
            ['name' => 'Công ty TNHH Esprit Việt Nam', 'email' => 'esprit@example.com', 'phone' => '02838221888', 'address' => 'Tòa nhà Bitexco, Quận 1, TP.HCM'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['email' => $supplier['email']], $supplier);
        }
    }
}
