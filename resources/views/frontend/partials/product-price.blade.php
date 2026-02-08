@php
    $displayPrice = 0;
    $displaySalePrice = null;
    $priceRange = false;
    
    // Check if product has valid variant prices
    if ($product->variants->count() > 0) {
        $minPrice = $product->variants->min('price');
        $maxPrice = $product->variants->max('price');
        
        if ($minPrice > 0) {
            // Variants have valid prices
            $displayPrice = $minPrice;
            if ($minPrice != $maxPrice) {
                $priceRange = true;
                $displaySalePrice = $maxPrice;
            }
        } else {
            // Variants have zero price, use product price
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
    @if($priceRange)
        {{-- Price range for variants --}}
        {{ number_format($displayPrice) }} - {{ number_format($displaySalePrice) }} đ
    @elseif($displaySalePrice && $displaySalePrice > 0 && $displaySalePrice < $displayPrice)
        {{-- Sale price --}}
        <span class="old_price" style="text-decoration: line-through; margin-right: 5px;">{{ number_format($displayPrice) }} đ</span>
        {{ number_format($displaySalePrice) }} đ
    @else
        {{-- Regular price --}}
        {{ number_format($displayPrice) }} đ
    @endif
</span>
