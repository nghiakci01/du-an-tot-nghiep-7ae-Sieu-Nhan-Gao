@extends('layouts.admin')

@section('title', 'Manage Sizes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Sizes</h3>
                    <a href="{{ route('admin.sizes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Size
                    </a>
                </div>
                <div class="card-body">

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="10%">ID</th>
                                <th width="40%">Name</th>
                                <th width="15%">Display Order</th>
                                <th width="15%">Status</th>
                                <th width="20%" class="sticky-action-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($sizes) > 0)
                                @foreach($sizes as $size) @php /** @var \App\Models\Size $size */ @endphp
                            <tr>
                                <td>{{ $size->id }}</td>
                                <td>{{ $size->name }}</td>
                                <td>{{ $size->display_order }}</td>
                                <td>
                                    <span class="badge badge-{{ $size->is_active ? 'success' : 'secondary' }}">
                                        {{ $size->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="sticky-action-column">
                                    <a href="{{ route('admin.sizes.edit', $size) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.sizes.destroy', $size) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this size?');">
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
                                <td colspan="5" class="text-center">No sizes found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $sizes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
