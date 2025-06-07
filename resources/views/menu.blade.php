@extends('layouts.app')

@section('content')
    <!-- <h1 class="mb-4">Menu Cơm Chay</h1> -->

    <div class="row mt-3">
        <!-- Quán chay Đại Bi -->
        <div class="col-12 col-md-6 quanchay-container">
            <h3>🌿Quán chay Đại Bi</h3>
            <div class="row">
                @foreach($quanChayProducts as $product)
                    <div class="col-12 col-sm-6 mb-4">
                        @include('components.food_card', ['product' => $product])
                    </div>
                @endforeach
            </div>

            <div class="pagination">
                {{ $quanChayProducts->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>



        <!-- Cửa hàng Đại Bi -->
        <div class="col-12 col-md-6 cuahang-container">
            <h3>🛒Cửa hàng Đại Bi</h3>
            <div class="row">
                @foreach($cuaHangProducts as $product)
                    <div class="col-12 col-sm-6 mb-4">
                        @include('components.product_card', ['product' => $product])
                    </div>
                @endforeach
            </div>

            <div class="pagination">
                {{ $cuaHangProducts->links('vendor.pagination.bootstrap-5') }}
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

        .quanchay-container{
            background: #fafafa;
            padding: 25px;
        }

        .cuahang-container{
            background: #f1f1f1;
            padding: 25px;
        }
    </style>


@endsection




