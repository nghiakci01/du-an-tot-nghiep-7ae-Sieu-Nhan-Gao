<?php

namespace App\Services\Shipping;

use App\Models\Setting;

class ShippingService
{
    /**
     * @var array<int, \App\Services\Shipping\ShippingProviderInterface>
     */
    protected array $providers;

    public function __construct(
        GhnShippingProvider $ghnShippingProvider,
        GhtkShippingProvider $ghtkShippingProvider,
        ViettelPostShippingProvider $viettelPostShippingProvider
    ) {
        $this->providers = [
            $ghnShippingProvider,
            $ghtkShippingProvider,
            $viettelPostShippingProvider,
        ];
    }

    public function estimateWeightFromCart(array $cart): int
    {
        $totalQuantity = collect($cart)->sum(fn ($item) => (int) ($item['quantity'] ?? 0));

        // Project currently has no per-product weight, so use a stable fallback:
        // 200g packaging + 300g per item, minimum 500g.
        return max(500, 200 + ($totalQuantity * 300));
    }

    public function getOptions(
        string $deliveryType,
        ?string $province,
        ?string $district,
        ?string $ward,
        int $weight,
        float $subtotal
    ): array {
        if ($deliveryType === 'store') {
            return [$this->storePickupOption()];
        }

        if (blank($province)) {
            return [];
        }

        $district = $district ?: $province;
        $ward = $ward ?: $district;

        $options = [];
        foreach ($this->providers as $provider) {
            $result = $provider->calculateFee($province, $district, $ward, $weight);

            if ($result !== null) {
                $options[] = $result;
            }
        }

        if (empty($options)) {
            $options[] = $this->fallbackOption($subtotal);
        }

        $configuredFee = Setting::getShippingFee($subtotal);
        if ($configuredFee === 0.0) {
            $options = array_map(function (array $option) {
                $option['fee'] = 0;

                return $option;
            }, $options);
        }

        usort($options, fn (array $left, array $right) => $left['fee'] <=> $right['fee']);

        return $options;
    }

    public function resolveSelectedOption(
        string $deliveryType,
        ?string $province,
        ?string $district,
        ?string $ward,
        int $weight,
        float $subtotal,
        ?string $selectedProvider
    ): ?array {
        $options = $this->getOptions($deliveryType, $province, $district, $ward, $weight, $subtotal);

        if (empty($options)) {
            return null;
        }

        if (blank($selectedProvider)) {
            return $deliveryType === 'store' ? $options[0] : null;
        }

        foreach ($options as $option) {
            if (($option['provider'] ?? null) === $selectedProvider) {
                return $option;
            }
        }

        return null;
    }

    protected function fallbackOption(float $subtotal): array
    {
        return [
            'provider' => 'flat_rate',
            'service_name' => 'Giao hang tieu chuan',
            'fee' => (int) Setting::getShippingFee($subtotal),
            'expected_delivery_time' => now()->addDays(3)->format('d/m/Y'),
        ];
    }

    protected function storePickupOption(): array
    {
        return [
            'provider' => 'store_pickup',
            'service_name' => 'Nhan tai cua hang',
            'fee' => 0,
            'expected_delivery_time' => 'Trong gio hanh chinh',
        ];
    }
}
