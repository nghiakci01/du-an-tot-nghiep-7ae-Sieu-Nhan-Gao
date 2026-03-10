@extends('layouts.admin')

@section('title', 'Chi tiết Nhật ký')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.audit-logs.index') }}">Nhật ký hệ thống</a></li>
                            <li class="breadcrumb-item" aria-current="page">Chi tiết</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Thời gian</span>
                                <strong>{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Người thực hiện</span>
                                <strong>{{ $auditLog->user ? $auditLog->user->name : 'Hệ thống' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Sự kiện</span>
                                <span class="badge {{ $auditLog->event == 'created' ? 'bg-success' : ($auditLog->event == 'updated' ? 'bg-info' : 'bg-danger') }}">
                                    {{ strtoupper($auditLog->event) }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Đối tượng</span>
                                <strong>{{ class_basename($auditLog->auditable_type) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>ID Đối tượng</span>
                                <strong>{{ $auditLog->auditable_id }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Thông tin Truy cập</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>URL:</strong> {{ $auditLog->url }}</p>
                        <p><strong>IP:</strong> {{ $auditLog->ip_address }}</p>
                        <p><strong>User Agent:</strong> <small>{{ $auditLog->user_agent }}</small></p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Thay đổi dữ liệu</h5>
                    </div>
                    <div class="card-body">
                        @if($auditLog->event == 'updated')
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Trường dữ liệu</th>
                                            <th>Giá trị cũ</th>
                                            <th>Giá trị mới</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $oldValues = $auditLog->old_values ?? [];
                                            $newValues = $auditLog->new_values ?? [];
                                            $allKeys = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
                                        @endphp
                                        @foreach($allKeys as $key)
                                            <tr>
                                                <td><strong>{{ $key }}</strong></td>
                                                <td class="text-danger">{{ is_array($oldValues[$key] ?? '') ? json_encode($oldValues[$key]) : ($oldValues[$key] ?? 'N/A') }}</td>
                                                <td class="text-success">{{ is_array($newValues[$key] ?? '') ? json_encode($newValues[$key]) : ($newValues[$key] ?? 'N/A') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <h6>Dữ liệu {{ $auditLog->event == 'created' ? 'mới' : 'đã xóa' }}:</h6>
                            <pre class="bg-light p-3"><code>{{ json_encode($auditLog->event == 'created' ? $auditLog->new_values : $auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
