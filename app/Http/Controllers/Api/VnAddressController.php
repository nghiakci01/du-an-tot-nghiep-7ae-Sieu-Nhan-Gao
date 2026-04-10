<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class VnAddressController extends Controller
{
    /**
     * Trả về danh sách tỉnh/thành unique (từ file JSON tĩnh)
     */
    public function provinces()
    {
        $communes = $this->loadCommunes();

        $provinces = collect($communes)
            ->unique('provinceCode')
            ->sortBy('provinceName')
            ->values()
            ->map(fn($c) => [
                'code' => $c['provinceCode'],
                'name' => preg_replace('/^(Tỉnh|Thành phố)\s+/u', '', $c['provinceName']),
            ]);

        return response()->json($provinces);
    }

    /**
     * Trả về danh sách xã/phường theo provinceCode (từ file JSON tĩnh)
     */
    public function communes(string $provinceCode)
    {
        $communes = collect($this->loadCommunes())
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

    private function loadCommunes(): array
    {
        $filePath = storage_path('app/vn_communes.json');

        if (! file_exists($filePath)) {
            return [];
        }

        return json_decode(file_get_contents($filePath), true) ?? [];
    }
}
