<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class VnAddressController extends Controller
{
    private const API_BASE = 'https://production.cas.so/address-kit/2025-07-01';
    private const CACHE_TTL = 86400; // 24h

    /**
     * Trả về danh sách tỉnh/thành unique (cache 24h)
     */
    public function provinces()
    {
        $communes = $this->fetchCommunes();

        $provinces = collect($communes)
            ->unique('provinceCode')
            ->sortBy('provinceName')
            ->values()
            ->map(fn($c) => [
                'code' => $c['provinceCode'],
                'name' => $c['provinceName'],
            ]);

        return response()->json($provinces);
    }

    /**
     * Trả về danh sách xã/phường theo provinceCode (cache 24h)
     */
    public function communes(string $provinceCode)
    {
        $communes = collect($this->fetchCommunes())
            ->filter(fn($c) => $c['provinceCode'] === $provinceCode)
            ->sortBy('name')
            ->values()
            ->map(fn($c) => [
                'code'  => $c['code'],
                'name'  => $c['name'],
                'level' => $c['administrativeLevel'],
            ]);

        return response()->json($communes);
    }

    private function fetchCommunes(): array
    {
        return Cache::remember('vn_communes', self::CACHE_TTL, function () {
            $response = Http::timeout(15)->get(self::API_BASE . '/communes');
            return $response->json('communes', []);
        });
    }
}
