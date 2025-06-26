-- ===== GUEST REVIEWS SAMPLE DATA =====
-- Dữ liệu mẫu cho hệ thống đánh giá khách hàng (chỉ từ website chính)

-- Thêm một số booking mẫu trước (giả định có sẵn)
-- Booking ID 1-5 sẽ được tạo trong ứng dụng thực tế

-- Thêm đánh giá mẫu cho booking thực tế
INSERT INTO guest_reviews (
    booking_id, guest_name, guest_email, stay_date_from, stay_date_to, room_number,
    stay_purpose, group_size,
    room_cleanliness_rating, room_comfort_rating, room_amenities_rating, bed_quality_rating,
    room_view_rating, room_size_rating, wifi_quality_rating, air_conditioning_rating,
    bathroom_quality_rating, value_for_money_rating,
    front_desk_service_rating, housekeeping_service_rating, concierge_service_rating,
    restaurant_service_rating, nps_score, overall_satisfaction,
    positive_feedback, improvement_suggestions, children_count, key_card_experience,
    allow_public_display, allow_marketing_use, review_source, review_status
) VALUES 
-- Đánh giá cho booking_id = 1 (Deluxe Room 201)
(1, 'Lê Văn Khách', 'customer@gmail.com', '2024-06-15', '2024-06-18', '201',
 'leisure', 3,
 5, 5, 4, 5, 4, 4, 5, 5, 5, 4,
 5, 5, 4, 4, 9, 5,
 'Phòng rất sạch sẽ, nhân viên thân thiện, view đẹp nhìn ra thành phố. WiFi rất nhanh, phòng tắm hiện đại.',
 'Có thể cải thiện thêm tiện nghi trong phòng như máy pha cà phê tốt hơn', 1, 'excellent',
 TRUE, TRUE, 'website_form', 'approved'),

-- Đánh giá cho booking_id = 2 (Deluxe Room 301)
(2, 'Lê Văn Khách', 'customer@gmail.com', '2024-07-20', '2024-07-22', '301',
 'business', 2,
 4, 4, 4, 4, 4, 4, 4, 4, 4, 4,
 4, 4, 3, NULL, 7, 4,
 'Phòng corner view rất đẹp, không gian rộng rãi. Dịch vụ lễ tân tốt.',
 'WiFi tầng 11 hơi chậm vào buổi tối, nên cải thiện', 0, 'good',
 TRUE, FALSE, 'email_survey', 'approved'),

-- Đánh giá thấp cho Deluxe Room (có vấn đề)
(3, 'Lê Văn Cường', 'cuong.le@email.com', '2024-12-08', '2024-12-10', '302',
 'family', 4,
 2, 3, 3, 3, 3, 3, 2, 3, 2, 2,
 3, 2, 2, 2, 4, 2,
 'Vị trí khách sạn tốt', 
 'Phòng không được dọn dẹp kỹ, WiFi rất chậm, phòng tắm có mùi không tốt. Cần training lại nhân viên dọn phòng và nâng cấp hệ thống WiFi.',
 2, 'poor',
 FALSE, FALSE, 'tablet_lobby', 'approved'),

-- Đánh giá xuất sắc cho The Level Suite
(4, 'Phạm Minh Đức', 'duc.pham@email.com', '2024-12-12', '2024-12-15', '2503',
 'honeymoon', 2,
 5, 5, 5, 5, 5, 5, 5, 5, 5, 4,
 5, 5, 5, 5, 10, 5,
 'Phòng Suite tuyệt vời! Dịch vụ The Level Lounge tầng 33 xuất sắc. View panoramic tuyệt đẹp. Nhân viên rất chuyên nghiệp và chu đáo. Trải nghiệm honeymoon hoàn hảo!',
 'Có thể bổ sung thêm một số amenities cao cấp như champagne welcome trong gói honeymoon', 0, 'excellent',
 TRUE, TRUE, 'website_form', 'approved'),

-- Đánh giá cho Presidential Suite
(5, 'Hoàng Thị Emi', 'emi.hoang@email.com', '2024-12-18', '2024-12-20', '3201',
 'business', 3,
 5, 5, 5, 5, 5, 5, 5, 5, 5, 3,
 5, 5, 5, 5, 9, 5,
 'Presidential Suite không gian rộng lớn, 2 phòng ngủ riêng biệt rất tiện. View toàn cảnh thành phố tuyệt đẹp. Dịch vụ butler xuất sắc.',
 'Giá hơi cao so với một số khách sạn 5 sao khác, nhưng chất lượng xứng đáng', 0, 'excellent',
 TRUE, TRUE, 'phone_survey', 'approved'),

-- Đánh giá pending (chờ duyệt)
(6, 'Võ Minh Tuấn', 'tuan.vo@email.com', '2024-12-20', '2024-12-22', '1008',
 'leisure', 2,
 4, 4, 5, 4, 4, 4, 4, 4, 4, 4,
 4, 4, 4, 4, 8, 4,
 'Dịch vụ tốt, phòng sạch sẽ, nhân viên chu đáo.',
 'Có thể cải thiện thêm về âm thanh cách âm', 0, 'good',
 TRUE, TRUE, 'sms_survey', 'pending');

-- ===== IMPROVEMENT ACTIONS SAMPLE DATA =====
-- Dữ liệu mẫu cho các hành động cải thiện

INSERT INTO improvement_actions (
    review_id, issue_category, issue_description, priority_level,
    action_type, action_description, target_completion_date,
    assigned_department, budget_allocated, status, progress_percentage
) VALUES 
-- Cải thiện WiFi dựa trên feedback
(2, 'facility_maintenance', 'WiFi tầng 11 chậm vào buổi tối', 'high',
 'system_enhancement', 'Nâng cấp router WiFi tầng 11 và tăng băng thông', '2025-01-15',
 'it', 15000000, 'in_progress', 60),

-- Cải thiện chất lượng dọn phòng
(3, 'service_quality', 'Chất lượng dọn phòng không đồng đều', 'high',
 'staff_training', 'Training lại toàn bộ nhân viên housekeeping về quy trình dọn phòng chuẩn', '2025-01-10',
 'housekeeping', 5000000, 'planned', 0),

-- Nâng cấp hệ thống phòng tắm
(3, 'facility_improvement', 'Một số phòng tắm có mùi không tốt', 'medium',
 'facility_improvement', 'Kiểm tra và nâng cấp hệ thống thoát nước phòng tắm tầng 3', '2025-02-01',
 'maintenance', 25000000, 'planned', 0),

-- Bổ sung amenities cao cấp
(4, 'service_quality', 'Khách honeymoon mong muốn thêm amenities', 'low',
 'process_change', 'Thiết kế gói honeymoon package với champagne và chocolate welcome', '2025-01-20',
 'front_office', 3000000, 'planned', 0),

-- Cải thiện cách âm
(6, 'facility_improvement', 'Yêu cầu cải thiện cách âm', 'medium',
 'facility_improvement', 'Khảo sát và nâng cấp hệ thống cách âm cho các phòng Premium Corner', '2025-02-15',
 'maintenance', 50000000, 'planned', 0);

-- Cập nhật rating cho room_types (sẽ được trigger tự động làm)
-- Nhưng có thể chạy manual để đảm bảo:
UPDATE room_types rt
SET 
    average_rating = (
        SELECT AVG(gr.overall_satisfaction)
        FROM guest_reviews gr
        JOIN bookings b ON gr.booking_id = b.booking_id
        JOIN rooms r ON b.room_id = r.room_id
        WHERE r.room_type_id = rt.type_id
        AND gr.review_status = 'approved'
    ),
    total_reviews = (
        SELECT COUNT(*)
        FROM guest_reviews gr
        JOIN bookings b ON gr.booking_id = b.booking_id
        JOIN rooms r ON b.room_id = r.room_id
        WHERE r.room_type_id = rt.type_id
        AND gr.review_status = 'approved'
    ),
    rating_updated_at = NOW()
WHERE rt.type_id IN (
    SELECT DISTINCT r.room_type_id
    FROM guest_reviews gr
    JOIN bookings b ON gr.booking_id = b.booking_id
    JOIN rooms r ON b.room_id = r.room_id
    WHERE gr.review_status = 'approved'
);
