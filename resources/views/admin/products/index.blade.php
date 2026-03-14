@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Quản lý Sản phẩm</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Sản phẩm</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Danh sách Sản phẩm</h5>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên sản phẩm..." value="{{ request('search') }}">
                            <select name="category_id" class="form-select" style="max-width: 200px;">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary px-3"><i class="ti ti-search m-0"></i></button>
                            @if(request()->hasAny(['search', 'category_id']))
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-3" title="Xóa bộ lọc"><i class="ti ti-x m-0"></i></a>
                            @endif
                        </div>
                    </form>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Biến thể</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                    width="50">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $product->name }} <br>
                                            <small>{{ $product->slug }}</small>
                                        </td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($product->variants->isNotEmpty())
                                                @php
                                                    $minPrice = $product->variants->min('price');
                                                    $maxPrice = $product->variants->max('price');
                                                @endphp
                                                @if($minPrice == $maxPrice)
                                                    {{ number_format($minPrice) }} đ
                                                @else
                                                    {{ number_format($minPrice) }} - {{ number_format($maxPrice) }} đ
                                                @endif
                                            @else
                                                {{ number_format($product->price) }} đ
                                            @endif
                                        </td>
                                        <td><span class="badge bg-info">{{ $product->variants_count }} variants</span></td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                                class="btn btn-warning btn-sm">Sửa</a>
                                            
                                            <button type="button" 
                                                    class="btn {{ $product->vton_image ? 'btn-success' : 'btn-info' }} btn-sm btn-generate-vton" 
                                                    data-id="{{ $product->id }}"
                                                    data-vton-image="{{ $product->vton_image ? asset('storage/' . $product->vton_image) : '' }}"
                                                    title="{{ $product->vton_image ? 'Đã có ảnh mẫu AI - Nhấn để xem hoặc tạo lại' : 'Tạo ảnh người mẫu AI cho sản phẩm này' }}">
                                                <i class="ti ti-{{ $product->vton_image ? 'circle-check' : 'photo' }} me-1"></i>
                                                AI Model
                                            </button>

                                            <form id="delete-form-prod-{{ $product->id }}"
                                                action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('delete-form-prod-{{ $product->id }}')">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <div class="modal fade" id="vtonPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">AI Model Generation - <span id="modalProductName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="vtonCurrentPreview" class="mb-3 d-none">
                        <h6>Bản xem trước hiện tại:</h6>
                        <img id="vtonPreviewImg" src="" alt="VTON Preview" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                        <hr>
                    </div>
                    
                    <div id="vtonProcessingUI" class="d-none">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mb-0" id="vtonStatusText">Đang chuẩn bị gửi yêu cầu tới AI...</p>
                        <small class="text-muted">Quá trình này có thể mất 1-2 phút. Vui lòng không đóng trình duyệt.</small>
                    </div>

                    <div id="vtonStartUI">
                        <p>Hệ thống sẽ tự động chọn người mẫu phù hợp (Nam/Nữ) dựa trên danh mục của sản phẩm này.</p>
                        <button type="button" class="btn btn-primary" id="btnStartGeneration">
                            <i class="ti ti-rocket me-1"></i> Bắt đầu tạo ngay
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('vtonPreviewModal'));
            const modalTitle = document.getElementById('modalProductName');
            const previewContainer = document.getElementById('vtonCurrentPreview');
            const previewImg = document.getElementById('vtonPreviewImg');
            const startUI = document.getElementById('vtonStartUI');
            const processingUI = document.getElementById('vtonProcessingUI');
            const statusText = document.getElementById('vtonStatusText');
            const startBtn = document.getElementById('btnStartGeneration');

            let currentProductId = null;
            let pollInterval = null;

            document.querySelectorAll('.btn-generate-vton').forEach(btn => {
                btn.addEventListener('click', function() {
                    currentProductId = this.dataset.id;
                    const productName = this.closest('tr').querySelector('td:nth-child(3)').innerText.split('\n')[0];
                    const existingImage = this.dataset.vtonImage;

                    modalTitle.innerText = productName;
                    
                    if (existingImage) {
                        previewImg.src = existingImage;
                        previewContainer.classList.remove('d-none');
                    } else {
                        previewContainer.classList.add('d-none');
                    }

                    startUI.classList.remove('d-none');
                    processingUI.classList.add('d-none');
                    modal.show();
                });
            });

            startBtn.addEventListener('click', function() {
                startUI.classList.add('d-none');
                processingUI.classList.remove('d-none');
                statusText.innerText = 'Đang gửi yêu cầu lên máy chủ AI...';

                fetch("{{ route('admin.vton.generate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ product_id: currentProductId })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        statusText.innerText = 'AI đang xử lý... (khoảng 1-2 phút)';
                        startPolling(currentProductId);
                    } else {
                        Swal.fire('Lỗi', data.message || 'Không thể bắt đầu tạo mẫu.', 'error');
                        processingUI.classList.add('d-none');
                        startUI.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    Swal.fire('Lỗi', 'Đã xảy ra lỗi kết nối.', 'error');
                    console.error(err);
                    processingUI.classList.add('d-none');
                    startUI.classList.remove('d-none');
                });
            });

            function startPolling(id) {
                if (pollInterval) clearInterval(pollInterval);
                let pollCount = 0;
                const maxPolls = 60; // 60 * 5s = 5 minutes max
                
                pollInterval = setInterval(() => {
                    pollCount++;
                    if (pollCount > maxPolls) {
                        clearInterval(pollInterval);
                        Swal.fire('Hết thời gian', 'AI xử lý quá lâu. Vui lòng thử lại.', 'warning');
                        processingUI.classList.add('d-none');
                        startUI.classList.remove('d-none');
                        return;
                    }

                    // Update status text with dots animation
                    const dots = '.'.repeat((pollCount % 3) + 1);
                    statusText.innerText = `AI đang xử lý${dots} (${pollCount * 5}s)`;

                    fetch(`/admin/vton/status/${currentProductId}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'completed') {
                            clearInterval(pollInterval);
                            statusText.innerText = 'Hoàn tất!';
                            previewImg.src = data.result_url;
                            previewContainer.classList.remove('d-none');
                            processingUI.classList.add('d-none');
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Đã tạo xong ảnh người mẫu AI cho sản phẩm.',
                                timer: 3000
                            });

                            // Update button to green
                            const btn = document.querySelector(`.btn-generate-vton[data-id="${currentProductId}"]`);
                            if (btn) {
                                btn.dataset.vtonImage = data.result_url;
                                btn.classList.remove('btn-info');
                                btn.classList.add('btn-success');
                            }
                        } else if (data.status === 'failed') {
                            clearInterval(pollInterval);
                            Swal.fire('Thất bại', 'AI gặp lỗi. Vui lòng thử lại sau.', 'error');
                            processingUI.classList.add('d-none');
                            startUI.classList.remove('d-none');
                        }
                    })
                    .catch(() => {});
                }, 5000);
            }

            document.getElementById('vtonPreviewModal').addEventListener('hidden.bs.modal', function () {
                if (pollInterval) {
                    clearInterval(pollInterval);
                    pollInterval = null;
                }
            });
        });
    </script>
@endsection