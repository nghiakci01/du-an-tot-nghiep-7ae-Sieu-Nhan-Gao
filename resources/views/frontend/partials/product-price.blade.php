@php
    $displayPrice = 0;
    $displaySalePrice = null;
    
    // Check if product has valid variant prices
    if ($product->variants->count() > 0) {
        $firstVariant = $product->variants->first();
        
        if ($firstVariant && $firstVariant->price > 0) {
            $displayPrice = $firstVariant->price;
            $displaySalePrice = $firstVariant->sale_price;
        } else {
            // Variant has zero price, use product price
            $displayPrice = $product->price;
            $displaySalePrice = $product->sale_price;
        }
    } else {
        // No variants, use product price
        $displayPrice = $product->price;
        $displaySalePrice = $product->sale_price;
    }
@endphp

<span class="current_price">
    @if($displayPrice > 0)
        @if($displaySalePrice && $displaySalePrice > 0 && $displaySalePrice < $displayPrice)
            {{-- Sale price --}}
            <span class="old_price" style="text-decoration: line-through; margin-right: 5px;">{{ number_format($displayPrice) }} VND</span>
            {{ number_format($displaySalePrice) }} VND
        @else
            {{-- Regular price --}}
            {{ number_format($displayPrice) }} VND
        @endif
    @else
        {{-- Price is 0 or NULL --}}
        <span class="contact_for_price" style="color: #ef233c; font-weight: 600;">Liên hệ</span>
    @endif
</span>
