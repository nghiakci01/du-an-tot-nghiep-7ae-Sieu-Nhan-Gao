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

        $options = [];

        foreach ($this->providers as $provider) {
            $quote = $provider->calculateFee(
                $province ?? '',
                $district ?? '',
                $ward ?? '',
                $weight
            );

            if ($quote !== null) {
                $options[] = $quote;
            }
        }

        if (empty($options)) {
            $options[] = $this->fallbackOption($subtotal);
        }

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

        if ($deliveryType === 'store') {
            return $options[0];
        }

        if (blank($selectedProvider) || in_array($selectedProvider, ['default', 'flat_rate'], true)) {
            return $options[0];
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
            'provider' => 'default',
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
