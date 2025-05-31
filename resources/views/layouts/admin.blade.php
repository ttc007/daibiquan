<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .navbar .navbar-brand.active {
            font-weight: bold;
            color: #ffc107 !important;
        }

        .navbar-container{
            display: block!important;
        }
        .body{
            background: #fafafa;
        }
        .btn-info {
          color: white !important;
        }
        .btn-info:hover {
          color: white !important;
        }

        .product-image {
          width: 100px;
          height: 100px;
          background-color: #f8f9fa; /* nền nhẹ sáng */
          display: flex;
          justify-content: center;
          align-items: center;
          overflow: hidden;
          border: 1px solid #ddd;
          border-radius: 4px;
        }

        .product-image img {
          max-width: 100%;
          max-height: 100%;
          object-fit: contain;
        }

    </style>

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container navbar-container">
            <a class="navbar-brand me-4 {{ request()->is('admin') ? 'active' : '' }}" href="/admin">Thống kê</a>
            <a class="navbar-brand me-4 {{ request()->is('admin/products*') ? 'active' : '' }}" href="/admin/products">Sản phẩm</a>
            <a class="navbar-brand {{ request()->is('admin/orders*') ? 'active' : '' }}" href="/admin/orders">Đơn hàng</a>

        </div>
    </nav>


    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
