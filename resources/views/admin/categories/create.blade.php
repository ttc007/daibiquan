@extends('layouts.admin')

@section('title', 'Thêm danh mục mới')

@section('content')
<div class="container">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary mb-4">
            ← Quay lại
        </a>
    <h2 class="mb-4">Thêm danh mục mới</h2>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded shadow-sm">
        @csrf

        <div class="mb-3 row">
            <div class="col-md-6">
                <label for="name" class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
            </div>

        </div>

        <button type="submit" class="btn btn-primary">Thêm danh mục</button>
        

    </form>
</div>
@endsection
