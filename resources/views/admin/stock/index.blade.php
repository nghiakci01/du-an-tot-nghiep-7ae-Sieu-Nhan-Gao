@extends('layouts.admin')

@section('title', 'Quản lý Tồn kho')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Quản lý Tồn kho</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Kho hàng</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Kiểm kê sản phẩm</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Phân loại / SKU</th>
                                <th width="200">Số lượng tồn</th>
                                <th>Cập nhật</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                @if($product->variants->count() > 0)
                                    @foreach($product->variants as $index => $variant)
                                    <tr>
                                        @if($index === 0)
                                        <td rowspan="{{ $product->variants->count() }}">
                                            <div class="d-flex align-items-center">
                                                @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" width="40" class="me-2">
                                                @endif
                                                <strong>{{ $product->name }}</strong>
                                            </div>
                                        </td>
                                        @endif
                                        <td>
                                            <span class="badge bg-secondary">{{ $variant->size }} - {{ $variant->color }}</span>
                                            <br><small class="text-muted">SKU: {{ $variant->sku }}</small>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm stock-input" 
                                                   data-id="{{ $variant->id }}" 
                                                   value="{{ $variant->stock_quantity }}" min="0">
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm update-stock-btn" data-id="{{ $variant->id }}">Lưu</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" width="40" class="me-2">
                                            @endif
                                            <strong>{{ $product->name }}</strong>
                                        </div>
                                    </td>
                                    <td class="text-muted">Không có biến thể</td>
                                    <td colspan="2" class="text-center text-danger">Hãy thêm biến thể để quản lý kho.</td>
                                </tr>
                                @endif
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

@push('scripts')
<script>
document.querySelectorAll('.update-stock-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const variantId = this.dataset.id;
        const input = document.querySelector(`.stock-input[data-id="${variantId}"]`);
        const quantity = input.value;
        const originalText = this.innerText;

        this.disabled = true;
        this.innerText = '...';

        fetch("{{ route('admin.stock.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                variant_id: variantId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                input.classList.add('is-valid');
                setTimeout(() => input.classList.remove('is-valid'), 2000);
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi kết nối server');
        })
        .finally(() => {
            this.disabled = false;
            this.innerText = originalText;
        });
    });
});
</script>
@endpush
@endsection
