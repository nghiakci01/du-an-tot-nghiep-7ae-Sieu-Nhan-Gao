@extends('layouts.public')

@section('title', 'Lịch sử thử đồ AI | Elite')

@section('content')
<div class="breadcrumbs_area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="{{ route('welcome') }}">Trang chủ</a></li>
                        <li>/</li>
                        <li>Lịch sử thử đồ AI</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="vton_history_area mt-60 mb-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section_title text-center mb-40">
                    <h2 style="font-weight: 800; text-transform: uppercase; letter-spacing: 2px;">Phòng Thử Đồ Cá Nhân</h2>
                    <p style="color: #666;">Xem lại các bộ trang phục bạn đã thử bằng công nghệ AI thông minh</p>
                    <div style="width: 50px; height: 3px; background: #ef233c; margin: 20px auto;"></div>
                </div>
            </div>
        </div>
        
        @if($histories->count() > 0)
            <div class="row">
                @foreach($histories as $history)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-30">
                        <div class="history_card shadow-sm" style="background: #fff; border-radius: 12px; overflow: hidden; height: 100%; display: flex; flex-direction: column; transition: transform 0.3s; border: 1px solid #f0f0f0;">
                            <div class="history_img" style="position: relative; padding-top: 135%; overflow: hidden; background: #f8f8f8;">
                                <img src="{{ asset('storage/' . $history->result_image) }}" alt="Result" 
                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;"
                                    onmouseover="this.style.transform='scale(1.05)'"
                                    onmouseout="this.style.transform='scale(1)'">
                                
                                <div class="history_actions" style="position: absolute; top: 10px; right: 10px; display: flex; flex-direction: column; gap: 8px;">
                                    <form action="{{ route('vton.history.destroy', $history->id) }}" method="POST" onsubmit="return confirm('Xóa mục này khỏi lịch sử?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-round" title="Xóa">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{ asset('storage/' . $history->result_image) }}" download="vton-result.jpg" class="btn-action-round" title="Tải xuống">
                                        <i class="fa fa-download"></i>
                                    </a>
                                </div>

                                @if($history->vtonModel)
                                <div style="position: absolute; bottom: 10px; left: 10px;">
                                    <span style="background: rgba(0,0,0,0.6); color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px; backdrop-filter: blur(4px);">
                                        Model: {{ $history->vtonModel->name }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            <div class="history_content p-3" style="flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <h4 style="font-size: 14px; margin-bottom: 5px; font-weight: 700; color: #111; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $history->product->name }}
                                    </h4>
                                    <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 12px;">
                                        <i class="fa fa-clock-o" style="font-size: 11px; color: #999;"></i>
                                        <p style="font-size: 11px; color: #999; margin: 0;">{{ $history->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <a href="{{ route('product.details', $history->product->slug) }}" class="btn btn-outline-dark btn-sm rounded-pill" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 8px;">
                                        Sắm Ngay
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="shop_toolbar t_bottom mt-30 d-flex justify-content-center">
                {{ $histories->links() }}
            </div>
        @else
            <div class="row">
                <div class="col-12 text-center py-5">
                    <div style="font-size: 80px; color: #f0f0f0; margin-bottom: 25px; animation: float 3s ease-in-out infinite;">
                        <i class="fa fa-magic vton-magic-icon"></i>
                    </div>
                    <h3 style="font-weight: 700; color: #333;">Lịch sử trống</h3>
                    <p style="color: #888; max-width: 400px; margin: 0 auto 30px;">Bạn chưa thực hiện thử đồ AI nào. Hãy chọn một sản phẩm yêu thích và bắt đầu trải nghiệm ngay!</p>
                    <a href="{{ route('shop') }}" class="btn btn-danger btn-lg rounded-pill px-5 shadow" style="background: #ef233c; border: none; font-weight: 700;">Khám Phá Cửa Hàng</a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .btn-action-round {
        background: rgba(255,255,255,0.9);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #333;
        font-size: 13px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none !important;
    }
    .btn-action-round:hover {
        background: #ef233c;
        color: #fff;
        transform: scale(1.1);
    }
    .history_card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
        border-color: #ef233c !important;
    }
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }
    .vton-magic-icon {
        background: linear-gradient(45deg, #833ab4, #fd1d1d, #fcb045);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .pagination {
        display: flex;
        gap: 5px;
    }
    .pagination li span, .pagination li a {
        padding: 8px 16px;
        border-radius: 50%;
        color: #333;
    }
    .pagination li.active span {
        background: #ef233c;
        color: #fff;
    }
</style>
@endsection
