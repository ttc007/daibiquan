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
                                        <img src="{{ asset($item['image']) }}" width="60">
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
                                <a href="{{ route('cart.decrease', $id) }}" class="btn btn-sm btn-outline-secondary me-2">-</a>
                                <form action="{{ route('cart.update', $id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:60px; text-align:center;" />
                                    <button type="submit" class="btn btn-sm btn-success ms-2 btn-green">Cập nhật</button>
                                </form>
                                <a href="{{ route('cart.increase', $id) }}" class="btn btn-sm btn-outline-secondary ms-2">+</a>
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
@endsection
