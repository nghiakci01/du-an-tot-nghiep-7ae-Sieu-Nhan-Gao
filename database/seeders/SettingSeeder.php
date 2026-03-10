<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Website Information
            [
                'key' => 'site_title',
                'value' => 'Elite',
                'group' => 'general',
            ],
            [
                'key' => 'shipping_fee',
                'value' => '30000',
                'group' => 'general',
            ],
            // Contact Information
            [
                'key' => 'site_email',
                'value' => 'Elite@gmail.com',
                'group' => 'contact',
            ],
            [
                'key' => 'site_phone',
                'value' => '0354869999',
                'group' => 'contact',
            ],
            [
                'key' => 'site_address',
                'value' => 'Số 7 Ngõ 91 Lai Xá - Hoài Đức - Thành Phố Hà Nội - Việt Nam',
                'group' => 'contact',
            ],
            [
                'key' => 'store_map_iframe',
                'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.6575765790473!2d105.71077797584149!3d21.04638368717544!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134546536093551%3A0x673199834278e993!2sNg.%2091%20Lai%20X%C3%A1%2C%20Kim%20Chung%2C%20Ho%C3%A0i%20%C4%90%E1%BB%A9c%2C%20H%C3%A0%20N%E1%BB%99i!5e0!3m2!1svi!2s!4v1710000000000!5m2!1svi!2s',
                'group' => 'contact',
            ],
            // Social Networks
            [
                'key' => 'social_facebook',
                'value' => '#',
                'group' => 'social',
            ],
            [
                'key' => 'social_instagram',
                'value' => '#',
                'group' => 'social',
            ],
            // Payment Information (kept for backup/logic)
            [
                'key' => 'bank_name',
                'value' => 'MB Bank (Ngân hàng Quân Đội)',
                'group' => 'payment',
            ],
            [
                'key' => 'bank_account_number',
                'value' => '0359756805',
                'group' => 'payment',
            ],
            [
                'key' => 'bank_account_name',
                'value' => 'NGUYEN CONG BANG',
                'group' => 'payment',
            ],
            [
                'key' => 'bank_id',
                'value' => 'MB',
                'group' => 'payment',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
