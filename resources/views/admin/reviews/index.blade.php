@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Quản lý Đánh giá</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Đánh giá</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar avtar-s bg-light-primary">
                            <i class="ti ti-message-2 f-20"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Tổng đánh giá</h6>
                        <h4 class="mb-0 fw-bold">{{ number_format($stats['total']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar avtar-s bg-light-warning">
                            <i class="ti ti-star f-20 text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Trung bình (All Time)</h6>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-0 fw-bold me-2">{{ $stats['average'] }}</h4>
                            <div class="text-warning small">
                                @php $fullStars = floor($stats['average']); $hasHalf = ($stats['average'] - $fullStars) >= 0.5; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $fullStars)
                                        <i class="fas fa-star"></i>
                                    @elseif($i == $fullStars + 1 && $hasHalf)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar avtar-s bg-light-danger">
                            <i class="ti ti-alert-triangle f-20 text-danger"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Phản hồi tiêu cực (1-2★)</h6>
                        <h4 class="mb-0 fw-bold text-danger">{{ number_format($stats['critical']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">Danh sách Đánh giá</h5>
                </div>
                
                <!-- Filters Bar -->
                <form action="{{ route('admin.reviews.index') }}" method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <div class="input-group border rounded bg-light">
                                <span class="input-group-text bg-transparent border-0"><i class="ti ti-search m-0"></i></span>
                                <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Tìm người dùng hoặc sản phẩm..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="rating" class="form-select border-0 bg-light">
                                <option value="">Tất cả sao</option>
                                @for($i=5; $i>=1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sort" class="form-select border-0 bg-light">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>Sao cao</option>
                                <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>Sao thấp</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1 shadow-none">Lọc</button>
                            @if(request()->hasAny(['search', 'rating', 'sort']))
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-light-secondary px-3" title="Xóa lọc"><i class="ti ti-x m-0"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card-body px-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-top">
                        <thead class="bg-light-secondary border-0">
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th style="width: 200px;">Khách hàng</th>
                                <th style="width: 200px;">Sản phẩm</th>
                                <th style="width: 120px;">Xếp hạng</th>
                                <th>Nội dung bình luận</th>
                                <th style="width: 150px;">Thời gian</th>
                                <th class="sticky-action-column text-center" style="width: 80px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                            <tr>
                                <td class="text-muted">{{ $review->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $review->user ? $review->user->avatar_url : asset('assets/images/default-avatar.png') }}" 
                                                 alt="user" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 fw-bold">{{ $review->user->name ?? 'Guest' }}</h6>
                                            <small class="text-muted text-truncate d-block" style="max-width: 150px;">{{ $review->user->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($review->product)
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <a href="{{ route('product.detail', $review->product->slug) }}" target="_blank" class="text-dark fw-bold small text-truncate d-block" style="max-width: 180px;">
                                                    {{ $review->product->name }}
                                                </a>
                                                <small class="text-muted" style="font-size: 0.7rem;">ID: #{{ $review->product->id }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge bg-light-danger text-danger">Đã xóa sản phẩm</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fas fa-star' : 'far fa-star' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="fw-bold ms-1">{{ $review->rating }} <i class="ti ti-star"></i></span>
                                </td>
                                <td class="text-start">
                                    <div class="review-content" style="max-width: 400px;">
                                        @if(strlen($review->comment) > 120)
                                            <span class="short-comment">{{ \Str::limit($review->comment, 120) }}</span>
                                            <a href="javascript:void(0)" class="text-primary small fw-bold ms-1 read-more-btn" 
                                               data-fulltext="{{ $review->comment }}">Xem thêm</a>
                                        @else
                                            {{ $review->comment }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column" style="font-size: 0.8rem;">
                                        <span class="text-dark"><i class="ti ti-calendar me-1"></i>{{ $review->created_at->format('d/m/Y') }}</span>
                                        <span class="text-muted small"><i class="ti ti-clock me-1"></i>{{ $review->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="sticky-action-column text-center">
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline no-pjax" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light-danger btn-sm p-1 px-2 border-0 shadow-none" title="Xóa">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ti ti-file-off f-30 mb-2"></i>
                                        <p>Không tìm thấy đánh giá nào phù hợp với bộ lọc của bạn.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    {{ $reviews->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Read More Modal -->
<div class="modal fade" id="readMoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold">Nội dung đánh giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p id="fullReviewText" class="mb-0" style="line-height: 1.6; white-space: pre-line;"></p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.read-more-btn').on('click', function() {
        const fullText = $(this).data('fulltext');
        $('#fullReviewText').text(fullText);
        $('#readMoreModal').modal('show');
    });
});
</script>
@endsection
