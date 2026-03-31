<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    private function normalize($str)
    {
        if (!class_exists('\Normalizer')) return mb_strtolower(trim($str));
        return mb_strtolower(trim(\Normalizer::normalize($str, \Normalizer::FORM_C)));
    }

    private function getJsonData($filename)
    {
        $path = public_path('data/' . $filename);
        if (!File::exists($path)) {
            Log::error("Address API: File not found at $path");
            return null;
        }
        $content = File::get($path);
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Address API: JSON decode error for $filename: " . json_last_error_msg());
            return null;
        }
        return $data;
    }

    public function getDistricts(Request $request)
    {
        $provinceName = $request->query('province_name');
        Log::info("Address API: getDistricts for '$provinceName'");
        
        if (!$provinceName) return response()->json([]);

        $provinces = $this->getJsonData('provinces.json');
        $districts = $this->getJsonData('districts.json');

        if (!$provinces || !$districts) {
            return response()->json(['error' => 'Data unavailable'], 500);
        }

        $provinceCode = null;
        $normalizedInput = $this->normalize($provinceName);

        foreach ($provinces as $code => $p) {
            $normalizedName = $this->normalize($p['name']);
            if ($normalizedName === $normalizedInput || str_contains($normalizedInput, $normalizedName) || str_contains($normalizedName, $normalizedInput)) {
                $provinceCode = $code;
                break;
            }
        }

        if (!$provinceCode) {
            Log::warning("Address API: Province '$provinceName' not found in JSON after normalization");
            return response()->json([]);
        }

        $filtered = [];
        foreach ($districts as $code => $d) {
            if ($d['parent_code'] === $provinceCode) {
                $filtered[] = [
                    'code' => $code,
                    'name' => $d['name'],
                    'name_with_type' => $d['name_with_type']
                ];
            }
        }

        usort($filtered, fn($a, $b) => strcmp($a['name'], $b['name']));
        return response()->json($filtered);
    }

    public function getWards(Request $request)
    {
        $districtName = $request->query('district_name');
        $provinceName = $request->query('province_name');
        Log::info("Address API: getWards for '$districtName' in '$provinceName'");

        if (!$districtName || !$provinceName) return response()->json([]);

        $provinces = $this->getJsonData('provinces.json');
        $districts = $this->getJsonData('districts.json');
        $wards = $this->getJsonData('wards.json');

        if (!$provinces || !$districts || !$wards) {
            return response()->json(['error' => 'Data unavailable'], 500);
        }

        $provinceCode = null;
        $normalizedProvinceInput = $this->normalize($provinceName);
        foreach ($provinces as $code => $p) {
            $normalizedName = $this->normalize($p['name']);
            if ($normalizedName === $normalizedProvinceInput || str_contains($normalizedName, $normalizedProvinceInput) || str_contains($normalizedProvinceInput, $normalizedName)) {
                $provinceCode = $code;
                break;
            }
        }

        if (!$provinceCode) return response()->json([]);

        $districtCode = null;
        $normalizedDistrictInput = $this->normalize($districtName);
        foreach ($districts as $code => $d) {
            $normalizedName = $this->normalize($d['name']);
            if (($normalizedName === $normalizedDistrictInput || str_contains($normalizedName, $normalizedDistrictInput) || str_contains($normalizedDistrictInput, $normalizedName)) && $d['parent_code'] === $provinceCode) {
                $districtCode = $code;
                break;
            }
        }

        if (!$districtCode) return response()->json([]);

        $filtered = [];
        foreach ($wards as $code => $w) {
            if ($w['parent_code'] === $districtCode) {
                $filtered[] = [
                    'code' => $code,
                    'name' => $w['name'],
                    'name_with_type' => $w['name_with_type']
                ];
            }
        }

        usort($filtered, fn($a, $b) => strcmp($a['name'], $b['name']));
        return response()->json($filtered);
    }
}
