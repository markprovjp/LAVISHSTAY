-- ===== UNIFIED POLICIES SCHEMA =====
-- Hệ thống quản lý chính sách thống nhất thay thế cho multiple policy tables

-- ==== POLICY CATEGORIES ====
-- Phân loại các nhóm chính sách
CREATE TABLE policy_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY  COMMENT 'ID duy nhất cho mỗi phân loại chính sách',
    category_code VARCHAR(20) NOT NULL UNIQUE COMMENT 'Mã phân loại: DEPOSIT, CANCEL, CHECKOUT, etc.',

    category_name VARCHAR(100) NOT NULL COMMENT 'Tên phân loại',
    description TEXT COMMENT 'Mô tả chi tiết',
    is_active BOOLEAN DEFAULT TRUE  COMMENT 'Trạng thái hoạt động của phân loại',
    display_order INT DEFAULT 1 COMMENT 'Thứ tự hiển thị',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật'
);

-- ==== UNIFIED POLICIES TABLE ====
-- Bảng chính sách thống nhất với JSON config
CREATE TABLE policies (
    policy_id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID duy nhất cho mỗi chính sách',
    category_id INT NOT NULL COMMENT 'Liên kết đến policy_categories',
    
    -- Basic Info
    policy_code VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã chính sách duy nhất',
    policy_name VARCHAR(200) NOT NULL COMMENT 'Tên chính sách',
    policy_description TEXT COMMENT 'Mô tả chi tiết',
    
    -- Policy Type & Season
    policy_type ENUM('deposit', 'cancellation', 'checkout', 'refund', 'room_change', 'cooldown', 'upgrade', 'loyalty') NOT NULL COMMENT 'Loại chính sách: deposit, cancellation, checkout, refund, room_change, cooldown, upgrade, loyalty',
    season_type ENUM('all', 'low', 'medium', 'high', 'peak') DEFAULT 'all' COMMENT 'Mùa áp dụng',

    -- JSON Configuration - Flexible structure for all policy types
    policy_config JSON NOT NULL COMMENT 'Cấu hình chính sách dạng JSON',
    


    
    -- Value Constraints
    min_booking_value DECIMAL(12,2) DEFAULT 0 COMMENT 'Giá trị booking tối thiểu',
    max_booking_value DECIMAL(12,2) DEFAULT NULL COMMENT 'Giá trị booking tối đa',
    min_stay_nights INT DEFAULT 1 COMMENT 'Số đêm tối thiểu',
    max_stay_nights INT DEFAULT NULL COMMENT 'Số đêm tối đa',
    
    -- Temporal Rules
    effective_start_date DATE NOT NULL COMMENT 'Ngày bắt đầu hiệu lực',
    effective_end_date DATE DEFAULT NULL COMMENT 'Ngày kết thúc (NULL = vô thời hạn)',
    advance_booking_days INT DEFAULT 0 COMMENT 'Số ngày đặt trước tối thiểu',
    
    -- Processing Rules
    auto_apply BOOLEAN DEFAULT TRUE COMMENT 'Tự động áp dụng',
    requires_approval BOOLEAN DEFAULT FALSE COMMENT 'Cần phê duyệt thủ công',
    approval_level ENUM('staff', 'supervisor', 'manager', 'director') DEFAULT 'staff',
    priority_order INT DEFAULT 1 COMMENT 'Thứ tự ưu tiên (1 = cao nhất)',
    
    -- Notification
    customer_notification TEXT COMMENT 'Thông báo hiển thị cho khách',
    staff_notification TEXT COMMENT 'Thông báo cho nhân viên',
    internal_notes TEXT COMMENT 'Ghi chú nội bộ',
    
    -- Status & Audit
    is_active BOOLEAN DEFAULT TRUE  COMMENT 'Trạng thái hoạt động của chính sách',
    is_system_policy BOOLEAN DEFAULT FALSE COMMENT 'Chính sách hệ thống (không được xóa)',
    created_by INT DEFAULT NULL COMMENT 'ID người tạo',
    updated_by INT DEFAULT NULL COMMENT 'ID người cập nhật',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',

    -- Indexes
    INDEX idx_category (category_id),
    INDEX idx_policy_type (policy_type),
    INDEX idx_season_type (season_type),
    INDEX idx_effective_dates (effective_start_date, effective_end_date),
    INDEX idx_active_priority (is_active, priority_order),
    INDEX idx_auto_apply (auto_apply, is_active),
    
    -- Foreign Keys
    FOREIGN KEY (category_id) REFERENCES policy_categories(category_id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE policy_room_types (
    policy_id INT NOT NULL COMMENT 'ID chính sách',
    room_type_id INT NOT NULL COMMENT 'ID loại phòng',
    PRIMARY KEY (policy_id, room_type_id),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',

    FOREIGN KEY (policy_id) REFERENCES policies(policy_id) ON DELETE CASCADE,
    FOREIGN KEY (room_type_id) REFERENCES room_types(type_id) ON DELETE CASCADE
);

CREATE TABLE policy_member_levels (
    policy_id INT NOT NULL COMMENT 'ID chính sách',
    member_level_id INT NOT NULL COMMENT 'ID level thành viên',
    PRIMARY KEY (policy_id, member_level_id),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',

    FOREIGN KEY (policy_id) REFERENCES policies(policy_id) ON DELETE CASCADE,
    FOREIGN KEY (member_level_id) REFERENCES member_levels(level_id) ON DELETE CASCADE
);

-- ==== POLICY APPLICATIONS LOG ====
-- Lịch sử áp dụng chính sách
CREATE TABLE policy_applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID duy nhất cho mỗi ứng dụng chính sách', 
    policy_id INT NOT NULL COMMENT 'ID chính sách áp dụng',
    booking_id INT NOT NULL COMMENT 'ID đặt phòng liên quan',
    
    -- Application Context
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP  COMMENT 'Ngày áp dụng chính sách',
    application_trigger ENUM('booking_creation', 'booking_modification', 'cancellation', 'checkout', 'manual') NOT NULL COMMENT 'Ngữ cảnh áp dụng chính sách',
    -- Giải thích rõ ràng về application_trigger:
    -- booking_creation: khi tạo mới booking
    -- booking_modification: khi sửa đổi booking (thay đổi ngày, loại phòng, số khách, v.v.)
    -- cancellation: khi hủy booking
    -- checkout: khi khách trả phòng
    -- manual: khi áp dụng thủ công (ví dụ: nhân viên can thiệp)

    -- Original vs Applied Values
    original_amount DECIMAL(12,2) DEFAULT 0 COMMENT 'Số tiền gốc',
    calculated_amount DECIMAL(12,2) DEFAULT 0 COMMENT 'Số tiền tính theo policy',
    final_amount DECIMAL(12,2) DEFAULT 0 COMMENT 'Số tiền cuối cùng (sau override)',
    
    -- Policy Calculation Details
    calculation_details JSON COMMENT 'Chi tiết tính toán',
    policy_config_snapshot JSON COMMENT 'Snapshot config tại thời điểm áp dụng',
    
    -- Override Information
    is_overridden BOOLEAN DEFAULT FALSE COMMENT 'Có can thiệp thủ công không',
    override_reason TEXT DEFAULT NULL COMMENT 'Lý do can thiệp',
    override_amount DECIMAL(12,2) DEFAULT NULL COMMENT 'Số tiền can thiệp',
    override_percentage DECIMAL(5,2) DEFAULT NULL COMMENT 'Tỷ lệ can thiệp',
    
    -- Processing Info
    processed_by INT DEFAULT NULL COMMENT 'ID nhân viên xử lý',
    approval_status ENUM('auto_approved', 'pending', 'approved', 'rejected') DEFAULT 'auto_approved' COMMENT 'Trạng thái phê duyệt',
    approved_by INT DEFAULT NULL COMMENT 'ID nhân viên phê duyệt',
    approval_date TIMESTAMP NULL COMMENT 'Ngày phê duyệt',

    -- Payment/Refund Status
    payment_status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending' COMMENT 'Trạng thái thanh toán/hoàn tiền',
    payment_reference VARCHAR(100) DEFAULT NULL COMMENT 'Mã giao dịch thanh toán',
    payment_date TIMESTAMP NULL COMMENT 'Ngày thanh toán',
    payment_method ENUM('credit_card', 'bank_transfer', 'cash', 'voucher') DEFAULT NULL COMMENT 'Phương thức thanh toán',
    payment_notes TEXT DEFAULT NULL COMMENT 'Ghi chú thanh toán',

    -- Audit
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes
    INDEX idx_policy_booking (policy_id, booking_id),
    INDEX idx_application_date (application_date),
    INDEX idx_trigger (application_trigger),
    INDEX idx_approval_status (approval_status),
    INDEX idx_payment_status (payment_status),
    
    -- Foreign Keys
    FOREIGN KEY (policy_id) REFERENCES policies(policy_id) ON DELETE RESTRICT,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (processed_by) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- ==== POLICY EXCEPTIONS ====
-- Các trường hợp ngoại lệ và override
CREATE TABLE policy_exceptions (
    exception_id INT AUTO_INCREMENT PRIMARY KEY  COMMENT 'ID duy nhất cho mỗi ngoại lệ',
    policy_id INT DEFAULT NULL COMMENT 'ID chính sách liên quan (NULL nếu không áp dụng cho chính sách cụ thể)',
    booking_id INT NOT NULL COMMENT 'ID đặt phòng liên quan',
    application_id INT DEFAULT NULL COMMENT 'ID đơn đăng ký liên quan',

    -- Exception Details
    exception_type ENUM(
        'emergency', 'vip_guest', 'force_majeure', 'system_error', 
        'staff_error', 'business_decision', 'loyalty_benefit', 'compensation'
    ) NOT NULL  COMMENT 'Loại ngoại lệ',
    -- Giải thích rõ ràng về exception_type:
    -- emergency: trường hợp khẩn cấp (như bệnh viện, tai nạn)
    -- vip_guest: khách VIP có yêu cầu đặc biệt
    -- force_majeure: trường hợp bất khả kháng (thiên tai, dịch bệnh)
    -- system_error: lỗi hệ thống (như double booking, lỗi thanh toán)
    -- staff_error: lỗi do nhân viên (như nhập sai thông tin)
    -- business_decision: quyết định kinh doanh (như ưu đãi đặc biệt)
    -- loyalty_benefit: quyền lợi từ chương trình khách hàng thân thiết
    -- compensation: bồi thường cho khách hàng (như hoàn tiền, giảm giá)
    -- Lưu ý: Các loại ngoại lệ này có thể mở rộng trong tương lai nếu cần thiết
    exception_reason TEXT NOT NULL,
    
    -- Override Values
    override_type ENUM('amount', 'percentage', 'waive_all', 'custom_policy') NOT NULL  COMMENT 'Loại can thiệp: amount, percentage, waive_all, custom_policy',
    -- Giải thích rõ ràng về override_type:
    -- amount: can thiệp bằng một số tiền cụ thể
    -- percentage: can thiệp bằng một tỷ lệ phần trăm
    -- waive_all: miễn toàn bộ phí phạt
    -- custom_policy: áp dụng cấu hình chính sách tùy chỉnh
    override_value DECIMAL(12,2) DEFAULT NULL COMMENT 'Giá trị can thiệp',
    override_percentage DECIMAL(5,2) DEFAULT NULL COMMENT 'Tỷ lệ can thiệp',
    custom_policy_config JSON DEFAULT NULL COMMENT 'Cấu hình chính sách tùy chỉnh',
    
    -- Approval Workflow
    requested_by INT NOT NULL COMMENT 'ID người yêu cầu',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày yêu cầu',
    approval_level_required ENUM('supervisor', 'manager', 'director') NOT NULL COMMENT 'Cấp độ phê duyệt yêu cầu',

    approved_by INT DEFAULT NULL COMMENT 'ID nhân viên phê duyệt',
    approval_date TIMESTAMP NULL COMMENT 'Ngày phê duyệt',
    status ENUM('pending', 'approved', 'rejected', 'expired', 'cancelled') DEFAULT 'pending' COMMENT 'Trạng thái yêu cầu',

    -- Documentation
    supporting_documents JSON COMMENT 'Danh sách tài liệu hỗ trợ',
    approval_notes TEXT DEFAULT NULL COMMENT 'Ghi chú phê duyệt',
    rejection_reason TEXT DEFAULT NULL COMMENT 'Lý do từ chối',

    -- Auto-expiry
    expires_at TIMESTAMP DEFAULT NULL COMMENT 'Ngày hết hạn tự động',

    -- Audit
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
    
    -- Indexes
    INDEX idx_booking_id (booking_id),
    INDEX idx_exception_type (exception_type),
    INDEX idx_status (status),
    INDEX idx_approval_workflow (requested_by, approved_by),
    INDEX idx_expires_at (expires_at),
    
    -- Foreign Keys
    FOREIGN KEY (policy_id) REFERENCES policies(policy_id) ON DELETE SET NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (application_id) REFERENCES policy_applications(application_id) ON DELETE SET NULL,
    FOREIGN KEY (requested_by) REFERENCES users(user_id) ON DELETE RESTRICT,
    FOREIGN KEY (approved_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- ==== POLICY TEMPLATES ====
-- Templates cho các loại policy config
CREATE TABLE policy_templates (
    template_id INT AUTO_INCREMENT PRIMARY KEY,
    template_code VARCHAR(50) NOT NULL UNIQUE,
    template_name VARCHAR(100) NOT NULL,
    policy_type ENUM('deposit', 'cancellation', 'checkout', 'refund', 'room_change', 'cooldown', 'upgrade', 'loyalty') NOT NULL,
    
    -- Template Configuration
    template_config JSON NOT NULL COMMENT 'Cấu hình mẫu',
    description TEXT,
    usage_instructions TEXT,
    
    -- Validation Rules
    validation_rules JSON COMMENT 'Quy tắc validation cho config',
    
    -- Status
    is_system_template BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_policy_type (policy_type),
    INDEX idx_active (is_active)
);

-- ==== VIEWS ====

-- View hiển thị policies đang active
CREATE VIEW active_policies AS
SELECT 
    p.*,
    pc.category_name,
    pc.category_code,
    CASE 
        WHEN p.effective_end_date IS NULL THEN 'Permanent'
        WHEN p.effective_end_date < CURDATE() THEN 'Expired'
        WHEN p.effective_start_date > CURDATE() THEN 'Future'
        ELSE 'Active'
    END as status,
    u1.full_name as created_by_name,
    u2.full_name as updated_by_name
FROM policies p
JOIN policy_categories pc ON p.category_id = pc.category_id
LEFT JOIN users u1 ON p.created_by = u1.user_id
LEFT JOIN users u2 ON p.updated_by = u2.user_id
WHERE p.is_active = TRUE
  AND (p.effective_end_date IS NULL OR p.effective_end_date >= CURDATE())
  AND p.effective_start_date <= CURDATE()
ORDER BY pc.display_order, p.priority_order;

-- View thống kê application
CREATE VIEW policy_application_stats AS
SELECT 
    p.policy_code,
    p.policy_name,
    p.policy_type,
    pc.category_name,
    COUNT(pa.application_id) as total_applications,
    COUNT(CASE WHEN pa.is_overridden = FALSE THEN 1 END) as auto_applications,
    COUNT(CASE WHEN pa.is_overridden = TRUE THEN 1 END) as overridden_applications,
    AVG(pa.final_amount) as avg_amount,
    SUM(pa.final_amount) as total_amount,
    COUNT(CASE WHEN pa.payment_status = 'completed' THEN 1 END) as completed_payments
FROM policies p
JOIN policy_categories pc ON p.category_id = pc.category_id
LEFT JOIN policy_applications pa ON p.policy_id = pa.policy_id
WHERE p.is_active = TRUE
GROUP BY p.policy_id, p.policy_code, p.policy_name, p.policy_type, pc.category_name
ORDER BY total_applications DESC;

-- ==== TRIGGERS ====

DELIMITER //

-- Trigger cập nhật timestamp
CREATE TRIGGER tr_policies_updated_at
    BEFORE UPDATE ON policies
    FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END //

-- Trigger validation policy config
CREATE TRIGGER tr_validate_policy_config
    BEFORE INSERT ON policies
    FOR EACH ROW
BEGIN
    -- Basic validation
    IF NEW.effective_start_date > IFNULL(NEW.effective_end_date, '9999-12-31') THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Effective start date must be <= end date';
    END IF;
    
    IF NEW.min_booking_value < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Min booking value cannot be negative';
    END IF;
    
    IF NEW.max_booking_value IS NOT NULL AND NEW.max_booking_value < NEW.min_booking_value THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Max booking value must be >= min booking value';
    END IF;
END //

DELIMITER ;

-- ==== COMMENTS ====
ALTER TABLE policy_categories COMMENT = 'Phân loại các nhóm chính sách nghiệp vụ';
ALTER TABLE policies COMMENT = 'Bảng chính sách thống nhất với cấu hình JSON linh hoạt';
ALTER TABLE policy_applications COMMENT = 'Lịch sử áp dụng chính sách cho từng booking';
ALTER TABLE policy_exceptions COMMENT = 'Các trường hợp ngoại lệ và override chính sách';
ALTER TABLE policy_templates COMMENT = 'Templates mẫu cho các loại chính sách';
