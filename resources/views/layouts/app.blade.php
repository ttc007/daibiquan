<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đại Bi Quán</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="#007bff" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .navbar-brand { font-weight: bold; }
        .content { padding: 20px; min-height: 70vh; }
        footer { background: #f2f2f2; padding: 20px; text-align: center; }

        .order-table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .order-items-table {
            width: 100%;
            min-width: 500px;
            border-collapse: collapse;
        }
        .product-add-cart{
            background: #4caf50;
            border-color: #4caf50;
        }

        .product-image {
          width: 100%;
          height: 180px;
          background-color: #f8f9fa;
          display: flex;
          justify-content: center;
          align-items: center;
          overflow: hidden;
          border: 1px solid #eee;
          border-radius: 8px;
          margin-bottom: 10px;
        }

        .product-image img {
          max-width: 100%;
          max-height: 100%;
          object-fit: contain;
        }

        h1 {
          font-size: 32px;
          font-weight: bold;
          text-align: center;
          color: #4caf50; /* Màu xanh lá nhẹ */
          margin-bottom: 30px;
          text-transform: uppercase;
          letter-spacing: 1px;
          position: relative;
          padding-bottom: 10px;
        }

        h1::after {
          content: "";
          width: 60px;
          height: 4px;
          background: #a5d6a7;
          display: block;
          margin: 8px auto 0;
          border-radius: 2px;
        }

        /* Container pagination */
        .pagination {
          justify-content: center; /* canh giữa */
          margin-top: 30px;
          margin-bottom: 30px;
        }

        .nav-link svg, 
        .nav-link .cart-icon {
          color: #fff; /* xanh lá chuối đậm hơn */
          font-weight: bold;
          font-size: 1.3rem;
        }

        .nav-link .badge {
          position: relative;
          top: -7px;    /* nâng lên trên */
          margin-left: 1px;
          font-size: 0.75rem;
          padding: 2px 6px;
          background-color: #ffffff;
          color: #fff;
          border-radius: 12px;
          font-weight: bold;
          box-shadow: 0 0 3px rgba(0,0,0,0.2);
        }

    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #7cb342;">
        <div class="container">
            <a class="navbar-brand" href="/">Đại Bi Quán</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('menu') ? 'active' : '' }}" href="/menu">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->is('cart') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                            @php
                                $cart = session('cart', []);
                                $totalQuantity = array_sum(array_column($cart, 'quantity'));
                            @endphp
                            <i class="fas fa-shopping-cart cart-icon"></i> 
                            @if($totalQuantity > 0)<span class="badge bg-success">{{ $totalQuantity }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('lich-su-don-hang') ? 'active' : '' }}" href="/lich-su-don-hang">Lịch sử đơn hàng</a>
                    </li>

                    <!-- <li class="nav-item">
                        <a class="nav-link {{ request()->is('lien-he') ? 'active' : '' }}" href="/lien-he">Liên hệ</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Nội dung -->
    <div class="content container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <!-- <strong>Thành công!</strong>  -->
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <!-- <strong>Lỗi!</strong>  -->
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="mt-5">
        <p>© 2025 Quán Chay Đại Bi. All rights reserved.</p>
        <p>Địa chỉ: 123 Đường Thanh Bình, Quận 1, TP. Đà Nẵng</p>
        <p>Điện thoại: 0909 123 456</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if (!localStorage.getItem('customer_id')) {
            const randomId = 'cust_' + Math.random().toString(36).substr(2, 12);
            localStorage.setItem('customer_id', randomId);
        }
    </script>

</body>
</html>
