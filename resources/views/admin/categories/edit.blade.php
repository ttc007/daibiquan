@extends('layouts.admin')

@section('title', 'Cập nhật danh mục')

@section('content')
<div class="container">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary mb-4">
            ← Quay lại
        </a>
    <h3 class="mb-4">Cập nhật danh mục</h3>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="border p-4 rounded shadow-sm bg-white">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Cập nhật danh mục</button>
        </div>
    </form>
</div>
@endsection
