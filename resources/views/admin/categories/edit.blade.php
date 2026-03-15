@extends('layouts.admin')

@section('title', 'Sửa Danh mục')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Sửa Danh mục</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh mục</a></li>
                    <li class="breadcrumb-item"><a href="#!">Sửa</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Cập nhật Thông tin</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Danh mục</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" maxlength="50" required>
                        <small class="text-muted">
                            <span id="charCount">0</span>/50 ký tự
                        </small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Danh mục Cha</label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                            <option value="">-- Chọn Danh mục Cha (Nếu có) --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const charCount = document.getElementById('charCount');
    
    // Update counter on input
    nameInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        // Color feedback
        if (length >= 50) {
            charCount.classList.add('text-danger');
            charCount.classList.remove('text-warning');
        } else if (length >= 40) {
            charCount.classList.add('text-warning');
            charCount.classList.remove('text-danger');
        } else {
            charCount.classList.remove('text-danger', 'text-warning');
        }
    });
    
    // Initialize counter with existing value
    if (nameInput.value) {
        nameInput.dispatchEvent(new Event('input'));
    }
});
</script>
@endsection
