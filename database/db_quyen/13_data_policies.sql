-- ===== UNIFIED POLICIES DATA =====
-- Dữ liệu cho hệ thống chính sách thống nhất

-- ==== POLICY CATEGORIES ====
INSERT INTO policy_categories (category_code, category_name, description, display_order) VALUES
('DEPOSIT', 'Chính sách đặt cọc', 'Các quy định về đặt cọc và thanh toán trước', 1),
('CANCEL', 'Chính sách hủy phòng', 'Các quy định về hủy booking và phí hủy', 2),
('CHECKOUT', 'Chính sách checkout', 'Quy định về checkout sớm/muộn và refund', 3),
('CHANGE', 'Chính sách đổi phòng', 'Quy định về thay đổi phòng và nâng/hạ hạng', 4),
('COOLDOWN', 'Chính sách cooldown', 'Quy định về thời gian chờ sau các hành động', 5),
('LOYALTY', 'Chính sách khách Diamond', 'Ưu đãi và quy định đặc biệt cho khách Diamond', 6);

-- ==== POLICY TEMPLATES ====
INSERT INTO policy_templates (template_code, template_name, policy_type, template_config, description) VALUES
-- Cancellation Policy Template
('CANCEL_SEASON', 'Mẫu chính sách hủy theo mùa', 'cancellation', 
'{"no_penalty_days": 7, "partial_penalty_days": 3, "partial_penalty_rate": 50.0, "full_penalty_rate": 100.0, "no_show_penalty_rate": 100.0}',
'Template cho chính sách hủy phòng theo giai đoạn thời gian'),

-- Deposit Policy Template  
('DEPOSIT_BASIC', 'Mẫu chính sách đặt cọc cơ bản', 'deposit',
'{"deposit_type": "fixed", "deposit_amount": 1000000, "deposit_percentage": null, "deadline_hours": 24, "refund_policy": "conditional"}',
'Template cho chính sách đặt cọc số tiền cố định'),

-- Checkout Policy Template
('CHECKOUT_FLEX', 'Mẫu chính sách checkout linh hoạt', 'checkout',
'{"early_checkout_allowed": true, "min_stay_percentage": 60.0, "early_checkout_fee": 20.0, "late_checkout_hours": 6, "late_checkout_rate": 100000}',
'Template cho chính sách checkout với các tùy chọn linh hoạt');


-- ==== CANCELLATION POLICIES ====
INSERT INTO policies (
    category_id, policy_code, policy_name, policy_description,
    policy_type, season_type, policy_config,
    effective_start_date, effective_end_date,
    customer_notification, priority_order, created_by
) VALUES 
(2, 'CANCEL_LOW_SEASON', 'Chính sách hủy - Giai đoạn thấp điểm', 
'Chính sách hủy phòng áp dụng trong giai đoạn thấp điểm với mức phạt thấp hơn',
'cancellation', 'low',
'{
    "no_penalty_days": 7,
    "partial_penalty_days": 3,
    "partial_penalty_rate": 50.0,
    "full_penalty_rate": 100.0,
    "no_show_penalty_rate": 100.0,
    "grace_period_hours": 2,
    "notification_required": true
}',
'2024-01-01', '2024-12-31',
'Giai đoạn thấp điểm: Hủy trước 7 ngày không phạt, hủy trước 3 ngày phạt 50%, trong vòng 3 ngày phạt 100%.', 
1, 1),

(2, 'CANCEL_HIGH_SEASON', 'Chính sách hủy - Giai đoạn cao điểm',
'Chính sách hủy phòng nghiêm ngặt hơn cho giai đoạn cao điểm',
'cancellation', 'high', 
'{
    "no_penalty_days": 21,
    "partial_penalty_days": 7,
    "partial_penalty_rate": 50.0,
    "full_penalty_rate": 100.0,
    "no_show_penalty_rate": 100.0,
    "grace_period_hours": 0,
    "notification_required": true,
    "travel_insurance_recommended": true
}',
'2024-01-01', '2024-12-31',
'Giai đoạn cao điểm: Hủy trước 21 ngày không phạt, hủy trước 7 ngày phạt 50%, trong vòng 7 ngày phạt 100%. Khuyến khích mua bảo hiểm du lịch.',
1, 1),

(2, 'CANCEL_PEAK_SEASON', 'Chính sách hủy - Giai đoạn đỉnh điểm',
'Chính sách hủy phòng rất nghiêm ngặt cho giai đoạn đỉnh điểm (Tết, lễ lớn)',
'cancellation', 'peak',
'{
    "no_penalty_days": 45,
    "partial_penalty_days": 30,
    "partial_penalty_rate": 50.0,
    "full_penalty_rate": 100.0,
    "no_show_penalty_rate": 100.0,
    "grace_period_hours": 0,
    "notification_required": true,
    "travel_insurance_required": true
}',
'2024-01-01', '2024-12-31',
'Giai đoạn đỉnh điểm: Hủy trước 45 ngày không phạt, hủy trước 30 ngày phạt 50%, trong vòng 30 ngày phạt 100%. BẮT BUỘC mua bảo hiểm du lịch.',
1, 1);

-- ==== DEPOSIT POLICIES ====
INSERT INTO policies (
    category_id, policy_code, policy_name, policy_description,
    policy_type, season_type, policy_config,
    min_booking_value,
    effective_start_date, customer_notification, priority_order, created_by
) VALUES 
(1, 'DEPOSIT_STANDARD_ROOM', 'Đặt cọc phòng thường',
'Chính sách đặt cọc cho các hạng phòng tiêu chuẩn',
'deposit', 'all',
'{
    "deposit_type": "fixed",
    "deposit_amount": 1000000,
    "deposit_percentage": null,
    "deadline_hours": 24,
    "refund_policy": "conditional",
    "refund_percentage": 100.0,
    "payment_methods": ["cash", "bank_transfer", "vietqr", "vnpay", "credit_card"]
}',
0,
'2024-01-01',
'Đặt cọc: 1.000.000 VNĐ/phòng/đêm cho phòng thường. Phương thức: Tiền mặt, VietQR, VNPAY, Chuyển khoản.',
1, 1),

(1, 'DEPOSIT_VILLA_SUITE', 'Đặt cọc villa/suite',
'Chính sách đặt cọc cho villa và suite cao cấp',
'deposit', 'all',
'{
    "deposit_type": "fixed",
    "deposit_amount": 3000000,
    "deposit_percentage": null,
    "deadline_hours": 24,
    "refund_policy": "conditional",
    "refund_percentage": 100.0,
    "payment_methods": ["cash", "bank_transfer", "vietqr", "vnpay", "credit_card"],
    "special_requirements": ["id_verification", "credit_check"]
}',
0,
'2024-01-01',
'Đặt cọc: 3.000.000 VNĐ/villa/đêm. Cần xác minh danh tính và kiểm tra tín dụng.',
1, 1);

-- ==== CHECKOUT POLICIES ====
INSERT INTO policies (
    category_id, policy_code, policy_name, policy_description,
    policy_type, season_type, policy_config,
    effective_start_date, customer_notification, priority_order, created_by
) VALUES
(3, 'CHECKOUT_EARLY_STANDARD', 'Checkout sớm - Chuẩn',
'Chính sách checkout sớm cho khách hàng thường',
'checkout', 'all',
'{
    "early_checkout_allowed": true,
    "min_stay_percentage": 60.0,
    "early_checkout_fee_percentage": 20.0,
    "min_fee_amount": 200000,
    "notification_hours": 4,
    "refund_processing_days": 7
}',
'2024-01-01',
'Checkout sớm: Cần lưu trú tối thiểu 60% thời gian đã đặt, phí 20% số tiền còn lại hoặc tối thiểu 200k.',
1, 1),

(3, 'CHECKOUT_LATE_STANDARD', 'Checkout muộn - Chuẩn', 
'Chính sách checkout muộn với phí theo giờ',
'checkout', 'all',
'{
    "late_checkout_allowed": true,
    "grace_period_hours": 2,
    "hourly_rate": 100000,
    "max_late_hours": 6,
    "full_day_threshold_hours": 6,
    "full_day_rate_percentage": 50.0,
    "notification_hours": 2
}',
'2024-01-01',
'Checkout muộn: Miễn phí 2h đầu, sau đó 100k/giờ, tối đa 6h. Quá 6h tính 50% giá phòng.',
1, 1);

-- ==== ROOM CHANGE POLICIES ====
INSERT INTO policies (
    category_id, policy_code, policy_name, policy_description,
    policy_type, season_type, policy_config,
    effective_start_date, customer_notification, priority_order, created_by
) VALUES
(4, 'ROOM_CHANGE_STANDARD', 'Đổi phòng - Chuẩn',
'Chính sách đổi phòng tiêu chuẩn với tối đa 2 lần',
'room_change', 'all',
'{
    "changes_allowed": true,
    "max_changes_per_booking": 2,
    "notification_hours": 4,
    "change_fee_type": "price_difference",
    "change_fee_percentage": 5.0,
    "upgrade_surcharge_percentage": 100.0,
    "downgrade_refund_percentage": 80.0,
    "same_day_changes_allowed": false,
    "manager_approval_required": false
}',
'2024-01-01',
'Đổi phòng: Tối đa 2 lần/booking, thông báo trước 4h, tính chênh lệch giá + phí 5%.',
1, 1),

(4, 'ROOM_UPGRADE_VIP', 'Nâng hạng VIP',
'Chính sách nâng hạng dành cho khách VIP',
'room_change', 'all',
'{
    "changes_allowed": true,
    "max_changes_per_booking": 3,
    "notification_hours": 2,
    "change_fee_type": "waived",
    "change_fee_percentage": 0.0,
    "upgrade_surcharge_percentage": 80.0,
    "downgrade_refund_percentage": 100.0,
    "same_day_changes_allowed": true,
    "priority_processing": true
}',
'2024-01-01',
'VIP upgrade: Miễn phí đổi phòng, ưu tiên xử lý, có thể đổi cùng ngày.',
2, 1);

-- ==== COOLDOWN POLICIES ====
INSERT INTO policies (
    category_id, policy_code, policy_name, policy_description,
    policy_type, season_type, policy_config,
    effective_start_date, customer_notification, priority_order, created_by
) VALUES
(5, 'COOLDOWN_CHECKOUT', 'Cooldown sau checkout',
'Thời gian chờ sau checkout bình thường',
'cooldown', 'all',
'{
    "trigger_event": "checkout",
    "cooldown_hours": 2,
    "same_room_cooldown_days": 1,
    "different_room_cooldown_hours": 0,
    "vip_exemption": true,
    "loyalty_exemption": true,
    "emergency_override": true
}',
'2024-01-01',
'Sau checkout: Có thể đặt phòng khác ngay, đặt lại cùng phòng sau 1 ngày.',
1, 1),

(5, 'COOLDOWN_CANCELLATION', 'Cooldown sau hủy phòng',
'Thời gian chờ sau khi hủy booking',
'cooldown', 'all',
'{
    "trigger_event": "cancellation", 
    "cooldown_days": 1,
    "same_room_cooldown_days": 3,
    "different_room_cooldown_days": 1,
    "vip_exemption": true,
    "loyalty_exemption": true,
    "emergency_override": true
}',
'2024-01-01',
'Sau hủy phòng: Chờ 1 ngày để đặt phòng mới, 3 ngày cho cùng phòng.',
1, 1),

(5, 'COOLDOWN_NO_SHOW', 'Cooldown sau No-show',
'Thời gian chờ nghiêm ngặt sau no-show',
'cooldown', 'all',
'{
    "trigger_event": "no_show",
    "cooldown_days": 3,
    "same_room_cooldown_days": 7,
    "different_room_cooldown_days": 3,
    "vip_exemption": false,
    "loyalty_exemption": true,
    "emergency_override": true,
    "manager_approval_required": true
}',
'2024-01-01',
'Sau no-show: Chờ 3 ngày để đặt phòng mới, 7 ngày cho cùng phòng. Cần phê duyệt manager.',
1, 1);

-- ==== VIP/LOYALTY POLICIES ====
INSERT INTO policies (
    category_id, policy_code, policy_name, policy_description,
    policy_type, season_type, policy_config,
    min_booking_value,
    effective_start_date, customer_notification, priority_order, created_by
) VALUES
(6, 'DIAMOND_CANCELLATION_BENEFIT', 'Diamond - Ưu đãi hủy phòng',
'Chính sách hủy phòng ưu đãi cho khách Diamond Gold+',
'cancellation', 'all',
'{
    "no_penalty_days": 14,
    "partial_penalty_days": 7,
    "partial_penalty_rate": 30.0,
    "full_penalty_rate": 80.0,
    "no_show_penalty_rate": 80.0,
    "grace_period_hours": 4,
    "priority_processing": true,
    "dedicated_support": true
}',
1000000,
'2024-01-01',
'VIP Member: Hủy trước 14 ngày miễn phí, hủy trước 7 ngày phạt 30%, trong 7 ngày phạt 80%. Hỗ trợ ưu tiên.',
1, 1),

(6, 'DIAMOND_CHECKOUT_BENEFIT', 'Diamond - Ưu đãi checkout',
'Chính sách checkout ưu đãi cho khách Diamond',
'checkout', 'all',
'{
    "early_checkout_allowed": true,
    "min_stay_percentage": 40.0,
    "early_checkout_fee_percentage": 10.0,
    "min_fee_amount": 100000,
    "late_checkout_complimentary_hours": 4,
    "late_checkout_discount_percentage": 50.0,
    "priority_processing": true
}',
0,
'2024-01-01',
'VIP checkout: Lưu trú tối thiểu 40%, phí 10%, checkout muộn miễn phí 4h đầu.',
1, 1);

-- ==== POLICY MAPPING ====
-- Mapping policies với loại phòng
INSERT INTO policy_room_types (policy_id, room_type_id)
SELECT policy_id, 1 FROM policies WHERE policy_code = 'CANCEL_LOW_SEASON';
INSERT INTO policy_room_types (policy_id, room_type_id)
SELECT policy_id, 2 FROM policies WHERE policy_code = 'CANCEL_LOW_SEASON';
INSERT INTO policy_room_types (policy_id, room_type_id)
SELECT policy_id, 3 FROM policies WHERE policy_code = 'CANCEL_HIGH_SEASON';
INSERT INTO policy_room_types (policy_id, room_type_id)
SELECT policy_id, 5 FROM policies WHERE policy_code = 'DEPOSIT_VILLA_SUITE';
INSERT INTO policy_room_types (policy_id, room_type_id)
SELECT policy_id, 6 FROM policies WHERE policy_code = 'DEPOSIT_VILLA_SUITE';
INSERT INTO policy_room_types (policy_id, room_type_id)
SELECT policy_id, 7 FROM policies WHERE policy_code = 'DEPOSIT_VILLA_SUITE';

-- Mapping policies với member level
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 2 FROM policies WHERE policy_code = 'COOLDOWN_CHECKOUT';
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 3 FROM policies WHERE policy_code = 'COOLDOWN_CHECKOUT';
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 2 FROM policies WHERE policy_code = 'COOLDOWN_CANCELLATION';
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 3 FROM policies WHERE policy_code = 'COOLDOWN_CANCELLATION';
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 2 FROM policies WHERE policy_code = 'COOLDOWN_NO_SHOW';
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 3 FROM policies WHERE policy_code = 'COOLDOWN_NO_SHOW';
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 2 FROM policies WHERE policy_code = 'DIAMOND_CANCELLATION_BENEFIT';
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 3 FROM policies WHERE policy_code = 'DIAMOND_CANCELLATION_BENEFIT';
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 4 FROM policies WHERE policy_code = 'DIAMOND_CANCELLATION_BENEFIT';
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 2 FROM policies WHERE policy_code = 'DIAMOND_CHECKOUT_BENEFIT';
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 3 FROM policies WHERE policy_code = 'DIAMOND_CHECKOUT_BENEFIT';
INSERT INTO policy_member_levels (policy_id, member_level_id)
SELECT policy_id, 4 FROM policies WHERE policy_code = 'DIAMOND_CHECKOUT_BENEFIT';

-- ==== CẬP NHẬT METADATA ====
-- Cập nhật thông tin cho tất cả policies
UPDATE policies SET 
    auto_apply = TRUE,
    requires_approval = FALSE,
    is_system_policy = TRUE,
    created_at = NOW(),
    updated_at = NOW()
WHERE created_by = 1;

-- Cập nhật policies cần approval
UPDATE policies SET 
    requires_approval = TRUE,
    approval_level = 'manager'
WHERE policy_code IN ('DIAMOND_CANCELLATION_BENEFIT', 'DIAMOND_CHECKOUT_BENEFIT', 'COOLDOWN_NO_SHOW');



-- ===== DỮ LIỆU ÁP DỤNG CHÍNH SÁCH =====
INSERT INTO policy_applications (
    booking_id, policy_id, application_date, application_trigger, 
    original_amount, calculated_amount, final_amount,
    processed_by, approval_status, approved_by, approval_date, 
    payment_status, payment_notes
) VALUES 

-- Áp dụng chính sách hủy phòng
(7, 15, '2024-09-28 16:30:00', 'cancellation', 1488600, 0, 0, 1, 'auto_approved', NULL, NULL, 'completed', 'Hủy miễn phí trong 48h - hoàn tiền đầy đủ theo chính sách'),
(8, 16, '2024-12-23 20:00:00', 'cancellation', 7746000, 3873000, 3873000, 1, 'approved', 2, '2024-12-23 21:00:00', 'completed', 'Cấp cứu y tế - áp dụng phí 50% theo chính sách'),
(3, 17, '2024-08-14 18:45:00', 'cancellation', 6776000, 0, 0, 2, 'approved', 2, '2024-08-14 19:00:00', 'completed', 'Khách sạn hủy do overbooking - hoàn tiền đầy đủ + bồi thường'),
(6, 18, '2024-11-29 22:00:00', 'cancellation', 7734000, 7734000, 7734000, 1, 'auto_approved', NULL, NULL, 'completed', 'Hủy cùng ngày - không hoàn tiền theo chính sách nghiêm ngặt'),

-- Áp dụng chính sách đổi phòng
(1, 5, '2024-06-15 10:00:00', 'booking_modification', 1480000, 1820000, 1820000, 1, 'auto_approved', NULL, NULL, 'completed', 'Nâng cấp miễn phí do bảo trì phòng Deluxe lên Premium Corner'),
(4, 6, '2024-06-25 08:30:00', 'booking_modification', 4200000, 4700000, 4700000, 1, 'approved', 2, '2024-06-25 09:00:00', 'completed', 'Chuyển gia đình sang suite yên tĩnh hơn do khiếu nại tiếng ồn'),

-- Áp dụng chính sách checkout sớm/muộn
(9, 9, '2024-05-12 16:45:00', 'checkout', 8130000, 6170000, 6170000, 1, 'approved', 2, '2024-05-12 17:00:00', 'completed', 'Khẩn cấp công việc - checkout sớm được phê duyệt với hoàn tiền một phần'),
(2, 10, '2024-07-23 14:00:00', 'checkout', 0, 0, 0, 1, 'auto_approved', NULL, NULL, 'completed', 'Khách VIP - checkout muộn miễn phí đến 2PM'),

-- Áp dụng chính sách đặt cọc
(1, 1, '2024-06-01 10:00:00', 'booking_creation', 0, 1791900, 1791900, 1, 'auto_approved', NULL, NULL, 'completed', 'Thu cọc 30% theo chính sách tiêu chuẩn'),
(2, 1, '2024-07-15 14:30:00', 'booking_creation', 0, 2234100, 2234100, 1, 'auto_approved', NULL, NULL, 'completed', 'Khách VIP - áp dụng điều khoản cọc tiêu chuẩn'),
(4, 1, '2024-06-20 11:00:00', 'booking_creation', 0, 2290200, 2290200, 1, 'auto_approved', NULL, NULL, 'completed', 'Booking gia đình suite - thu cọc theo quy định');

-- ===== DỮ LIỆU NGOẠI LỆ CHÍNH SÁCH =====
INSERT INTO policy_exceptions (
    booking_id, policy_id, exception_type, exception_reason, 
    override_type, override_value, override_percentage,
    requested_by, request_date, approval_level_required, approved_by, approval_date,
    status, supporting_documents, approval_notes
) VALUES 

-- Ngoại lệ cho trường hợp cấp cứu y tế
(8, 16, 'emergency', 'Khách cấp cứu y tế với giấy xác nhận bệnh viện', 
 'percentage', 1936500, 25.0, 1, '2024-12-23 21:15:00', 'manager', 2, '2024-12-23 21:15:00',
 'approved', '{"hospital_certificate": "certificate_001.pdf", "medical_report": "report_001.pdf"}',
 'Giảm phạt từ 50% xuống 25% do có giấy xác nhận y tế. Duy trì mối quan hệ khách hàng.'),

-- Ngoại lệ cho phục hồi dịch vụ VIP  
(2, 10, 'vip_guest', 'Khách VIP - phục hồi dịch vụ sau khiếu nại về nhân viên', 
 'custom_policy', 2000000, 0.0, 2, '2024-07-21 17:00:00', 'manager', 2, '2024-07-21 17:00:00',
 'approved', '{"complaint_record": "complaint_001.pdf"}',
 'Nâng cấp Presidential Suite và tiện ích bổ sung để phục hồi dịch vụ. Không vi phạm chính sách.'),

-- Ngoại lệ cho gia đình có trẻ nhỏ
(4, 6, 'business_decision', 'Gia đình có em bé - giải quyết khiếu nại tiếng ồn', 
 'waive_all', 500000, 0.0, 1, '2024-06-25 09:30:00', 'supervisor', 1, '2024-06-25 09:30:00',
 'approved', NULL,
 'Nâng cấp suite miễn phí để giải quyết vấn đề tiếng ồn. Thực hành dịch vụ khách hàng tốt.'),

-- Ngoại lệ cho lỗi kỹ thuật overbooking
(3, 17, 'system_error', 'Lỗi hệ thống overbooking - bồi thường nâng cao', 
 'custom_policy', 8776000, 0.0, 2, '2024-08-14 19:30:00', 'director', 2, '2024-08-14 19:30:00',
 'approved', '{"system_log": "error_log_001.txt", "incident_report": "incident_001.pdf"}',
 'Lỗi kỹ thuật overbooking cần bồi thường nâng cao. Hoàn tiền đầy đủ + voucher 2M + đặt phòng khách sạn đối tác.'),

-- Ngoại lệ cho thành viên chương trình khách hàng thân thiết
(9, 9, 'loyalty_benefit', 'Thành viên Gold - quyền lợi chương trình khách hàng thân thiết', 
 'percentage', 250000, 50.0, 1, '2024-05-12 17:30:00', 'supervisor', 1, '2024-05-12 17:30:00',
 'approved', NULL,
 'Quyền lợi thành viên Gold - giảm phí checkout sớm từ 500K xuống 250K VND.');
