<?php

namespace Database\Seeders;

// use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            ['name' => 'Kho Hà Nội', 'address' => 'Số 1 Trần Duy Hưng, Cầu Giấy, Hà Nội'],
            ['name' => 'Kho TP.HCM', 'address' => 'Số 100 Nguyễn Huệ, Quận 1, TP.HCM'],
            ['name' => 'Kho Đà Nẵng', 'address' => 'Số 50 Nguyễn Văn Linh, Hải Châu, Đà Nẵng'],
        ];

        // Warehouse model does not exist currently
        // foreach ($warehouses as $warehouse) {
        //     \App\Models\Warehouse::firstOrCreate(['name' => $warehouse['name']], $warehouse);
        // }
    }
}
