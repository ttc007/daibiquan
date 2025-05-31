@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lịch sử đặt hàng</h1>
    <div id="order-list">Đang tải đơn hàng...</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const customerId = localStorage.getItem('customer_id');
    const orderList = document.getElementById('order-list');

    if (!customerId) {
        orderList.innerHTML = '<p>Không tìm thấy thông tin khách hàng.</p>';
        return;
    }

    fetch('/api/orders/history', {
        headers: {
            'X-Customer-ID': customerId
        }
    })
    .then(response => response.json())
    .then(orders => {
        if (orders.length === 0) {
            orderList.innerHTML = '<p>Bạn chưa có đơn hàng nào.</p>';
            return;
        }

        let html = '';
        orders.forEach(order => {
            html += `
                <div class="order-card">
                    <h4>Đơn hàng #${order.id} - Thời gian đặt hàng: ${new Date(order.created_at).toLocaleString()}</h4>
                    <p><strong>Tên:</strong> ${order.name}</p>
                    <p><strong>Điện thoại:</strong> ${order.phone}</p>
                    <p><strong>Địa chỉ:</strong> ${order.address}</p>
                    <p><strong>Trạng thái:</strong> ${order.status ?? 'Đang xử lý'}</p>
                    <p><strong>Tổng tiền:</strong> ${Number(order.total_price).toLocaleString()}đ</p>
                    <p><strong>Chi tiết:</strong></p>
                    <ul>
                        ${order.items.map(item => `
                            <li>${item.product_name} - SL: ${item.quantity} - Giá: ${Number(item.price).toLocaleString()}đ</li>
                        `).join('')}
                    </ul>
                </div>
            `;
        });

        orderList.innerHTML = html;
    })
    .catch(err => {
        console.error(err);
        orderList.innerHTML = '<p>Có lỗi khi tải đơn hàng.</p>';
    });
});
</script>

<style>
.order-card {
    padding: 16px;
    border: 1px solid #ccc;
    margin-bottom: 20px;
    border-radius: 6px;
    background: #f9f9f9;
}
</style>
@endsection
