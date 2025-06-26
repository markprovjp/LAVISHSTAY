-- ===== OPERATIONS DATA =====
-- Dữ liệu mẫu cho hệ thống vận hành khách sạn
-- Bao gồm: check-in/out, hủy phòng, khiếu nại, thay đổi booking

-- ===== DỮ LIỆU CHECK-IN/CHECK-OUT =====
INSERT INTO check_inout (
    booking_id, actual_checkin_time, actual_checkout_time, 
    standard_checkin_time, standard_checkout_time,
    early_checkin_fee, late_checkout_fee,
    staff_checkin_id, staff_checkout_id,
    identity_verified, identity_document_type, identity_document_number,
    marriage_certificate_required, marriage_certificate_verified, registration_form_signed,
    security_deposit_collected, room_key_type, room_key_number,
    key_replacement_count, key_replacement_fee, key_replacement_notes,
    luggage_assistance_provided, room_orientation_given,
    room_condition_checkin, room_condition_checkout,
    minibar_items_checkin, minibar_items_checkout, minibar_consumption_charges,
    room_damage_charges, damage_reported, damage_description, damage_photos,
    damage_assessment_completed, damage_repair_cost, damage_guest_liable,
    checkin_notes, checkout_notes
) VALUES 

-- Check-in/out cho booking 1 (Lê Văn Khách - Deluxe Room - Hoàn thành)
(1, '2024-06-15 14:30:00', '2024-06-18 11:45:00',
 '14:00:00', '12:00:00',
 0, 0,
 1, 1,
 TRUE, 'cccd', '123456789012',
 FALSE, FALSE, TRUE,
 1000000, 'key_card', 'KC001',
 0, 0, NULL,
 TRUE, TRUE,
 '{"room_clean": true, "amenities_complete": true, "minibar_full": true, "towels": 4, "bathrobes": 2}',
 '{"room_clean": true, "amenities_complete": true, "minibar_partial": true, "towels": 4, "bathrobes": 2}',
 '["beer_333": 2, "coca_cola": 1, "pringles": 1, "water": 2]',
 '["beer_333": 0, "coca_cola": 0, "pringles": 0, "water": 1]',
 150000,
 0, FALSE, NULL, NULL,
 FALSE, 0, FALSE,
 'Khách check-in đúng giờ, rất lịch sự. Hướng dẫn đầy đủ tiện ích.',
 'Check-out sớm 15 phút, không phí. Minibar consumption noted. Khách hài lòng dịch vụ.'),

-- Check-in/out cho booking 2 (Nguyễn Thị VIP - Premium Corner - VIP)
(2, '2024-07-20 13:00:00', '2024-07-23 14:00:00',
 '14:00:00', '12:00:00',
 50000, 0,
 1, 1,
 TRUE, 'cccd', '987654321098',
 FALSE, FALSE, TRUE,
 2000000, 'key_card', 'KC046',
 0, 0, NULL,
 TRUE, TRUE,
 '{"room_clean": true, "amenities_complete": true, "minibar_full": true, "towels": 6, "bathrobes": 2, "welcome_champagne": true}',
 '{"room_clean": true, "amenities_complete": true, "minibar_consumed": true, "towels": 6, "bathrobes": 2}',
 '["champagne_moet": 1, "wine_red": 1, "chocolate_premium": 1, "nuts_mix": 1, "water": 4]',
 '["champagne_moet": 0, "wine_red": 1, "chocolate_premium": 0, "nuts_mix": 0, "water": 3]',
 500000,
 0, FALSE, NULL, NULL,
 FALSE, 0, FALSE,
 'VIP guest - early check-in 1h. Champagne welcome. Full orientation about premium amenities.',
 'VIP late checkout until 2PM - complimentary. High minibar consumption typical for VIP. Excellent feedback.'),

-- Check-in/out cho booking 4 (Gia đình Suite - đang lưu trú)
(4, '2024-06-24 15:00:00', NULL,
 '14:00:00', '12:00:00',
 0, 0,
 1, NULL,
 TRUE, 'cccd', '456789012345',
 TRUE, TRUE, TRUE,
 2500000, 'key_card', 'KC091',
 1, 100000, 'Lost key card on day 2, replaced immediately',
 TRUE, TRUE,
 '{"room_clean": true, "amenities_complete": true, "minibar_full": true, "towels": 8, "bathrobes": 4, "baby_crib": true}',
 NULL,
 '["beer_heineken": 2, "wine_white": 1, "kids_juice": 4, "chocolate": 2, "crackers": 2, "water": 6]',
 NULL,
 0,
 0, FALSE, NULL, NULL,
 FALSE, 0, FALSE,
 'Family with 2 kids - baby crib prepared. Marriage certificate verified for family room. Key card explained.',
 NULL),

-- Check-in/out cho booking 9 (Early checkout - đã rời sớm)
(9, '2024-05-10 14:15:00', '2024-05-13 09:30:00',
 '14:00:00', '12:00:00',
 0, 0,
 1, 1,
 TRUE, 'passport', 'AB1234567',
 FALSE, FALSE, TRUE,
 1500000, 'key_card', 'KC075',
 0, 0, NULL,
 FALSE, TRUE,
 '{"room_clean": true, "amenities_complete": true, "minibar_full": true, "towels": 4, "bathrobes": 2}',
 '{"room_clean": true, "amenities_complete": false, "minibar_partial": true, "towels": 3, "bathrobes": 2, "missing_towel": 1}',
 '["beer_tiger": 3, "vodka_mini": 1, "nuts": 2, "chocolate": 1, "water": 4]',
 '["beer_tiger": 1, "vodka_mini": 0, "nuts": 1, "chocolate": 0, "water": 2]',
 200000,
 50000, TRUE, 'Missing 1 towel, minor bathroom fixture loose', '["damage_photo_001.jpg"]',
 TRUE, 50000, TRUE,
 'Guest needed early check-in, no fee charged. Passport verification smooth.',
 'Early checkout day 4/7. Minibar consumption normal. Minor damage - towel missing + loose fixture. Guest agreed to charges.');

-- ===== DỮ LIỆU HỦY ĐẶT PHÒNG =====
INSERT INTO booking_cancellations (
    booking_id, cancellation_date, cancellation_reason, cancelled_by_guest,
    cancellation_policy_applied, penalty_percentage, penalty_amount, refund_amount,
    refund_method, refund_status, refund_processed_date, 
    staff_processed_by, cancellation_notes
) VALUES 

-- Hủy booking do khách hàng (trước 48h - miễn phí)
(7, '2024-09-28 16:30:00', 'Thay đổi kế hoạch du lịch do công việc đột xuất', TRUE,
 'CANCEL_FREE_48H', 0.00, 0, 1488600,
 'bank_transfer', 'completed', '2024-09-30 10:15:00',
 1, 'Khách hủy trước 48h theo policy. Hoàn tiền đầy đủ qua bank transfer như yêu cầu.'),

-- Hủy booking do khách hàng (trong 24h - phí 50%)
(8, '2024-12-23 20:00:00', 'Ốm đột ngột, không thể đi được', TRUE,
 'CANCEL_24H_50PCT', 50.00, 3873000, 3873000,
 'credit_card', 'completed', '2024-12-26 14:20:00',
 1, 'Hủy trong 24h - áp dụng phí 50%. Khách thông cảm policy. Hoàn 50% về credit card.'),

-- Hủy booking do khách sạn (overbooking)
(3, '2024-08-14 18:45:00', 'Overbooking do lỗi hệ thống, phòng không available', FALSE,
 'HOTEL_CANCELLATION', 0.00, 0, 6776000,
 'bank_transfer', 'completed', '2024-08-15 09:00:00',
 2, 'Hotel-initiated cancellation due to overbooking. Full refund + compensation voucher 1M VND offered.'),

-- Hủy booking muộn (no refund)
(6, '2024-11-29 22:00:00', 'Không thể sắp xếp được thời gian', TRUE,
 'CANCEL_SAME_DAY_NO_REFUND', 100.00, 7734000, 0,
 'not_applicable', 'not_applicable', NULL,
 1, 'Same-day cancellation - no refund policy applied. Deposit forfeited. Guest understood terms.');

-- ===== DỮ LIỆU KHIẾU NẠI KHÁCH HÀNG =====
INSERT INTO guest_complaints (
    booking_id, guest_name, guest_contact, complaint_type, complaint_description,
    complaint_date, assigned_to, priority_level, status,
    resolution_description, compensation_offered, compensation_amount,
    guest_satisfaction, follow_up_required, follow_up_date
) VALUES 

-- Khiếu nại về tiếng ồn
(4, 'Trần Văn Gia Đình', 'family@gmail.com', 'noise', 
 'Phòng bên cạnh gây ồn ào từ 11h đêm đến 2h sáng. Có tiếng nhạc to và tiếng nói. Ảnh hưởng đến giấc ngủ của gia đình có trẻ nhỏ.',
 '2024-06-25 08:30:00', 1, 'high', 'resolved',
 'Đã liên hệ và nhắc nhở khách phòng bên. Di chuyển gia đình sang suite khác tầng 15 yên tĩnh hơn. Không phát sinh thêm chi phí.',
 'Upgrade suite miễn phí + late checkout + voucher spa', 500000,
 'satisfied', TRUE, '2024-06-27'),

-- Khiếu nại về vệ sinh phòng
(1, 'Lê Văn Khách', 'customer@gmail.com', 'cleanliness',
 'Phòng tắm có vết bẩn ở khe gạch. Khăn tắm có mùi ẩm mốc. Gối có vết ố vàng nhỏ.',
 '2024-06-16 07:45:00', 1, 'medium', 'resolved',
 'Housekeeping đã dọn lại phòng ngay lập tức. Thay mới toàn bộ khăn tắm và gối. Xin lỗi khách về sơ suất.',
 'Miễn phí spa massage + room service dinner', 200000,
 'satisfied', FALSE, NULL),

-- Khiếu nại về dịch vụ nhân viên
(2, 'Nguyễn Thị VIP', 'vip.customer@gmail.com', 'service_quality',
 'Nhân viên bell boy có thái độ không thân thiện khi hỗ trợ hành lý. Không smile, trả lời cộc lốc. Không worthy VIP treatment.',
 '2024-07-21 16:20:00', 2, 'urgent', 'resolved',
 'Đã training lại nhân viên về VIP service standard. Manager đến xin lỗi trực tiếp. Assign dedicated butler cho remaining stay.',
 'Upgrade Presidential Suite 1 night + champagne + fruit basket + spa voucher', 2000000,
 'very_satisfied', TRUE, '2024-08-21'),

-- Khiếu nại về hóa đơn
(9, 'Phan Thị Sớm', 'early@gmail.com', 'billing_issue',
 'Bị charge minibar items không sử dụng. Hóa đơn ghi 5 beer nhưng chỉ uống 2. Không đồng ý với phí early checkout.',
 '2024-05-13 11:00:00', 1, 'medium', 'resolved',
 'Recheck minibar log, confirmed guest đúng. Adjust bill, refund overcharge. Early checkout fee explained clearly theo policy.',
 'Refund overcharge + voucher next stay', 150000,
 'neutral', FALSE, NULL),

-- Khiếu nại về tiện ích
(4, 'Trần Văn Gia Đình', 'family@gmail.com', 'facility_problem',
 'Wi-Fi trong phòng rất chậm, không thể video call cho công việc. Pool area không có lifeguard khi trẻ em bơi.',
 '2024-06-25 14:30:00', 1, 'high', 'resolved',
 'IT đã fix wifi, upgrade bandwidth. Đã arrange lifeguard schedule đầy đủ cho pool area. Test wifi tốc độ OK.',
 'Extend stay 1 night free + kids activity voucher', 1500000,
 'satisfied', TRUE, '2024-07-25');

-- ===== DỮ LIỆU THAY ĐỔI BOOKING =====
INSERT INTO booking_changes_log (
    booking_id, change_type, old_values, new_values, change_reason,
    financial_impact_amount, fee_applied, refund_amount, additional_charge,
    processed_by, change_source, approval_required, approved_by, approval_date, notes
) VALUES 

-- Thay đổi ngày check-in muộn 1 ngày
(4, 'dates_change', 
 '{"check_in_date": "2024-06-23", "check_out_date": "2024-06-27", "total_nights": 4}',
 '{"check_in_date": "2024-06-24", "check_out_date": "2024-06-27", "total_nights": 3}',
 'Khách muốn check-in muộn 1 ngày do flight delay',
 -1620000, 0, 1620000, 0,
 1, 'guest_request', TRUE, 2, '2024-06-20 15:30:00',
 'Guest requested 1-day later check-in. Adjusted total nights from 4 to 3. Refund 1 night fee.'),

-- Upgrade phòng
(1, 'room_change',
 '{"room_id": 1, "room_type": "Deluxe Room", "room_number": "D001"}',
 '{"room_id": 46, "room_type": "Premium Corner", "room_number": "PC046"}',
 'Upgrade do Deluxe room maintenance issue',
 340000, 0, 0, 340000,
 1, 'staff_initiated', FALSE, NULL, NULL,
 'Complimentary upgrade due to maintenance in original room. No additional charge to guest.'),

-- Early checkout
(9, 'early_checkout',
 '{"check_out_date": "2024-05-16", "total_nights": 7}',
 '{"check_out_date": "2024-05-13", "total_nights": 4}',
 'Khách phải về sớm do công việc khẩn cấp',
 -1960000, 0, 1960000, 0,
 1, 'guest_request', TRUE, 2, '2024-05-12 16:45:00',
 'Early checkout by 3 days. Partial refund after deducting early checkout fee according to policy.'),

-- Thay đổi số người
(2, 'other',
 '{"adults_count": 2, "children_count": 0}',
 '{"adults_count": 2, "children_count": 1}',
 'Khách đưa thêm 1 trẻ em đi cùng last minute',
 0, 0, 0, 0,
 1, 'guest_request', FALSE, NULL, NULL,
 'Added 1 child to booking. No additional charge as child under 12 and within room capacity.'),

-- Late checkout
(2, 'late_checkout',
 '{"checkout_time": "12:00:00"}',
 '{"checkout_time": "14:00:00"}',
 'VIP guest request late checkout',
 0, 0, 0, 0,
 1, 'guest_request', FALSE, NULL, NULL,
 'VIP complimentary late checkout until 2PM. No additional fee for VIP tier.'),

-- Payment status update
(8, 'payment_update',
 '{"payment_status": "pending", "deposit_amount": 0}',
 '{"payment_status": "partial", "deposit_amount": 2325000}',
 'Guest paid 30% deposit after booking confirmation',
 2325000, 0, 0, 2325000,
 1, 'system_automated', FALSE, NULL, NULL,
 'Deposit payment received and confirmed. Booking status updated from pending to confirmed.'),

-- Cancellation
(7, 'cancellation',
 '{"booking_status": "confirmed"}',
 '{"booking_status": "cancelled"}',
 'Guest cancelled booking within free cancellation period',
 -4965000, 0, 1488600, 0,
 1, 'guest_request', TRUE, 1, '2024-09-28 17:00:00',
 'Free cancellation 48h before check-in. Full refund processed to original payment method.');

-- ===== DỮ LIỆU NO-SHOW MANAGEMENT =====
INSERT INTO no_show_management (
    booking_id, expected_checkin_time, no_show_declared_time, grace_period_hours,
    no_show_fee_applied, deposit_forfeited, room_released,
    contact_attempts, last_contact_time, customer_response,
    final_action, refund_amount, reschedule_date, staff_handled_by, notes
) VALUES 

-- No-show case 1: Không liên hệ được, tịch thu deposit
(6, '2024-11-30 14:00:00', '2024-11-30 20:00:00', 6,
 7734000, 2320200, TRUE,
 3, '2024-11-30 18:30:00', 'Phone không bắt máy, email không reply',
 'fee_charged', 0, NULL, 1,
 'Guest không xuất hiện và không liên hệ được. Đã gọi 3 lần và gửi email. Phòng đã release lúc 8PM.'),

-- No-show case 2: Late arrival, resolved
(10, '2025-09-15 14:00:00', '2025-09-15 20:00:00', 6,
 0, 0, FALSE,
 2, '2025-09-15 19:45:00', 'Flight delay, sẽ đến vào 10PM cùng ngày',
 'rescheduled', 0, NULL, 1,
 'Guest liên hệ báo flight delay. Giữ phòng đến 10PM. Không tính phí no-show.'),

-- No-show case 3: Medical emergency, full refund
(5, '2024-09-01 14:00:00', '2024-09-01 20:00:00', 6,
 0, 0, TRUE,
 1, '2024-09-02 10:00:00', 'Emergency hospitalization, có giấy xác nhận bệnh viện',
 'full_refund', 7867000, NULL, 2,
 'Guest báo emergency vào viện. Có giấy xác nhận. Manager approve full refund theo humanitarian policy.');

-- ===== DỮ LIỆU OVERBOOKING MANAGEMENT =====
INSERT INTO overbooking_management (
    booking_id, overbooking_date, overbooking_reason,
    solution_type, alternative_room_id, upgrade_room_type_id, partner_hotel_name,
    compensation_amount, additional_benefits, resolution_status, customer_acceptance,
    resolution_notes, staff_handled_by, manager_approved_by
) VALUES 

-- Overbooking case 1: Room upgrade solution
(3, '2024-08-14 18:00:00', 'System double booking error during maintenance period',
 'room_upgrade', NULL, 3, NULL,
 0, 'Complimentary breakfast, spa voucher 1M VND, late checkout',
 'completed', TRUE,
 'Guest accepted Premium Corner upgrade. Very satisfied with compensation package.',
 1, 2),

-- Overbooking case 2: Partner hotel solution  
(8, '2024-12-23 19:00:00', 'Peak season demand exceeded available rooms',
 'partner_hotel', NULL, NULL, 'Hanoi Pearl Hotel',
 2000000, 'Transportation, upgrade to suite at partner hotel, dinner voucher',
 'completed', TRUE,
 'Moved to 5-star partner hotel with suite upgrade. Guest satisfied with arrangement.',
 1, 2),

-- Overbooking case 3: Compensation solution
(7, '2024-09-28 16:00:00', 'Last-minute room out of order due to pipe burst',
 'compensation', NULL, NULL, NULL,
 5000000, 'Full refund + 5M cash compensation + voucher cho booking tiếp theo',
 'completed', TRUE,
 'Room 091 pipe burst 2h before check-in. Substantial compensation offered and accepted.',
 2, 2),

-- Overbooking case 4: Reschedule solution
(4, '2024-06-23 15:00:00', 'Housekeeping delay due to previous guest damage',
 'reschedule', NULL, NULL, NULL,
 1000000, 'Free night extension, spa voucher, priority booking',
 'completed', TRUE,
 'Delayed check-in by 1 day due to room repairs. Guest accepted with compensation.',
 1, 2);

-- ===== DỮ LIỆU ROOM MAINTENANCE =====
INSERT INTO room_maintenance (
    room_id, maintenance_type, description, start_date, end_date, status,
    assigned_to, cost, priority, notes
) VALUES 

-- Regular cleaning maintenance
(1, 'deep_cleaning', 'Deep cleaning sau khi khách checkout - carpet shampooing, wall washing',
 '2024-06-18 12:00:00', '2024-06-18 18:00:00', 'completed',
 'Housekeeping Team A', 500000, 'medium',
 'Deep clean hoàn thành tốt. Phòng ready cho guest tiếp theo.'),

-- Repair maintenance  
(91, 'repair', 'Sửa chữa pipe burst trong bathroom - thay ống nước và re-tile',
 '2024-09-28 14:00:00', '2024-09-30 18:00:00', 'completed',
 'Maintenance Team - Mr. Tuấn', 15000000, 'urgent',
 'Pipe burst major repair. Cần thay toàn bộ bathroom pipes và re-tile floor.'),

-- Upgrade maintenance
(46, 'upgrade', 'Upgrade TV từ 43 inch lên 55 inch 4K Smart TV',
 '2024-07-24 09:00:00', '2024-07-24 15:00:00', 'completed',
 'Technical Team - Mr. Đức', 12000000, 'low',
 'VIP room upgrade completed. New 55 inch Samsung QLED installed.'),

-- Preventive inspection
(75, 'inspection', 'Kiểm tra định kỳ electrical và plumbing systems',
 '2024-05-14 08:00:00', '2024-05-14 12:00:00', 'completed',
 'Technical Team - Mr. Long', 200000, 'medium',
 'Quarterly inspection completed. All systems functioning normally.'),

-- Ongoing maintenance
(105, 'repair', 'Thay thế air conditioning unit - old unit not cooling effectively',
 '2024-06-25 08:00:00', NULL, 'in_progress',
 'Technical Team - Mr. Hùng', 8000000, 'high',
 'AC replacement in progress. Guest moved to different room during repair.'),

-- Scheduled maintenance
(120, 'cleaning', 'Room preparation cho VIP guest arrival ngày mai',
 '2024-06-26 10:00:00', NULL, 'scheduled',
 'Housekeeping Team B', 300000, 'medium',
 'Special VIP preparation - extra amenities, fresh flowers, champagne setup.'),

-- Cancelled maintenance
(60, 'upgrade', 'Bathroom renovation project - postponed due to budget',
 '2024-08-01 08:00:00', NULL, 'cancelled',
 'Renovation Team', 25000000, 'low',
 'Project cancelled due to budget constraints. Rescheduled to Q1 2025.');

-- ===== DỮ LIỆU GUEST COOLDOWN TRACKING =====
INSERT INTO guest_cooldown_tracking (
    guest_identifier, guest_id_number, last_checkout_date, last_booking_id, last_room_id,
    cooldown_end_date, cooldown_type, total_stays, loyalty_level, is_cooldown_active,
    cooldown_exemption_reason
) VALUES 

-- Early checkout cooldown
('early@gmail.com', '789012345678', '2024-05-13', 9, 75,
 '2024-05-20', 'early_checkout', 1, 'member', FALSE,
 NULL),

-- Cancellation cooldown  
('cancel@gmail.com', '456789012345', '2024-12-23', 8, NULL,
 '2024-12-30', 'cancellation', 2, 'member', FALSE,
 NULL),

-- No-show cooldown
('noshow@gmail.com', '012345678901', '2024-11-30', 6, NULL,
 '2024-12-07', 'no_show', 1, 'member', FALSE,
 NULL),

-- VIP exemption
('vip.customer@gmail.com', '987654321098', '2024-07-23', 2, 46,
 NULL, NULL, 5, 'diamond', FALSE,
 'VIP customer - exempt from cooldown policies'),

-- Loyal customer exemption
('loyal@gmail.com', '345678901234', '2024-06-27', 4, 91,
 NULL, NULL, 12, 'gold', FALSE,
 'Gold member - cooldown waived due to loyalty status'),

-- Standard guest tracking
('customer@gmail.com', '123456789012', '2024-06-18', 1, 1,
 NULL, NULL, 3, 'member', FALSE,
 NULL);

