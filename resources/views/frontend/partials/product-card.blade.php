@php
    $product = $product ?? null;
    if (!$product) return;
@endphp

<div class="{{ $columnClass ?? 'col-lg-3' }}">
    <div class="single_product">
        <div class="product_thumb">
            <a class="primary_img" href="{{ route('product.detail', $product->slug) }}">
                <img loading="lazy" src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product21.jpg') }}" alt="{{ $product->name }}">
            </a>
            @php
                $hoverImage = $product->images->firstWhere('image_path', '!=', $product->image);
            @endphp
            @if($hoverImage)
            <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                <img loading="lazy" src="{{ $hoverImage->image_url }}" alt="{{ $product->name }}">
            </a>
            @endif
            <div class="product_action">
                <div class="hover_action">
                    <a href="#"><i class="fa fa-plus"></i></a>
                    <div class="action_button">
                        <ul>
                            <li>
                                <a title="add to cart" href="javascript:void(0);" class="btn-ajax-add-to-cart" data-form-id="add-to-cart-card-{{ $product->id }}">
                                    <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                </a>
                                <form id="add-to-cart-card-{{ $product->id }}" class="ajax-add-to-cart-form" action="{{ route('cart.add') }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    @if($product->variants->count() === 1)
                                        <input type="hidden" name="variant_id" value="{{ $product->variants->first()->id }}">
                                    @endif
                                </form>
                            </li>
                            <li><a href="javascript:void(0)" class="add-to-wishlist" data-id="{{ $product->id }}" title="Add to Wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="quick_button">
                <a href="#" data-bs-toggle="modal" data-bs-target="#modal_box" title="Quick view">+ quick view</a>
            </div>
            @if($product->created_at && $product->created_at->diffInDays(now()) <= 7)
                <div class="label_product"><span>new</span></div>
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
