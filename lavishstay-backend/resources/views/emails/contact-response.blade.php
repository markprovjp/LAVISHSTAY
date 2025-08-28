<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cảm ơn bạn đã liên hệ</title>
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
        }
        .greeting p {
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 30px;
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
        .message-summary {
            background-color: #f8f8f8;
            border: 1px solid #e5e5e5;
            padding: 20px;
            margin: 20px 0;
        }
        .response-info {
            background-color: #fdfdfd;
            border: 1px solid #e9e9e9;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
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
                <h2>Kính gửi {{ $name }},</h2>
                <p>Chân thành cảm ơn quý khách đã liên hệ với LavishStay Thanh Hóa. Chúng tôi đã nhận được yêu cầu của quý khách và sẽ phản hồi trong thời gian sớm nhất.</p>
            </div>

            <!-- Message Summary -->
            <div class="section">
                <h3 class="section-title">Tóm tắt yêu cầu của quý khách</h3>
                <div class="message-summary">
                    <table class="info-table">
                        <tr>
                            <td class="label">Tiêu đề:</td>
                            <td>{{ $subject }}</td>
                        </tr>
                        <tr>
                            <td class="label">Email:</td>
                            <td>{{ $email }}</td>
                        </tr>
                        <tr>
                            <td class="label">Số điện thoại:</td>
                            <td>{{ $phone }}</td>
                        </tr>
                    </table>
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e5e5;">
                        <strong>Nội dung:</strong><br>
                        <span style="font-style: italic;">{{ $messageContent }}</span>
                    </div>
                </div>
            </div>

            <!-- Response Time -->
            <div class="section">
                <h3 class="section-title">Thông tin phản hồi</h3>
                <div class="response-info">
                    <p style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Thời gian phản hồi dự kiến: 24 giờ</p>
                    <p>Đội ngũ chuyên viên của chúng tôi sẽ liên hệ lại với quý khách trong vòng 24 giờ làm việc kể từ khi nhận được yêu cầu này.</p>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="section">
                <h3 class="section-title">Thông tin liên hệ khẩn cấp</h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Hotline:</td>
                        <td><a href="tel:842378936888" style="color: #c0a062;">84-237 893 6888</a></td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td><a href="mailto:quyenjpn@gmail.com" style="color: #c0a062;">quyenjpn@gmail.com</a></td>
                    </tr>
                    <tr>
                        <td class="label">Địa chỉ:</td>
                        <td>Số 27 Trần Phú, Phường Điện Biên, TP. Thanh Hóa</td>
                    </tr>
                    <tr>
                        <td class="label">Giờ phục vụ:</td>
                        <td>24/7</td>
                    </tr>
                </table>
            </div>

            <p style="margin-top: 30px; line-height: 1.7;">
                Cảm ơn quý khách đã lựa chọn LavishStay Thanh Hóa. Chúng tôi mong được phục vụ quý khách!<br><br>
                Trân trọng,<br>
                <strong>Đội ngũ LavishStay Thanh Hóa</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} LavishStay Hotel. All Rights Reserved.</p>
            <p>Quý khách nhận được email này vì đã gửi yêu cầu liên hệ đến chúng tôi.</p>
            <p>
                <a href="mailto:quyenjpn@gmail.com">Liên hệ hỗ trợ</a> | 
                <a href="tel:842378936888">Hotline: 84-237 893 6888</a>
            </p>
        </div>
    </div>
</body>
</html>
