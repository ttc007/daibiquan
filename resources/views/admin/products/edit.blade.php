@extends('layouts.admin')

@section('content')
<h1>Sửa sản phẩm</h1>

@if ($errors->any())
    <div style="color:red">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.products.update', $product) }}">
    @csrf
    @method('PUT')
    <p>
        <label>Tên sản phẩm</label><br>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
    </p>
    <p>
        <label>Mô tả</label><br>
        <textarea name="description">{{ old('description', $product->description) }}</textarea>
    </p>
    <p>
        <label>Giá gốc (VNĐ)</label><br>
        <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" required>
    </p>
    <p>
        <label>Giá khuyến mãi (VNĐ)</label><br>
        <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0">
    </p>
    <button type="submit">Cập nhật</button>
</form>

<a href="{{ route('admin.products.index') }}">Quay lại danh sách</a>
@endsection
