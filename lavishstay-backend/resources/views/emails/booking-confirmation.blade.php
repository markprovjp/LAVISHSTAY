<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt phòng - LavishStay</title>
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
            color: #c0a062; /* Muted Gold */
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
            width: 180px;
        }
        .booking-summary {
            background-color: #f8f8f8;
            border: 1px solid #e5e5e5;
            padding: 20px;
            margin-bottom: 30px;
        }
        .booking-summary .code {
            font-size: 20px;
            font-weight: bold;
            color: #1a1a1a;
            text-align: center;
            letter-spacing: 1px;
        }
        .room-item {
            background-color: #fdfdfd;
            border: 1px solid #e9e9e9;
            padding: 20px;
            margin-bottom: 15px;
        }
        .room-item .room-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 0 0 15px 0;
        }
        .total-row {
            text-align: right;
            margin-top: 20px;
        }
        .total-row .total-label {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .total-row .total-value {
            font-size: 24px;
            font-weight: bold;
            color: #c0a062;
        }
        .notes ul {
            padding-left: 20px;
            margin: 0;
            list-style-type: square;
            color: #555;
        }
        .notes li {
            margin-bottom: 10px;
            line-height: 1.6;
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
                <h2>Xác nhận đặt phòng thành công</h2>
                <p>Kính gửi Quý khách {{ $representative->full_name ?? $booking->guest_name }},<br>
                Chúng tôi xin trân trọng xác nhận đặt phòng của Quý khách tại Khách sạn LavishStay đã được thực hiện thành công. Xin chân thành cảm ơn Quý khách đã tin tưởng và lựa chọn dịch vụ của chúng tôi.</p>
            </div>

            <div class="booking-summary">
                <div style="text-align: center; font-size: 16px; color: #555; margin-bottom: 10px;">Mã số đặt phòng của Quý khách</div>
                <div class="code">{{ $booking->booking_code }}</div>
            </div>

            <!-- Thông tin đặt phòng -->
            <div class="section">
                <h3 class="section-title">Thông tin lưu trú</h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Ngày nhận phòng:</td>
                        <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d/m/Y') }} (sau 14:00)</td>
                    </tr>
                    <tr>
                        <td class="label">Ngày trả phòng:</td>
                        <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d/m/Y') }} (trước 12:00)</td>
                    </tr>
                    <tr>
                        <td class="label">Số đêm:</td>
                        <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($booking->check_out_date)) }} đêm</td>
                    </tr>
                    <tr>
                        <td class="label">Tổng số khách:</td>
                        <td>{{ $booking->guest_count }} người</td>
                    </tr>
                </table>
            </div>

            <!-- Thông tin khách hàng -->
            <div class="section">
                <h3 class="section-title">Thông tin khách hàng</h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Họ và tên:</td>
                        <td>{{ $representative->full_name ?? $booking->guest_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td>{{ $representative->email ?? $booking->guest_email }}</td>
                    </tr>
                    <tr>
                        <td class="label">Số điện thoại:</td>
                        <td>{{ $representative->phone_number ?? $booking->guest_phone }}</td>
                    </tr>
                </table>
            </div>

            <!-- Chi tiết phòng -->
            <div class="section">
                <h3 class="section-title">Chi tiết phòng đặt</h3>
                @foreach($bookingRooms as $room)
                <div class="room-item">
                    <h4 class="room-name">{{ $room->room_name ?? 'Phòng #' . $room->room_id }}</h4>
                    <table class="info-table">
                        <tr><td class="label">Gói dịch vụ:</td><td>{{ $room->option_name ?? 'Gói tiêu chuẩn' }}</td></tr>
                        <tr><td class="label">Người lớn:</td><td>{{ $room->adults }} người</td></tr>
                        @if($room->children > 0)
                        <tr><td class="label">Trẻ em:</td><td>{{ $room->children }} trẻ</td></tr>
                        @endif
                        <tr><td class="label">Giá mỗi đêm:</td><td>{{ number_format($room->price_per_night, 0, ',', '.') }} VNĐ</td></tr>
                        <tr style="font-weight: bold; color: #333;"><td class="label">Tổng tiền phòng:</td><td>{{ number_format($room->total_price, 0, ',', '.') }} VNĐ</td></tr>
                    </table>
                </div>
                @endforeach
            </div>

            <!-- Tổng thanh toán -->
            <div classz="section">
                <h3 class="section-title">Tổng thanh toán</h3>
                <div class="total-row">
                    <span class="total-label">TỔNG CỘNG:</span>
                    <span class="total-value">{{ number_format($booking->total_price_vnd, 0, ',', '.') }} VNĐ</span>
                </div>
            </div>

            <!-- Lưu ý quan trọng -->
            <div class="section notes">
                <h3 class="section-title">Lưu ý quan trọng</h3>
                <ul>
                    <li>Quý khách vui lòng xuất trình giấy tờ tùy thân (CCCD/Passport) khi làm thủ tục nhận phòng.</li>
                    <li>Thời gian nhận phòng chính thức là 14:00 và thời gian trả phòng là 12:00.</li>
                    <li>Mọi yêu cầu thay đổi về đặt phòng, xin vui lòng liên hệ với chúng tôi ít nhất 24 giờ trước ngày nhận phòng.</li>
                    <li>Mã đặt phòng của Quý khách là <strong>{{ $booking->booking_code }}</strong>. Vui lòng sử dụng mã này cho mọi giao dịch.</li>
                </ul>
            </div>

            <!-- Thông tin liên hệ -->
            <div class="section">
                 <h3 class="section-title">Thông tin liên hệ</h3>
                 <p style="line-height: 1.7;">
                    <strong>LavishStay Hotel</strong><br>
                    Địa chỉ: Phường đông vệ , Tp thanh hoá<br>
                    Hotline: 1900 1234<br>
                    Email: support@lavishstay.com
                 </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} LavishStay Hotel. All Rights Reserved.</p>
            <p>Email này được gửi tự động. Quý khách vui lòng không trả lời trực tiếp email này.</p>
        </div>
    </div>
</body>
</html>