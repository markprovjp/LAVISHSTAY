<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng đến với LavishStay</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Lato:wght@400;700&display=swap');
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            background-color: #f0f0f0;
            font-family: 'Lato', sans-serif;
            color: #555555;
        }
        .container {
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background-color: #1a1a1a;
            padding: 40px;
            text-align: center;
        }
        .header .logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 36px;
            color: #c0a062;
            letter-spacing: 2px;
            margin: 0;
        }
        .main-content {
            padding: 40px 30px;
        }
        .greeting h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 28px;
            color: #1a1a1a;
            margin: 0 0 15px 0;
            text-align: center;
        }
        .greeting p {
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 30px;
            text-align: center;
        }
        .section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #e5e5e5;
        }
        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px;
            color: #c0a062;
            margin: 0 0 20px 0;
            text-align: center;
        }
        .discount-box {
            background-color: #f8f8f8;
            border: 1px solid #e5e5e5;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .discount-code {
            background-color: #1a1a1a;
            color: #c0a062;
            font-size: 28px;
            font-weight: bold;
            padding: 15px 25px;
            letter-spacing: 2px;
            margin: 15px 0;
            display: inline-block;
            font-family: 'Lato', sans-serif;
        }
        .cta-button {
            background-color: #1a1a1a;
            color: #c0a062;
            padding: 15px 30px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            display: inline-block;
            border: 1px solid #c0a062;
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            background-color: #c0a062;
            color: #1a1a1a;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 10px 0;
            font-size: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-table td.label {
            font-weight: bold;
            color: #333333;
            width: 50px;
        }
        .benefits {
            background-color: #fdfdfd;
            border: 1px solid #e9e9e9;
            padding: 25px;
            margin: 30px 0;
        }
        .footer {
            background-color: #1a1a1a;
            color: #999999;
            padding: 30px;
            text-align: center;
            font-size: 12px;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #c0a062;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="logo">LAVISHSTAY</h1>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="greeting">
                <h2>Chào mừng quý khách đến với LavishStay</h2>
                <p>Chân thành cảm ơn quý khách đã đăng ký nhận tin từ LavishStay Thanh Hóa. Để chào mừng, chúng tôi xin gửi tặng quý khách mã giảm giá đặc biệt 20% cho lần đặt phòng tiếp theo.</p>
            </div>

            <!-- Discount Code Section -->
            <div class="section">
                <h3 class="section-title">Ưu đãi đặc biệt dành cho quý khách</h3>
                <div class="discount-box">
                    <div style="font-size: 16px; color: #555; margin-bottom: 15px;">Mã giảm giá của quý khách</div>
                    <div class="discount-code">{{ $couponCode }}</div>
                    <div style="font-size: 18px; font-weight: bold; color: #333; margin-top: 15px;">GIẢM GIÁ 20%</div>
                </div>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{ $bookingUrl }}" class="cta-button">Đặt phòng ngay</a>
                </div>
            </div>

            <!-- Expiry Information -->
            <div class="section">
                <h3 class="section-title">Thông tin sử dụng</h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Thời hạn:</td>
                        <td>{{ $expiryDate }}</td>
                    </tr>
                    <tr>
                        <td class="label">Áp dụng:</td>
                        <td>Tất cả loại phòng tại LavishStay Thanh Hóa</td>
                    </tr>
                    <tr>
                        <td class="label">Điều kiện:</td>
                        <td>Đặt phòng trực tuyến, có hiệu lực trong 90 ngày</td>
                    </tr>
                </table>
            </div>

            <!-- Benefits -->
            <div class="section">
                <h3 class="section-title">Tại sao chọn LavishStay Thanh Hóa?</h3>
                <div class="benefits">
                    <table class="info-table">
                        <tr>
                            <td class="label">🏖️</td>
                            <td>Vị trí đắc địa tại bãi biển Sầm Sơn tuyệt đẹp</td>
                        </tr>
                        <tr>
                            <td class="label">🏊</td>
                            <td>Hồ bơi trong nhà và spa thư giãn đẳng cấp</td>
                        </tr>
                        <tr>
                            <td class="label">🍽️</td>
                            <td>Nhà hàng fine dining với ẩm thực đặc sắc</td>
                        </tr>
                        <tr>
                            <td class="label">⭐</td>
                            <td>Đánh giá 9.4/10 từ khách hàng</td>
                        </tr>
                        <tr>
                            <td class="label">🚗</td>
                            <td>Chỉ 20km từ sân bay Thọ Xuân</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Instructions -->
            <div class="section">
                <h3 class="section-title">Hướng dẫn sử dụng mã giảm giá</h3>
                <p style="line-height: 1.7;">
                    <strong>Bước 1:</strong> Truy cập trang đặt phòng của chúng tôi<br>
                    <strong>Bước 2:</strong> Chọn ngày và loại phòng mong muốn<br>
                    <strong>Bước 3:</strong> Nhập mã <strong>{{ $couponCode }}</strong> vào ô "Mã giảm giá"<br>
                    <strong>Bước 4:</strong> Nhận ngay ưu đãi 20% và hoàn tất đặt phòng
                </p>
            </div>

            <!-- Contact Information -->
            <div class="section">
                <h3 class="section-title">Thông tin liên hệ</h3>
                <p style="line-height: 1.7;">
                    <strong>LavishStay Thanh Hóa</strong><br>
                    Địa chỉ: Số 27 Trần Phú, Phường Điện Biên, TP. Thanh Hóa<br>
                    Hotline: <a href="tel:842378936888" style="color: #c0a062;">84-237 893 6888</a><br>
                    Email: <a href="mailto:quyenjpn@gmail.com" style="color: #c0a062;">quyenjpn@gmail.com</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} LavishStay Hotel. All Rights Reserved.</p>
            <p>Quý khách nhận được email này vì đã đăng ký newsletter từ LavishStay.</p>
            <p><a href="{{ $unsubscribeUrl }}">Hủy đăng ký</a> nếu không muốn nhận email từ chúng tôi nữa.</p>
        </div>
    </div>
</body>
</html>
