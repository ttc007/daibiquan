@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container">
    <h3>Chi tiết đơn hàng #{{ $order->id }}</h3>

    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên khách hàng <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $order->name) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $order->phone) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
            <textarea name="address" id="address" rows="2" class="form-control">{{ old('address', $order->address) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
            <select name="status" id="status" class="form-select">
                @php
                    $statuses = ['Mới', 'Đang xử lý', 'Đang giao hàng', 'Đã nhận hàng', 'Hoàn thành', 'Hủy'];
                @endphp
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Cập nhật đơn hàng
        </button>
    </form>

    <hr>

    <h5>Chi tiết sản phẩm</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tổng phụ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td style="width: 80px;">
                        <div class="product-image">
                            <img src="{{ asset($item->product->image) }}" class="img-fluid" alt="Ảnh">
                        </div>
                    </td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price) }}đ</td>
                    <td>{{ number_format($item->price * $item->quantity) }}đ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-end">
        <strong>Tổng tiền:</strong> {{ number_format($order->total_price) }}đ
    </div>
</div>
@endsection
