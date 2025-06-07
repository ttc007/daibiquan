@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    <style type="text/css">
        .product-image {
            height: 100px;
            width: 100px;
        }

        .cart-footer {
            display: flex;
            justify-content: flex-end; /* Đẩy nội dung bên phải */
            margin-top: 20px; /* Cách phần trên */
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
    <h1>Giỏ hàng</h1>

    @if(count($cartComChay) > 0 || count($cartCuaHang) > 0)
        @if (count($cartComChay) > 0)
            <h4 class="text-success">🌿Cơm chay</h4>
            @include('components.cart_table', ['cart' => $cartComChay])
            <div class="cart-footer">
                <a href="{{ route('checkout', ['type' => 'com_chay']) }}" class="btn btn-success btn-lg btn-green">Đặt hàng</a>
            </div>
        @endif

        @if (count($cartCuaHang) > 0)
            <h4 class="text-success">🛒Cửa hàng Đại Bi</h4>
            @include('components.cart_table', ['cart' => $cartCuaHang])
            <div class="cart-footer">
                <a href="{{ route('checkout', ['type' => 'cua_hang']) }}" class="btn btn-success btn-lg btn-green">Đặt hàng</a>
            </div>
        @endif
        
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center">Giỏ hàng trống</td>
                </tr>
            </tbody>
        </table>
    @endif


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.quantity-input');

            inputs.forEach(input => {
                let id = input.getAttribute('data');
                input.addEventListener('change', function () {
                    const form = document.getElementById('form' + id);
                    const inputQuantity = document.getElementById('input' + id);
                    if (inputQuantity) {
                        inputQuantity.value = input.value; // lấy giá trị mới người dùng nhập
                    }
                    if (form) {
                        form.submit();
                    }
                });
            });
        });
    </script>

@endsection
