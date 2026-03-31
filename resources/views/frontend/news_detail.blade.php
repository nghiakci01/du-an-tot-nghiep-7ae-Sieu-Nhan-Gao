@extends('layouts.public')

@section('title', $post->title . ' | Elite')

@section('content')
    <style>
        .atino-blog-detail {
            background-color: #fff;
            padding-bottom: 60px;
        }
        .atino-breadcrumb {
            background: #f8f9fa;
            padding: 15px 0;
            margin-bottom: 30px;
        }
        .atino-breadcrumb ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .atino-breadcrumb ul li {
            font-size: 14px;
            color: #6c757d;
        }
        .atino-breadcrumb ul li a {
            color: #333;
            text-decoration: none;
            transition: 0.3s;
        }
        .atino-breadcrumb ul li a:hover {
            color: #ff6a28;
        }
        .atino-breadcrumb ul li.separator {
            margin: 0 10px;
            color: #ccc;
        }
        .atino-post-header {
            text-align: center;
            max-width: 900px;
            margin: 0 auto 40px;
        }
        .atino-post-title {
            font-size: 36px;
            line-height: 1.3;
            font-weight: 700;
            color: #222;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif; /* Phù hợp blog thời trang */
        }
        .atino-post-meta {
            font-size: 14px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }
        .atino-post-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .atino-post-thumbnail {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto 40px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .atino-post-thumbnail img {
            width: 100%;
            height: auto;
            max-height: 550px;
            object-fit: cover;
            display: block;
        }
        .atino-post-content {
            max-width: 800px;
            margin: 0 auto;
            font-size: 18px;
            line-height: 1.8;
            color: #444;
        }
        .atino-post-content p {
            margin-bottom: 25px;
        }
        .atino-post-content h2, .atino-post-content h3, .atino-post-content h4 {
            color: #222;
            margin-top: 40px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .atino-post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .atino-post-content blockquote {
            border-left: 4px solid #ff6a28;
            padding-left: 20px;
            margin: 30px 0;
            font-style: italic;
            color: #555;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 4px;
        }
        .atino-divider {
            max-width: 800px;
            margin: 60px auto;
            border: 0;
            height: 1px;
            background: #eee;
        }
        .atino-related-section {
            background-color: #fcfcfc;
            padding: 60px 0;
            border-top: 1px solid #f1f1f1;
        }
        .atino-section-title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 40px;
            color: #222;
            text-transform: uppercase;
        }
        
        /* Box Related Post giống màn news */
        .single_blog {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            height: 100%;
        }
        .single_blog:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            transform: translateY(-5px);
        }
        .blog_thumb {
            overflow: hidden;
        }
        .blog_thumb img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .single_blog:hover .blog_thumb img {
            transform: scale(1.05);
        }
        .blog_content {
            padding: 20px;
        }
        .blog_content .post_title {
            font-size: 18px;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 10px;
            height: 50px;
            overflow: hidden;
        }
        .blog_content .post_title a {
            color: #222;
            text-decoration: none;
            transition: color 0.3s;
        }
        .blog_content .post_title a:hover {
            color: #ff6a28;
        }
        .blog_content .articles_date {
            font-size: 12px;
            color: #888;
            margin-bottom: 15px;
        }
        .blog_content .articles_date a {
            color: #ff6a28;
        }
    </style>

    <div class="atino-blog-detail">
        <!-- Breadcrumbs -->
        <div class="atino-breadcrumb">
            <div class="container border-bottom-0">
                <ul>
                    <li><a href="{{ route('welcome') }}"><i class="fa fa-home"></i> Trang chủ</a></li>
                    <li class="separator">/</li>
                    <li><a href="{{ route('news') }}">Tin tức</a></li>
                    <li class="separator">/</li>
                    <li>{{ $post->title }}</li>
                </ul>
            </div>
        </div>

        <div class="container">
            <!-- Header bài viết -->
            <div class="atino-post-header">
                <h1 class="atino-post-title">{{ $post->title }}</h1>
                <div class="atino-post-meta">
                    <span><i class="fa fa-calendar-o"></i> {{ $post->created_at->format('d/m/Y') }}</span>
                    <span><i class="fa fa-folder-o"></i> {{ $post->category ? $post->category->name : 'Tin tức' }}</span>
                    <span><i class="fa fa-user-o"></i> Admin</span>
                </div>
            </div>

            <!-- Ảnh đại diện -->
            @if($post->image)
            <div class="atino-post-thumbnail">
                <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}">
            </div>
            @endif

            <!-- Nội dung bài viết -->
            <div class="atino-post-content">
                {!! $post->content !!}
            </div>

            {{-- ===== HIDDEN VOUCHER WIDGET ===== --}}
            @if($post->coupon && $post->coupon->is_active)
            @php
                $coupon = $post->coupon;
                $isExhausted = $coupon->hasReachedUsageLimit();
                $isExpired = $coupon->isExpired();
                $remaining = $coupon->remainingClaims();
            @endphp
            <div class="voucher-widget-wrapper" id="voucher-widget">
                <div class="voucher-widget {{ $hasClaimed ? 'claimed' : '' }} {{ $isExhausted ? 'exhausted' : '' }}">
                    <div class="voucher-widget-header">
                        @if($hasClaimed)
                            <span class="voucher-icon">🎉</span>
                            <span class="voucher-title">BẠN ĐÃ NHẬN MÃ THÀNH CÔNG!</span>
                        @elseif($isExhausted || $isExpired)
                            <span class="voucher-icon">😔</span>
                            <span class="voucher-title">MÃ GIẢM GIÁ ĐÃ HẾT</span>
                        @else
                            <span class="voucher-icon">🎁</span>
                            <span class="voucher-title">ƯU ĐÃI ĐẶC BIỆT DÀNH CHO BẠN</span>
                        @endif
                    </div>

                    <div class="voucher-card">
                        <div class="voucher-code-area">
                            @if($hasClaimed)
                                <span class="voucher-code revealed" id="voucher-code-text">{{ $coupon->code }}</span>
                                <button class="btn-copy-code" onclick="copyVoucherCode()" title="Sao chép mã">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            @elseif($isExhausted || $isExpired)
                                <span class="voucher-code exhausted-text">HẾT MÃ</span>
                            @else
                                <span class="voucher-code blurred" id="voucher-code-text">{{ str_repeat('●', strlen($coupon->code)) }}</span>
                                <button class="btn-reveal-voucher" id="btn-claim-voucher"
                                    data-coupon-id="{{ $coupon->id }}"
                                    data-source="news"
                                    data-source-id="{{ $post->id }}"
                                    @guest data-require-login="true" @endguest>
                                    <i class="bi bi-gift-fill me-1"></i> Khám phá mã giảm giá
                                </button>
                            @endif
                        </div>

                        <div class="voucher-info">
                            <p class="voucher-value">
                                <i class="bi bi-tag-fill"></i>
                                Giảm <strong>{{ $coupon->getFormattedValue() }}</strong>
                                @if($coupon->min_order_amount)
                                    cho đơn từ {{ number_format($coupon->min_order_amount, 0, ',', '.') }}đ
                                @endif
                            </p>
                            @if($coupon->description)
                                <p class="voucher-desc">{{ $coupon->description }}</p>
                            @endif
                            @if($hasClaimed)
                                <p class="voucher-status-saved"><i class="bi bi-check-circle-fill"></i> Đã lưu vào tài khoản</p>
                            @endif
                        </div>

                        <div class="voucher-meta">
                            @if($coupon->end_date)
                                <span><i class="bi bi-clock"></i> HSD: {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</span>
                            @endif
                            @if($remaining !== null && !$isExhausted)
                                <span><i class="bi bi-people"></i> Còn {{ $remaining }} lượt</span>
                            @endif
                        </div>

                        @if($hasClaimed)
                        <div class="voucher-use-hint">
                            <i class="bi bi-arrow-right-circle me-1"></i> Dùng tại <a href="{{ route('cart.index') }}">Giỏ hàng</a> hoặc <a href="{{ route('checkout.index') }}">Thanh toán</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <hr class="atino-divider">
            
            <div class="text-center mb-5">
                <a href="{{ route('news') }}" class="btn btn-dark px-4 py-2" style="border-radius: 30px; letter-spacing: 1px; text-transform: uppercase; font-size: 14px;">Kho lưu trữ bài viết</a>
            </div>
        </div>
    </div>

    <!-- Tin tức liên quan (Grid layout under content) -->
    @if($relatedPosts->count() > 0)
    <div class="atino-related-section">
        <div class="container">
            <h3 class="atino-section-title">Tin Tức Liên Quan</h3>
            <div class="row">
                @foreach($relatedPosts as $related)
                <div class="col-lg-4 col-md-6 mb-4">
                    <article class="single_blog">
                        <figure>
                            <div class="blog_thumb">
                                <a href="{{ route('news.detail', $related->slug) }}">
                                    <img src="{{ $related->image ? asset('storage/'.$related->image) : asset('frontend-assets/img/blog/blog1.jpg') }}" alt="{{ $related->title }}">
                                </a>
                                @if($related->coupon && $related->coupon->is_active && !$related->coupon->hasReachedUsageLimit())
                                <span class="voucher-badge-card"><i class="bi bi-gift-fill"></i> Có ưu đãi</span>
                                @endif
                            </div>
                            <figcaption class="blog_content">
                                <h4 class="post_title">
                                    <a href="{{ route('news.detail', $related->slug) }}">{{ $related->title }}</a>
                                </h4>
                                <div class="articles_date">
                                    <p>{{ $related->created_at->format('d/m/Y') }} | <a href="#">{{ $related->category ? $related->category->name : 'Tin tức' }}</a></p>
                                </div>
                            </figcaption>
                        </figure>
                    </article>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@endsection

@push('styles')
<style>
/* ===== VOUCHER WIDGET ===== */
.voucher-widget-wrapper {
    max-width: 800px;
    margin: 50px auto 10px;
    animation: fadeInUp 0.6s ease;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.voucher-widget {
    border: 2px dashed #1a1a2e;
    border-radius: 16px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9ff 0%, #eef1ff 100%);
    position: relative;
}
.voucher-widget.exhausted {
    border-color: #ccc;
    background: #f5f5f5;
    opacity: 0.7;
}
.voucher-widget-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #2d2d5e 100%);
    color: white;
    padding: 14px 24px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.voucher-widget.exhausted .voucher-widget-header {
    background: #999;
}
.voucher-icon { font-size: 1.4rem; }
.voucher-title {
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}
.voucher-card {
    padding: 24px;
}
.voucher-code-area {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}
.voucher-code {
    font-size: 1.8rem;
    font-weight: 900;
    letter-spacing: 3px;
    color: #1a1a2e;
    font-family: 'Courier New', monospace;
}
.voucher-code.blurred {
    filter: blur(6px);
    user-select: none;
    color: #555;
    letter-spacing: 5px;
}
.voucher-code.revealed {
    animation: revealCode 0.5s ease;
}
.voucher-code.exhausted-text {
    color: #999;
    font-size: 1.4rem;
}
@keyframes revealCode {
    from { filter: blur(6px); opacity: 0.5; }
    to { filter: blur(0); opacity: 1; }
}
.btn-reveal-voucher {
    background: linear-gradient(135deg, #1a1a2e 0%, #2d2d5e 100%);
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
}
.btn-reveal-voucher:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(26, 26, 46, 0.3);
}
.btn-reveal-voucher:active {
    transform: translateY(0);
}
.btn-copy-code {
    background: #eee;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 8px 14px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 1.1rem;
    color: #555;
}
.btn-copy-code:hover {
    background: #1a1a2e;
    color: white;
    border-color: #1a1a2e;
}
.voucher-info p {
    margin: 0 0 6px;
    font-size: 0.95rem;
    color: #444;
}
.voucher-info .voucher-value i { color: #e74c3c; }
.voucher-desc {
    color: #777 !important;
    font-size: 0.85rem !important;
}
.voucher-status-saved {
    color: #28a745 !important;
    font-weight: 600;
    font-size: 0.9rem !important;
}
.voucher-meta {
    display: flex;
    gap: 20px;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid #e8e8e8;
    font-size: 0.8rem;
    color: #888;
}
.voucher-meta span { display: flex; align-items: center; gap: 5px; }
.voucher-use-hint {
    margin-top: 12px;
    padding: 10px 14px;
    background: #f0f8e8;
    border-radius: 8px;
    font-size: 0.85rem;
    color: #555;
}
.voucher-use-hint a {
    color: #1a1a2e;
    font-weight: 600;
    text-decoration: underline;
}

/* Badge on news cards */
.voucher-badge-card {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    color: white;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    z-index: 2;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.4); }
    50% { box-shadow: 0 0 0 6px rgba(231, 76, 60, 0); }
}
.blog_thumb { position: relative; }

/* Confetti canvas */
#confetti-canvas {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    pointer-events: none;
    z-index: 9999;
}

@media (max-width: 576px) {
    .voucher-code { font-size: 1.2rem; letter-spacing: 2px; }
    .voucher-code-area { flex-direction: column; align-items: flex-start; }
    .btn-reveal-voucher { width: 100%; text-align: center; }
    .voucher-meta { flex-direction: column; gap: 6px; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const claimBtn = document.getElementById('btn-claim-voucher');
    if (!claimBtn) return;

    claimBtn.addEventListener('click', function() {
        // Check if guest needs login
        if (this.dataset.requireLogin === 'true') {
            const currentUrl = window.location.href;
            window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(currentUrl);
            return;
        }

        const couponId = this.dataset.couponId;
        const source = this.dataset.source;
        const sourceId = this.dataset.sourceId;

        // Disable button
        this.disabled = true;
        this.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Đang xử lý...';

        fetch('{{ route("voucher.claim") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                coupon_id: couponId,
                source: source,
                source_id: sourceId,
            }),
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            if (body.success) {
                // Success — reveal code
                const codeEl = document.getElementById('voucher-code-text');
                codeEl.textContent = body.coupon_code;
                codeEl.classList.remove('blurred');
                codeEl.classList.add('revealed');

                // Replace button with copy button
                claimBtn.outerHTML = `
                    <button class="btn-copy-code" onclick="copyVoucherCode()" title="Sao chép mã">
                        <i class="bi bi-clipboard"></i>
                    </button>
                `;

                // Update header
                const widget = document.querySelector('.voucher-widget');
                widget.classList.add('claimed');
                const header = widget.querySelector('.voucher-widget-header');
                header.innerHTML = '<span class="voucher-icon">🎉</span><span class="voucher-title">BẠN ĐÃ NHẬN MÃ THÀNH CÔNG!</span>';

                // Add saved status
                const infoArea = widget.querySelector('.voucher-info');
                const savedP = document.createElement('p');
                savedP.className = 'voucher-status-saved';
                savedP.innerHTML = '<i class="bi bi-check-circle-fill"></i> Đã lưu vào tài khoản';
                infoArea.appendChild(savedP);

                // Add use hint
                const card = widget.querySelector('.voucher-card');
                const hintDiv = document.createElement('div');
                hintDiv.className = 'voucher-use-hint';
                hintDiv.innerHTML = '<i class="bi bi-arrow-right-circle me-1"></i> Dùng tại <a href="{{ route("cart.index") }}">Giỏ hàng</a> hoặc <a href="{{ route("checkout.index") }}">Thanh toán</a>';
                card.appendChild(hintDiv);

                // Fire confetti
                fireConfetti();

            } else if (body.already_claimed) {
                // Already claimed
                const codeEl = document.getElementById('voucher-code-text');
                codeEl.textContent = body.coupon_code;
                codeEl.classList.remove('blurred');
                codeEl.classList.add('revealed');
                claimBtn.outerHTML = `
                    <button class="btn-copy-code" onclick="copyVoucherCode()" title="Sao chép mã">
                        <i class="bi bi-clipboard"></i>
                    </button>
                `;
            } else {
                // Error
                claimBtn.disabled = false;
                claimBtn.innerHTML = '<i class="bi bi-gift-fill me-1"></i> Khám phá mã giảm giá';
                showToast(body.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(err => {
            claimBtn.disabled = false;
            claimBtn.innerHTML = '<i class="bi bi-gift-fill me-1"></i> Khám phá mã giảm giá';
            showToast('Không thể kết nối. Vui lòng thử lại.', 'error');
        });
    });
});

function copyVoucherCode() {
    const code = document.getElementById('voucher-code-text').textContent;
    navigator.clipboard.writeText(code).then(() => {
        showToast('Đã sao chép mã: ' + code, 'success');
    });
}

function showToast(msg, type) {
    const toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:' + (type === 'success' ? '#1a1a2e' : '#e74c3c') + ';color:white;padding:14px 22px;border-radius:12px;font-size:0.9rem;font-weight:600;z-index:10000;box-shadow:0 8px 30px rgba(0,0,0,0.2);animation:fadeInUp 0.3s ease;';
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; }, 2500);
    setTimeout(() => toast.remove(), 3000);
}

function fireConfetti() {
    const canvas = document.createElement('canvas');
    canvas.id = 'confetti-canvas';
    document.body.appendChild(canvas);
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const pieces = [];
    const colors = ['#e74c3c', '#f39c12', '#2ecc71', '#3498db', '#9b59b6', '#1abc9c', '#e67e22'];

    for (let i = 0; i < 100; i++) {
        pieces.push({
            x: Math.random() * canvas.width,
            y: -20 - Math.random() * 100,
            w: 6 + Math.random() * 6,
            h: 4 + Math.random() * 4,
            color: colors[Math.floor(Math.random() * colors.length)],
            vx: (Math.random() - 0.5) * 4,
            vy: 2 + Math.random() * 4,
            rotation: Math.random() * 360,
            rotSpeed: (Math.random() - 0.5) * 10,
        });
    }

    let frame = 0;
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        pieces.forEach(p => {
            p.x += p.vx;
            p.y += p.vy;
            p.vy += 0.05;
            p.rotation += p.rotSpeed;
            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate(p.rotation * Math.PI / 180);
            ctx.fillStyle = p.color;
            ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
            ctx.restore();
        });
        frame++;
        if (frame < 120) {
            requestAnimationFrame(animate);
        } else {
            canvas.remove();
        }
    }
    animate();
}
</script>
@endpush
