@extends('layouts.admin')

@section('title', 'Quản lý Người mẫu AI')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Quản lý Người mẫu Virtua Try-On</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">AI Models</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Danh sách Người mẫu</h5>
                <a href="{{ route('admin.vton-models.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Giới tính</th>
                                <th>Mặc định</th>
                                <th>Ngày tạo</th>
                                <th>Hạnh động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($models) > 0)
                                @foreach($models as $model) @php /** @var \App\Models\VtonModel $model */ @endphp
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $model->image) }}" alt="{{ $model->name }}" style="width: 60px; height: 80px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td>{{ $model->name }}</td>
                                <td>
                                    @if($model->gender == 'male')
                                        <span class="badge bg-primary">Nam</span>
                                    @elseif($model->gender == 'female')
                                        <span class="badge bg-danger">Nữ</span>
                                    @else
                                        <span class="badge bg-warning">Trẻ em</span>
                                    @endif
                                </td>
                                <td>
                                    @if($model->is_default)
                                        <span class="badge bg-success"><i class="feather icon-check"></i> Mặc định</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $model->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.vton-models.edit', $model) }}" class="btn btn-warning btn-sm"><i class="feather icon-edit"></i></a>
                                    <form action="{{ route('admin.vton-models.destroy', $model) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người mẫu này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="feather icon-trash-2"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" class="text-center">Chưa có người mẫu nào được thêm.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $models->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
