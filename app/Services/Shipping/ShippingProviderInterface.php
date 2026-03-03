<?php

namespace App\Services\Shipping;

interface ShippingProviderInterface
{
    /**
     * Calculate shipping fee based on address and weight.
     *
     * @param string $toProvince
     * @param string $toDistrict
     * @param string $toWard
     * @param int $weight (in grams)
     * @return array|null Returns array with 'fee', 'expected_delivery_time', 'service_name', or null on failure.
     */
    public function calculateFee(string $toProvince, string $toDistrict, string $toWard, int $weight): ?array;
}
