import re

with open('resources/views/frontend/products/show.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

new_product_details = """    <!--product details start-->
    <style>
        .editorial-layout { display: flex; flex-wrap: wrap; margin-top: 40px; margin-bottom: 60px; }
        .editorial-images { flex: 0 0 100%; max-width: 100%; margin-bottom: 30px; }
        .editorial-content { flex: 0 0 100%; max-width: 100%; }
        .editorial-sticky { position: relative; }
        
        @media (min-width: 992px) {
            .editorial-layout { align-items: flex-start; }
            .editorial-images { flex: 0 0 60%; max-width: 60%; padding-right: 50px; margin-bottom: 0; }
            .editorial-content { flex: 0 0 40%; max-width: 40%; }
            .editorial-sticky { position: sticky; top: 120px; max-height: calc(100vh - 140px); overflow-y: auto; padding-right: 20px; scrollbar-width: none; }
            .editorial-sticky::-webkit-scrollbar { display: none; }
        }

        .editorial-img-item { margin-bottom: 20px; width: 100%; overflow: hidden; background: #f5f5f5; }
        .editorial-img-item img { width: 100%; height: auto; display: block; object-fit: cover; transition: transform 0.5s ease; }
        .editorial-img-item:hover img { transform: scale(1.03); }

        .product_d_right h1 { font-size: 36px; font-weight: 800; text-transform: uppercase; margin-bottom: 15px; line-height: 1.1; letter-spacing: -1px; color: #111; }
        .product_ratting { margin-bottom: 20px; }
        .product_ratting ul li { display: inline-block; }
        .product_ratting ul li.review a { color: #666; margin-left: 10px; text-decoration: underline; }

        .product_price { font-size: 28px; font-weight: 700; margin-bottom: 25px; color: #111; display: flex; align-items: baseline; gap: 15px; }
        .product_price .old_price { font-size: 18px; color: #999; text-decoration: line-through; font-weight: 400; }

        .product_desc { margin-bottom: 30px; font-size: 15px; line-height: 1.6; color: #555; border-bottom: 1px solid #eee; padding-bottom: 25px; }

        .variant-group { margin-bottom: 25px; }
        .variant-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; color: #111; }
        .variant-swatch-container { display: flex; flex-wrap: wrap; gap: 10px; }
        .variant-swatch { display: inline-flex; align-items: center; justify-content: center; min-width: 50px; height: 45px; padding: 0 15px; border: 1px solid #ddd; background: #fff; color: #333; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; text-transform: uppercase; }
        .variant-swatch:hover, .variant-swatch.active { border-color: #111; }
        .variant-swatch.active { background: #111; color: #fff; }
        
        .action-group { margin-bottom: 30px; }
        .qty-wrapper { display: flex; align-items: center; border: 1px solid #ddd; width: 120px; height: 50px; margin-bottom: 20px; }
        .qty-btn { width: 40px; height: 100%; background: #fff; border: none; font-size: 20px; cursor: pointer; color: #111; display: flex; align-items: center; justify-content: center; transition: background 0.2s; }
        .qty-btn:hover { background: #f5f5f5; }
        .qty-input { width: 40px; height: 100%; border: none; text-align: center; font-size: 16px; font-weight: 600; color: #111; padding: 0; -moz-appearance: textfield; }
        .qty-input::-webkit-outer-spin-button, .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

        .btn-massive { width: 100%; height: 55px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1); border: none; border-radius: 0 !important; margin-bottom: 10px; }
        .btn-add-cart { background: #111; color: #fff; border: 1px solid #111; }
        .btn-add-cart:hover:not(:disabled) { background: #fff; color: #111; }
        .btn-add-cart:disabled { background: #f0f0f0; color: #aaa; border-color: #eee; cursor: not-allowed; }
        .btn-buy-now { background: #ef233c; color: #fff; }
        .btn-buy-now:hover:not(:disabled) { background: #d90429; box-shadow: 0 10px 20px rgba(239,35,60,0.2); transform: translateY(-2px); }
        .btn-buy-now:disabled { background: #f8a1ac; cursor: not-allowed; }

        .product_d_action ul { display: flex; gap: 20px; margin-bottom: 25px; padding-bottom: 25px; border-bottom: 1px solid #eee; }
        .product_d_action ul li a { font-weight: 600; font-size: 13px; color: #555; text-transform: uppercase; letter-spacing: 1px; transition: color 0.2s; display: flex; align-items: center; gap: 8px; }
        .product_d_action ul li a:hover { color: #111; }
        
        .priduct_social h3 { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; color: #111; }
        .priduct_social ul { display: flex; gap: 15px; }
        .priduct_social ul li a { display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1px solid #ddd; color: #555; transition: all 0.3s; border-radius: 50%; }
        .priduct_social ul li a:hover { background: #111; border-color: #111; color: #fff; }

        .variant-msg { font-size: 14px; color: #ef233c; padding: 10px 15px; background: #fff0f2; border: 1px solid #ffccd3; margin-bottom: 20px; display: none; font-weight: 500; }
    </style>
    <div class="product_details">
        <div class="container">
            <div class="editorial-layout">
                <div class="editorial-images">
                    <div class="editorial-img-item">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('frontend-assets/img/product/product5.jpg') }}" alt="{{ $product->name }} Main Image">
                    </div>
                    @foreach($product->images as $image)
                        <div class="editorial-img-item">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }} Image {{ $loop->iteration }}">
                        </div>
                    @endforeach
                </div>

                <div class="editorial-content">
                    <div class="editorial-sticky">
                        <div class="product_d_right">
                            <form action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" id="variant_select" name="variant_id" required>

                                <h1>{{ $product->name }}</h1>
                                <div class="product_ratting">
                                    <ul>
                                        @php $rating = $product->reviews->avg('rating') ?? 0; @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            <li><a href="#"><i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}"></i></a></li>
                                        @endfor
                                        <li class="review"><a href="#reviews"> ({{ $product->reviews->count() }} reviews) </a></li>
                                    </ul>
                                </div>
                                <div class="product_price" id="dynamic-price-container">
                                    @include('frontend.partials.product-price', ['product' => $product])
                                </div>
                                <div class="product_desc">
                                    <p>{{ $product->short_description }}</p>
                                </div>

                                @if($product->variants->count() > 0 && $product->variants->min('price') > 0)
                                    @php
                                        $uniqueSizes = $product->variants->pluck('sizeRelationship')->filter()->unique('id');
                                        $uniqueColors = $product->variants->pluck('colorRelationship')->filter()->unique('id');
                                    @endphp

                                    <div class="variant-group">
                                        <div class="variant-title">Size</div>
                                        <div class="variant-swatch-container">
                                            @foreach($uniqueSizes as $size)
                                                <button type="button" class="variant-swatch size-swatch" data-value="{{ $size->id }}">{{ $size->name }}</button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" id="select_size" name="size_id" value="">
                                    </div>

                                    <div class="variant-group">
                                        <div class="variant-title">Color</div>
                                        <div class="variant-swatch-container">
                                            @foreach($uniqueColors as $color)
                                                <button type="button" class="variant-swatch color-swatch" data-value="{{ $color->id }}">{{ $color->name }}</button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" id="select_color" name="color_id" value="">
                                    </div>

                                    <div id="variant-message" class="variant-msg"></div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const variants = @json($product->variants);
                                            const sizeInput = document.getElementById('select_size');
                                            const colorInput = document.getElementById('select_color');
                                            const variantInput = document.getElementById('variant_select');
                                            const msg = document.getElementById('variant-message');
                                            
                                            const addToCartBtn = document.querySelector('.btn-add-cart');
                                            const buyNowBtn = document.querySelector('.btn-buy-now');
                                            const priceContainer = document.getElementById('dynamic-price-container');
                                            const originalPriceHtml = priceContainer.innerHTML;

                                            const sizeSwatches = document.querySelectorAll('.size-swatch');
                                            const colorSwatches = document.querySelectorAll('.color-swatch');

                                            sizeSwatches.forEach(swatch => {
                                                swatch.addEventListener('click', function() {
                                                    if(this.classList.contains('active')) {
                                                        this.classList.remove('active');
                                                        sizeInput.value = '';
                                                    } else {
                                                        sizeSwatches.forEach(s => s.classList.remove('active'));
                                                        this.classList.add('active');
                                                        sizeInput.value = this.dataset.value;
                                                    }
                                                    checkSelection();
                                                });
                                            });

                                            colorSwatches.forEach(swatch => {
                                                swatch.addEventListener('click', function() {
                                                    if(this.classList.contains('active')) {
                                                        this.classList.remove('active');
                                                        colorInput.value = '';
                                                    } else {
                                                        colorSwatches.forEach(s => s.classList.remove('active'));
                                                        this.classList.add('active');
                                                        colorInput.value = this.dataset.value;
                                                    }
                                                    checkSelection();
                                                });
                                            });

                                            const formatCurrency = (amount) => {
                                                return new Intl.NumberFormat('en-US').format(amount) + ' VND';
                                            };

                                            function checkSelection() {
                                                const selectedSize = sizeInput.value;
                                                const selectedColor = colorInput.value;
                                                
                                                if (!selectedSize || !selectedColor) {
                                                    variantInput.value = '';
                                                    if(addToCartBtn) addToCartBtn.disabled = true;
                                                    if(buyNowBtn) buyNowBtn.disabled = true;
                                                    priceContainer.innerHTML = originalPriceHtml;
                                                    msg.style.display = 'none';
                                                    if(addToCartBtn) addToCartBtn.textContent = 'ADD TO CART';
                                                    return;
                                                }

                                                let filteredVariants = variants.filter(v => v.size_id == selectedSize && v.color_id == selectedColor);

                                                if (filteredVariants.length > 0) {
                                                    const matchedVariant = filteredVariants[0];
                                                    let html = '';

                                                    if (matchedVariant.sale_price && matchedVariant.sale_price > 0 && matchedVariant.sale_price < matchedVariant.price) {
                                                        html = `<span class="old_price">${formatCurrency(matchedVariant.price)}</span>
                                                                <span style="color: #ef233c;">${formatCurrency(matchedVariant.sale_price)}</span>`;
                                                    } else {
                                                        html = `<span>${formatCurrency(matchedVariant.price)}</span>`;
                                                    }

                                                    if (matchedVariant.stock_quantity > 0) {
                                                        variantInput.value = matchedVariant.id;
                                                        msg.style.display = 'none';
                                                        if(addToCartBtn) addToCartBtn.disabled = false;
                                                        if(buyNowBtn) buyNowBtn.disabled = false;
                                                        if(addToCartBtn) addToCartBtn.textContent = 'ADD TO CART';
                                                    } else {
                                                        variantInput.value = '';
                                                        msg.textContent = 'This variant is temporarily out of stock';
                                                        msg.style.display = 'block';
                                                        if(addToCartBtn) addToCartBtn.disabled = true;
                                                        if(buyNowBtn) buyNowBtn.disabled = true;
                                                        if(addToCartBtn) addToCartBtn.textContent = 'OUT OF STOCK';
                                                    }
                                                    priceContainer.innerHTML = html;
                                                } else {
                                                    variantInput.value = '';
                                                    msg.textContent = 'This combination is not available';
                                                    msg.style.display = 'block';
                                                    if(addToCartBtn) addToCartBtn.disabled = true;
                                                    if(buyNowBtn) buyNowBtn.disabled = true;
                                                    priceContainer.innerHTML = originalPriceHtml;
                                                }
                                            }

                                            if(addToCartBtn) addToCartBtn.disabled = true;
                                            if(buyNowBtn) buyNowBtn.disabled = true;
                                        });
                                    </script>
                                @endif

                                <div class="action-group">
                                    <div class="variant-title">Quantity</div>
                                    <div class="qty-wrapper">
                                        <button type="button" class="qty-btn" onclick="let input = document.getElementById('qty-input'); if(input.value > 1) input.value--;">-</button>
                                        <input type="number" id="qty-input" name="quantity" class="qty-input" value="1" min="1" max="100">
                                        <button type="button" class="qty-btn" onclick="let input = document.getElementById('qty-input'); if(input.value < 100) input.value++;">+</button>
                                    </div>
                                    
                                    <button class="btn-massive btn-add-cart" type="submit" name="action" value="add_to_cart">ADD TO CART</button>
                                    <button class="btn-massive btn-buy-now" type="submit" name="action" value="buy_now">BUY NOW</button>
                                </div>

                                <div class="product_d_action">
                                    <ul>
                                        <li>
                                            <a href="#" class="add-to-wishlist" data-id="{{ $product->id }}" title="Add to wishlist">
                                                <i class="fa fa-heart-o" aria-hidden="true"></i> Add to Wish List
                                            </a>
                                        </li>
                                        <li><a href="#" title="Add to Compare"><i class="fa fa-sliders" aria-hidden="true"></i> Compare</a></li>
                                    </ul>
                                </div>

                            </form>
                            
                            <div class="priduct_social">
                                <h3>Share on:</h3>
                                <ul>
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--product details end-->"""

new_product_info = """    <!--product info start-->
    <style>
        .product_d_info { background: #fff; padding: 60px 0; border-top: 1px solid #eee; }
        .custom-tabs { border-bottom: 1px solid #ddd; margin-bottom: 40px; display: flex; justify-content: center; gap: 60px; }
        .custom-tabs li a { font-size: 14px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #999; padding-bottom: 20px; border-bottom: 2px solid transparent; display: block; position: relative; top: 1px; transition: all 0.3s; }
        .custom-tabs li a.active, .custom-tabs li a:hover { color: #111; border-bottom: 2px solid #111; }
        .product_info_content { max-width: 800px; margin: 0 auto; }
        .product_info_content p { font-size: 16px; line-height: 1.8; color: #555; margin-bottom: 20px; }
        .review-box { padding: 30px; border: 1px solid #eee; margin-bottom: 20px; background: #fff; }
        .review-author { font-size: 14px; font-weight: 800; text-transform: uppercase; color: #111; margin-bottom: 5px; display: flex; align-items: center; justify-content: space-between; }
        .review-stars { color: #f6b500; }
        .review-date { font-size: 12px; color: #999; margin-bottom: 15px; }
        .review-form-wrapper { margin-top: 50px; border-top: 1px solid #eee; padding-top: 40px; }
        .review-form-wrapper h2 { font-size: 18px; font-weight: 800; text-transform: uppercase; margin-bottom: 20px; letter-spacing: 1px; }
    </style>
    <div class="product_d_info">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="product_d_inner">
                        <div class="product_info_button">
                            <ul class="nav custom-tabs" role="tablist">
                                <li>
                                    <a class="active" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Description</a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews ({{ $product->reviews->count() }})</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="info" role="tabpanel">
                                <div class="product_info_content">
                                    <p>{!! nl2br(e($product->description)) !!}</p>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="reviews" role="tabpanel">
                                <div class="product_info_content">
                                    @if($product->reviews->count() > 0)
                                        @foreach($product->reviews as $review)
                                            <div class="review-box">
                                                <div class="review-author">
                                                    <span>{{ $review->user->name ?? 'Guest' }}</span>
                                                    <div class="review-stars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fa {{ $i <= $review->rating ? 'fa-star' : 'fa-star-o' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="review-date">{{ $review->created_at->format('M d, Y') }}</div>
                                                <div class="product_demo">
                                                    <p style="margin-bottom: 0;">{{ $review->comment }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p style="text-align: center; color: #999; margin: 40px 0;">No reviews yet.</p>
                                    @endif

                                    <div class="review-form-wrapper">
                                        <form action="#">
                                            <h2>Add a review</h2>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label for="review_comment" style="font-weight: 700; text-transform: uppercase; font-size: 12px; margin-bottom: 10px; display: block;">Your review</label>
                                                    <textarea name="comment" id="review_comment" style="width: 100%; border: 1px solid #ddd; padding: 15px; height: 120px; margin-bottom: 20px; outline: none; border-radius: 0;" placeholder="Write your thoughts here..."></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn-massive btn-add-cart" style="max-width: 250px;">Submit Review</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--product info end-->"""

content_updated = re.sub(r'<!--product details start-->.*?<!--product details end-->', new_product_details, content, flags=re.DOTALL)
content_updated = re.sub(r'<!--product info start-->.*?<!--product info end-->', new_product_info, content_updated, flags=re.DOTALL)

with open('resources/views/frontend/products/show.blade.php', 'w', encoding='utf-8') as f:
    f.write(content_updated)

print('Updated successfully')
