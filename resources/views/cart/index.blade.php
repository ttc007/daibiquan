@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    <style type="text/css">
        .product-image {
            height: 100px;
            width: 100px;
        }

        .btn-green {
            background: #4caf50;
            border-color: #4caf50;
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

    @if(session('cart') && count(session('cart')) > 0)
        <div class="order-table-wrapper">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên</th>
                        <th>Giá</th>
                        <th style="width:350px">Số lượng</th>
                        <th>Tổng</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($cart as $id => $item)
                        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                        <tr>
                            <td width="80">
                                @if($item['image'])
                                    <div class="product-image">
                                        <img src="{{ asset($item['image']) }}">
                                    </div>
                                @else
                                    <div class="product-image">
                                      <img src="{{ asset('storage/default.png') }}">
                                    </div>
                                @endif
                            </td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ number_format($item['price'], 0, ',', '.') }}đ</td>
                            <td>
                            <div class="d-flex align-items-center">
                                <div class="input-group input-group-sm" style="width: 82px;">
                                    <a type="button" class="btn btn-outline-secondary btn-sm"  href="{{ route('cart.decrease', $id) }}">−</a>
                                    <input type="text" name="quantity" value="{{$item['quantity']}}" min="1" class="form-control text-center quantity-input" data="{{$id}}"/>
                                    <a type="button" class="btn btn-outline-secondary btn-sm" href="{{ route('cart.increase', $id) }}">+</a>
                                </div>
                                
                                <form action="{{ route('cart.update', $id) }}" method="POST" style="display:inline;" id="form{{$id}}">
                                    @csrf
                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:60px; text-align:center;" id="input{{$id}}"/>
                                    
                                </form>
                                
                            </div>
                        </td>
                            <td>{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                        <td colspan="2"><strong>{{ number_format($total, 0, ',', '.') }}đ</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="cart-footer">
            <a href="{{ route('checkout') }}" class="btn btn-success btn-lg btn-green">Đặt hàng</a>
        </div>
        
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
