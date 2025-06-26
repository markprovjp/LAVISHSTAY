-- ===== VOUCHER & LOYALTY DATA =====
-- Dữ liệu mẫu cho hệ thống voucher, loyalty program và dịch vụ bổ sung

-- Thêm phí dịch vụ khác
INSERT INTO additional_fees (fee_name, fee_type, amount, description) VALUES 
('Đặt cọc phòng thường', 'deposit', 1000000, 'Đặt cọc cho phòng Deluxe, Premium, Suite'),
('Đặt cọc The Level', 'deposit', 3000000, 'Đặt cọc cho phòng The Level các loại'),
('Thay đổi thông tin khách', 'change_guest_info', 350000, 'Phí thay đổi thông tin khách trong vòng 30 ngày từ ngày nhận phòng'),
('Dịch vụ đưa đón sân bay', 'airport_transfer', 500000, 'Phí đưa đón sân bay một chiều (thông báo trước 5 ngày)');

-- Thêm gói voucher
INSERT INTO voucher_packages (package_name, package_type, description, price_per_person, validity_start, validity_end, min_nights) VALUES 
('Voucher tri ân 2N1Đ', 'service_promo', 'Gói ưu đãi tri ân khách hàng với nhiều tiện ích', 800000, '2024-01-01', '2024-12-31', 1),
('Voucher Hè rực rỡ 2N1Đ', 'service_promo', 'Gói ưu đãi mùa hè với buffet trọn gói', 900000, '2024-05-01', '2024-09-30', 1),
('Ưu đãi thành viên Diamond', 'member_special', 'Ưu đãi đặc biệt cho thành viên Platinum', 0, '2024-01-01', '2024-12-31', 2),
('Voucher đền bù dịch vụ', 'compensation', 'Voucher đền bù cho sự cố dịch vụ', 0, '2024-01-01', '2024-12-31', 1);

-- Thêm chi tiết gói voucher tri ân
INSERT INTO package_services (package_id, service_type, service_description, quantity, discount_percent, is_free) VALUES 
(1, 'accommodation', '01 đêm lưu trú phòng Deluxe', 1, 0, TRUE),
(1, 'breakfast', '01 bữa sáng buffet Á-Âu', 1, 0, TRUE),
(1, 'discount', 'Giảm 50% phí phụ thu trẻ em', 1, 50, FALSE),
(1, 'free_service', 'Miễn phí cho 01 trẻ em ngủ cùng bố mẹ', 1, 0, TRUE);

-- Thêm chi tiết gói voucher hè
INSERT INTO package_services (package_id, service_type, service_description, quantity, discount_percent, is_free) VALUES 
(2, 'accommodation', '01 đêm lưu trú phòng Deluxe', 1, 0, TRUE),
(2, 'breakfast', '01 bữa sáng buffet sang trọng', 1, 0, TRUE),
(2, 'meal', 'Tặng thêm bữa ăn trưa', 1, 0, TRUE),
(2, 'meal', 'Tặng thêm bữa ăn tối', 1, 0, TRUE);

-- Thêm chi tiết gói thành viên Diamond
INSERT INTO package_services (package_id, service_type, service_description, quantity, discount_percent, is_free) VALUES 
(3, 'free_service', 'Tặng 1 đêm miễn phí sau 5 đêm lưu trú', 1, 0, TRUE),
(3, 'discount', 'Giảm 20% dịch vụ spa', 1, 20, FALSE),
(3, 'free_service', 'Miễn phí upgrade phòng (tùy tình trạng)', 1, 0, TRUE);

-- Thêm chi tiết gói voucher đền bù
INSERT INTO package_services (package_id, service_type, service_description, quantity, discount_percent, is_free) VALUES 
(4, 'free_service', 'Phòng miễn phí 1 đêm', 1, 0, TRUE),
(4, 'free_service', 'Miễn phí bữa sáng buffet', 1, 0, TRUE),
(4, 'discount', 'Giảm 100% phí dịch vụ spa', 1, 100, FALSE);

-- ===== DỮ LIỆU VOUCHER ĐẶC BIỆT =====

-- Thêm voucher nghỉ dưỡng đặc biệt
INSERT INTO special_vacation_vouchers (
    voucher_code, voucher_name, voucher_type, price_per_person, original_value, savings_amount,
    min_guests, max_guests, applicable_days, nights_included, room_type_included,
    includes_breakfast, breakfast_type, includes_lunch, includes_dinner,
    child_discount_percentage, free_children_age_limit, valid_from, valid_to
) VALUES 
('TRI_AN_2N1D', 'Voucher Tri Ân 2N1Đ', 'tri_an_2n1d', 800000, 1200000, 400000,
 1, 4, 'all', 1, 'Deluxe Room', TRUE, 'Buffet Á-Âu', FALSE, FALSE,
 50.00, 12, '2024-01-01', '2024-12-31'),

('HE_RUC_RO_2N1D', 'Voucher Hè Rực Rỡ 2N1Đ', 'he_ruc_ro_2n1d', 900000, 1400000, 500000,
 1, 4, 'all', 1, 'Deluxe Room', TRUE, 'Buffet sang trọng', TRUE, TRUE,
 50.00, 12, '2024-05-01', '2024-09-30');


-- ===== CHƯƠNG TRÌNH LAVISHSTAYREWARDS =====

-- Thêm cấp độ thành viên theo tiêu chí LavishStayClub
INSERT INTO member_tiers (
    tier_name, tier_color, min_nights_required, min_spending_required,
    points_earning_rate, max_rooms_for_points, welcome_points, birthday_surprise, online_checkin,
    room_upgrade_priority, late_checkout_hours, description
) VALUES 
('Member', '#FFFFFF', 0, 0, 0.00, 2, 0, TRUE, TRUE, 1, 0, 'Miễn phí: Giảm 5% phòng, 2% VinWonders/Safari, 35% golf & ẩm thực, Free 2 đêm/năm'),
('Gold', '#FFD700', 5, 10000000, 1.00, 2, 0, TRUE, TRUE, 3, 4, 'Chi tiêu 10-50 triệu/5-25 đêm: Tích LPoint 1%, giảm 5% phòng, ưu đãi W/Safari'),
('Platinum', '#E5E4E2', 25, 50000000, 1.50, 2, 0, TRUE, TRUE, 4, 6, 'Chi tiêu 50-100 triệu/25-50 đêm: Tích LPoint 1.5%, giảm 10% phòng, 50% golf & food'),
('Diamond', '#B9F2FF', 50, 100000000, 2.00, 2, 0, TRUE, TRUE, 5, 8, 'Chi tiêu >100 triệu/>50 đêm: Tích LPoint 2%, giảm 10% phòng, ưu tiên upgrade & late checkout');


-- ===== THÊM DỮ LIỆU LOYALTY PROGRAM CHI TIẾT =====

-- Thêm thêm members cho loyalty program với logic năm
INSERT INTO loyalty_program (
    user_id, membership_number, level_id, 
    current_year_revenue, previous_year_revenue, current_year_nights, previous_year_nights,
    total_points, available_points, total_nights, total_spent, 
    join_date, tier_activation_date, last_activity_date, last_tier_review_date
) VALUES 
-- User 3: Gold member - đã có booking activity
(3, 'LSR2024001', 2, 15000000, 10000000, 8, 5, 336, 300, 13, 25000000,
 '2024-01-15', '2024-01-15', '2024-07-15', '2024-12-31'),

-- User 4: Platinum member - doanh thu cao năm trước và năm nay
(4, 'LSR2024002', 3, 45000000, 75000000, 25, 30, 7800, 6500, 55, 520000000, 
 '2023-03-10', '2023-03-10', '2024-07-23', '2023-12-31'),

-- User 5: Gold member - đạt điều kiện Gold năm nay  
(5, 'LSR2024003', 2, 12000000, 8000000, 8, 5, 1440, 1200, 12, 85000000, 
 '2024-02-01', '2024-02-01', '2024-06-15', '2024-12-31'),

-- User 6: Gold member - Stable Gold level
(6, 'LSR2023001', 2, 18000000, 22000000, 15, 18, 3450, 2800, 28, 185000000, 
 '2023-06-01', '2023-06-01', '2024-05-30', '2023-12-31'),

-- User 7: Member - Mới gia nhập, chưa có doanh thu đáng kể
(7, 'LSR2025001', 1, 0, 0, 0, 0, 0, 0, 0, 0, 
 '2025-06-01', '2025-06-01', '2025-06-01', NULL);

-- Thêm lịch sử LPoint và tier changes (theo logic LavishStayClub)
INSERT INTO points_history (
    member_id, booking_id, transaction_type, points_change, description, transaction_date
) VALUES 

-- Points history cho member 1 (Gold - user_id = 3)
(1, NULL, 'welcome', 0, 'Chào mừng thành viên LavishStayClub (Member không tích LPoint)', '2024-01-15 10:00:00'),
(1, 1, 'adjustment', 0, 'Tier review: member → gold. Revenue: 15000000, Nights: 8', '2024-06-18 12:00:00'),
(1, 1, 'earn', 149, 'LPoint từ booking: 14900000 VND (rate: 1%)', '2024-06-18 12:00:00'),
(1, 2, 'earn', 187, 'LPoint từ booking: 18700000 VND (rate: 1%)', '2024-07-23 12:00:00'),

-- Points history cho member 2 (Platinum - user_id = 4)  
(2, NULL, 'welcome', 0, 'Chào mừng thành viên LavishStayClub', '2023-03-10 10:00:00'),
(2, NULL, 'adjustment', 0, 'Tier review: gold → platinum. Revenue: 67500000, Nights: 30', '2023-12-31 23:59:59'),
(2, NULL, 'earn', 675, 'LPoint từ booking: 45000000 VND (rate: 1.5%)', '2024-07-23 12:00:00'),
(2, NULL, 'redeem', -300, 'Đổi LPoint thành voucher spa 300K VND', '2024-06-15 16:00:00'),

-- Points history cho member 3 (Gold - user_id = 5)
(3, NULL, 'welcome', 0, 'Chào mừng thành viên LavishStayClub', '2024-02-01 10:00:00'),
(3, NULL, 'adjustment', 0, 'Tier review: member → gold. Revenue: 14400000, Nights: 8', '2024-12-31 23:59:59'),
(3, NULL, 'earn', 120, 'LPoint từ booking: 12000000 VND (rate: 1%)', '2024-06-15 12:00:00'),

-- Points history cho member 4 (Gold - user_id = 6)  
(4, NULL, 'welcome', 0, 'Chào mừng thành viên LavishStayClub', '2023-06-01 10:00:00'),
(4, NULL, 'adjustment', 0, 'Tier review: member → gold. Revenue: 22000000, Nights: 18', '2023-12-31 23:59:59'),
(4, NULL, 'earn', 180, 'LPoint từ booking: 18000000 VND (rate: 1%)', '2024-05-30 12:00:00'),
(4, NULL, 'redeem', -100, 'Đổi LPoint thành upgrade phòng', '2024-04-10 15:30:00'),

-- Points history cho member 5 (Member - user_id = 7)
(5, NULL, 'welcome', 0, 'Chào mừng thành viên LavishStayClub (Member chỉ hưởng giảm giá)', '2025-06-01 10:00:00');

-- Thêm voucher cá nhân mẫu
INSERT INTO customer_vouchers (voucher_code, user_id, package_id, issue_date, expiry_date, status) VALUES 
('TRIAN2024001', 3, 1, '2024-06-01', '2024-12-31', 'active'),
('SUMMER2024001', 3, 2, '2024-07-01', '2024-09-30', 'active');

-- Thêm dữ liệu airport transfer mẫu
INSERT INTO airport_transfers (
    booking_id, transfer_type, flight_number, flight_date, flight_time,
    pickup_location, dropoff_location, passenger_count, vehicle_type, 
    fee, status, notes
) VALUES 
(1, 'pickup', 'VN120', '2024-06-15', '13:30:00', 'Noi Bai Airport', 'LAVISHSTAY Hanoi Hotel', 3, 'Sedan', 500000, 'completed', 'Đón sân bay - Booking Deluxe'),
(2, 'both', 'VN220', '2024-07-20', '14:00:00', 'Noi Bai Airport', 'LAVISHSTAY Hanoi Hotel', 2, 'Sedan', 1000000, 'completed', 'Đưa đón 2 chiều - Booking Premium'),
(3, 'pickup', 'VN320', '2024-08-10', '09:30:00', 'Noi Bai Airport', 'LAVISHSTAY Hanoi Hotel', 2, 'Luxury', 800000, 'confirmed', 'Diamond transfer - The Level guest');




