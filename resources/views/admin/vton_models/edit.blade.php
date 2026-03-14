@extends('layouts.admin')

@section('title', 'Chỉnh sửa Người mẫu AI')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Chỉnh sửa Người mẫu: {{ $vtonModel->name }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vton-models.index') }}">AI Models</a></li>
                    <li class="breadcrumb-item"><a href="#!">Chỉnh sửa</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-header">
                <h5>Thông tin người mẫu</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.vton-models.update', $vtonModel) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Tên người mẫu</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $vtonModel->name) }}" placeholder="VD: Model A (Summer)">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Giới tính</label>
                        <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value="female" {{ old('gender', $vtonModel->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="male" {{ old('gender', $vtonModel->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="kid" {{ old('gender', $vtonModel->gender) == 'kid' ? 'selected' : '' }}>Trẻ em</option>
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label d-block">Ảnh hiện tại</label>
                        <img src="{{ asset('storage/' . $vtonModel->image) }}" class="img-thumbnail mb-2" style="max-height: 200px;">
                        
                        <label class="form-label d-block">Thay đổi ảnh (nếu muốn)</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default" {{ old('is_default', $vtonModel->is_default) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">
                                Đặt làm mặc định cho giới tính này
                            </label>
                        </div>
                    </div>

                    <hr>
                    <div class="text-end">
                        <a href="{{ route('admin.vton-models.index') }}" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
