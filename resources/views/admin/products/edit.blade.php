@extends('layouts.admin')

@section('title', 'Cập nhật sản phẩm')

@section('content')
<div class="container">
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary mb-4">
            ← Quay lại
        </a>
    <h3 class="mb-4">Cập nhật sản phẩm</h3>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="border p-4 rounded shadow-sm bg-white">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="col-md-6">
                <label for="price" class="form-label fw-bold">Giá (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
            <select name="category_id" class="form-select" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Mô tả <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label fw-bold">Ảnh sản phẩm (tuỳ chọn)</label>
            <input type="file" name="image" class="form-control">
            @if ($product->image)
                <div class="mt-2">
                    <img src="{{ asset($product->image) }}" alt="Ảnh hiện tại" class="img-thumbnail" style="max-width: 150px;">
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
        </div>
    </form>
</div>
@endsection
