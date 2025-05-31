@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Menu Cơm Chay</h1>
    <div class="row">
        @foreach($products as $product)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="product-image">
                        <img src="{{ asset($product->image ?? 'storage/default.png') }}" alt="{{ $product->name }}">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                        <p class="card-text text-truncate">{{ $product->description }}</p>
                        <p><strong>{{ number_format($product->price, 0, ',', '.') }}đ</strong></p>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 product-add-cart">Thêm vào giỏ</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <div class="pagination">
        {{ $products->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection




