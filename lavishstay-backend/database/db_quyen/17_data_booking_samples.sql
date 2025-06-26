-- ===== COMPREHENSIVE BOOKING SAMPLE DATA =====
-- Dữ liệu booking chi tiết và đầy đủ để test tất cả tính năng
-- Bao gồm: nhiều loại phòng, trạng thái, pricing, voucher, loyalty, policies

-- Thêm users mẫu để test đầy đủ
INSERT IGNORE INTO users (user_id, full_name, email, phone, password_hash, role_id, is_active, created_at) VALUES 
(3, 'Lê Văn Khách', 'customer@gmail.com', '0345678901', '$2y$10$example_hash', 3, TRUE, '2024-01-01 00:00:00'),
(4, 'Nguyễn Thị VIP', 'vip.customer@gmail.com', '0356789012', '$2y$10$example_hash', 3, TRUE, '2024-01-15 00:00:00'),
(5, 'Trần Văn Doanh Nghiệp', 'corporate@company.com', '0367890123', '$2y$10$example_hash', 3, TRUE, '2024-02-01 00:00:00'),
(6, 'Hoàng Thị Thường Xuyên', 'loyal@gmail.com', '0378901234', '$2y$10$example_hash', 3, TRUE, '2023-06-01 00:00:00'),
(7, 'Phạm Văn Mới', 'newguest@gmail.com', '0389012345', '$2y$10$example_hash', 3, TRUE, '2025-06-01 00:00:00');
-- ===== BOOKING DATA THEO TỪNG LOẠI PHÒNG VÀ TÌNH HUỐNG =====

INSERT INTO bookings (
    user_id, guest_full_name, guest_email, guest_phone, guest_id_number, guest_nationality, guest_address,
    room_id, check_in_date, check_out_date, total_nights, adults_count, children_count,
    room_price_per_night, meal_plan_id, meal_plan_total_fee, extra_bed_fee, extra_guest_fee,
    room_subtotal, services_subtotal, gross_total, tax_amount, final_total,
    deposit_amount, deposit_percentage, required_deposit_amount, deposit_deadline,
    payment_method, booking_status, level_id, booking_source,
    special_requests, internal_notes, voucher_code, voucher_discount_amount,
    requires_vat_invoice, vat_company_name, vat_company_address, vat_tax_code,
    no_show_deadline, early_checkout_date, late_checkout_time,
    applied_deposit_policy, applied_pricing_policies,
    created_at
) VALUES 

-- ===== BOOKING 1: Deluxe Room - Checked Out (Hoàn thành) =====
(3, 'Lê Văn Khách', 'customer@gmail.com', '0345678901', '123456789012', 'Vietnamese', '123 Nguyễn Văn Cừ, Q1, TP.HCM',
 1, '2024-06-15', '2024-06-18', 3, 2, 1, 1480000, 2, 540000, 300000, 0,
 4440000, 150000, 5430000, 543000, 5973000,
 1791900, 30.00, 1791900, '2024-06-13 23:59:59',
 'vnpay', 'checked_out', 2, 'website',
 'Phòng tầng cao, view đẹp', 'Khách hàng thân thiện, checkout đúng giờ', NULL, 0,
 FALSE, NULL, NULL, NULL,
 '2024-06-15 14:00:00', NULL, NULL,
 'DEPOSIT_MEMBER_30PCT', '{"season": "regular", "weekend_surcharge": 0, "early_booking": true}',
 '2024-06-01 10:00:00'),

-- ===== BOOKING 2: Corner Suite - Checked Out với Diamond Treatment =====
(4, 'Nguyễn Thị VIP', 'vip.customer@gmail.com', '0356789012', '987654321098', 'Vietnamese', '456 Lê Lợi, Q1, TP.HCM',
 46, '2024-07-20', '2024-07-23', 3, 2, 0, 1820000, 3, 810000, 0, 0,
 5460000, 500000, 6770000, 677000, 7447000,
 2234100, 30.00, 2234100, '2024-07-18 23:59:59',
 'credit_card', 'checked_out', 3, 'phone',
 'Champagne chào mừng, chocolate tặng kèm, late checkout', 'Diamond guest, đã upgrade suite miễn phí', 'DIAMOND10', 500000,
 TRUE, 'Công ty TNHH ABC', '789 Nguyễn Huệ, Q1, TP.HCM', '0123456789',
 '2024-07-20 14:00:00', NULL, '14:00:00',
 'DEPOSIT_DIAMOND_30PCT', '{"season": "high", "weekend_surcharge": 200000, "diamond_discount": 500000}',
 '2024-07-01 14:30:00'),

-- ===== BOOKING 3: The Level Suite - Confirmed, sắp check-in =====
(5, 'Trần Văn Doanh Nghiệp', 'corporate@company.com', '0367890123', '456789012345', 'Vietnamese', '321 Võ Văn Tần, Q3, TP.HCM',
 76, '2025-07-01', '2025-07-05', 4, 2, 0, 2120000, 4, 1200000, 0, 0,
 8480000, 0, 10880000, 1088000, 11968000,
 3590400, 30.00, 3590400, '2025-06-29 23:59:59',
 'bank_transfer', 'confirmed', 3, 'direct',
 'Doanh nghiệp, cần hóa đơn VAT, yêu cầu executive lounge access', 'Corporate contract applied, giảm 15%', 'CORP15', 1632000,
 TRUE, 'Công ty XYZ Ltd', '654 Lê Duẩn, Q1, TP.HCM', '9876543210',
 '2025-07-01 14:00:00', NULL, NULL,
 'DEPOSIT_CORPORATE_30PCT', '{"season": "peak", "weekend_surcharge": 0, "corporate_discount": 15, "long_stay": true}',
 '2025-06-20 15:30:00'),

-- ===== BOOKING 4: Suite - Checked In (đang lưu trú) =====
(6, 'Hoàng Thị Thường Xuyên', 'loyal@gmail.com', '0378901234', '789012345678', 'Vietnamese', '987 Cách Mạng Tháng 8, Q10, TP.HCM',
 136, '2025-06-23', '2025-06-27', 4, 2, 2, 2680000, 4, 1440000, 600000, 200000,
 10720000, 300000, 13360000, 1336000, 14696000,
 4408800, 30.00, 4408800, '2025-06-21 23:59:59',
 'vietqr', 'checked_in', 2, 'website',
 'Khách quen, có 2 trẻ em, cần thêm giường, minibar đầy đủ', 'Loyalty member - Gold level, tự động upgrade', 'LOYAL20', 2000000,
 FALSE, NULL, NULL, NULL,
 '2025-06-23 14:00:00', NULL, NULL,
 'DEPOSIT_LOYALTY_25PCT', '{"season": "regular", "loyalty_discount": 20, "family_package": true}',
 '2025-06-10 08:45:00'),

-- ===== BOOKING 5: Presidential Suite - Confirmed (Diamond cao cấp) =====
(4, 'Nguyễn Thị VIP', 'vip.customer@gmail.com', '0356789012', '987654321098', 'Vietnamese', '456 Lê Lợi, Q1, TP.HCM',
 151, '2025-08-15', '2025-08-18', 3, 4, 1, 4200000, 4, 1620000, 300000, 500000,
 12600000, 1000000, 16020000, 1602000, 17622000,
 5286600, 30.00, 5286600, '2025-08-13 23:59:59',
 'credit_card', 'confirmed', 3, 'phone',
 'Anniversary celebration, cần trang trí phòng, butler service, spa package', 'Presidential suite, full diamond treatment, complimentary everything', 'ANNIVERSARY', 1500000,
 TRUE, 'Công ty TNHH ABC', '789 Nguyễn Huệ, Q1, TP.HCM', '0123456789',
 '2025-08-15 14:00:00', NULL, NULL,
 'DEPOSIT_PRESIDENTIAL_50PCT', '{"season": "peak", "presidential_package": true, "anniversary_discount": 1500000}',
 '2025-07-25 16:20:00'),

-- ===== BOOKING 6: Deluxe Room - Cancelled (đã hủy) =====
(7, 'Phạm Văn Mới', 'newguest@gmail.com', '0389012345', '012345678901', 'Vietnamese', '159 Pasteur, Q3, TP.HCM',
 15, '2025-08-01', '2025-08-03', 2, 1, 0, 1480000, 1, 0, 0, 0,
 2960000, 0, 2960000, 296000, 3256000,
 976800, 30.00, 976800, '2025-07-30 23:59:59',
 'vnpay', 'cancelled', 1, 'website',
 'Đặt thử, chưa chắc chắn', 'Khách hủy do thay đổi kế hoạch, đã hoàn tiền', NULL, 0,
 FALSE, NULL, NULL, NULL,
 '2025-08-01 14:00:00', NULL, NULL,
 'DEPOSIT_MEMBER_30PCT', '{"season": "regular", "cancellation_fee": 200000}',
 '2025-07-20 11:30:00'),

-- ===== BOOKING 7: Corner Suite - No Show =====
(7, 'Phạm Văn Mới', 'newguest@gmail.com', '0389012345', '012345678901', 'Vietnamese', '159 Pasteur, Q3, TP.HCM',
 50, '2025-06-20', '2025-06-22', 2, 2, 0, 1820000, 2, 360000, 0, 0,
 3640000, 0, 4360000, 436000, 4796000,
 1438800, 30.00, 1438800, '2025-06-18 23:59:59',
 'momo', 'no_show', 1, 'website',
 'Lần đầu đặt phòng', 'No-show, không liên lạc được, đã charge phí', NULL, 0,
 FALSE, NULL, NULL, NULL,
 '2025-06-20 18:00:00', NULL, NULL,
 'DEPOSIT_MEMBER_30PCT', '{"season": "regular", "no_show_fee": 1438800}',
 '2025-06-15 22:15:00'),

-- ===== BOOKING 8: The Level Corner Suite - Pending (chờ xác nhận) =====
(5, 'Trần Văn Doanh Nghiệp', 'corporate@company.com', '0367890123', '456789012345', 'Vietnamese', '321 Võ Văn Tần, Q3, TP.HCM',
 120, '2025-12-24', '2025-12-28', 4, 3, 1, 2320000, 4, 1440000, 300000, 150000,
 9280000, 0, 11470000, 1147000, 12617000,
 3785100, 30.00, 3785100, '2025-12-22 23:59:59',
 'bank_transfer', 'pending', 3, 'email',
 'Christmas holiday, corporate group booking, cần nhiều phòng liên tiếp', 'Corporate booking, chờ xác nhận số lượng phòng cuối cùng', 'XMAS25', 1000000,
 TRUE, 'Công ty XYZ Ltd', '654 Lê Duẩn, Q1, TP.HCM', '9876543210',
 '2025-12-24 14:00:00', NULL, NULL,
 'DEPOSIT_CORPORATE_30PCT', '{"season": "peak", "holiday_surcharge": 500000, "corporate_discount": 15}',
 '2025-12-15 09:00:00'),

-- ===== BOOKING 9: Deluxe Room - Early Checkout =====
(3, 'Lê Văn Khách', 'customer@gmail.com', '0345678901', '123456789012', 'Vietnamese', '123 Nguyễn Văn Cừ, Q1, TP.HCM',
 25, '2024-05-10', '2024-05-15', 5, 2, 0, 1480000, 2, 900000, 0, 0,
 7400000, 200000, 8300000, 830000, 9130000,
 2739000, 30.00, 2739000, '2024-05-08 23:59:59',
 'bank_transfer', 'checked_out', 3, 'website',
 'Kế hoạch có thể thay đổi', 'Checkout sớm 2 ngày, đã tính phí và hoàn tiền', NULL, 0,
 FALSE, NULL, NULL, NULL,
 '2024-05-10 14:00:00', '2024-05-13', NULL,
 'DEPOSIT_MEMBER_30PCT', '{"season": "regular", "early_checkout_fee": 1000000, "early_checkout_refund": 1960000}',
 '2024-04-28 16:45:00'),

-- ===== BOOKING 10: Multiple Rooms - Group Booking =====
(5, 'Trần Văn Doanh Nghiệp', 'corporate@company.com', '0367890123', '456789012345', 'Vietnamese', '321 Võ Văn Tần, Q3, TP.HCM',
 60, '2025-09-15', '2025-09-17', 2, 10, 0, 2120000, 3, 1200000, 0, 1500000,
 4240000, 500000, 6940000, 694000, 7634000,
 2290200, 30.00, 2290200, '2025-09-13 23:59:59',
 'bank_transfer', 'confirmed', 3, 'direct',
 'Group booking 5 phòng, team building company, cần meeting room', 'Corporate group, đã book thêm 4 phòng khác, total 5 rooms', 'GROUP20', 1200000,
 TRUE, 'Công ty XYZ Ltd', '654 Lê Duẩn, Q1, TP.HCM', '9876543210',
 '2025-09-15 14:00:00', NULL, NULL,
 'DEPOSIT_GROUP_25PCT', '{"season": "regular", "group_discount": 20, "multiple_rooms": 5}',
 '2025-08-20 10:30:00');
-- ===== PAYMENTS DATA TƯƠNG ỨNG =====
INSERT INTO payments (
    booking_id, payment_type, payment_amount, payment_method, payment_status, 
    transaction_reference, gateway_transaction_id, payment_notes, payment_date, created_at
) VALUES 

-- Payments cho booking 1 (Checked out - Full payment)
(1, 'deposit', 1791900, 'vnpay', 'completed', 'VNPAY_DEP_20240601001', 'VNPAY001', 'Tiền cọc đặt phòng', '2024-06-01 10:30:00', '2024-06-01 10:30:00'),
(1, 'full_payment', 4181100, 'vnpay', 'completed', 'VNPAY_BAL_20240618001', 'VNPAY002', 'Thanh toán số dư khi check-out', '2024-06-18 08:00:00', '2024-06-18 08:00:00'),

-- Payments cho booking 2 (Diamond - Full payment với upgrade)
(2, 'deposit', 2234100, 'credit_card', 'completed', 'CC_DEP_20240701001', 'CC001', 'Tiền cọc Diamond', '2024-07-01 15:00:00', '2024-07-01 15:00:00'),
(2, 'full_payment', 5212900, 'credit_card', 'completed', 'CC_BAL_20240723001', 'CC002', 'Thanh toán Diamond + dịch vụ', '2024-07-23 10:00:00', '2024-07-23 10:00:00'),

-- Payments cho booking 3 (Corporate - Deposit only)
(3, 'deposit', 3590400, 'bank_transfer', 'completed', 'BANK_DEP_20250620001', 'BANK001', 'Tiền cọc công ty', '2025-06-20 16:00:00', '2025-06-20 16:00:00'),

-- Payments cho booking 4 (Loyalty - Deposit + partial balance)
(4, 'deposit', 4408800, 'vietqr', 'completed', 'VIETQR_DEP_20250610001', 'VIETQR001', 'Tiền cọc thành viên thân thiết', '2025-06-10 09:00:00', '2025-06-10 09:00:00'),
(4, 'partial_payment', 5000000, 'vietqr', 'completed', 'VIETQR_PAR_20250623001', 'VIETQR002', 'Thanh toán một phần khi check-in', '2025-06-23 14:30:00', '2025-06-23 14:30:00'),

-- Payments cho booking 5 (Presidential - Deposit only)
(5, 'deposit', 5286600, 'credit_card', 'completed', 'CC_DEP_20250725001', 'CC003', 'Tiền cọc phòng Presidential', '2025-07-25 16:30:00', '2025-07-25 16:30:00'),

-- Payments cho booking 6 (Cancelled - Refund)
(6, 'deposit', 976800, 'vnpay', 'completed', 'VNPAY_DEP_20250720001', 'VNPAY003', 'Tiền cọc (đã hủy)', '2025-07-20 11:45:00', '2025-07-20 11:45:00'),
(6, 'refund', 776800, 'vnpay', 'completed', 'VNPAY_REF_20250720002', 'VNPAY004', 'Hoàn tiền sau khi trừ phí hủy', '2025-07-20 12:00:00', '2025-07-20 12:00:00'),

-- Payments cho booking 7 (No Show - No refund)
(7, 'deposit', 1438800, 'momo', 'completed', 'MOMO_DEP_20250615001', 'MOMO001', 'Tiền cọc (No-show, không hoàn tiền)', '2025-06-15 22:30:00', '2025-06-15 22:30:00'),

-- Payments cho booking 9 (Early checkout - with refund)
(9, 'deposit', 2739000, 'bank_transfer', 'completed', 'BANK_DEP_20240428001', 'BANK002', 'Tiền cọc phòng', '2024-04-28 17:00:00', '2024-04-28 17:00:00'),
(9, 'full_payment', 5391000, 'bank_transfer', 'completed', 'BANK_BAL_20240513001', 'BANK003', 'Thanh toán số dư', '2024-05-13 10:00:00', '2024-05-13 10:00:00'),
(9, 'refund', 1960000, 'bank_transfer', 'completed', 'BANK_REF_20240513002', 'BANK004', 'Hoàn tiền checkout sớm', '2024-05-13 11:00:00', '2024-05-13 11:00:00'),

-- Payments cho booking 10 (Group - Deposit only)
(10, 'deposit', 2290200, 'bank_transfer', 'completed', 'BANK_DEP_20250820001', 'BANK005', 'Tiền cọc đặt phòng nhóm', '2025-08-20 11:00:00', '2025-08-20 11:00:00');

-- ===== SERVICE USAGE DATA =====
INSERT INTO service_usage (
    booking_id, service_id, quantity, unit_price, total_price, usage_date, notes, created_at
) VALUES 

-- Services cho booking 1 (Deluxe checked out)
(1, 1, 2, 50000, 100000, '2024-06-16 08:30:00', 'Spa massage cho 2 người', '2024-06-16 08:30:00'),
(1, 3, 1, 50000, 50000, '2024-06-17 20:00:00', 'Room service dinner', '2024-06-17 20:00:00'),

-- Services cho booking 2 (Diamond Corner Suite)
(2, 1, 2, 50000, 100000, '2024-07-21 14:00:00', 'Diamond spa treatment', '2024-07-21 14:00:00'),
(2, 2, 4, 80000, 320000, '2024-07-22 19:30:00', 'Fine dining 4 people', '2024-07-22 19:30:00'),
(2, 4, 2, 40000, 80000, '2024-07-23 09:00:00', 'Laundry service', '2024-07-23 09:00:00'),

-- Services cho booking 4 (Suite checked in)
(4, 3, 3, 50000, 150000, '2024-06-24 18:00:00', 'Family room service', '2024-06-24 18:00:00'),
(4, 6, 2, 75000, 150000, '2024-06-25 10:00:00', 'Kids fitness activity', '2024-06-25 10:00:00'),

-- Services cho booking 9 (Early checkout)
(9, 5, 5, 25000, 125000, '2024-05-11 21:00:00', 'Minibar consumption', '2024-05-11 21:00:00'),
(9, 4, 1, 40000, 40000, '2024-05-12 08:00:00', 'Express laundry', '2024-05-12 08:00:00'),
(9, 3, 1, 35000, 35000, '2024-05-12 19:00:00', 'Late night room service', '2024-05-12 19:00:00'),

-- Services cho booking 10 (Group booking)
(10, 2, 10, 80000, 800000, '2025-09-16 19:00:00', 'Group dinner reservation', '2025-09-16 19:00:00');


