<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đại Bi Quán</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial; margin: 0; padding: 0; }
        header { background: #7cb342; color: white; padding: 15px; }
        nav a { color: white; margin: 0 10px; text-decoration: none; }
        .content { padding: 20px; }
    </style>
</head>
<body>
    <header>
        <h1>Đại Bi Quán</h1>
        <nav>
            <a href="/">Trang chủ</a>
            <a href="/san-pham">Sản phẩm</a>
            <a href="/dat-hang">Đặt hàng</a>
            <a href="/lien-he">Liên hệ</a>
        </nav>
    </header>
    <div class="content">
        @yield('content')
    </div>

    <footer style="background: #f2f2f2; padding: 20px; text-align: center; margin-top: 30px;">
        <p>© 2025 Quán Chay Đại Bi. All rights reserved.</p>
        <p>Địa chỉ: 123 Đường Thanh Bình, Quận 1, TP. Đà Nẵng</p>
        <p>Điện thoại: 0909 123 456</p>
    </footer>

</body>
</html>
