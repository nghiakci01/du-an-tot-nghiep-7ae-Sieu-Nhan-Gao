@if(isset($columnClass) && $columnClass)
<div class="{{ $columnClass }}">
@endif
    <div class="single_product">
        <div class="product_thumb">
            <a class="primary_img" href="{{ route('product.detail', $product->slug) }}">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            </a>
            <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                @php
                    // If product has multiple gallery images, use the second one for hover
                    // Otherwise, use the main image
                    $secondaryImage = $product->images->count() > 1 
                        ? $product->images->skip(1)->first()?->image_url 
                        : $product->image_url;
                @endphp
                <img src="{{ $secondaryImage }}" alt="{{ $product->name }}">
            </a>
            <div class="product_action">
                <div class="hover_action">
                   <a href="{{ route('product.detail', $product->slug) }}"><i class="fa fa-plus"></i></a>
                    <div class="action_button">
                        <ul>
                            <li>
                                <a title="add to cart" href="#" onclick="event.preventDefault(); document.getElementById('add-to-cart-{{ $product->id }}').submit();">
                                    <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                </a>
                                <form id="add-to-cart-{{ $product->id }}" action="{{ route('cart.add') }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    @if($product->variants->count() === 1)
                                        <input type="hidden" name="variant_id" value="{{ $product->variants->first()->id }}">
                                    @endif
                                </form>
                            </li>
                            <li><a href="#" title="Add to Wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
               </div>
            </div>
            <div class="quick_button">
                <a href="{{ route('product.detail', $product->slug) }}" title="quick_view">+ quick view</a>
            </div>
            <div class="double_base">
                @if($product->price < $product->original_price)
                <div class="product_sale">
                    <span>Sale</span>
                </div>
                @endif
                @if($product->created_at->diffInDays(now()) < 7)
                <div class="label_product">
                    <span>new</span>
                </div>
                @endif
            </div>
        </div>
        <div class="product_content {{ $contentClass ?? '' }}">
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
        
        @if(isset($showListContent) && $showListContent)
        <div class="product_content list_content">
            <h3><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h3>
            <div class="product_ratting">
                <ul>
                    @php $rating = $product->reviews->avg('rating') ?? 0; @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <li><a href="#"><i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}"></i></a></li>
                    @endfor
                </ul>
            </div>
            <div class="product_price">
                @include('frontend.partials.product-price', ['product' => $product])
            </div>
            <div class="product_desc">
                <p>{{ $product->short_description }}</p>
            </div>
        </div>
        @endif
    </div>
@if(isset($columnClass) && $columnClass)
</div>
@endif
