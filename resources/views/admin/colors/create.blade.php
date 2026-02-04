@extends('layouts.admin')

@section('title', 'Create Color')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Color</h3>
                </div>
                <form action="{{ route('admin.colors.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Color Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">e.g., Red, Blue, Black, White</small>
                        </div>

                        <div class="form-group">
                            <label for="hex_code">Hex Color Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" id="color_picker" class="form-control" style="max-width: 80px; padding: 2px;" value="{{ old('hex_code', '#000000') }}">
                                <input type="text" name="hex_code" id="hex_code" class="form-control @error('hex_code') is-invalid @enderror" value="{{ old('hex_code', '#000000') }}" pattern="^#[0-9A-Fa-f]{6}$" required>
                                @error('hex_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Format: #RRGGBB (e.g., #FF5733)</small>
                        </div>

                        <div class="form-group">
                            <label>Color Preview</label>
                            <div id="color_preview" style="width: 100px; height: 100px; border: 1px solid #ddd; border-radius: 4px; background-color: {{ old('hex_code', '#000000') }};"></div>
                        </div>

                        <div class="form-group">
                            <label for="display_order">Display Order</label>
                            <input type="number" name="display_order" id="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', 0) }}" min="0">
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Color
                        </button>
                        <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorPicker = document.getElementById('color_picker');
    const hexInput = document.getElementById('hex_code');
    const preview = document.getElementById('color_preview');

    // Update hex input and preview when color picker changes
    colorPicker.addEventListener('input', function() {
        hexInput.value = this.value.toUpperCase();
        preview.style.backgroundColor = this.value;
    });

    // Update color picker and preview when hex input changes
    hexInput.addEventListener('input', function() {
        const hex = this.value;
        if (/^#[0-9A-Fa-f]{6}$/.test(hex)) {
            colorPicker.value = hex;
            preview.style.backgroundColor = hex;
        }
    });
});
</script>
@endsection
