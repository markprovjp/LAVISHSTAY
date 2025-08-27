<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class DashboardExport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new BusinessSummarySheet($this->data),
            new RoomTypeStatsSheet($this->data),
            new CustomerStatsSheet($this->data),
            new FinancialMetricsSheet($this->data),
            new RecentBookingsSheet($this->data),
            new ChartDataSheet($this->data),
        ];
    }
}

class BusinessSummarySheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Tổng quan kinh doanh';
    }

    public function collection()
    {
        $summary = $this->data['businessSummary'];
        
        return collect([
            ['THÔNG TIN PHÒNG', ''],
            ['Tổng số phòng', $summary['rooms']['total']],
            ['Phòng khả dụng', $summary['rooms']['available']],
            ['Phòng đang sử dụng', $summary['rooms']['occupied']],
            ['Phòng bảo trì', $summary['rooms']['maintenance'] ?? 0],
            ['', ''],
            ['CHỈ SỐ KINH DOANH', ''],
            ['Tỷ lệ lấp đầy (%)', $summary['occupancy_rate']],
            ['ADR - Giá phòng TB (VNĐ)', number_format($summary['adr'])],
            ['RevPAR (VNĐ)', number_format($summary['revpar'])],
            ['Tỷ lệ hủy phòng (%)', $summary['cancellation_rate']],
            ['', ''],
            ['DOANH THU', ''],
            ['Doanh thu hôm nay (VNĐ)', number_format($summary['revenue']['today'])],
            ['Doanh thu tuần này (VNĐ)', number_format($summary['revenue']['this_week'] ?? 0)],
            ['Doanh thu tháng này (VNĐ)', number_format($summary['revenue']['this_month'] ?? 0)],
            ['', ''],
            ['ĐẶT PHÒNG', ''],
            ['Đặt phòng hôm nay', $summary['bookings']['today']],
            ['Đặt phòng tuần này', $summary['bookings']['this_week'] ?? 0],
            ['Đặt phòng tháng này', $summary['bookings']['this_month'] ?? 0],
            ['Tổng đặt phòng', $summary['bookings']['total'] ?? 0],
            ['', ''],
            ['THỜI GIAN BÁO CÁO', ''],
            ['Từ ngày', $this->data['startDate']->format('d/m/Y')],
            ['Đến ngày', $this->data['endDate']->format('d/m/Y')],
            ['Loại báo cáo', $this->data['reportType'] ?? 'Tùy chỉnh'],
        ]);
    }

    public function headings(): array
    {
        return [
            'Chỉ số',
            'Giá trị'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A:B' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ],
                ],
            ],
            // Header rows styling
            'A2:B2' => ['font' => ['bold' => true, 'color' => ['rgb' => '1F2937']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']]],
            'A7:B7' => ['font' => ['bold' => true, 'color' => ['rgb' => '1F2937']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']]],
            'A13:B13' => ['font' => ['bold' => true, 'color' => ['rgb' => '1F2937']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']]],
            'A18:B18' => ['font' => ['bold' => true, 'color' => ['rgb' => '1F2937']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']]],
            'A24:B24' => ['font' => ['bold' => true, 'color' => ['rgb' => '1F2937']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']]],
        ];
    }
}

class RoomTypeStatsSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Thống kê theo loại phòng';
    }

    public function collection()
    {
        $roomTypes = $this->data['roomTypeStats'];
        $result = collect();

        // Revenue by type
        $result->push(['DOANH THU THEO LOẠI PHÒNG', '', '', '']);
        $result->push(['Loại phòng', 'Số booking', 'Doanh thu (VNĐ)', 'Tỷ trọng (%)']);
        
        $totalRevenue = collect($roomTypes['booking_by_type'])->sum('revenue');
        
        foreach ($roomTypes['booking_by_type'] as $type) {
            $percentage = $totalRevenue > 0 ? round(($type->revenue / $totalRevenue) * 100, 2) : 0;
            $result->push([
                $type->name,
                $type->bookings ?? 0,
                number_format($type->revenue),
                $percentage . '%'
            ]);
        }

        $result->push(['', '', '', '']);
        
        // Occupancy by type
        $result->push(['TỶ LỆ LẤP ĐẦY THEO LOẠI PHÒNG', '', '', '']);
        $result->push(['Loại phòng', 'Tổng phòng', 'Phòng đang sử dụng', 'Tỷ lệ lấp đầy (%)']);
        
        foreach ($roomTypes['occupancy_by_type'] as $type) {
            $result->push([
                $type->name,
                $type->total_rooms ?? 0,
                $type->occupied_rooms ?? 0,
                ($type->occupancy_rate ?? 0) . '%'
            ]);
        }

        return $result;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A:D' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ],
                ],
            ],
            'A1:D1' => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '059669']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A2:D2' => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ECFDF5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }
}

class CustomerStatsSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Thống kê khách hàng';
    }

    public function collection()
    {
        $customerStats = $this->data['customerStats'];
        $result = collect();

        // Summary
        $result->push(['TỔNG QUAN KHÁCH HÀNG', '', '', '']);
        $result->push(['Khách mới', $customerStats['new_customers'], '', '']);
        $result->push(['Khách quen', $customerStats['returning_customers'], '', '']);
        $result->push(['', '', '', '']);

        // Top customers
        $result->push(['TOP KHÁCH HÀNG VIP', '', '', '']);
        $result->push(['Tên khách hàng', 'Email', 'Số lần đặt', 'Tổng chi tiêu (VNĐ)']);
        
        foreach ($customerStats['top_customers'] as $customer) {
            $result->push([
                $customer->guest_name,
                $customer->guest_email,
                $customer->booking_count,
                number_format($customer->total_spent)
            ]);
        }

        return $result;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A:D' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ],
                ],
            ],
            'A1:D1' => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A5:D5' => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A6:D6' => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF2F2']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }
}

class FinancialMetricsSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Chỉ số tài chính';
    }

    public function collection()
    {
        $financial = $this->data['financialMetrics'];
        
        return collect([
            ['CHỈ SỐ TÀI CHÍNH', ''],
            ['Tổng đã thu (VNĐ)', number_format($financial['total_collected'])],
            ['Chờ thanh toán (VNĐ)', number_format($financial['pending_payments'])],
            ['Tổng giá trị booking (VNĐ)', number_format($financial['total_bookings_value'])],
            ['', ''],
            ['PHÂN TÍCH TÀI CHÍNH', ''],
            ['Tỷ lệ thu tiền (%)', $financial['total_bookings_value'] > 0 ? round(($financial['total_collected'] / $financial['total_bookings_value']) * 100, 2) : 0],
            ['Doanh thu chưa thu (VNĐ)', number_format($financial['total_bookings_value'] - $financial['total_collected'])],
            ['', ''],
            ['THỜI GIAN BÁO CÁO', ''],
            ['Từ ngày', $this->data['startDate']->format('d/m/Y')],
            ['Đến ngày', $this->data['endDate']->format('d/m/Y')],
            ['Số ngày', $this->data['startDate']->diffInDays($this->data['endDate']) + 1],
            ['Xuất lúc', now()->format('d/m/Y H:i:s')],
        ]);
    }

    public function headings(): array
    {
        return [
            'Chỉ số tài chính',
            'Giá trị'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '16A34A']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A:B' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ],
                ],
            ],
            'A2:B2' => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0FDF4']]],
            'A6:B6' => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0FDF4']]],
            'A10:B10' => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0FDF4']]],
        ];
    }
}

class RecentBookingsSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Đặt phòng gần đây';
    }

    public function collection()
    {
        $bookings = $this->data['detailTables']['recent_bookings'] ?? collect([]);
        
        return $bookings->map(function ($booking) {
            return [
                $booking->booking_code ?? '',
                $booking->guest_name ?? '',
                $booking->guest_email ?? '',
                $booking->guest_phone ?? '',
                $booking->status ?? '',
                number_format($booking->total_price_vnd ?? 0),
                isset($booking->check_in_date) ? \Carbon\Carbon::parse($booking->check_in_date)->format('d/m/Y') : '',
                isset($booking->check_out_date) ? \Carbon\Carbon::parse($booking->check_out_date)->format('d/m/Y') : '',
                isset($booking->created_at) ? \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i') : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Mã booking',
            'Tên khách',
            'Email',
            'Điện thoại',
            'Trạng thái',
            'Tổng tiền (VNĐ)',
            'Check-in',
            'Check-out',
            'Ngày tạo'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '7C3AED']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A:I' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ],
                ],
            ],
        ];
    }
}

class ChartDataSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Dữ liệu biểu đồ';
    }

    public function collection()
    {
        $chartData = $this->data['chartData'];
        $result = collect();

        // Revenue data
        $result->push(['DOANH THU THEO NGÀY', '', '']);
        $result->push(['Ngày', 'Doanh thu (VNĐ)', '']);
        
        foreach ($chartData['revenue'] as $item) {
            $result->push([
                \Carbon\Carbon::parse($item->date)->format('d/m/Y'),
                $item->revenue,
                ''
            ]);
        }

        $result->push(['', '', '']);

        // Booking data
        $result->push(['SỐ LƯỢT ĐẶT PHÒNG THEO NGÀY', '', '']);
        $result->push(['Ngày', 'Số lượt đặt', '']);
        
        foreach ($chartData['bookings'] as $item) {
            $result->push([
                \Carbon\Carbon::parse($item->date)->format('d/m/Y'),
                $item->bookings,
                ''
            ]);
        }

        return $result;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A:C' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ],
                ],
            ],
            'A1:C1' => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F59E0B']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A2:C2' => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBEB']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }
}