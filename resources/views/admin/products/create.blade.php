@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="container">
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary mb-4">
            ← Quay lại
        </a>
    <h2 class="mb-4">Thêm sản phẩm mới</h2>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded shadow-sm">
        @csrf

        <div class="mb-3 row">
            <div class="col-md-6">
                <label for="name" class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="price" class="form-label fw-bold">Giá (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control" required value="20000">
            </div>
        </div>

        <div class="mb-3">
            <label for="sale_price" class="form-label">Giá khuyến mãi (nếu có)</label>
            <input type="number" name="sale_price" id="sale_price" class="form-control">
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh</label>
            <input type="file" name="image" id="image" accept="image/*" class="form-control">
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
            <select name="category_id" class="form-select" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả<span class="text-danger">*</span></label>
            <textarea name="description" id="description" rows="4" class="form-control">Một sản phẩm của đại bi quán
            </textarea>
        </div>

        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
        

    </form>
</div>
@endsection
