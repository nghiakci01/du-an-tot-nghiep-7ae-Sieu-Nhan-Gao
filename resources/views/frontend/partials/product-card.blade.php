@php
    $product = $product ?? null;
    if (!$product) return;
@endphp

<div class="{{ $columnClass ?? 'col-lg-3' }}">
    <div class="single_product">
        <div class="product_thumb">
            <a class="primary_img" href="{{ route('product.detail', $product->slug) }}">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product21.jpg') }}" alt="{{ $product->name }}">
            </a>
            <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product22.jpg') }}" alt="{{ $product->name }}">
            </a>
            <div class="product_action">
                <div class="hover_action">
                    <a href="#"><i class="fa fa-plus"></i></a>
                    <div class="action_button">
                        <ul>
                            <li><a title="Thêm vào giỏ" href="#"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="Yêu thích"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="So sánh"><i class="fa fa-sliders" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="quick_button">
                <a href="#" data-bs-toggle="modal" data-bs-target="#modal_box" title="Xem nhanh">+ xem nhanh</a>
            </div>
            @if($product->created_at && $product->created_at->diffInDays(now()) <= 7)
                <div class="label_product"><span>mới</span></div>
            @endif
        </div>
        <div class="product_content">
            <h3><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h3>
            @include('frontend.partials.product-price', ['product' => $product])
            @php $ratingAvg = $product->reviews->avg('rating') ?? 0; $ratingCount = $product->reviews->count(); @endphp
            <div class="product_ratting" style="margin-top:5px;">
                <ul style="display:flex; align-items:center; gap:2px; list-style:none; padding:0; margin:0;">
                    @for($i = 1; $i <= 5; $i++)
                        <li><i class="fa {{ $i <= round($ratingAvg) ? 'fa-star' : 'fa-star-o' }}" style="color:#f39c12; font-size:12px;"></i></li>
                    @endfor
                    @if($ratingCount > 0)
                        <li style="font-size:11px; color:#999; margin-left:4px;">({{ $ratingCount }})</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
