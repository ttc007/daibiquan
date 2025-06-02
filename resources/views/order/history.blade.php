    @extends('layouts.app')

    @section('content')
    <div class="container">
        <h1>Lịch sử đặt hàng</h1>
        <div id="order-list">Đang tải đơn hàng...</div>
        <div id="pagination"></div>
    </div>
    <form method="POST" action="" id="form-reorder" style="display:none;">
        @csrf
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerId = localStorage.getItem('customer_id');
        const orderList = document.getElementById('order-list');
        let currentPage = 1;

        if (!customerId) {
            orderList.innerHTML = '<p>Không tìm thấy thông tin khách hàng.</p>';
            return;
        }

        function renderPagination(data) {
            const pagination = document.getElementById('pagination');
            let buttons = '';

            if (data.prev_page_url) {
                buttons += `<button onclick="fetchOrders(${data.current_page - 1})"><i class="fa-solid fa-arrow-left"></i></button>`;
            }

            // Hiển thị các nút số trang
            for (let i = 1; i <= data.last_page; i++) {
                if (i === data.current_page) {
                    buttons += `<button class="active" disabled>${i}</button>`;
                } else {
                    buttons += `<button onclick="fetchOrders(${i})">${i}</button>`;
                }
            }

            if (data.next_page_url) {
                buttons += `<button onclick="fetchOrders(${data.current_page + 1})"><i class="fa-solid fa-arrow-right"></i></button>`;
            }

            pagination.innerHTML = buttons;
        }

        function fetchOrders(page = 1) {
            const customerId = localStorage.getItem('customer_id');
            fetch(`/api/orders/history?page=${page}`, {
                headers: {
                    'X-Customer-ID': customerId
                }
            })
            .then(res => res.json())
            .then(data => {
                renderOrders(data.data);
                renderPagination(data);
            });
        }

        function getStatusInfo(status) {
            const statusMap = {
                'Mới': { icon: '🆕', class: 'text-primary' },
                'Đang xử lý': { icon: '🔄', class: 'text-warning' },
                'Đang giao hàng': { icon: '🚚', class: 'text-info' },
                'Đã nhận hàng': { icon: '📦', class: 'text-success' },
                'Hoàn thành': { icon: '✅', class: 'text-success' },
                'Hủy': { icon: '❌', class: 'text-danger' },
            };

            return statusMap[status] || { icon: '❓', class: 'text-secondary' };
        }


        function renderOrders(orders) {
            const list = document.getElementById('order-list');
            list.innerHTML = orders.map(order => {
                const { icon, class: statusClass } = getStatusInfo(order.status);

                return `
                    <div class="order-card">
                        <h4>Đơn hàng #${order.id} - Thời gian đặt hàng: ${new Date(order.created_at).toLocaleString()}

                        </h4>
                        
                        <p><strong>Tên:</strong> ${order.name}</p>
                        <p><strong>Điện thoại:</strong> ${order.phone}</p>
                        <p><strong>Địa chỉ:</strong> ${order.address}</p>
                        <p><strong>Trạng thái:</strong> <b class="${statusClass}">${icon} ${order.status ?? 'Đang xử lý'}</b></p>

                        <p><strong>Chi tiết:</strong></p>
                        <div class="order-table-wrapper">
                            <table class="order-items-table">
                                <thead>
                                    <tr>
                                        <th style='width:100px'></th>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Tổng phụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${order.items.map(item => `
                                        <tr>
                                            <td>
                                                <div class="product-image">
                                                    <a href="/product/${item.product_id}"><img src='${item.product.image}' alt="${item.product_name}"></a>
                                                </div>
                                            </td>
                                            <td>${item.product_name}</td>
                                            <td>${item.quantity}</td>
                                            <td>${Number(item.price).toLocaleString()}đ</td>
                                            <td>${Number(item.price * item.quantity).toLocaleString()}đ</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        <p><strong>Tổng tiền:</strong> ${Number(order.total_price).toLocaleString()}đ</p>
                        ${
                          order.status === 'Mới'
                            ? `<button onclick="cancelOrder(${order.id})" class="btn-cancel-order">Hủy đơn hàng</button>`
                            : ['Đang giao hàng'].includes(order.status)
                                ? `Vui lòng nhấn vào nút "Đã nhận hàng" nếu bạn đã nhận được hàng <button onclick="confirmReceived(${order.id})" class="btn-confirm-received">Đã nhận hàng</button>`
                                : order.status === 'Hoàn thành'
                                    ? `<button onclick="copyOrder(${order.id})" class="btn-copy-order">Đặt lại giống đơn này</button>`
                                    : ''
                        }


                    </div>
                `;
            }).join('');
        }

        function cancelOrder(orderId) {
          if (!confirm('Bạn có chắc muốn hủy đơn hàng này?')) return;
          fetch(`/api/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Customer-ID': customerId,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
          })
          .then(() => location.reload());
        }

        function copyOrder(orderId) {
            const form = document.getElementById('form-reorder');
            form.action = '/reorder/' + orderId;
            form.submit();
        }

        function confirmReceived(orderId) {
          fetch(`/api/orders/${orderId}/received`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Customer-ID': customerId,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
          })
          .then(() => {
            location.reload()
            });
        }

        // Hàm helper để map trạng thái sang class màu
        function getStatusClass(status) {
            switch (status?.toLowerCase()) {
                case 'mới':
                    return 'status-new';
                case 'đang xử lí':
                case 'đang xử lý':
                    return 'status-processing';
                case 'đang giao hàng':
                    return 'status-delivering';
                case 'đã nhận hàng':
                case 'hoàn thành':
                    return 'status-completed';
                case 'hủy':
                case 'đã hủy':
                    return 'status-canceled';
                default:
                    return 'status-default';
            }
        }

        window.fetchOrders = fetchOrders;
        window.copyOrder = copyOrder;
        window.confirmReceived = confirmReceived;
        window.cancelOrder = cancelOrder;
        fetchOrders();
    });
    </script>

    <style>
    .product-image {
        height: 100px;
        width: 100px;
    }

    /* Nút Hủy đơn hàng: mờ nhạt */
    .btn-cancel-order {
        background-color: #e0e0e0;
        color: #666;
        border: 1px solid #ccc;
        padding: 6px 14px;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-cancel-order:hover {
        background-color: #d5d5d5;
    }

    .btn-copy-order{
        background-color: #42947c; /* xanh lá tươi */
        color: white;
        border: none;
        padding: 8px 16px;
        font-size: 15px;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .btn-copy-order:hover {
        background-color: #57a790;
    }

    /* Nút Đã nhận hàng: nổi bật */
    .btn-confirm-received {
        background-color: #28a745; /* xanh lá tươi */
        color: white;
        border: none;
        padding: 8px 16px;
        font-size: 15px;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .btn-confirm-received:hover {
        background-color: #218838;
    }


    .status-new {
        color: #295282; /* xanh đậm */
        font-weight: bold;
    }
    .status-processing {
        color: #f9a825; /* vàng cam */
        font-weight: bold;
    }
    .status-delivering {
        color: #0288d1; /* xanh dương */
        font-weight: bold;
    }
    .status-completed {
        color: #388e3c; /* xanh lá cây */
        font-weight: bold;
    }
    .status-canceled {
        color: #d32f2f; /* đỏ */
        font-weight: bold;
    }
    .status-default {
        color: #555; /* xám */
    }

    .order-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease;
    }

    .order-items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
        font-size: 14px;
    }

    .order-items-table th,
    .order-items-table td {
        border: 1px solid #ddd;
        padding: 8px 12px;
        text-align: left;
    }

    .order-items-table th {
        background-color: #f4f6f8;
        color: #333;
        font-weight: 600;
    }

    .order-items-table tr:nth-child(even) {
        background-color: #fafafa;
    }

    .order-items-table tr:hover {
        background-color: #e8f5e9;
    }

    .order-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .order-card h4 {
        margin-bottom: 12px;
        font-weight: 600;
        color: #2c3e50;
    }

    .order-card p {
        margin: 6px 0;
        font-size: 15px;
        color: #444;
    }

    .order-card p strong {
        color: #27ae60; /* màu xanh lá chuối non để nổi bật */
        width: 90px;
        display: inline-block;
    }

    .order-card ul {
        padding-left: 20px;
        margin-top: 8px;
    }

    .order-card ul li {
        font-size: 14px;
        color: #555;
        margin-bottom: 4px;
        border-bottom: 1px solid #eee;
        padding-bottom: 4px;
    }

    .order-card ul li:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    #pagination {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 20px;
    }

    #pagination button {
        padding: 6px 12px;
        min-width: 36px;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
        color: #333;
        font-weight: 500;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    #pagination button:hover:not(.active):not(:disabled) {
        background-color: #dcedc8; /* xanh lá nhạt */
        color: #000;
    }

    #pagination button.active {
        background-color: #4caf50; /* xanh lá chuối non */
        color: white;
        font-weight: bold;
        cursor: default;
        border: none;
    }

    #pagination button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    </style>
    @endsection
