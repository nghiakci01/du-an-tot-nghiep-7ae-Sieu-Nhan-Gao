@extends('layouts.admin')

@section('title', 'Nhật ký hệ thống')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Nhật ký hệ thống</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Bộ lọc</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.audit-logs.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Sự kiện</label>
                                        <select name="event" class="form-control">
                                            <option value="">Tất cả</option>
                                            <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                                            <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                                            <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Loại đối tượng</label>
                                        <input type="text" name="auditable_type" class="form-control" value="{{ request('auditable_type') }}" placeholder="Ví dụ: Product">
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Lọc</button>
                                    <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Người dùng</th>
                                        <th>Sự kiện</th>
                                        <th>Đối tượng</th>
                                        <th>ID</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ $log->user ? $log->user->name : 'Hệ thống' }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = match($log->event) {
                                                        'created' => 'bg-success',
                                                        'updated' => 'bg-info',
                                                        'deleted' => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ strtoupper($log->event) }}</span>
                                            </td>
                                            <td>{{ class_basename($log->auditable_type) }}</td>
                                            <td>{{ $log->auditable_id }}</td>
                                            <td>
                                                <a href="{{ route('admin.audit-logs.show', $log->id) }}" class="btn btn-sm btn-light-primary">Chi tiết</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
