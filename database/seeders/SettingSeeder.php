<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'bank_name',
                'value' => 'Vietcombank',
                'group' => 'payment',
            ],
            [
                'key' => 'bank_account_number',
                'value' => '0071001234567',
                'group' => 'payment',
            ],
            [
                'key' => 'bank_account_name',
                'value' => 'CÔNG TY TNHH SIÊU NHÂN GAO',
                'group' => 'payment',
            ],
            [
                'key' => 'bank_id', // VietQR bank ID (vcb for Vietcombank)
                'value' => 'vcb',
                'group' => 'payment',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
