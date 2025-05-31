@extends('layouts.app')

@section('content')
<style type="text/css">
    .product-image {
        height: 100px;
        width: 100px;
    }
</style>
<h1>Thanh toán - Đặt hàng</h1>

@if(count($cart) > 0)
    <div class="cart-items mb-4">
        <h2>Thông tin đơn hàng</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Ảnh</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $id => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>
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
                    <td>{{ number_format($item['price'], 0, ',', '.') }}đ</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="text-end fs-5 fw-bold">
            Tổng cộng: 
            {{ number_format(array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $cart)), 0, ',', '.') }}đ
        </p>
    </div>

    <h2>Thông tin giao hàng</h2>
    <form action="{{ route('checkout.placeOrder') }}" method="POST">
        @csrf
        <input type="hidden" name="customer_id" id="customer_id">
        <script>
          document.getElementById('customer_id').value = localStorage.getItem('customer_id');
        </script>
        <div class="mb-3">
            <label for="name" class="form-label">Họ và tên</label>
            <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="tel" id="phone" name="phone" class="form-control" required value="{{ old('phone') }}">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ giao hàng</label>
            <textarea id="address" name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success btn-lg">Xác nhận đặt hàng</button>
    </form>

@else
    <p>Giỏ hàng của bạn đang trống.</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">Tiếp tục mua hàng</a>
@endif

@endsection
