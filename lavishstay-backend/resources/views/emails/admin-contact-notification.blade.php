<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo liên hệ mới</title>
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
        .header .subtitle {
            color: #999999;
            font-size: 16px;
            margin: 10px 0 0 0;
        }
        .main-content {
            padding: 40px 30px;
        }
        .alert {
            background-color: #f8f8f8;
            border: 1px solid #c0a062;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        .alert-text {
            font-size: 16px;
            font-weight: bold;
            color: #1a1a1a;
            margin: 0;
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
            width: 120px;
        }
        .message-content {
            background-color: #fdfdfd;
            border: 1px solid #e9e9e9;
            padding: 20px;
            margin: 20px 0;
        }
        .action-needed {
            background-color: #f8f8f8;
            border: 1px solid #e5e5e5;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .cta-button {
            background-color: #1a1a1a;
            color: #c0a062;
            padding: 12px 25px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            border: 1px solid #c0a062;
            margin-top: 15px;
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
            <p class="subtitle">Hệ thống thông báo liên hệ</p>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="alert">
                <p class="alert-text">⚠️ YÊU CẦU LIÊN HỆ MỚI CẦN XỬ LÝ</p>
            </div>

            <!-- Customer Information -->
            <div class="section">
                <h3 class="section-title">Thông tin khách hàng</h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Họ tên:</td>
                        <td>{{ $name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td><a href="mailto:{{ $email }}" style="color: #c0a062;">{{ $email }}</a></td>
                    </tr>
                    <tr>
                        <td class="label">Điện thoại:</td>
                        <td><a href="tel:{{ $phone }}" style="color: #c0a062;">{{ $phone }}</a></td>
                    </tr>
                    <tr>
                        <td class="label">Tiêu đề:</td>
                        <td>{{ $subject }}</td>
                    </tr>
                    <tr>
                        <td class="label">Thời gian:</td>
                        <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Message Content -->
            <div class="section">
                <h3 class="section-title">Nội dung tin nhắn</h3>
                <div class="message-content">
                    <p style="margin: 0; line-height: 1.7; font-style: italic;">{{ $messageContent }}</p>
                </div>
            </div>

            <!-- Action Required -->
            <div class="section">
                <h3 class="section-title">Hành động cần thực hiện</h3>
                <div class="action-needed">
                    <p><strong>1.</strong> Phản hồi email khách hàng trong vòng 24 giờ</p>
                    <p><strong>2.</strong> Gọi điện cho khách nếu cần thiết</p>
                    <p><strong>3.</strong> Cập nhật trạng thái xử lý trong hệ thống</p>
                    
                    <a href="mailto:{{ $email }}?subject=Re: {{ $subject }}" class="cta-button">
                        Phản hồi ngay
                    </a>
                </div>
            </div>

            <p style="font-size: 14px; color: #999; line-height: 1.7; margin-top: 30px;">
                <strong>Lưu ý:</strong> Đây là email tự động từ hệ thống contact form. 
                Vui lòng phản hồi trực tiếp cho khách hàng qua email: <a href="mailto:{{ $email }}" style="color: #c0a062;">{{ $email }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} LavishStay Hotel. All Rights Reserved.</p>
            <p>Email tự động từ hệ thống quản lý liên hệ LavishStay</p>
        </div>
    </div>
</body>
</html>
