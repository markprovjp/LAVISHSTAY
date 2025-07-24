-- ===== PRICING SYSTEM DATA =====
-- Dữ liệu mẫu cho hệ thống định giá động

-- Thêm loại mùa giá (4 mùa chính với khoảng biến động chuẩn)
INSERT INTO season_types (type_name, display_name, base_multiplier, min_multiplier, max_multiplier, typical_increase_range, description) VALUES 
('low', 'Thấp điểm', 0.90, 0.85, 0.95, 'Giảm 5-15%', 'Mùa thấp điểm - ít khách, giá thấp nhất'),
('high', 'Cao điểm', 1.07, 1.05, 1.10, 'Tăng 5-10%', 'Mùa cao điểm - tăng 5-10% so với giá cơ bản'),
('peak', 'Đỉnh điểm', 1.30, 1.20, 1.45, 'Tăng 20-45%', 'Mùa lễ hội - tăng 20-45% so với giá cơ bản'),
('festival', 'Lễ hội đặc biệt', 1.40, 1.25, 1.50, 'Tăng 25-50%', 'Các sự kiện lễ hội lớn với mức tăng cao nhất');

-- Thêm mùa giá chi tiết cho năm 2025 (cập nhật theo khoảng biến động chuẩn)
INSERT INTO pricing_seasons (season_name, season_type_id, start_date, end_date, year_applicable, price_multiplier, description, priority) VALUES 
-- Mùa thấp điểm (giảm nhẹ so với giá cơ bản)
('Thấp điểm đầu năm', 1, '2025-01-15', '2025-03-15', 2025, 0.90, 'Sau Tết đến trước mùa du lịch xuân', 1),
('Thấp điểm giữa năm', 1, '2025-05-15', '2025-07-15', 2025, 0.85, 'Sau lễ 30/4 đến trước hè', 1),
('Thấp điểm cuối năm', 1, '2025-11-15', '2025-12-15', 2025, 0.90, 'Sau thu đến trước Noel', 1),

-- Mùa cao điểm (tăng 5-10% so với giá cơ bản)
('Cao điểm hè', 2, '2025-07-15', '2025-08-31', 2025, 1.08, 'Mùa hè du lịch - tăng 8%', 2),
('Cao điểm Thu', 2, '2025-09-01', '2025-11-15', 2025, 1.06, 'Mùa thu thời tiết đẹp - tăng 6%', 2),
('Cao điểm Xuân', 2, '2025-03-15', '2025-05-15', 2025, 1.07, 'Mùa xuân thời tiết đẹp - tăng 7%', 2),

-- Mùa đỉnh điểm lễ hội (tăng 20-45% so với giá cơ bản)
('Tết Nguyên Đán 2025', 3, '2025-01-28', '2025-02-05', 2025, 1.35, 'Tết Nguyên Đán 2025 - tăng 35%', 5),
('Lễ 30/4 - 1/5', 3, '2025-04-27', '2025-05-05', 2025, 1.25, 'Nghỉ lễ giải phóng miền Nam - tăng 25%', 5),
('Lễ Quốc Khánh', 3, '2025-08-30', '2025-09-03', 2025, 1.22, 'Lễ Quốc Khánh 2/9 - tăng 22%', 5),
('Noel - Tết Dương', 3, '2025-12-20', '2026-01-05', 2025, 1.40, 'Mùa lễ hội cuối năm - tăng 40%', 5),

-- Lễ hội đặc biệt (mức tăng cao nhất)
('Lễ hội Hoa Đào', 4, '2025-02-15', '2025-02-22', 2025, 1.45, 'Lễ hội hoa đào Nhật Tân - tăng 45%', 8),
('Hội nghị ASEAN+3', 4, '2025-11-10', '2025-11-15', 2025, 1.30, 'Hội nghị ASEAN+3 tại Hà Nội - tăng 30%', 8);

-- Thêm phụ thu cuối tuần (10-15% nhất quán cho tất cả hạng phòng)
INSERT INTO weekend_surcharge (surcharge_name, day_of_week_pattern, surcharge_type, surcharge_value, min_surcharge_percentage, max_surcharge_percentage, applicable_room_types, applies_consistently, customer_notification_required, description) VALUES 
('Phụ thu cuối tuần tiêu chuẩn', 'friday,saturday', 'percentage', 12.00, 10.00, 15.00, 'ALL', TRUE, TRUE, 'Phụ thu 12% cho thứ 6 và thứ 7 - áp dụng nhất quán tất cả hạng phòng'),
('Phụ thu Chủ nhật', 'sunday', 'percentage', 10.00, 10.00, 15.00, 'ALL', TRUE, TRUE, 'Phụ thu 10% cho Chủ nhật - thông báo rõ ràng khi đặt phòng'),
('Phụ thu cuối tuần đặc biệt', 'friday,saturday,sunday', 'percentage', 15.00, 10.00, 15.00, '["The Level Premium", "The Level Premium Corner", "The Level Suite", "Suite", "Presidential Suite"]', TRUE, TRUE, 'Phụ thu 15% cho phòng cao cấp trong dịp đặc biệt');

-- Thêm giá đặc biệt theo sự kiện (Dynamic Pricing theo công suất và sự kiện)
INSERT INTO event_pricing (event_name, event_type, start_date, end_date, price_multiplier, occupancy_threshold, min_nights, description) VALUES 
('Lễ hội Bia Hà Nội', 'festival', '2025-08-15', '2025-08-25', 1.30, 0.75, 2, 'Lễ hội bia quốc tế - điều chỉnh giá linh hoạt theo công suất'),
('Hội nghị Công nghệ FPT', 'conference', '2025-10-20', '2025-10-25', 1.40, 0.80, 1, 'Hội nghị công nghệ lớn - dynamic pricing theo tình trạng phòng'),
('Marathon Hà Nội', 'local_event', '2025-11-01', '2025-11-03', 1.25, 0.70, 1, 'Giải marathon quốc tế - giá thay đổi theo demand'),
('Tuần lễ thời trang', 'special_occasion', '2025-12-01', '2025-12-07', 1.35, 0.85, 2, 'Vietnam Fashion Week - pricing tối ưu hóa doanh thu');

-- Thêm hợp đồng khách đoàn mẫu
INSERT INTO group_contracts (contract_name, contract_type, company_name, contact_person, contact_email, contact_phone, 
    min_rooms_per_booking, min_nights_per_booking, min_bookings_per_year, min_revenue_per_year,
    discount_type, discount_value, max_discount_amount, start_date, end_date, applicable_room_types, description) VALUES 

('Hợp đồng FPT Corporation', 'corporate', 'Tập đoàn FPT', 'Nguyễn Văn A', 'a.nguyen@fpt.com.vn', '0987654321',
 5, 2, 12, 500000000, 'percentage', 15.00, 50000000, '2024-01-01', '2024-12-31', 
 '["Deluxe Room", "Premium Corner", "The Level Premium"]', 'Hợp đồng doanh nghiệp FPT - ưu đãi 15%'),

('Hợp đồng Viettel Group', 'corporate', 'Viettel Group', 'Trần Thị B', 'b.tran@viettel.com.vn', '0912345678',
 10, 1, 24, 800000000, 'percentage', 18.00, 80000000, '2024-01-01', '2024-12-31',
 'ALL', 'Hợp đồng doanh nghiệp Viettel - ưu đãi 18%'),

('Du lịch Saigon Tourist', 'travel_agent', 'Saigon Tourist', 'Lê Văn C', 'c.le@saigontourist.net', '0923456789',
 15, 3, 36, 1200000000, 'tiered', 20.00, 100000000, '2024-01-01', '2024-12-31',
 '["Deluxe Room", "Premium Corner"]', 'Đại lý du lịch lớn - ưu đãi theo bậc'),

('Khách lưu trú dài hạn', 'long_stay', 'Individual', 'Phạm Thị D', 'phamd@email.com', '0934567890',
 1, 30, 4, 200000000, 'percentage', 25.00, 30000000, '2024-01-01', '2024-12-31',
 'ALL', 'Ưu đãi khách lưu trú dài hạn trên 30 ngày');

-- Thêm chính sách lưu trú dài ngày
INSERT INTO long_stay_pricing (policy_name, min_nights, max_nights, discount_type, discount_value, 
    applicable_room_types, valid_from, valid_to, includes_breakfast, includes_housekeeping_frequency, 
    includes_laundry, description) VALUES 

('Lưu trú 1 tuần', 7, 13, 'percentage', 5.00, 'ALL', '2024-01-01', '2024-12-31', 
 FALSE, 2, FALSE, 'Giảm 5% cho lưu trú từ 7-13 đêm'),

('Lưu trú 2 tuần', 14, 29, 'percentage', 10.00, 'ALL', '2024-01-01', '2024-12-31', 
 TRUE, 3, FALSE, 'Giảm 10% cho lưu trú từ 14-29 đêm, bao gồm ăn sáng'),

('Lưu trú 1 tháng', 30, 59, 'percentage', 18.00, 'ALL', '2024-01-01', '2024-12-31', 
 TRUE, 2, TRUE, 'Giảm 18% cho lưu trú từ 30-59 đêm, full service'),

('Lưu trú dài hạn', 60, NULL, 'percentage', 25.00, '["Deluxe Room", "Premium Corner", "The Level Premium"]', '2024-01-01', '2024-12-31', 
 TRUE, 1, TRUE, 'Giảm 25% cho lưu trú từ 60 đêm trở lên, VIP service'),

('Lưu trú kinh doanh', 5, 30, 'percentage', 8.00, '["The Level Premium", "The Level Premium Corner", "The Level Suite"]', '2024-01-01', '2024-12-31', 
 TRUE, 1, FALSE, 'Gói đặc biệt cho khách kinh doanh');

-- Thêm dữ liệu công suất mẫu cho 30 ngày tới (để test dynamic pricing)
INSERT INTO daily_occupancy (room_type_id, date_applicable, total_rooms, occupied_rooms, available_rooms, occupancy_rate, forecasted_occupancy, price_adjustment_factor) VALUES 
-- Deluxe Room (type_id = 1, total 90 rooms)
(1, CURDATE(), 90, 65, 25, 0.72, 0.75, 1.05),
(1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), 90, 72, 18, 0.80, 0.85, 1.10),
(1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), 90, 58, 32, 0.64, 0.70, 1.00),
(1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), 90, 45, 45, 0.50, 0.55, 0.95),

-- Premium Corner (type_id = 2, total 96 rooms)  
(2, CURDATE(), 96, 78, 18, 0.81, 0.85, 1.12),
(2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), 96, 85, 11, 0.89, 0.92, 1.18),
(2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), 96, 68, 28, 0.71, 0.75, 1.06),
(2, DATE_ADD(CURDATE(), INTERVAL 3 DAY), 96, 52, 44, 0.54, 0.60, 0.98),

-- The Level Premium (type_id = 3, total 36 rooms)
(3, CURDATE(), 36, 32, 4, 0.89, 0.92, 1.20),
(3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), 36, 34, 2, 0.94, 0.97, 1.25),
(3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), 36, 28, 8, 0.78, 0.82, 1.10),
(3, DATE_ADD(CURDATE(), INTERVAL 3 DAY), 36, 22, 14, 0.61, 0.65, 1.02);


-- ===== DỮ LIỆU LỊCH SỬ GIÁ PHÒNG (PRICE HISTORY) =====
INSERT INTO price_history (
    room_type_id, date_applicable, base_price_single, base_price_double,
    final_price_single, final_price_double, season_multiplier, weekend_surcharge,
    event_multiplier, occupancy_rate, calculated_at, pricing_rules_applied, notes
) VALUES 

-- Deluxe Room price history (type_id = 1)
(1, '2024-06-15', 1200000, 1480000, 1200000, 1480000, 1.00, 0, 1.00, 0.72, '2024-06-14 23:00:00', 
 '{"season": "standard", "weekend": false, "event": "none"}', 'Standard weekday pricing'),
(1, '2024-06-16', 1200000, 1480000, 1380000, 1702000, 1.00, 200000, 1.00, 0.78, '2024-06-15 23:00:00',
 '{"season": "standard", "weekend": true, "surcharge": "saturday_12pct"}', 'Saturday weekend surcharge applied'),
(1, '2024-06-17', 1200000, 1480000, 1320000, 1628000, 1.00, 148000, 1.00, 0.65, '2024-06-16 23:00:00',
 '{"season": "standard", "weekend": true, "surcharge": "sunday_10pct"}', 'Sunday weekend surcharge applied'),

-- Premium Corner price history (type_id = 2)  
(2, '2024-07-20', 1500000, 1820000, 1500000, 1820000, 1.00, 0, 1.00, 0.81, '2024-07-19 23:00:00',
 '{"season": "standard", "weekend": false, "event": "none"}', 'Standard weekday pricing'),
(2, '2024-07-21', 1500000, 1820000, 1680000, 2038000, 1.00, 180000, 1.00, 0.85, '2024-07-20 23:00:00',
 '{"season": "standard", "weekend": true, "surcharge": "saturday_12pct"}', 'Weekend premium pricing'),
(2, '2024-07-22', 1500000, 1820000, 1650000, 2002000, 1.00, 150000, 1.00, 0.89, '2024-07-21 23:00:00',
 '{"season": "standard", "weekend": true, "surcharge": "sunday_10pct"}', 'Sunday pricing with high occupancy'),

-- The Level Premium price history (type_id = 3)
(3, '2024-08-10', 2200000, 2680000, 2376000, 2948000, 1.08, 0, 1.00, 0.92, '2024-08-09 23:00:00',
 '{"season": "high", "multiplier": "summer_8pct", "weekend": false}', 'High season summer pricing'),
(3, '2024-08-11', 2200000, 2680000, 2640000, 3212000, 1.08, 264000, 1.00, 0.95, '2024-08-10 23:00:00',
 '{"season": "high", "weekend": true, "surcharge": "saturday_12pct"}', 'High season weekend peak'),

-- Suite price history với event pricing (type_id = 4)
(4, '2025-01-28', 3500000, 4200000, 4725000, 5670000, 1.35, 0, 1.00, 0.98, '2025-01-27 23:00:00',
 '{"season": "peak", "event": "tet_2025", "multiplier": "35pct_increase"}', 'Tết Nguyên Đán 2025 peak pricing'),
(4, '2025-01-29', 3500000, 4200000, 5040000, 6048000, 1.35, 420000, 1.00, 1.00, '2025-01-28 23:00:00',
 '{"season": "peak", "event": "tet_2025", "weekend": true, "sold_out": true}', 'Tết weekend - fully booked'),

-- Presidential Suite pricing (type_id = 5)
(5, '2024-12-24', 8000000, 10000000, 11200000, 14000000, 1.40, 0, 1.00, 0.85, '2024-12-23 23:00:00',
 '{"season": "festival", "event": "christmas", "multiplier": "40pct_increase"}', 'Christmas Eve premium pricing'),
(5, '2024-12-31', 8000000, 10000000, 12000000, 15000000, 1.40, 1000000, 1.00, 1.00, '2024-12-30 23:00:00',
 '{"season": "festival", "event": "new_year", "weekend": true, "sold_out": true}', 'New Year Eve maximum pricing'),

-- Low season pricing examples
(1, '2024-02-15', 1200000, 1480000, 1080000, 1332000, 0.90, 0, 1.00, 0.45, '2024-02-14 23:00:00',
 '{"season": "low", "multiplier": "10pct_decrease", "low_occupancy": true}', 'Low season discount applied'),
(2, '2024-06-01', 1500000, 1820000, 1275000, 1547000, 0.85, 0, 1.00, 0.38, '2024-05-31 23:00:00',
 '{"season": "low", "multiplier": "15pct_decrease", "promotion": "summer_early_bird"}', 'Early summer promotion pricing');

-- Thêm chú thích về price history tracking
/*
=== PRICE HISTORY TRACKING ===

SEASONAL MULTIPLIERS:
- Low season: 0.85-0.95 (giảm 5-15%)
- Standard: 1.00 (giá cơ bản)
- High season: 1.05-1.10 (tăng 5-10%) 
- Peak season: 1.20-1.45 (tăng 20-45%)
- Festival: 1.25-1.50 (tăng 25-50%)

WEEKEND SURCHARGES:
- Friday/Saturday: 12% surcharge
- Sunday: 10% surcharge
- Áp dụng nhất quán tất cả hạng phòng

EVENT PRICING:
- Tết Nguyên Đán: +35% 
- Christmas/New Year: +40%
- Local events: +25-30%
- Conference periods: +40%

OCCUPANCY IMPACT:
- <50%: Có thể giảm giá hoặc promotion
- 70-80%: Giá chuẩn
- 85-95%: Tăng giá dynamic
- 95-100%: Maximum pricing

PRICING RULES:
- Calculated daily at 11PM cho ngày hôm sau
- JSON format tracking các factors
- Historical data for revenue analysis
- Support dynamic pricing algorithms
*/
