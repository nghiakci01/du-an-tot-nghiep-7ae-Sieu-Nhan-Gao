@php
    $recentIds = session('recently_viewed', []);
    if ($recentIds && count($recentIds) > 0) {
        // Exclude current product if on product detail page
        $excludeId = isset($product) ? $product->id : null;
        $filteredIds = array_filter($recentIds, fn($id) => $id !== $excludeId);
        $recentProducts = \App\Models\Product::whereIn('id', array_slice($filteredIds, 0, 6))
            ->where('is_active', true)
            ->with(['variants', 'reviews', 'images'])
            ->get()
            ->sortBy(function($p) use ($filteredIds) {
                return array_search($p->id, $filteredIds);
            });
    } else {
        $recentProducts = collect();
    }
@endphp

@if($recentProducts->count() > 0)
<section class="product_section womens_product product_section_six bottom" style="padding: 30px 0;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="section_title">
                    <h2><i class="fa fa-clock-o"></i> Sản phẩm đã xem gần đây</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($recentProducts as $rp)
            <div class="col-lg-2 col-md-3 col-4 mb-3">
                <div class="single_product">
                    <div class="product_thumb">
                        <a class="primary_img" href="{{ route('product.detail', $rp->slug) }}">
                            <img src="{{ $rp->image_url }}" alt="{{ $rp->name }}" style="height: 180px; object-fit: cover;">
                        </a>
                    </div>
                    <div class="product_content">
                        <h3><a href="{{ route('product.detail', $rp->slug) }}">{{ Str::limit($rp->name, 30) }}</a></h3>
                        @include('frontend.partials.product-price', ['product' => $rp])
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
