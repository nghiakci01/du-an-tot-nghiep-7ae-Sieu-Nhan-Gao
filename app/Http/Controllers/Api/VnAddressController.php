<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class VnAddressController extends Controller
{
    /**
     * Trả về danh sách tỉnh/thành unique (từ file JSON tĩnh)
     */
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
                'name' => str_replace(['Tỉnh ', 'Thành phố '], '', $c['provinceName']),
            ]);

        return response()->json($provinces);
    }

    /**
     * Trả về danh sách quận/huyện theo provinceCode (Proxy tới Open API)
     */
    public function districts(string $provinceCode)
    {
        $url = "https://provinces.open-api.vn/api/p/{$provinceCode}?depth=2";
        try {
            $data = json_decode(file_get_contents($url), true);
            $districts = collect($data['districts'] ?? [])->map(fn($d) => [
                'code' => $d['code'],
                'name' => $d['name'],
            ]);
            return response()->json($districts);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch districts'], 500);
        }
    }

    /**
     * Trả về danh sách xã/phường theo districtCode (Proxy tới Open API)
     */
    public function wards(string $districtCode)
    {
        $url = "https://provinces.open-api.vn/api/d/{$districtCode}?depth=2";
        try {
            $data = json_decode(file_get_contents($url), true);
            $wards = collect($data['wards'] ?? [])->map(fn($w) => [
                'code' => $w['code'],
                'name' => $w['name'],
            ]);
            return response()->json($wards);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch wards'], 500);
        }
    }

    /**
     * Trả về danh sách xã/phường theo provinceCode (legacy)
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
