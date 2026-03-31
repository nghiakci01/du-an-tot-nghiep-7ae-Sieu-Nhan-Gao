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

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Danh sách Đánh giá</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người dùng</th>
                                <th>Sản phẩm</th>
                                <th>Đánh giá</th>
                                <th>Nội dung</th>
                                <th>Ngày tạo</th>
                                <th class="sticky-action-column">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($reviews) > 0)
                                @foreach($reviews as $review) @php /** @var \App\Models\Review $review */ @endphp
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>
                                    <strong>{{ $review->user->name ?? 'Guest' }}</strong><br>
                                    <small>{{ $review->user->email ?? '' }}</small>
                                </td>
                                <td>
                                    @if($review->product)
                                        <a href="{{ route('product.detail', $review->product->slug) }}" target="_blank">
                                            {{ $review->product->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa {{ $i <= $review->rating ? 'fa-star' : 'fa-star-o' }}"></i>
                                        @endfor
                                    </div>
                                    <small>({{ $review->rating }} sao)</small>
                                </td>
                                <td class="text-start" style="max-width: 300px;">
                                    {{ \Illuminate\Support\Str::limit($review->comment, 100) }}
                                </td>
                                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                <td class="sticky-action-column">
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="feather icon-trash-2"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="7" class="text-center">Chưa có đánh giá nào.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
