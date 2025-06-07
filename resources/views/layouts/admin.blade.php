<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <meta name="theme-color" content="#007bff" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        /* Container pagination */
        .pagination {
          justify-content: center; /* canh giữa */
          margin-top: 30px;
          margin-bottom: 30px;
        }


        /* Khi màn hình nhỏ hơn 576px (điện thoại) */
        @media (max-width: 575.98px) {
          .product-image {
            width: 30px;
            height: 30px;
          }
        }

    </style>

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
      <div class="container navbar-container">
        <a class="navbar-brand {{ request()->is('admin') ? 'active' : '' }}" href="/admin">Đại Bi Quán</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="/admin">Thống kê</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}" href="/admin/products">Sản phẩm</a>
            </li>
            <!-- <li class="nav-item">
              <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="/admin/categories">Danh mục</a>
            </li> -->
            <li class="nav-item">
              <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}" href="/admin/orders">Đơn hàng</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

    <div id="fcm-popup" style="display:none; position:fixed; bottom:20px; right:20px; background:#333; color:#fff; padding:15px 20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.3); z-index:9999;">
      <strong id="fcm-title">Thông báo</strong>
      <p id="fcm-body" style="margin:5px 0 10px;"></p>
      <button id="fcm-ok-btn" style="background:#00c853; color:#fff; border:none; padding:6px 12px; border-radius:5px; cursor:pointer;">
        OK
      </button>
    </div>

    <footer class="bg-light text-center text-muted py-3 mt-5 border-top">
        <div class="container">
            <small>&copy; {{ date('Y') }} Hệ thống quản trị Đại Bi Quán | Phát triển bởi Thành Công</small>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
