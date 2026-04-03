@extends('layouts.admin')

@section('title', 'Manage Colors')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Colors</h3>
                    <a href="{{ route('admin.colors.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Color
                    </a>
                </div>
                <div class="card-body">

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="8%">ID</th>
                                <th width="50%">Name</th>
                                <th width="12%">Display Order</th>
                                <th width="10%">Status</th>
                                <th width="15%" class="sticky-action-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($colors) > 0)
                                @foreach($colors as $color)
                            <tr>
                                <td>{{ $color->id }}</td>
                                <td>{{ $color->name }}</td>
                                <td>{{ $color->display_order }}</td>
                                <td>
                                    <span class="badge badge-{{ $color->is_active ? 'success' : 'secondary' }}">
                                        {{ $color->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="sticky-action-column">
                                    <a href="{{ route('admin.colors.edit', $color) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.colors.destroy', $color) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this color?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="7" class="text-center">No colors found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $colors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
