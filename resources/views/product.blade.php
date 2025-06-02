@extends('layouts.app')

@section('content')
<style type="text/css">
    .product-image {
        height: 400px;
    }

    .input-group .quantity-input {
        border: 1px solid #ced4da; /* giống màu border của button */
        border-left: 0;
        border-right: 0;
    }

    .input-group .btn-outline-secondary {
        border-color: #ced4da;
    }
</style>

<div class="container my-5">
    <div class="row">
        <!-- Ảnh sản phẩm bên trái -->
        <div class="col-md-6">
            <div class="product-image">
                <img src="{{ asset( $product->image) }}"
                 alt="{{ $product->name }}"
                 class="img-fluid rounded shadow">    
            </div>
            
        </div>

        <!-- Thông tin sản phẩm bên phải -->
        <div class="col-md-6">
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf

                <h2 class="mb-3">{{ $product->name }}</h2>

                <h4 class="text-danger mb-3">
                    {{ number_format($product->price, 0, ',', '.') }} VNĐ
                </h4>

                <p class="mb-4">{{ $product->description }}</p>

                <div class="input-group mb-4" style="width: 110px;">
                    <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity(this, -1)">−</button>
                    <input type="text" name="quantity" value="1" min="1" class="form-control text-center quantity-input"/>
                    <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity(this, 1)">+</button>
                </div>

                <button type="submit" class="btn btn-success btn-green btn-lg">
                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function changeQuantity(button, delta) {
        const input = button.parentElement.querySelector('.quantity-input');
        let value = parseInt(input.value) || 1;
        value += delta;
        if (value < 1) value = 1;
        input.value = value;
    }
</script>
@endsection
