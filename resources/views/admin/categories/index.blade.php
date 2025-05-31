@extends('layouts.admin')

@section('title', 'Danh sách danh mục')

@section('content')

<div class="mb-3 d-flex justify-content-between align-items-center">
    <h3>Danh sách Danh mục</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Thêm danh mục
    </a>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>STT</th>
            <th>Tên danh mục</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $index => $category)
        <tr>
            <td>{{ $index + 1}}</td>
            <td>{{ $category->name }}</td>
            <td>
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-info me-1">
                    <i class="bi bi-pencil"></i> Sửa
                </a>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
        @if($categories->isEmpty())
            <tr>
                <td colspan='3' class='text-center'>Hiện chưa có danh mục nào</td>
            </tr>
        @endif
    </tbody>
</table>

@endsection
