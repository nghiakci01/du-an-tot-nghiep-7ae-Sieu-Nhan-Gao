@if(isset($columnClass) && $columnClass)
<div class="{{ $columnClass }}">
@endif
    <div class="single_product">
        <div class="product_thumb">
            <a class="primary_img" href="{{ route('product.detail', $product->slug) }}">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product21.jpg') }}" alt="{{ $product->name }}">
            </a>
            <a class="secondary_img" href="{{ route('product.detail', $product->slug) }}">
                <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : ($product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product21.jpg')) }}" alt="{{ $product->name }}">
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
                                </form>
                            </li>
                            <li><a href="#" title="Add to Wishlist"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="Add to Compare"><i class="fa fa-sliders" aria-hidden="true"></i></a></li>
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
        </div>
    </div>
@if(isset($columnClass) && $columnClass)
</div>
@endif
