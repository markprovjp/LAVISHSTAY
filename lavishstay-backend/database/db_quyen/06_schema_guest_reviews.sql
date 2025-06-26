-- ===== GUEST REVIEWS & FEEDBACK SYSTEM =====
-- Hệ thống đánh giá và phản hồi khách hàng

-- Bảng đánh giá khách hàng (chỉ từ website chính)
CREATE TABLE guest_reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã đánh giá',
    booking_id INT NOT NULL COMMENT 'Mã đặt phòng',
    guest_name VARCHAR(100) NOT NULL COMMENT 'Tên khách đánh giá',
    guest_email VARCHAR(100) COMMENT 'Email khách (để follow-up)',
    
    -- THÔNG TIN CHUNG VỀ CHUYẾN ĐI
    stay_date_from DATE NOT NULL COMMENT 'Ngày check-in',
    stay_date_to DATE NOT NULL COMMENT 'Ngày check-out',
    room_number VARCHAR(10) COMMENT 'Số phòng đã ở',
    stay_purpose ENUM('business', 'leisure', 'family', 'honeymoon', 'conference', 'group_tour') COMMENT 'Mục đích lưu trú',
    -- Giải thích các mục đích lưu trú:
    -- business: Công tác, làm việc hoặc 1 mình
    -- leisure: Du lịch, nghỉ dưỡng
    -- family: Đi cùng gia đình
    -- honeymoon: Tuần trăng mật
    -- conference: Dự hội nghị, sự kiện
    -- group_tour: Đi theo tour nhóm
    group_size INT DEFAULT 1 COMMENT 'Số người trong nhóm',
    
    -- ĐÁNH GIÁ BẰNG THANG ĐIỂM 5
    -- Đánh giá căn phòng
    room_cleanliness_rating TINYINT CHECK (room_cleanliness_rating BETWEEN 1 AND 5) COMMENT 'Điểm sạch sẽ phòng (1-5)',
    room_comfort_rating TINYINT CHECK (room_comfort_rating BETWEEN 1 AND 5) COMMENT 'Điểm thoải mái phòng (1-5)',
    room_amenities_rating TINYINT CHECK (room_amenities_rating BETWEEN 1 AND 5) COMMENT 'Điểm tiện nghi phòng (1-5)',
    bed_quality_rating TINYINT CHECK (bed_quality_rating BETWEEN 1 AND 5) COMMENT 'Điểm chất lượng giường (1-5)',
    room_view_rating TINYINT CHECK (room_view_rating BETWEEN 1 AND 5) COMMENT 'Điểm view phòng (1-5)',
    room_size_rating TINYINT CHECK (room_size_rating BETWEEN 1 AND 5) COMMENT 'Điểm kích thước phòng (1-5)',
    
    -- Đánh giá dịch vụ kỹ thuật
    wifi_quality_rating TINYINT CHECK (wifi_quality_rating BETWEEN 1 AND 5) COMMENT 'Điểm chất lượng WiFi (1-5)',
    air_conditioning_rating TINYINT CHECK (air_conditioning_rating BETWEEN 1 AND 5) COMMENT 'Điểm điều hòa (1-5)',
    bathroom_quality_rating TINYINT CHECK (bathroom_quality_rating BETWEEN 1 AND 5) COMMENT 'Điểm chất lượng phòng tắm (1-5)',
    
    -- Đánh giá giá trị
    value_for_money_rating TINYINT CHECK (value_for_money_rating BETWEEN 1 AND 5) COMMENT 'Điểm tương xứng với giá (1-5)',
    
    -- Đánh giá nhân viên dịch vụ
    front_desk_service_rating TINYINT CHECK (front_desk_service_rating BETWEEN 1 AND 5) COMMENT 'Điểm dịch vụ lễ tân (1-5)',
    housekeeping_service_rating TINYINT CHECK (housekeeping_service_rating BETWEEN 1 AND 5) COMMENT 'Điểm dịch vụ dọn phòng (1-5)',
    concierge_service_rating TINYINT CHECK (concierge_service_rating BETWEEN 1 AND 5) COMMENT 'Điểm dịch vụ hỗ trợ khách (1-5)',
    
    -- Đánh giá dịch vụ phụ
    restaurant_service_rating TINYINT CHECK (restaurant_service_rating BETWEEN 1 AND 5) COMMENT 'Điểm dịch vụ nhà hàng (1-5)',
    spa_service_rating TINYINT CHECK (spa_service_rating BETWEEN 1 AND 5) COMMENT 'Điểm dịch vụ spa (1-5)',
    fitness_service_rating TINYINT CHECK (fitness_service_rating BETWEEN 1 AND 5) COMMENT 'Điểm dịch vụ thể thao (1-5)',
    
    -- THANG ĐIỂM NPS (Net Promoter Score)
    nps_score TINYINT CHECK (nps_score BETWEEN 0 AND 10) COMMENT 'Điểm NPS - Giới thiệu cho người khác (0-10)',
    overall_satisfaction TINYINT CHECK (overall_satisfaction BETWEEN 1 AND 5) COMMENT 'Điểm hài lòng tổng thể (1-5)',
    
    -- PHẢN HỒI MỞ
    positive_feedback TEXT COMMENT 'Điều hài lòng nhất',
    negative_feedback TEXT COMMENT 'Điều không hài lòng',
    improvement_suggestions TEXT COMMENT 'Gợi ý cải thiện',
    additional_comments TEXT COMMENT 'Nhận xét thêm',
    
    -- THÔNG TIN BỔ SUNG
    children_count INT DEFAULT 0 COMMENT 'Số trẻ em dưới 4 tuổi',
    special_needs TEXT COMMENT 'Nhu cầu đặc biệt (nếu có)',
    key_card_experience ENUM('excellent', 'good', 'fair', 'poor') COMMENT 'Trải nghiệm thẻ từ',
    -- Giải thích trải nghiệm thẻ từ:
    -- excellent: Thẻ từ hoạt động tốt, không gặp vấn đề
    -- good: Thỉnh thoảng gặp vấn đề nhỏ
    -- fair: Thường xuyên gặp vấn đề, cần cải thiện
    -- poor: Thẻ từ hỏng, không sử dụng được

    -- CHỈ TỪNG WEBSITE CHÍNH - KHÔNG OTA
    review_source ENUM('website_form', 'email_survey', 'sms_survey', 'tablet_lobby', 'paper_form', 'phone_survey') DEFAULT 'website_form' COMMENT 'Nguồn thu thập đánh giá từ website chính',
    -- Giải thích nguồn thu thập:
    -- website_form: Form đánh giá trên website chính của khách sạn
    -- email_survey: Khảo sát gửi qua email sau khi check-out
    -- sms_survey: Link khảo sát gửi qua SMS
    -- tablet_lobby: Máy tính bảng đặt tại lobby
    -- paper_form: Phiếu đánh giá giấy trong phòng
    -- phone_survey: Khảo sát qua điện thoại
    
    -- CHO PHÉP HIỂN THỊ
    allow_public_display BOOLEAN DEFAULT FALSE COMMENT 'Cho phép hiển thị công khai trên website',
    allow_marketing_use BOOLEAN DEFAULT FALSE COMMENT 'Cho phép sử dụng cho mục đích marketing',
    
    -- THÔNG TIN XỬ LÝ
    review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày đánh giá',
    staff_response TEXT COMMENT 'Phản hồi từ nhân viên',
    management_response TEXT COMMENT 'Phản hồi từ ban quản lý',
    follow_up_required BOOLEAN DEFAULT FALSE COMMENT 'Cần follow-up',
    follow_up_completed BOOLEAN DEFAULT FALSE COMMENT 'Đã hoàn thành follow-up',
    follow_up_date TIMESTAMP NULL COMMENT 'Ngày follow-up',
    
    -- TRẠNG THÁI
    review_status ENUM('pending', 'approved', 'rejected', 'hidden') DEFAULT 'pending' COMMENT 'Trạng thái duyệt đánh giá',
    moderation_notes TEXT COMMENT 'Ghi chú kiểm duyệt',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
) COMMENT 'Bảng đánh giá khách hàng từ website chính (không bao gồm OTA)';

-- Bảng hành động cải thiện dựa trên feedback
CREATE TABLE improvement_actions (
    action_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã hành động',
    review_id INT COMMENT 'Mã đánh giá liên quan (nếu có)',
    
    -- PHÂN LOẠI VẤN ĐỀ
    issue_category ENUM('room_quality', 'service_quality', 'facility_maintenance', 'facility_improvement', 'staff_training', 'process_improvement', 'technology_upgrade') COMMENT 'Loại vấn đề',
    -- Giải thích các loại vấn đề:
    -- room_quality: Vấn đề liên quan đến chất lượng phòng (sạch sẽ, tiện nghi, view)
    -- service_quality: Vấn đề liên quan đến chất lượng dịch vụ (lễ tân, dọn phòng, nhà hàng)
    -- facility_maintenance: Vấn đề liên quan đến bảo trì cơ sở vật chất (hồ bơi, phòng tập, spa)
    -- staff_training: Vấn đề liên quan đến đào tạo nhân viên (kỹ năng giao tiếp, chuyên môn)
    -- process_improvement: Vấn đề liên quan đến cải tiến quy trình (check-in, check-out, đặt phòng)
    -- technology_upgrade: Vấn đề liên quan đến nâng cấp công nghệ (WiFi, hệ thống đặt phòng)
    issue_description TEXT NOT NULL COMMENT 'Mô tả vấn đề',
    priority_level ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium' COMMENT 'Mức độ ưu tiên',
    -- Giải thích mức độ ưu tiên:
    -- low: Không cần xử lý ngay, có thể lên kế hoạch sau
    -- medium: Cần xử lý trong thời gian tới, không quá gấp
    -- high: Cần xử lý sớm, ảnh hưởng đến trải nghiệm khách hàng
    -- urgent: Cần xử lý ngay lập tức, ảnh hưởng nghiêm trọng đến khách

    -- HÀNH ĐỘNG CỤ THỂ
    action_type ENUM('staff_training', 'equipment_upgrade', 'process_change', 'facility_improvement', 'policy_update', 'system_enhancement') COMMENT 'Loại hành động',
    -- Giải thích các loại hành động:
    -- staff_training: Đào tạo lại nhân viên về kỹ năng hoặc quy trình
    -- equipment_upgrade: Nâng cấp thiết bị hoặc công nghệ (WiFi, điều hòa)
    -- process_change: Thay đổi quy trình làm việc (check-in, check-out)
    -- facility_improvement: Cải thiện cơ sở vật chất (phòng tắm, hồ bơi)
    -- policy_update: Cập nhật chính sách (đặt phòng, hủy phòng)
    -- system_enhancement: Nâng cấp hệ thống công nghệ (phần mềm đặt phòng, hệ thống quản lý khách sạn)

    action_description TEXT NOT NULL COMMENT 'Mô tả hành động cải thiện',
    target_completion_date DATE COMMENT 'Ngày dự kiến hoàn thành',
    actual_completion_date DATE COMMENT 'Ngày hoàn thành thực tế',
    
    -- PHÂN CÔNG TRÁCH NHIỆM
    assigned_department ENUM('front_office', 'housekeeping', 'maintenance', 'f&b', 'spa', 'management', 'it', 'hr') COMMENT 'Phòng ban phụ trách',
    -- Giải thích các phòng ban:
    -- front_office: Lễ tân, tiếp tân
    -- housekeeping: Dọn phòng, vệ sinh
    -- maintenance: Bảo trì, sửa chữa
    -- f&b: Nhà hàng, dịch vụ ăn uống
    -- spa: Dịch vụ spa và làm đẹp
    -- management: Ban quản lý, điều hành
    -- it: Công nghệ thông tin, hệ thống
    -- hr: Nhân sự, đào tạo

    assigned_to INT COMMENT 'Người phụ trách',
    budget_allocated BIGINT DEFAULT 0 COMMENT 'Ngân sách phân bổ (VND)',
    actual_cost BIGINT DEFAULT 0 COMMENT 'Chi phí thực tế (VND)',
    
    -- TRẠNG THÁI VÀ THEO DÕI
    status ENUM('planned', 'in_progress', 'completed', 'cancelled', 'on_hold') DEFAULT 'planned' COMMENT 'Trạng thái thực hiện',
    -- Giải thích các trạng thái:
    -- planned: Đã lên kế hoạch, chưa bắt đầu
    -- in_progress: Đang thực hiện, chưa hoàn thành
    -- completed: Đã hoàn thành, thực hiện xong
    -- cancelled: Hủy bỏ, không thực hiện nữa
    -- on_hold: Tạm dừng, chờ xử lý thêm
    
    progress_percentage INT DEFAULT 0 COMMENT 'Tiến độ hoàn thành (%)',
    impact_assessment TEXT COMMENT 'Đánh giá tác động sau cải thiện',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    
    FOREIGN KEY (review_id) REFERENCES guest_reviews(review_id),
    FOREIGN KEY (assigned_to) REFERENCES users(user_id)
) COMMENT 'Bảng quản lý hành động cải thiện dựa trên phản hồi khách hàng';

-- Trigger tự động cập nhật rating cho room_types khi có đánh giá mới
DELIMITER //
CREATE TRIGGER update_room_type_rating 
AFTER INSERT ON guest_reviews
FOR EACH ROW
BEGIN
    DECLARE room_type_id_var INT;
    
    -- Lấy room_type_id từ booking
    SELECT rt.type_id INTO room_type_id_var
    FROM bookings b
    JOIN rooms r ON b.room_id = r.room_id
    JOIN room_types rt ON r.room_type_id = rt.type_id
    WHERE b.booking_id = NEW.booking_id;
    
    -- Cập nhật rating trung bình và số lượng review cho room_type
    UPDATE room_types 
    SET 
        average_rating = (
            SELECT AVG(overall_satisfaction)
            FROM guest_reviews gr
            JOIN bookings b ON gr.booking_id = b.booking_id
            JOIN rooms r ON b.room_id = r.room_id
            WHERE r.room_type_id = room_type_id_var
            AND gr.review_status = 'approved'
        ),
        total_reviews = (
            SELECT COUNT(*)
            FROM guest_reviews gr
            JOIN bookings b ON gr.booking_id = b.booking_id
            JOIN rooms r ON b.room_id = r.room_id
            WHERE r.room_type_id = room_type_id_var
            AND gr.review_status = 'approved'
        ),
        rating_updated_at = NOW()
    WHERE type_id = room_type_id_var;
END//

-- Trigger cập nhật rating khi trạng thái review thay đổi
CREATE TRIGGER update_room_type_rating_on_status_change
AFTER UPDATE ON guest_reviews
FOR EACH ROW
BEGIN
    DECLARE room_type_id_var INT;
    
    -- Chỉ chạy khi trạng thái review thay đổi
    IF OLD.review_status != NEW.review_status THEN
        -- Lấy room_type_id từ booking
        SELECT rt.type_id INTO room_type_id_var
        FROM bookings b
        JOIN rooms r ON b.room_id = r.room_id
        JOIN room_types rt ON r.room_type_id = rt.type_id
        WHERE b.booking_id = NEW.booking_id;
        
        -- Cập nhật rating trung bình và số lượng review cho room_type
        UPDATE room_types 
        SET 
            average_rating = (
                SELECT AVG(overall_satisfaction)
                FROM guest_reviews gr
                JOIN bookings b ON gr.booking_id = b.booking_id
                JOIN rooms r ON b.room_id = r.room_id
                WHERE r.room_type_id = room_type_id_var
                AND gr.review_status = 'approved'
            ),
            total_reviews = (
                SELECT COUNT(*)
                FROM guest_reviews gr
                JOIN bookings b ON gr.booking_id = b.booking_id
                JOIN rooms r ON b.room_id = r.room_id
                WHERE r.room_type_id = room_type_id_var
                AND gr.review_status = 'approved'
            ),
            rating_updated_at = NOW()
        WHERE type_id = room_type_id_var;
    END IF;
END//

DELIMITER ;
