<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncVnAddress extends Command
{
    protected $signature   = 'address:sync';
    protected $description = 'Fetch VN address data from API and save as local JSON file';

    public function handle(): int
    {
        $this->info('Đang tải dữ liệu địa danh Việt Nam...');

        try {
            $response = Http::timeout(30)->get(
                'https://production.cas.so/address-kit/2025-07-01/communes'
            );

            if (! $response->successful()) {
                $this->error('API trả về lỗi: ' . $response->status());
                return self::FAILURE;
            }

            $communes = $response->json('communes', []);

            if (empty($communes)) {
                $this->error('Không có dữ liệu communes từ API.');
                return self::FAILURE;
            }

            $filePath = storage_path('app/vn_communes.json');
            file_put_contents($filePath, json_encode($communes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

            $this->info('✅ Đã lưu ' . count($communes) . ' xã/phường vào storage/app/vn_communes.json');
            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Lỗi: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
