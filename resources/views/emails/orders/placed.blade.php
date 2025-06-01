@component('mail::message')
# Đơn hàng mới từ {{ $order->name }}

Thông tin khách hàng:  
- Họ tên: {{ $order->name }}  
- Số điện thoại: {{ $order->phone }}  
- Địa chỉ: {{ $order->address }}  

## Chi tiết đơn hàng:
@component('mail::table')
| Sản phẩm      | Giá (VNĐ)  | Số lượng | Thành tiền (VNĐ) |
| ------------- | ---------- | -------- | ---------------- |
@foreach ($orderItems as $item)
| {{ $item->product_name }}           | {{ number_format($item->price) }} | {{ $item->quantity }} | {{ number_format($item->price * $item->quantity) }} |
@endforeach
@endcomponent

**Tổng cộng: {{ number_format($order->total_price) }} VNĐ**

Cảm ơn bạn đã đặt hàng!  
@endcomponent
