-- ===== VIEWS AND REPORTS =====
-- Các view báo cáo và thống kê cho hệ thống

-- ==== VIEW QUẢN LÝ PHÒNG ====

-- View phòng trống theo loại phòng và kiểu giường
CREATE VIEW available_rooms_by_type AS
SELECT 
    rt.type_name,
    rt.view_type,
    r.bed_type_fixed,
    COUNT(r.room_id) as available_count,
    rt.base_price_single,
    rt.base_price_double,
    rt.area_sqm,
    rt.max_occupancy
FROM room_types rt
LEFT JOIN rooms r ON rt.type_id = r.room_type_id 
WHERE r.status = 'available'
GROUP BY rt.type_id, rt.type_name, rt.view_type, r.bed_type_fixed, rt.base_price_single, rt.base_price_double, rt.area_sqm, rt.max_occupancy
ORDER BY rt.type_id, r.bed_type_fixed;

-- View phòng trống chi tiết theo tầng
CREATE VIEW available_rooms_by_floor AS
SELECT 
    f.floor_number,
    f.floor_name,
    rt.type_name,
    r.bed_type_fixed,
    r.room_number,
    r.status,
    rt.base_price_single,
    rt.base_price_double
FROM floors f
JOIN rooms r ON f.floor_id = r.floor_id
JOIN room_types rt ON r.room_type_id = rt.type_id
WHERE r.status IN ('available', 'occupied', 'maintenance')
ORDER BY f.floor_number, r.room_number;

-- View kiểm tra phân bổ phòng theo kế hoạch
CREATE VIEW room_distribution_check AS
SELECT 
    rt.type_name,
    rt.total_rooms_planned as 'Kế hoạch',
    COUNT(r.room_id) as 'Thực tế',
    SUM(CASE WHEN r.bed_type_fixed = 'King' THEN 1 ELSE 0 END) as 'Phòng King',
    SUM(CASE WHEN r.bed_type_fixed = 'Twin' THEN 1 ELSE 0 END) as 'Phòng Twin',
    SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) as 'Phòng trống',
    SUM(CASE WHEN r.status = 'occupied' THEN 1 ELSE 0 END) as 'Phòng đang sử dụng',
    SUM(CASE WHEN r.status = 'maintenance' THEN 1 ELSE 0 END) as 'Phòng bảo trì',
    SUM(CASE WHEN r.status = 'out_of_order' THEN 1 ELSE 0 END) as 'Phòng hỏng',
    CASE 
        WHEN rt.total_rooms_planned = COUNT(r.room_id) THEN 'Đúng kế hoạch'
        ELSE 'Sai lệch kế hoạch'
    END as 'Trạng thái'
FROM room_types rt 
LEFT JOIN rooms r ON rt.type_id = r.room_type_id 
GROUP BY rt.type_id, rt.type_name, rt.total_rooms_planned
ORDER BY rt.type_id;

-- ==== VIEW BÁO CÁO TỔNG QUAN ====

-- View tổng quan khách sạn
CREATE VIEW hotel_overview AS
SELECT 
    (SELECT COUNT(*) FROM rooms) as total_rooms,
    (SELECT COUNT(*) FROM rooms WHERE status = 'available') as available_rooms,
    (SELECT COUNT(*) FROM rooms WHERE status = 'occupied') as occupied_rooms,
    (SELECT COUNT(*) FROM room_types) as total_room_types,
    (SELECT COUNT(*) FROM floors WHERE floor_type = 'residential') as residential_floors,
    (SELECT COUNT(*) FROM floors WHERE floor_type IN ('service', 'special')) as service_floors,
    (SELECT COUNT(*) FROM rooms WHERE bed_type_fixed = 'King') as total_king_rooms,
    (SELECT COUNT(*) FROM rooms WHERE bed_type_fixed = 'Twin') as total_twin_rooms;

-- View tiện ích tầng đặc biệt
CREATE VIEW special_floors_facilities AS
SELECT 
    floor_number,
    floor_name,
    floor_type,
    facilities,
    description
FROM floors 
WHERE floor_type IN ('service', 'special', 'penthouse')
ORDER BY floor_number;

-- ==== VIEW BÁO CÁO BOOKING VÀ DOANH THU ====

-- View thống kê booking
CREATE VIEW booking_statistics AS
SELECT 
    COUNT(*) as total_bookings,
    SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
    SUM(CASE WHEN booking_status = 'checked_in' THEN 1 ELSE 0 END) as checked_in_bookings,
    SUM(CASE WHEN booking_status = 'checked_out' THEN 1 ELSE 0 END) as completed_bookings,
    SUM(CASE WHEN booking_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings,
    SUM(CASE WHEN booking_status = 'no_show' THEN 1 ELSE 0 END) as no_show_bookings,
    SUM(CASE WHEN booking_status = 'overbooked' THEN 1 ELSE 0 END) as overbooked_bookings,
    AVG(final_total) as avg_booking_value,
    SUM(final_total) as total_revenue
FROM bookings
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY);

-- View chi tiết booking
CREATE VIEW booking_details_view AS
SELECT 
    b.booking_id,
    DATE(b.created_at) as booking_date,
    b.check_in_date,
    b.check_out_date,
    b.guest_full_name,
    b.guest_email,
    b.guest_phone,
    r.room_number,
    rt.type_name as room_type,
    r.bed_type_fixed,
    f.floor_number,
    (b.adults_count + b.children_count) as guest_count,
    mp.plan_name as meal_plan,
    b.final_total,
    b.booking_status,
    DATEDIFF(b.check_out_date, b.check_in_date) as stay_nights
FROM bookings b
LEFT JOIN rooms r ON b.room_id = r.room_id
LEFT JOIN room_types rt ON r.room_type_id = rt.type_id
LEFT JOIN floors f ON r.floor_id = f.floor_id
LEFT JOIN meal_plans mp ON b.meal_plan_id = mp.meal_plan_id
ORDER BY b.created_at DESC;
-- View tóm tắt check-in
CREATE VIEW checkin_summary_view AS
SELECT 
    ci.checkin_id,
    b.booking_id,
    b.guest_full_name,
    r.room_number,
    rt.type_name,
    r.bed_type_fixed,
    ci.actual_checkin_time,
    ci.actual_checkout_time,
    ci.identity_verified,
    ci.security_deposit_collected,
    ci.room_key_type,
    ci.room_key_number,
    -- Chỉ cộng các trường có thật, nếu không có thì để 0
    0 as additional_charges
FROM check_inout ci
JOIN bookings b ON ci.booking_id = b.booking_id
JOIN rooms r ON b.room_id = r.room_id
JOIN room_types rt ON r.room_type_id = rt.type_id
ORDER BY ci.actual_checkin_time DESC;

-- View báo cáo doanh thu theo tháng
CREATE VIEW monthly_revenue_report AS
SELECT 
    YEAR(b.created_at) as year,
    MONTH(b.created_at) as month,
    COUNT(b.booking_id) as total_bookings,
    SUM(b.final_total) as total_revenue,
    AVG(b.final_total) as avg_booking_value,
    SUM(CASE WHEN b.booking_status = 'checked_out' THEN b.final_total ELSE 0 END) as confirmed_revenue,
    COUNT(DISTINCT b.room_id) as unique_rooms_booked,
    SUM(b.total_nights) as total_room_nights
FROM bookings b
WHERE b.created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
GROUP BY YEAR(b.created_at), MONTH(b.created_at)
ORDER BY year DESC, month DESC;

-- ==== VIEW TIỆN NGHI PHÒNG ====

-- View tiện nghi theo loại phòng
CREATE VIEW room_type_amenities_view AS
SELECT 
    rt.type_name,
    a.name as amenity_name,
    a.category,
    a.icon,
    rta.is_main_amenity,
    a.description
FROM room_types rt
JOIN room_type_amenities rta ON rt.type_id = rta.room_type_id
JOIN amenities a ON rta.amenity_id = a.amenity_id
WHERE a.is_active = TRUE
ORDER BY rt.type_id, a.category, rta.is_main_amenity DESC;

-- View tiện nghi nổi bật theo loại phòng
CREATE VIEW room_type_main_amenities AS
SELECT 
    rt.type_id,
    rt.type_name,
    GROUP_CONCAT(
        CONCAT(a.name, ' (', a.icon, ')') 
        ORDER BY a.category, a.name 
        SEPARATOR ', '
    ) as main_amenities
FROM room_types rt
JOIN room_type_amenities rta ON rt.type_id = rta.room_type_id
JOIN amenities a ON rta.amenity_id = a.amenity_id
WHERE rta.is_main_amenity = TRUE AND a.is_active = TRUE
GROUP BY rt.type_id, rt.type_name
ORDER BY rt.type_id;

-- View tất cả tiện nghi theo loại phòng
CREATE VIEW room_type_all_amenities AS
SELECT 
    rt.type_id,
    rt.type_name,
    a.category,
    GROUP_CONCAT(a.name ORDER BY a.name SEPARATOR ', ') as amenities_in_category
FROM room_types rt
JOIN room_type_amenities rta ON rt.type_id = rta.room_type_id
JOIN amenities a ON rta.amenity_id = a.amenity_id
WHERE a.is_active = TRUE
GROUP BY rt.type_id, rt.type_name, a.category
ORDER BY rt.type_id, a.category;

-- ==== VIEW QUẢN LÝ VẬN HÀNH ====

-- View trạng thái phòng theo tầng
CREATE VIEW floor_room_status AS
SELECT 
    f.floor_number,
    f.floor_name,
    COUNT(r.room_id) as total_rooms,
    SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) as available_rooms,
    SUM(CASE WHEN r.status = 'occupied' THEN 1 ELSE 0 END) as occupied_rooms,
    SUM(CASE WHEN r.status = 'maintenance' THEN 1 ELSE 0 END) as maintenance_rooms,
    SUM(CASE WHEN r.status = 'out_of_order' THEN 1 ELSE 0 END) as out_of_order_rooms,
    SUM(CASE WHEN r.bed_type_fixed = 'King' THEN 1 ELSE 0 END) as king_rooms,
    SUM(CASE WHEN r.bed_type_fixed = 'Twin' THEN 1 ELSE 0 END) as twin_rooms,
    ROUND(SUM(CASE WHEN r.status = 'occupied' THEN 1 ELSE 0 END) / COUNT(r.room_id) * 100, 2) as occupancy_rate
FROM floors f
LEFT JOIN rooms r ON f.floor_id = r.floor_id
WHERE f.floor_type = 'residential'
GROUP BY f.floor_id, f.floor_number, f.floor_name
ORDER BY f.floor_number;

-- View lịch trình dọn phòng
CREATE VIEW housekeeping_schedule AS
SELECT 
    r.room_number,
    f.floor_number,
    rt.type_name,
    r.bed_type_fixed,
    r.status,
    r.last_cleaned,
    CASE 
        WHEN r.last_cleaned IS NULL THEN 'Cần dọn ngay'
        WHEN r.last_cleaned < DATE_SUB(NOW(), INTERVAL 1 DAY) THEN 'Cần dọn'
        WHEN r.last_cleaned < DATE_SUB(NOW(), INTERVAL 12 HOUR) THEN 'Kiểm tra'
        ELSE 'Đã dọn'
    END as cleaning_priority,
    TIMESTAMPDIFF(HOUR, r.last_cleaned, NOW()) as hours_since_cleaned
FROM rooms r
JOIN floors f ON r.floor_id = f.floor_id
JOIN room_types rt ON r.room_type_id = rt.type_id
WHERE f.floor_type = 'residential'
ORDER BY 
    CASE 
        WHEN r.last_cleaned IS NULL THEN 1
        WHEN r.last_cleaned < DATE_SUB(NOW(), INTERVAL 1 DAY) THEN 2
        WHEN r.last_cleaned < DATE_SUB(NOW(), INTERVAL 12 HOUR) THEN 3
        ELSE 4
    END,
    f.floor_number, r.room_number;

-- ==== VIEW THAY THẾ FLOOR_ROOM_TYPES ====

-- View phân bổ phòng theo tầng và loại phòng (thay thế bảng floor_room_types)
CREATE VIEW floor_room_type_distribution AS
SELECT 
    f.floor_id,
    f.floor_number,
    f.floor_name,
    rt.type_id as room_type_id,
    rt.type_name,
    COUNT(r.room_id) as rooms_count,
    MIN(r.room_number) as room_number_start,
    MAX(r.room_number) as room_number_end,
    GROUP_CONCAT(r.room_number ORDER BY r.room_number SEPARATOR ', ') as room_numbers,
    SUM(CASE WHEN r.bed_type_fixed = 'King' THEN 1 ELSE 0 END) as king_beds_count,
    SUM(CASE WHEN r.bed_type_fixed = 'Twin' THEN 1 ELSE 0 END) as twin_beds_count,
    SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) as available_count,
    SUM(CASE WHEN r.status = 'occupied' THEN 1 ELSE 0 END) as occupied_count,
    CONCAT(rt.type_name, ' tầng ', f.floor_number) as notes
FROM floors f
JOIN rooms r ON f.floor_id = r.floor_id
JOIN room_types rt ON r.room_type_id = rt.type_id
WHERE f.floor_type = 'residential'
GROUP BY f.floor_id, f.floor_number, f.floor_name, rt.type_id, rt.type_name
HAVING COUNT(r.room_id) > 0
ORDER BY f.floor_number, rt.type_id;

-- View tóm tắt phân bổ theo loại phòng
CREATE VIEW room_type_floor_summary AS
SELECT 
    rt.type_name,
    rt.total_rooms_planned,
    COUNT(DISTINCT r.floor_id) as floors_used,
    COUNT(r.room_id) as actual_rooms,
    GROUP_CONCAT(DISTINCT f.floor_number ORDER BY f.floor_number SEPARATOR ', ') as floor_numbers,
    SUM(CASE WHEN r.bed_type_fixed = 'King' THEN 1 ELSE 0 END) as total_king_beds,
    SUM(CASE WHEN r.bed_type_fixed = 'Twin' THEN 1 ELSE 0 END) as total_twin_beds,
    CASE 
        WHEN rt.total_rooms_planned = COUNT(r.room_id) THEN 'Đúng kế hoạch'
        WHEN rt.total_rooms_planned > COUNT(r.room_id) THEN 'Thiếu phòng'
        ELSE 'Thừa phòng'
    END as allocation_status
FROM room_types rt
LEFT JOIN rooms r ON rt.type_id = r.room_type_id
LEFT JOIN floors f ON r.floor_id = f.floor_id
GROUP BY rt.type_id, rt.type_name, rt.total_rooms_planned
ORDER BY rt.type_id;

-- ==== VIEW BÁO CÁO XỬ LÝ TRƯỜNG HỢP ĐẶC BIỆT ====

-- View thống kê overbooking
CREATE VIEW overbooking_statistics AS
SELECT 
    COUNT(*) as total_overbooking_cases,
    SUM(CASE WHEN solution_type = 'room_upgrade' THEN 1 ELSE 0 END) as upgrade_solutions,
    SUM(CASE WHEN solution_type = 'alternative_room' THEN 1 ELSE 0 END) as alternative_room_solutions,
    SUM(CASE WHEN solution_type = 'partner_hotel' THEN 1 ELSE 0 END) as partner_hotel_solutions,
    SUM(CASE WHEN solution_type = 'compensation' THEN 1 ELSE 0 END) as compensation_solutions,
    SUM(CASE WHEN customer_acceptance = TRUE THEN 1 ELSE 0 END) as accepted_solutions,
    AVG(compensation_amount) as avg_compensation,
    SUM(compensation_amount) as total_compensation_cost
FROM overbooking_management
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY);

-- View thống kê no-show
CREATE VIEW no_show_statistics AS
SELECT 
    COUNT(*) as total_no_show_cases,
    SUM(CASE WHEN final_action = 'fee_charged' THEN 1 ELSE 0 END) as fee_charged_cases,
    SUM(CASE WHEN final_action = 'full_refund' THEN 1 ELSE 0 END) as full_refund_cases,
    SUM(CASE WHEN final_action = 'partial_refund' THEN 1 ELSE 0 END) as partial_refund_cases,
    SUM(CASE WHEN final_action = 'rescheduled' THEN 1 ELSE 0 END) as rescheduled_cases,
    AVG(no_show_fee_applied) as avg_no_show_fee,
    SUM(no_show_fee_applied) as total_no_show_revenue,
    AVG(contact_attempts) as avg_contact_attempts
FROM no_show_management
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY);

-- View thống kê khiếu nại
CREATE VIEW complaint_statistics AS
SELECT 
    COUNT(*) as total_complaints,
    SUM(CASE WHEN complaint_type = 'room_condition' THEN 1 ELSE 0 END) as room_condition_complaints,
    SUM(CASE WHEN complaint_type = 'service_quality' THEN 1 ELSE 0 END) as service_quality_complaints,
    SUM(CASE WHEN complaint_type = 'staff_behavior' THEN 1 ELSE 0 END) as staff_behavior_complaints,
    SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved_complaints,
    SUM(CASE WHEN guest_satisfaction IN ('satisfied', 'very_satisfied') THEN 1 ELSE 0 END) as satisfied_resolutions,
    AVG(compensation_amount) as avg_compensation,
    SUM(compensation_amount) as total_complaint_cost
FROM guest_complaints
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY);

-- View báo cáo bảo trì phòng
CREATE VIEW maintenance_report AS
SELECT 
    r.room_number,
    rt.type_name,
    f.floor_number,
    rm.maintenance_type,
    rm.status,
    rm.priority,
    rm.start_date,
    rm.end_date,
    rm.cost,
    rm.assigned_to,
    rm.description
FROM room_maintenance rm
JOIN rooms r ON rm.room_id = r.room_id
JOIN room_types rt ON r.room_type_id = rt.type_id
JOIN floors f ON r.floor_id = f.floor_id
WHERE rm.status IN ('scheduled', 'in_progress')
ORDER BY rm.priority DESC, rm.start_date ASC;

-- View báo cáo doanh thu chi tiết
CREATE VIEW revenue_detailed_report AS
SELECT 
    DATE(b.created_at) as booking_date,
    b.booking_id,
    b.guest_full_name,
    rt.type_name,
    b.total_nights,
    b.room_subtotal,
    b.services_subtotal,
    b.final_total,
    b.booking_status,
    CASE 
        WHEN b.booking_status = 'cancelled' THEN COALESCE(bc.refund_amount, 0)
        WHEN b.booking_status = 'no_show' THEN COALESCE(nsm.no_show_fee_applied, 0)
        ELSE b.final_total
    END as actual_revenue
FROM bookings b
JOIN rooms r ON b.room_id = r.room_id
JOIN room_types rt ON r.room_type_id = rt.type_id
LEFT JOIN booking_cancellations bc ON b.booking_id = bc.booking_id
LEFT JOIN no_show_management nsm ON b.booking_id = nsm.booking_id
WHERE b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
ORDER BY b.created_at DESC;

-- View phân tích tỷ lệ lấp đầy theo loại phòng
CREATE VIEW occupancy_rate_by_room_type AS
SELECT 
    rt.type_name,
    COUNT(r.room_id) as total_rooms,
    SUM(CASE WHEN r.status = 'occupied' THEN 1 ELSE 0 END) as occupied_rooms,
    SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) as available_rooms,
    SUM(CASE WHEN r.status = 'maintenance' THEN 1 ELSE 0 END) as maintenance_rooms,
    ROUND((SUM(CASE WHEN r.status = 'occupied' THEN 1 ELSE 0 END) / COUNT(r.room_id)) * 100, 2) as occupancy_percentage,
    ROUND((SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) / COUNT(r.room_id)) * 100, 2) as availability_percentage
FROM room_types rt
LEFT JOIN rooms r ON rt.type_id = r.room_type_id
GROUP BY rt.type_id, rt.type_name
ORDER BY occupancy_percentage DESC;

-- View cảnh báo cần xử lý ưu tiên
CREATE VIEW priority_alerts AS
SELECT 
    'Overbooking' as alert_type,
    CONCAT('Booking ID: ', om.booking_id) as reference,
    om.overbooking_reason as description,
    om.created_at as alert_time,
    'High' as priority
FROM overbooking_management om
WHERE om.resolution_status = 'pending'

UNION ALL

SELECT 
    'No-Show' as alert_type,
    CONCAT('Booking ID: ', nsm.booking_id) as reference,
    'Khách chưa check-in sau thời hạn' as description,
    nsm.no_show_declared_time as alert_time,
    'Medium' as priority
FROM no_show_management nsm
WHERE nsm.final_action IS NULL

UNION ALL

SELECT 
    'Urgent Maintenance' as alert_type,
    CONCAT('Room ', r.room_number) as reference,
    rm.description as description,
    rm.created_at as alert_time,
    'Urgent' as priority
FROM room_maintenance rm
JOIN rooms r ON rm.room_id = r.room_id
WHERE rm.priority = 'urgent' AND rm.status = 'scheduled'

UNION ALL

SELECT 
    'Unresolved Complaint' as alert_type,
    CONCAT('Complaint ID: ', gc.complaint_id) as reference,
    gc.complaint_description as description,
    gc.created_at as alert_time,
    CASE 
        WHEN gc.priority_level = 'urgent' THEN 'High'
        WHEN gc.priority_level = 'high' THEN 'Medium'
        ELSE 'Low'
    END as priority
FROM guest_complaints gc
WHERE gc.status NOT IN ('resolved', 'closed')

ORDER BY 
    CASE priority 
        WHEN 'Urgent' THEN 1
        WHEN 'High' THEN 2
        WHEN 'Medium' THEN 3
        ELSE 4
    END,
    alert_time DESC;

-- ===== GUEST FEEDBACK & RATING ANALYSIS VIEWS =====
-- Các view phân tích đánh giá và phản hồi khách hàng

-- View tổng quan phản hồi khách hàng (30 ngày qua)
CREATE VIEW guest_feedback_overview AS
SELECT 
    COUNT(*) as total_responses,
    ROUND((COUNT(*) / (SELECT COUNT(*) FROM bookings WHERE booking_status = 'checked_out' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY))) * 100, 2) as response_rate_percentage,
    ROUND(AVG(nps_score), 2) as avg_nps_score,
    ROUND(AVG(overall_satisfaction), 2) as avg_overall_satisfaction,
    
    -- Phân bố NPS
    SUM(CASE WHEN nps_score >= 9 THEN 1 ELSE 0 END) as promoters_count,
    SUM(CASE WHEN nps_score BETWEEN 7 AND 8 THEN 1 ELSE 0 END) as passives_count,
    SUM(CASE WHEN nps_score <= 6 THEN 1 ELSE 0 END) as detractors_count,
    
    -- Tính NPS thực tế
    ROUND(
        ((SUM(CASE WHEN nps_score >= 9 THEN 1 ELSE 0 END) - SUM(CASE WHEN nps_score <= 6 THEN 1 ELSE 0 END)) / COUNT(*)) * 100, 
        2
    ) as actual_nps_score,
    
    -- Phân bố mức hài lòng
    SUM(CASE WHEN overall_satisfaction = 5 THEN 1 ELSE 0 END) as very_satisfied_count,
    SUM(CASE WHEN overall_satisfaction = 4 THEN 1 ELSE 0 END) as satisfied_count,
    SUM(CASE WHEN overall_satisfaction = 3 THEN 1 ELSE 0 END) as neutral_count,
    SUM(CASE WHEN overall_satisfaction <= 2 THEN 1 ELSE 0 END) as unsatisfied_count
FROM guest_reviews
WHERE review_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY);

-- View phân tích theo loại phòng
CREATE VIEW feedback_by_room_type AS
SELECT 
    rt.type_name,
    rt.area_sqm,
    rt.base_price_double,
    COUNT(gr.review_id) as total_reviews,
    
    -- Điểm trung bình các tiêu chí phòng
    ROUND(AVG(gr.room_cleanliness_rating), 2) as avg_cleanliness,
    ROUND(AVG(gr.room_comfort_rating), 2) as avg_comfort,
    ROUND(AVG(gr.room_amenities_rating), 2) as avg_amenities,
    ROUND(AVG(gr.bed_quality_rating), 2) as avg_bed_quality,
    ROUND(AVG(gr.room_view_rating), 2) as avg_view,
    ROUND(AVG(gr.room_size_rating), 2) as avg_size,
    ROUND(AVG(gr.wifi_quality_rating), 2) as avg_wifi,
    ROUND(AVG(gr.bathroom_quality_rating), 2) as avg_bathroom,
    ROUND(AVG(gr.value_for_money_rating), 2) as avg_value,
    
    -- Điểm tổng và NPS
    ROUND(AVG(gr.overall_satisfaction), 2) as avg_overall,
    ROUND(AVG(gr.nps_score), 2) as avg_nps,
    
    -- So sánh 1 khách vs 2 khách
    ROUND(AVG(CASE WHEN gr.group_size = 1 THEN gr.overall_satisfaction END), 2) as avg_satisfaction_single,
    ROUND(AVG(CASE WHEN gr.group_size = 2 THEN gr.overall_satisfaction END), 2) as avg_satisfaction_double,
    
    -- Phòng có trẻ em
    ROUND(AVG(CASE WHEN gr.children_count > 0 THEN gr.overall_satisfaction END), 2) as avg_satisfaction_with_children
    
FROM room_types rt
LEFT JOIN rooms r ON rt.type_id = r.room_type_id
LEFT JOIN bookings b ON r.room_id = b.room_id
LEFT JOIN guest_reviews gr ON b.booking_id = gr.booking_id
WHERE gr.review_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY rt.type_id, rt.type_name, rt.area_sqm, rt.base_price_double
ORDER BY avg_overall DESC;

-- View phân tích theo tầng
CREATE VIEW feedback_by_floor AS
SELECT 
    f.floor_number,
    f.floor_name,
    f.floor_type,
    COUNT(gr.review_id) as total_reviews,
    
    -- Điểm trung bình theo tầng
    ROUND(AVG(gr.room_view_rating), 2) as avg_view_rating,
    ROUND(AVG(gr.wifi_quality_rating), 2) as avg_wifi_rating,
    ROUND(AVG(gr.overall_satisfaction), 2) as avg_overall_satisfaction,
    ROUND(AVG(gr.nps_score), 2) as avg_nps,
    
    -- Đánh giá thẻ từ (tầng cao thường có vấn đề)
    SUM(CASE WHEN gr.key_card_experience = 'excellent' THEN 1 ELSE 0 END) as excellent_keycard,
    SUM(CASE WHEN gr.key_card_experience = 'poor' THEN 1 ELSE 0 END) as poor_keycard,
    
    -- Phản hồi về view (tầng cao)
    SUM(CASE WHEN gr.room_view_rating = 5 THEN 1 ELSE 0 END) as excellent_view_count,
    SUM(CASE WHEN gr.room_view_rating <= 2 THEN 1 ELSE 0 END) as poor_view_count
    
FROM floors f
LEFT JOIN rooms r ON f.floor_id = r.floor_id
LEFT JOIN bookings b ON r.room_id = b.room_id
LEFT JOIN guest_reviews gr ON b.booking_id = gr.booking_id
WHERE gr.review_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY f.floor_id, f.floor_number, f.floor_name, f.floor_type
HAVING total_reviews > 0
ORDER BY f.floor_number;

-- View top feedback tích cực và tiêu cực
CREATE VIEW top_feedback_samples AS
(SELECT 
    'POSITIVE' as feedback_type,
    gr.guest_name,
    rt.type_name,
    r.room_number,
    gr.overall_satisfaction,
    gr.nps_score,
    gr.positive_feedback as feedback_content,
    gr.review_date
FROM guest_reviews gr
JOIN bookings b ON gr.booking_id = b.booking_id
JOIN rooms r ON b.room_id = r.room_id
JOIN room_types rt ON r.room_type_id = rt.type_id
WHERE gr.positive_feedback IS NOT NULL 
    AND gr.overall_satisfaction >= 4
    AND gr.review_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
ORDER BY gr.overall_satisfaction DESC, gr.nps_score DESC
LIMIT 10)

UNION ALL

(SELECT 
    'NEGATIVE' as feedback_type,
    gr.guest_name,
    rt.type_name,
    r.room_number,
    gr.overall_satisfaction,
    gr.nps_score,
    gr.negative_feedback as feedback_content,
    gr.review_date
FROM guest_reviews gr
JOIN bookings b ON gr.booking_id = b.booking_id
JOIN rooms r ON b.room_id = r.room_id
JOIN room_types rt ON r.room_type_id = rt.type_id
WHERE gr.negative_feedback IS NOT NULL 
    AND gr.overall_satisfaction <= 3
    AND gr.review_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
ORDER BY gr.overall_satisfaction ASC, gr.nps_score ASC
LIMIT 10);

-- View gợi ý cải thiện từ khách hàng
CREATE VIEW improvement_suggestions_summary AS
SELECT 
    gr.improvement_suggestions,
    COUNT(*) as mention_count,
    AVG(gr.overall_satisfaction) as avg_satisfaction_of_suggester,
    
    -- Phân loại gợi ý
    CASE 
        WHEN gr.improvement_suggestions LIKE '%wifi%' OR gr.improvement_suggestions LIKE '%internet%' THEN 'Technology'
        WHEN gr.improvement_suggestions LIKE '%staff%' OR gr.improvement_suggestions LIKE '%service%' THEN 'Service'
        WHEN gr.improvement_suggestions LIKE '%room%' OR gr.improvement_suggestions LIKE '%clean%' THEN 'Room Quality'
        WHEN gr.improvement_suggestions LIKE '%food%' OR gr.improvement_suggestions LIKE '%restaurant%' THEN 'F&B'
        WHEN gr.improvement_suggestions LIKE '%spa%' OR gr.improvement_suggestions LIKE '%gym%' THEN 'Facilities'
        ELSE 'Other'
    END as suggestion_category,
    
    MIN(gr.review_date) as first_mentioned,
    MAX(gr.review_date) as last_mentioned
FROM guest_reviews gr
WHERE gr.improvement_suggestions IS NOT NULL 
    AND TRIM(gr.improvement_suggestions) != ''
    AND gr.review_date >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
GROUP BY gr.improvement_suggestions
HAVING mention_count >= 2
ORDER BY mention_count DESC, avg_satisfaction_of_suggester ASC;

-- View theo dõi cải thiện dựa trên feedback
CREATE VIEW improvement_tracking AS
SELECT 
    ia.action_id,
    ia.issue_category,
    ia.issue_description,
    ia.action_type,
    ia.action_description,
    ia.priority_level,
    ia.status,
    ia.progress_percentage,
    ia.assigned_department,
    u.full_name as assigned_person,
    ia.budget_allocated,
    ia.actual_cost,
    ia.target_completion_date,
    ia.actual_completion_date,
    
    -- So sánh điểm trước và sau (nếu có)
    CASE 
        WHEN ia.actual_completion_date IS NOT NULL THEN
            (SELECT AVG(overall_satisfaction) 
             FROM guest_reviews 
             WHERE review_date >= ia.actual_completion_date 
             AND review_date <= DATE_ADD(ia.actual_completion_date, INTERVAL 30 DAY))
    END as satisfaction_after_improvement,
    
    ia.impact_assessment
FROM improvement_actions ia
LEFT JOIN users u ON ia.assigned_to = u.user_id
WHERE ia.created_at >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
ORDER BY ia.priority_level, ia.created_at DESC;

-- View cần follow-up khách hàng
CREATE VIEW follow_up_required AS
SELECT 
    gr.review_id,
    gr.guest_name,
    gr.guest_email,
    b.booking_id,
    rt.type_name,
    r.room_number,
    gr.overall_satisfaction,
    gr.nps_score,
    gr.negative_feedback,
    gr.follow_up_required,
    gr.follow_up_completed,
    gr.review_date,
    
    -- Loại follow-up cần thiết
    CASE 
        WHEN gr.overall_satisfaction <= 2 THEN 'Urgent - Personal Call'
        WHEN gr.overall_satisfaction = 3 AND gr.nps_score <= 6 THEN 'Email with Coupon'
        WHEN gr.nps_score >= 9 THEN 'Thank You Email'
        ELSE 'Standard Follow-up'
    END as follow_up_type,
    
    DATEDIFF(CURDATE(), gr.review_date) as days_since_review
FROM guest_reviews gr
JOIN bookings b ON gr.booking_id = b.booking_id
JOIN rooms r ON b.room_id = r.room_id
JOIN room_types rt ON r.room_type_id = rt.type_id
WHERE (gr.follow_up_required = TRUE AND gr.follow_up_completed = FALSE)
   OR (gr.overall_satisfaction <= 3)
   OR (gr.nps_score >= 9 AND gr.follow_up_completed = FALSE)
ORDER BY 
    CASE 
        WHEN gr.overall_satisfaction <= 2 THEN 1
        WHEN gr.overall_satisfaction = 3 THEN 2
        WHEN gr.nps_score >= 9 THEN 3
        ELSE 4
    END,
    gr.review_date DESC;

-- ==== VIEW VOUCHER & LOYALTY ====
-- View thống kê thành viên theo cấp độ
CREATE VIEW member_stats AS
SELECT 
    ml.level_code,
    ml.level_name,
    COUNT(*) as total_members,
    AVG(lp.total_points) as avg_points,
    AVG(lp.total_nights) as avg_nights,
    AVG(lp.total_spent) as avg_spent,
    SUM(lp.total_spent) as total_revenue,
    COUNT(CASE WHEN lp.status = 'active' THEN 1 END) as active_members,
    COUNT(CASE WHEN lp.last_activity_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) THEN 1 END) as recently_active
FROM loyalty_program lp
JOIN member_levels ml ON lp.level_id = ml.level_id
GROUP BY ml.level_code, ml.level_name
ORDER BY 
    CASE ml.level_code
        WHEN 'diamond' THEN 1
        WHEN 'platinum' THEN 2
        WHEN 'gold' THEN 3
        WHEN 'member' THEN 4
        ELSE 5
    END;
-- View voucher đang hoạt động
CREATE VIEW active_vouchers AS
SELECT 
    vp.package_name,
    vp.package_type,
    vp.description,
    vp.price_per_person,
    vp.validity_start,
    vp.validity_end,
    vp.min_nights,
    COUNT(cv.voucher_id) as total_issued,
    COUNT(CASE WHEN cv.status = 'active' THEN 1 END) as active_count,
    COUNT(CASE WHEN cv.status = 'used' THEN 1 END) as used_count,
    COUNT(CASE WHEN cv.status = 'expired' THEN 1 END) as expired_count,
    COUNT(CASE WHEN cv.status = 'cancelled' THEN 1 END) as cancelled_count,
    ROUND(COUNT(CASE WHEN cv.status = 'used' THEN 1 END) * 100.0 / NULLIF(COUNT(cv.voucher_id), 0), 2) as usage_rate
FROM voucher_packages vp
LEFT JOIN customer_vouchers cv ON vp.package_id = cv.package_id
WHERE vp.is_active = TRUE 
    AND vp.validity_end >= CURDATE()
GROUP BY vp.package_id, vp.package_name, vp.package_type, vp.description, 
         vp.price_per_person, vp.validity_start, vp.validity_end, vp.min_nights
ORDER BY vp.validity_end DESC;

-- View dịch vụ phổ biến
CREATE VIEW popular_services AS
SELECT 
    ps.service_type,
    ps.service_description,
    COUNT(cv.voucher_id) as usage_count,
    COUNT(CASE WHEN cv.status = 'used' THEN 1 END) as actual_usage,
    ps.discount_percent,
    ps.is_free,
    vp.package_name
FROM package_services ps
JOIN voucher_packages vp ON ps.package_id = vp.package_id
LEFT JOIN customer_vouchers cv ON ps.package_id = cv.package_id
WHERE vp.is_active = TRUE
GROUP BY ps.service_type, ps.service_description, ps.discount_percent, 
         ps.is_free, vp.package_name
ORDER BY actual_usage DESC, usage_count DESC;
CREATE VIEW customer_voucher_summary AS
SELECT 
    u.user_id,
    u.full_name,
    u.email,
    ml.level_code,
    ml.level_name,
    lp.total_points,
    COUNT(cv.voucher_id) as total_vouchers,
    COUNT(CASE WHEN cv.status = 'active' THEN 1 END) as active_vouchers,
    COUNT(CASE WHEN cv.status = 'used' THEN 1 END) as used_vouchers,
    COUNT(CASE WHEN cv.status = 'expired' THEN 1 END) as expired_vouchers,
    MAX(cv.issue_date) as last_voucher_date,
    MIN(cv.expiry_date) as nearest_expiry
FROM users u
LEFT JOIN loyalty_program lp ON u.user_id = lp.user_id
LEFT JOIN member_levels ml ON lp.level_id = ml.level_id
LEFT JOIN customer_vouchers cv ON u.user_id = cv.user_id
WHERE u.role_id = 3 -- chỉ customer
GROUP BY u.user_id, u.full_name, u.email, ml.level_code, ml.level_name, lp.total_points
HAVING total_vouchers > 0
ORDER BY used_vouchers DESC, active_vouchers DESC;
-- View phân tích điểm thưởng
CREATE VIEW points_analysis AS
SELECT 
    DATE_FORMAT(ph.transaction_date, '%Y-%m') as month_year,
    ph.transaction_type,
    COUNT(*) as transaction_count,
    SUM(ph.points_change) as total_points_change,
    AVG(ph.points_change) as avg_points_change,
    COUNT(DISTINCT ph.member_id) as unique_members
FROM points_history ph
WHERE ph.transaction_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
GROUP BY DATE_FORMAT(ph.transaction_date, '%Y-%m'), ph.transaction_type
ORDER BY month_year DESC, ph.transaction_type;

-- View dịch vụ đưa đón sân bay
CREATE VIEW airport_transfer_summary AS
SELECT 
    DATE_FORMAT(at.flight_date, '%Y-%m') as month_year,
    at.transfer_type,
    at.vehicle_type,
    COUNT(*) as transfer_count,
    SUM(at.passenger_count) as total_passengers,
    AVG(at.passenger_count) as avg_passengers,
    SUM(at.fee) as total_revenue,
    AVG(at.fee) as avg_fee,
    COUNT(CASE WHEN at.status = 'completed' THEN 1 END) as completed_transfers,
    COUNT(CASE WHEN at.status = 'cancelled' THEN 1 END) as cancelled_transfers
FROM airport_transfers at
WHERE at.flight_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY DATE_FORMAT(at.flight_date, '%Y-%m'), at.transfer_type, at.vehicle_type
ORDER BY month_year DESC, transfer_count DESC;

-- ==== VIEW BÁO CÁO DYNAMIC PRICING ====

-- View tổng quan giá phòng hiện tại và dự báo
CREATE VIEW pricing_overview AS
SELECT 
    rt.type_name,
    rt.base_price_single,
    rt.base_price_double,
    
    -- Giá hiện tại
    COALESCE(ph_today.final_price_single, rt.base_price_single) as current_price_single,
    COALESCE(ph_today.final_price_double, rt.base_price_double) as current_price_double,
    
    -- Giá ngày mai
    COALESCE(ph_tomorrow.final_price_single, rt.base_price_single) as tomorrow_price_single,
    COALESCE(ph_tomorrow.final_price_double, rt.base_price_double) as tomorrow_price_double,
    
    -- So sánh với giá gốc
    ROUND((COALESCE(ph_today.final_price_double, rt.base_price_double) - rt.base_price_double) * 100.0 / rt.base_price_double, 2) as price_change_percent,
    
    -- Occupancy và availability
    COALESCE(do_today.occupancy_rate, 0.00) as current_occupancy,
    COALESCE(do_today.available_rooms, rt.total_rooms_planned) as available_rooms,
    
    -- Pricing factors
    COALESCE(ph_today.season_multiplier, 1.00) as season_factor,
    COALESCE(ph_today.weekend_surcharge, 0) as weekend_surcharge,
    COALESCE(ph_today.event_multiplier, 1.00) as event_factor,
    
    -- Trạng thái giá
    CASE 
        WHEN COALESCE(ph_today.final_price_double, rt.base_price_double) > rt.base_price_double * 1.1 THEN 'High Premium'
        WHEN COALESCE(ph_today.final_price_double, rt.base_price_double) > rt.base_price_double THEN 'Above Base'
        WHEN COALESCE(ph_today.final_price_double, rt.base_price_double) < rt.base_price_double THEN 'Discounted'
        ELSE 'Base Price'
    END as pricing_status

FROM room_types rt
LEFT JOIN price_history ph_today ON rt.type_id = ph_today.room_type_id AND ph_today.date_applicable = CURDATE()
LEFT JOIN price_history ph_tomorrow ON rt.type_id = ph_tomorrow.room_type_id AND ph_tomorrow.date_applicable = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
LEFT JOIN daily_occupancy do_today ON rt.type_id = do_today.room_type_id AND do_today.date_applicable = CURDATE()
ORDER BY rt.type_id;

-- View phân tích revenue impact của dynamic pricing
CREATE VIEW pricing_revenue_impact AS
SELECT 
    rt.type_name,
    
    -- Revenue so sánh: Base vs Dynamic
    COUNT(b.booking_id) as total_bookings_30days,
    SUM(CASE WHEN b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN b.final_total END) as actual_revenue_30days,
    SUM(CASE WHEN b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN rt.base_price_double * b.total_nights END) as base_price_revenue_30days,
    
    -- Tính toán impact
    SUM(CASE WHEN b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN b.final_total END) - 
    SUM(CASE WHEN b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN rt.base_price_double * b.total_nights END) as revenue_difference,
    
    ROUND(
        (SUM(CASE WHEN b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN b.final_total END) - 
         SUM(CASE WHEN b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN rt.base_price_double * b.total_nights END)) * 100.0 /
        NULLIF(SUM(CASE WHEN b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN rt.base_price_double * b.total_nights END), 0), 2
    ) as revenue_impact_percent,
    
    -- Occupancy metrics
    AVG(CASE WHEN ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN ph.occupancy_rate END) as avg_occupancy_30days,
    AVG(CASE WHEN ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 
        ph.final_price_double / rt.base_price_double END) as avg_price_multiplier,
    
    -- Hiệu quả pricing
    CASE 
        WHEN AVG(CASE WHEN ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN ph.occupancy_rate END) > 0.80 
             AND (SUM(CASE WHEN b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN b.final_total END) - 
                  SUM(CASE WHEN b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN rt.base_price_double * b.total_nights END)) > 0
        THEN 'Optimal'
        WHEN AVG(CASE WHEN ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN ph.occupancy_rate END) < 0.60 
        THEN 'Too High Pricing'
        WHEN (SUM(CASE WHEN b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN b.final_total END) - 
              SUM(CASE WHEN b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN rt.base_price_double * b.total_nights END)) < 0
        THEN 'Revenue Loss'
        ELSE 'Moderate'
    END as pricing_effectiveness

FROM room_types rt
LEFT JOIN rooms r ON rt.type_id = r.room_type_id
LEFT JOIN bookings b ON r.room_id = b.room_id
LEFT JOIN price_history ph ON rt.type_id = ph.room_type_id
GROUP BY rt.type_id, rt.type_name, rt.base_price_double
ORDER BY revenue_impact_percent DESC;

-- View seasonal pricing analysis
CREATE VIEW seasonal_pricing_analysis AS
SELECT 
    st.display_name as season_type,
    ps.season_name,
    ps.start_date,
    ps.end_date,
    ps.price_multiplier,
    
    -- Bookings trong mùa này
    COUNT(b.booking_id) as bookings_in_season,
    AVG(b.final_total) as avg_booking_value,
    SUM(b.final_total) as total_revenue,
    
    -- Occupancy trong mùa
    AVG(ph.occupancy_rate) as avg_occupancy,
    
    -- So sánh với base price
    AVG(ph.final_price_double) as avg_actual_price,
    AVG(rt.base_price_double) as avg_base_price,
    ROUND(AVG(ph.final_price_double) / AVG(rt.base_price_double), 2) as actual_multiplier,
    
    -- Performance
    CASE 
        WHEN AVG(ph.occupancy_rate) > 0.85 AND ps.price_multiplier >= 1.2 THEN 'High Demand - Optimal'
        WHEN AVG(ph.occupancy_rate) < 0.60 THEN 'Low Demand - Consider Lower Price'
        WHEN ps.price_multiplier < 0.9 AND AVG(ph.occupancy_rate) > 0.70 THEN 'Opportunity to Increase'
        ELSE 'Balanced'
    END as season_performance

FROM pricing_seasons ps
JOIN season_types st ON ps.season_type_id = st.season_type_id
LEFT JOIN price_history ph ON ph.date_applicable BETWEEN ps.start_date AND ps.end_date
LEFT JOIN room_types rt ON ph.room_type_id = rt.type_id
LEFT JOIN rooms r ON rt.type_id = r.room_type_id
LEFT JOIN bookings b ON r.room_id = b.room_id 
    AND b.check_in_date BETWEEN ps.start_date AND ps.end_date
WHERE ps.year_applicable IS NULL OR ps.year_applicable = YEAR(CURDATE())
GROUP BY ps.season_id, st.display_name, ps.season_name, ps.start_date, ps.end_date, ps.price_multiplier
ORDER BY ps.start_date;

-- View weekend pricing effectiveness
CREATE VIEW weekend_pricing_analysis AS
SELECT 
    MONTHNAME(ph.date_applicable) as month,
    YEAR(ph.date_applicable) as year,
    
    -- Weekend vs Weekday pricing
    AVG(CASE WHEN DAYOFWEEK(ph.date_applicable) IN (1,6,7) THEN ph.final_price_double END) as avg_weekend_price,
    AVG(CASE WHEN DAYOFWEEK(ph.date_applicable) NOT IN (1,6,7) THEN ph.final_price_double END) as avg_weekday_price,
    
    -- Weekend premium
    AVG(CASE WHEN DAYOFWEEK(ph.date_applicable) IN (1,6,7) THEN ph.weekend_surcharge END) as avg_weekend_surcharge,
    
    -- Occupancy comparison
    AVG(CASE WHEN DAYOFWEEK(ph.date_applicable) IN (1,6,7) THEN ph.occupancy_rate END) as avg_weekend_occupancy,
    AVG(CASE WHEN DAYOFWEEK(ph.date_applicable) NOT IN (1,6,7) THEN ph.occupancy_rate END) as avg_weekday_occupancy,
    
    -- Revenue comparison
    SUM(CASE WHEN DAYOFWEEK(ph.date_applicable) IN (1,6,7) THEN 
        ph.final_price_double * (rt.total_rooms_planned * ph.occupancy_rate) END) as weekend_revenue,
    SUM(CASE WHEN DAYOFWEEK(ph.date_applicable) NOT IN (1,6,7) THEN 
        ph.final_price_double * (rt.total_rooms_planned * ph.occupancy_rate) END) as weekday_revenue,
    
    -- Effectiveness
    ROUND(
        (AVG(CASE WHEN DAYOFWEEK(ph.date_applicable) IN (1,6,7) THEN ph.final_price_double END) - 
         AVG(CASE WHEN DAYOFWEEK(ph.date_applicable) NOT IN (1,6,7) THEN ph.final_price_double END)) * 100.0 /
        NULLIF(AVG(CASE WHEN DAYOFWEEK(ph.date_applicable) NOT IN (1,6,7) THEN ph.final_price_double END), 0), 2
    ) as weekend_premium_percent

FROM price_history ph
JOIN room_types rt ON ph.room_type_id = rt.type_id
WHERE ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
GROUP BY YEAR(ph.date_applicable), MONTH(ph.date_applicable)
ORDER BY year DESC, month DESC;

-- View event pricing performance
CREATE VIEW event_pricing_performance AS
SELECT 
    ep.event_name,
    ep.event_type,
    ep.start_date,
    ep.end_date,
    ep.price_multiplier as planned_multiplier,
    ep.occupancy_threshold,
    
    -- Actual performance
    COUNT(b.booking_id) as bookings_during_event,
    AVG(ph.occupancy_rate) as actual_avg_occupancy,
    AVG(ph.final_price_double / rt.base_price_double) as actual_avg_multiplier,
    SUM(b.final_total) as total_event_revenue,
    
    -- Comparison with regular days
    (SELECT AVG(final_price_double) FROM price_history 
     WHERE date_applicable BETWEEN DATE_SUB(ep.start_date, INTERVAL 7 DAY) AND DATE_SUB(ep.start_date, INTERVAL 1 DAY)
     AND room_type_id = rt.type_id) as avg_price_week_before,
    
    -- Event effectiveness
    CASE 
        WHEN AVG(ph.occupancy_rate) >= ep.occupancy_threshold AND 
             AVG(ph.final_price_double / rt.base_price_double) >= ep.price_multiplier * 0.9 THEN 'Successful'
        WHEN AVG(ph.occupancy_rate) < ep.occupancy_threshold THEN 'Low Occupancy'
        WHEN AVG(ph.final_price_double / rt.base_price_double) < ep.price_multiplier * 0.9 THEN 'Price Not Achieved'
        ELSE 'Partial Success'
    END as event_performance,
    
    DATEDIFF(ep.end_date, ep.start_date) + 1 as event_duration_days

FROM event_pricing ep
LEFT JOIN price_history ph ON ph.date_applicable BETWEEN ep.start_date AND ep.end_date
LEFT JOIN room_types rt ON ph.room_type_id = rt.type_id
LEFT JOIN rooms r ON rt.type_id = r.room_type_id
LEFT JOIN bookings b ON r.room_id = b.room_id 
    AND b.check_in_date BETWEEN ep.start_date AND ep.end_date
WHERE ep.start_date >= DATE_SUB(CURDATE(), INTERVAL 180 DAY)
GROUP BY ep.event_id, rt.type_id
HAVING rt.type_id IS NOT NULL
ORDER BY ep.start_date DESC;

-- View corporate contract utilization
CREATE VIEW corporate_contract_utilization AS
SELECT 
    gc.contract_name,
    gc.company_name,
    gc.contract_type,
    gc.discount_value,
    gc.start_date,
    gc.end_date,
    
    -- Contract usage
    COUNT(b.booking_id) as total_bookings,
    SUM(b.total_nights) as total_nights,
    SUM(b.final_total) as total_revenue,
    AVG(b.final_total) as avg_booking_value,
    
    -- Discount impact
    SUM(rt.base_price_double * b.total_nights) as would_be_revenue_without_discount,
    SUM(rt.base_price_double * b.total_nights) - SUM(b.final_total) as total_discount_given,
    
    -- Contract compliance
    AVG(CASE WHEN 1 >= gc.min_rooms_per_booking THEN 1.0 ELSE 0.0 END) as room_min_compliance,
    AVG(CASE WHEN b.total_nights >= gc.min_nights_per_booking THEN 1.0 ELSE 0.0 END) as night_min_compliance,
    
    -- Utilization rate
    COUNT(b.booking_id) * 100.0 / NULLIF(gc.min_bookings_per_year, 0) as yearly_booking_progress,
    SUM(b.final_total) * 100.0 / NULLIF(gc.min_revenue_per_year, 0) as yearly_revenue_progress,
    
    DATEDIFF(CURDATE(), gc.start_date) as days_active,
    DATEDIFF(gc.end_date, CURDATE()) as days_remaining

FROM group_contracts gc
LEFT JOIN bookings b ON JSON_CONTAINS(b.internal_notes, CONCAT('"contract_id":', gc.contract_id))
LEFT JOIN rooms r ON b.room_id = r.room_id
LEFT JOIN room_types rt ON r.room_type_id = rt.type_id
WHERE gc.is_active = TRUE
GROUP BY gc.contract_id
ORDER BY total_revenue DESC;

-- View long stay pricing effectiveness
CREATE VIEW long_stay_pricing_effectiveness AS
SELECT 
    lsp.policy_name,
    lsp.min_nights,
    lsp.max_nights,
    lsp.discount_value,
    
    -- Usage statistics
    COUNT(b.booking_id) as qualifying_bookings,
    AVG(b.total_nights) as avg_stay_nights,
    AVG(b.final_total) as avg_booking_value,
    SUM(b.final_total) as total_revenue,
    
    -- Discount analysis
    AVG(rt.base_price_double * b.total_nights) as avg_base_price_total,
    AVG(b.final_total) as avg_discounted_total,
    AVG(rt.base_price_double * b.total_nights) - AVG(b.final_total) as avg_discount_amount,
    
    -- Effectiveness metrics
    COUNT(b.booking_id) * 100.0 / (
        SELECT COUNT(*) FROM bookings 
        WHERE total_nights >= lsp.min_nights 
        AND (lsp.max_nights IS NULL OR total_nights <= lsp.max_nights)
    ) as capture_rate,
    
    CASE 
        WHEN COUNT(b.booking_id) > 10 AND AVG(b.total_nights) > lsp.min_nights + 3 THEN 'High Success'
        WHEN COUNT(b.booking_id) BETWEEN 3 AND 10 THEN 'Moderate Success'
        WHEN COUNT(b.booking_id) < 3 THEN 'Low Uptake'
        ELSE 'Unknown'
    END as policy_effectiveness

FROM long_stay_pricing lsp
LEFT JOIN bookings b ON b.total_nights >= lsp.min_nights 
    AND (lsp.max_nights IS NULL OR b.total_nights <= lsp.max_nights)
    AND b.created_at BETWEEN COALESCE(lsp.valid_from, '2024-01-01') AND COALESCE(lsp.valid_to, CURDATE())
LEFT JOIN rooms r ON b.room_id = r.room_id
LEFT JOIN room_types rt ON r.room_type_id = rt.type_id
WHERE lsp.is_active = TRUE
GROUP BY lsp.long_stay_id
ORDER BY qualifying_bookings DESC;

-- View pricing optimization recommendations
CREATE VIEW pricing_optimization_recommendations AS
SELECT 
    rt.type_name,
    
    -- Current metrics
    AVG(ph.occupancy_rate) as avg_occupancy_30days,
    AVG(ph.final_price_double) as avg_current_price,
    rt.base_price_double,
    COUNT(b.booking_id) as bookings_30days,
    
    -- Pricing analysis
    CASE 
        WHEN AVG(ph.occupancy_rate) > 0.90 AND AVG(ph.final_price_double) <= rt.base_price_double * 1.1 
        THEN 'INCREASE PRICE - High demand, room for premium'
        
        WHEN AVG(ph.occupancy_rate) < 0.50 AND AVG(ph.final_price_double) >= rt.base_price_double 
        THEN 'DECREASE PRICE - Low occupancy, consider discounts'
        
        WHEN AVG(ph.occupancy_rate) BETWEEN 0.75 AND 0.85 AND AVG(ph.final_price_double) < rt.base_price_double * 0.95
        THEN 'INCREASE DISCOUNT - Good occupancy, can afford lower margins'
        
        WHEN AVG(ph.occupancy_rate) BETWEEN 0.60 AND 0.75 
        THEN 'OPTIMIZE SEASONALITY - Adjust seasonal multipliers'
        
        WHEN COUNT(b.booking_id) < 5 
        THEN 'MARKETING NEEDED - Low booking volume'
        
        ELSE 'MAINTAIN CURRENT - Balanced performance'
    END as recommendation,
    
    -- Suggested price range
    CASE 
        WHEN AVG(ph.occupancy_rate) > 0.90 
        THEN ROUND(rt.base_price_double * 1.15, -3)
        WHEN AVG(ph.occupancy_rate) < 0.50 
        THEN ROUND(rt.base_price_double * 0.85, -3)
        ELSE ROUND(rt.base_price_double, -3)
    END as suggested_base_price,
    
    -- Priority level
    CASE 
        WHEN AVG(ph.occupancy_rate) > 0.95 OR AVG(ph.occupancy_rate) < 0.40 THEN 'High'
        WHEN AVG(ph.occupancy_rate) BETWEEN 0.75 AND 0.95 OR AVG(ph.occupancy_rate) BETWEEN 0.40 AND 0.60 THEN 'Medium'
        ELSE 'Low'
    END as priority

FROM room_types rt
LEFT JOIN price_history ph ON rt.type_id = ph.room_type_id 
    AND ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
LEFT JOIN rooms r ON rt.type_id = r.room_type_id
LEFT JOIN bookings b ON r.room_id = b.room_id 
    AND b.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY rt.type_id, rt.type_name, rt.base_price_double
ORDER BY 
    CASE priority 
        WHEN 'High' THEN 1
        WHEN 'Medium' THEN 2
        ELSE 3
    END,
    avg_occupancy_30days DESC;
