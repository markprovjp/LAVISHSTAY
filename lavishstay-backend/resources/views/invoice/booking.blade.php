<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoá đơn - {{ $booking->booking_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
          body {
                /* Use DejaVu Sans and Noto fallbacks for better CJK/glyph coverage (dompdf bundles DejaVu)
                    Keep Arial as a last fallback for browsers. */
                font-family: 'DejaVu Sans', 'Noto Sans', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            /* Use table layout so .header-left / .header-right (table-cells) have a proper parent
               — dompdf requires table-cell elements to belong to a table; using flex can create
               orphan table-cell frames and trigger "Parent table not found for table cell". */
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid #1e88e5; /* primary site color */
            padding-bottom: 16px;
        }
        
        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        
        .header-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }
        
        .hotel-name {
            font-size: 22px;
            font-weight: 700;
            color: #1e88e5;
            margin-bottom: 6px;
        }
        
        .hotel-info {
            font-size: 12px;
            color: #666;
            line-height: 1.3;
        }
        
        .invoice-title {
            font-size: 22px;
            font-weight: 800;
            color: #0d47a1;
            margin-bottom: 8px;
        }
        
        .invoice-meta {
            font-size: 12px;
            color: #666;
        }
        
        .invoice-details {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        
        .bill-to {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        
        .invoice-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #1890ff;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5px;
        }
        
        .info-row {
            margin-bottom: 5px;
            font-size: 13px;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }
        
        .table th {
            background-color: #f3f7fb;
            border: 1px solid #e6eef9;
            padding: 10px 8px;
            text-align: left;
            font-weight: 700;
            color: #0d47a1;
        }

        .invoice-container {
            box-shadow: 0 6px 18px rgba(13,71,161,0.08);
            border-radius: 6px;
            padding: 24px;
        }
        
        .table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            vertical-align: top;
        }
        
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-section {
            margin-top: 20px;
            float: right;
            width: 300px;
        }
        
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        
        .total-label {
            display: table-cell;
            text-align: right;
            padding-right: 20px;
            font-weight: bold;
        }
        
        .total-value {
            display: table-cell;
            text-align: right;
            width: 120px;
        }
        
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #d32f2f;
            border-top: 2px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
            text-align: center;
        }
        
        .notes {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #1890ff;
            font-size: 12px;
        }
        
        .currency {
            font-family: monospace;
        }

        /* force fonts that contain CJK glyphs on specific elements */
        .cjk {
            font-family: 'DejaVu Sans', 'Noto Sans', 'Noto Sans CJK', Arial, sans-serif;
        }
        
        @media print {
            body { margin: 0; }
            .invoice-container { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                {{-- If a logo URL/path is provided, show the image; otherwise fall back to hotel name text --}}
                @if(!empty($hotel_info['logo']))
                    {{-- dompdf might require absolute paths or data URIs for images. Make sure controller passes a usable URL/path. --}}
                    <img src="{{ $hotel_info['logo'] }}" alt="{{ $hotel_info['name'] }} logo" style="max-height:80px; max-width:220px; display:block; margin-bottom:8px; object-fit:contain;">
                @else
                    <div class="hotel-name">{{ $hotel_info['name'] }}</div>
                @endif
                <div class="hotel-info">
                    {{ $hotel_info['address'] }}<br>
                    Điện thoại: {{ $hotel_info['phone'] }}<br>
                    Email: {{ $hotel_info['email'] }}<br>
                    Website: {{ $hotel_info['website'] }}
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-meta">
                    <strong>Số hoá đơn:</strong> {{ $invoice_number }}<br>
                    <strong>Ngày:</strong> {{ $invoice_date }}
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="bill-to">
                <div class="section-title">THÔNG TIN KHÁCH HÀNG</div>
                <div class="info-row">
                    <span class="info-label">Tên:</span> {{ $booking->guest_name }}
                </div>
                <div class="info-row">
                    <span class="info-label">Điện thoại:</span> {{ $booking->guest_phone }}
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span> {{ $booking->guest_email ?? 'Chưa có' }}
                </div>
                <div class="info-row">
                    <span class="info-label">Số khách:</span> {{ $booking->guest_count }} người
                </div>
            </div>
            <div class="invoice-info">
                <div class="section-title">THÔNG TIN ĐẶT PHÒNG</div>
                <div class="info-row">
                    <span class="info-label">Mã booking:</span> {{ $booking->booking_code }}
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày nhận:</span> {{ date('d/m/Y', strtotime($booking->check_in_date)) }}
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày trả:</span> {{ date('d/m/Y', strtotime($booking->check_out_date)) }}
                </div>
                <div class="info-row">
                    <span class="info-label">Trạng thái:</span> 
                    @if($booking->status == 'confirmed')
                        Đã xác nhận
                    @elseif($booking->status == 'completed')
                        Hoàn thành
                    @elseif($booking->status == 'cancelled')
                        Đã hủy
                    @else
                        Chờ xử lý
                    @endif
                </div>
            </div>
        </div>

        <!-- Room Details -->
        @if(count($booking_rooms) > 0)
        <div class="section-title">CHI TIẾT PHÒNG</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Tên gói / Phòng</th>
                    <th class="text-center">Số đêm</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Group rooms by package/option_name so we show the package once
                    $groups = [];
                    foreach ($booking_rooms as $r) {
                        $key = $r->option_name ?? 'Gói cơ bản';
                        if (!isset($groups[$key])) $groups[$key] = ['rows' => [], 'total_price' => 0, 'price_per_night' => $r->price_per_night ?? 0, 'nights' => $r->nights ?? 1];
                        $groups[$key]['rows'][] = $r;
                        $groups[$key]['total_price'] += $r->total_price ?? 0;
                    }
                @endphp

                @foreach($groups as $packageName => $group)
                @php
                    // join room names and unique representative names for display
                    $roomNames = array_filter(array_map(function($r){ return $r->room_name ?? null; }, $group['rows']));
                    $roomList = count($roomNames) ? implode(', ', $roomNames) : '-';
                    $repNames = array_unique(array_filter(array_map(function($r){ return $r->representative_name ?? null; }, $group['rows'])));
                    $repList = count($repNames) ? implode(', ', $repNames) : null;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $packageName }}</strong>
                        <br><small>Số phòng: {{ count($group['rows']) }}</small>
                        <br><small>Phòng: {{ $roomList }}</small>
                        @if($repList)
                            <br><small class="cjk">Đại diện: {{ $repList }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $group['nights'] }}</td>
                    <td class="text-right currency">{!! number_format($group['total_price'] ?? 0, 0, ',', '.') . ' VND' !!}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Services -->
        @if(count($booking_services) > 0)
        <div class="section-title">DỊCH VỤ PHÁT SINH</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Tên dịch vụ</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking_services as $service)
                <tr>
                    <td>
                        <strong>{{ $service->service_name }}</strong>
                        @if($service->service_description)
                            <br><small>{{ $service->service_description }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $service->quantity }} {{ $service->service_unit ?? '' }}</td>
                    <td class="text-right currency">{{ number_format($service->price_vnd ?? 0, 0, ',', '.') }} VND</td>
                    <td class="text-right currency">{{ number_format(($service->quantity * $service->price_vnd) ?? 0, 0, ',', '.') }} VND</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Totals -->
        <div class="total-section">
                <div class="total-row">
                    <div class="total-label">Tổng tiền đặt phòng (đã bao gồm gói/phòng):</div>
                    <div class="total-value currency">{!! number_format($room_total ?? ($booking->total_price_vnd ?? 0), 0, ',', '.') . ' VND' !!}</div>
                </div>
            @if($service_total > 0)
            <div class="total-row">
                <div class="total-label">Tổng dịch vụ:</div>
                <div class="total-value currency">{{ number_format($service_total, 0, ',', '.') }} VND</div>
            </div>
            @endif
            <div class="total-row grand-total">
                <div class="total-label">TỔNG CỘNG:</div>
                <div class="total-value currency">{{ number_format($grand_total, 0, ',', '.') }} VND</div>
            </div>
        </div>

        <div style="clear: both;"></div>

        <!-- Notes -->
        @if($booking->notes)
        <div class="notes">
            <strong>Ghi chú:</strong><br>
            {{ $booking->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Cảm ơn quý khách đã sử dụng dịch vụ của {{ $hotel_info['name'] }}!</strong></p>
            <p>Hoá đơn này được tạo tự động vào {{ date('d/m/Y H:i:s') }}</p>
            <p>Mọi thắc mắc xin liên hệ: {{ $hotel_info['phone'] }} hoặc {{ $hotel_info['email'] }}</p>
        </div>
    </div>
</body>
</html>
