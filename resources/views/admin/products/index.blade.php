@extends('layouts.admin')

@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-center">
    <h3>Danh sách sản phẩm</h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Thêm sản phẩm
    </a>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>STT</th>
            <th style="width: 120px">Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $index => $product)
        <tr>
            <td>{{ $index + 1}}</td>

            <td>
              @if($product->image)
                <div class="product-image">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                </div>
              @else
                <div class="product-image">
                  <img src="{{ asset('storage/default.png') }}" alt="{{ $product->name }}">
                </div>
              @endif
            </td>

            <td>{{ $product->name }}</td>
            <td>{{$product->category->name??'Chưa có'}}</td>
            <td>{{ number_format($product->price, 0, ',', '.') }} ₫</td>
            <td>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-info me-1">
                    <i class="bi bi-pencil"></i> Sửa
                </a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
        @if($products->isEmpty())
            <tr>
                <td colspan='6' class='text-center'>Hiện chưa có sản phẩm nào</td>
            </tr>
        @endif
    </tbody>
</table>

{{ $products->links() }}

@endsection
