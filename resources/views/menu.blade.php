@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Menu Cơm Chay</h1>

    <div class="category-scroll">
        <a href="{{ route('menu') }}"
           class="btn btn-sm {{ !isset($category) ? 'active' : '' }}">
            Tất cả
        </a>

        @foreach ($categories as $cat)
            <a href="{{ route('products.byCategory', $cat->id) }}"
               class="btn btn-outline-primary btn-sm {{ isset($category) && $category->id == $cat->id ? 'active' : '' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

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

                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ number_format($product->price, 0, ',', '.') }}đ</strong>
                                <div class="input-group input-group-sm" style="width: 82px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeQuantity(this, -1)">−</button>
                                    <input type="text" name="quantity" value="1" min="1" class="form-control text-center quantity-input"/>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeQuantity(this, 1)">+</button>
                                </div>
                            </div>
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

    <script>
        function changeQuantity(button, delta) {
            const input = button.parentElement.querySelector('.quantity-input');
            let value = parseInt(input.value) || 1;
            value += delta;
            if (value < 1) value = 1;
            input.value = value;
        }
    </script>

    <style type="text/css">
        .input-group .quantity-input {
            border: 1px solid #ced4da; /* giống màu border của button */
            border-left: 0;
            border-right: 0;
        }

        .input-group .btn-outline-secondary {
            border-color: #ced4da;
        }

        .category-scroll {
            overflow-x: auto;
            white-space: nowrap;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            padding-left: 5px;
        }

        .category-scroll .btn {
            display: inline-block;
            white-space: nowrap;
            margin: 6px 10px 10px 0;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            padding: 10px 22px;
            border: 2px solid #4caf50;
            background-color: white;
            color: #4caf50;
            transition: all 0.3s ease;
        }

        .category-scroll .btn:hover {
            background-color: #43a047; /* xanh nhạt hơn */
            color: white;
        }

        .category-scroll .btn.active {
            background-color: #4caf50;
            color: white;
            border-color: #4caf50;
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
        }

        .category-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .category-scroll::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
    </style>


@endsection




