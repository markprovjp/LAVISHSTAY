<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt phòng - LavishStay</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 10px;
        }
        .booking-code {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            display: inline-block;
            margin: 20px 0;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h3 {
            color: #e74c3c;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .room-item {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border-left: 4px solid #e74c3c;
        }
        .total-amount {
            background-color: #2ecc71;
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
        }
        .contact-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        @media (max-width: 600px) {
            .info-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🏨 LavishStay</div>
            <h2>Xác nhận đặt phòng thành công!</h2>
            <div class="booking-code">{{ $booking->booking_code }}</div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="section">
            <h3>👤 Thông tin khách hàng</h3>
            <div class="info-row">
                <span class="info-label">Họ và tên:</span>
                <span>{{ $representative->full_name ?? $booking->guest_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span>{{ $representative->email ?? $booking->guest_email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Số điện thoại:</span>
                <span>{{ $representative->phone_number ?? $booking->guest_phone }}</span>
            </div>
        </div>

        <!-- Thông tin đặt phòng -->
        <div class="section">
            <h3>📅 Thông tin đặt phòng</h3>
            <div class="info-row">
                <span class="info-label">Ngày nhận phòng:</span>
                <span>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày trả phòng:</span>
                <span>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Số đêm:</span>
                <span>{{ \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($booking->check_out_date)) }} đêm</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tổng số khách:</span>
                <span>{{ $booking->guest_count }} người</span>
            </div>
        </div>

        <!-- Chi tiết phòng -->
        <div class="section">
            <h3>🏠 Chi tiết phòng đặt</h3>
            @foreach($bookingRooms as $room)
            <div class="room-item">
                <div class="info-row">
                    <span class="info-label">Tên phòng:</span>
                    <span>{{ $room->room_name ?? 'Phòng #' . $room->room_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Gói dịch vụ:</span>
                    <span>{{ $room->option_name ?? 'Gói tiêu chuẩn' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Người lớn:</span>
                    <span>{{ $room->adults }} người</span>
                </div>
                @if($room->children > 0)
                <div class="info-row">
                    <span class="info-label">Trẻ em:</span>
                    <span>{{ $room->children }} trẻ</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Giá mỗi đêm:</span>
                    <span>{{ number_format($room->price_per_night, 0, ',', '.') }} VNĐ</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tổng tiền phòng:</span>
                    <span><strong>{{ number_format($room->total_price, 0, ',', '.') }} VNĐ</strong></span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tổng thanh toán -->
        <div class="total-amount">
            💰 Tổng thanh toán: {{ number_format($booking->total_price_vnd, 0, ',', '.') }} VNĐ
        </div>

        <!-- Thông tin liên hệ -->
        <div class="contact-info">
            <h3 style="margin-top: 0; color: #e74c3c;">📞 Thông tin liên hệ</h3>
            <p><strong>LavishStay Hotel</strong></p>
            <p>📧 Email: support@lavishstay.com</p>
            <p>📱 Hotline: 1900 1234</p>
            <p>🏠 Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
        </div>

        <!-- Lưu ý quan trọng -->
        <div class="section">
            <h3>⚠️ Lưu ý quan trọng</h3>
            <ul>
                <li>Vui lòng mang theo giấy tờ tùy thân khi check-in</li>
                <li>Thời gian check-in: 14:00 | Thời gian check-out: 12:00</li>
                <li>Nếu có thay đổi, vui lòng liên hệ với chúng tôi ít nhất 24h trước</li>
                <li>Mã đặt phòng của bạn: <strong>{{ $booking->booking_code }}</strong></li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Cảm ơn quý khách đã chọn LavishStay Hotel!</p>
            <p>Chúng tôi rất mong được phục vụ quý khách.</p>
            <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">
            <p style="font-size: 12px; color: #999;">
                Email này được gửi tự động, vui lòng không trả lời trực tiếp.<br>
                Nếu có thắc mắc, vui lòng liên hệ hotline: 1900 1234
            </p>
        </div>
    </div>
</body>
</html>
