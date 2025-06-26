-- ===== PRICING SYSTEM SCHEMA =====
-- Hệ thống định giá động và biến động giá phòng



-- Bảng loại mùa giá 
CREATE TABLE season_types (
    season_type_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã loại mùa',
    type_name VARCHAR(50) NOT NULL UNIQUE COMMENT 'Tên loại mùa: low, high, peak, festival , all , medium',
    display_name VARCHAR(100) NOT NULL COMMENT 'Tên hiển thị tiếng Việt',
    base_multiplier DECIMAL(4,2) DEFAULT 1.00 COMMENT 'Hệ số nhân cơ bản',
    min_multiplier DECIMAL(4,2) DEFAULT 1.00 COMMENT 'Hệ số tối thiểu của loại mùa này',
    max_multiplier DECIMAL(4,2) DEFAULT 1.00 COMMENT 'Hệ số tối đa của loại mùa này',
    typical_increase_range VARCHAR(20) COMMENT 'Khoảng tăng điển hình (VD: 5-10%, 20-45%)',
    description TEXT COMMENT 'Mô tả loại mùa',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Bảng phân loại mùa giá với khoảng biến động chuẩn';

-- Bảng mùa giá chi tiết 
CREATE TABLE pricing_seasons (
    season_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã mùa giá',
    season_name VARCHAR(100) NOT NULL COMMENT 'Tên mùa giá cụ thể',
    season_type_id INT NOT NULL COMMENT 'Loại mùa giá',
    start_date DATE NOT NULL COMMENT 'Ngày bắt đầu áp dụng',
    end_date DATE NOT NULL COMMENT 'Ngày kết thúc áp dụng',
    year_applicable YEAR COMMENT 'Năm áp dụng (NULL = hàng năm)',
    price_multiplier DECIMAL(4,2) DEFAULT 1.00 COMMENT 'Hệ số nhân giá so với giá gốc',
    min_nights INT DEFAULT 1 COMMENT 'Số đêm tối thiểu',
    max_nights INT DEFAULT NULL COMMENT 'Số đêm tối đa (NULL = không giới hạn)',
    advance_booking_days INT DEFAULT 0 COMMENT 'Yêu cầu đặt trước bao nhiêu ngày',
    description TEXT COMMENT 'Mô tả mùa giá',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động',
    priority INT DEFAULT 0 COMMENT 'Độ ưu tiên (cao hơn được áp dụng trước)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (season_type_id) REFERENCES season_types(season_type_id)
) COMMENT 'Bảng mùa giá chi tiết với biến động phức tạp';

-- Bảng phụ thu cuối tuần và ngày đặc biệt
CREATE TABLE weekend_surcharge (
    surcharge_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã phụ thu',
    surcharge_name VARCHAR(100) NOT NULL COMMENT 'Tên phụ thu',
    day_of_week_pattern VARCHAR(50) NOT NULL COMMENT 'Pattern ngày: friday,saturday hoặc sunday, có thể nhiều ngày',
    surcharge_type ENUM('percentage', 'fixed_amount') DEFAULT 'percentage' COMMENT 'Loại phụ thu',
    surcharge_value DECIMAL(10,2) NOT NULL COMMENT 'Giá trị phụ thu (% hoặc VND)',
    min_surcharge_percentage DECIMAL(5,2) DEFAULT 10.00 COMMENT 'Phụ thu tối thiểu (%)',
    max_surcharge_percentage DECIMAL(5,2) DEFAULT 15.00 COMMENT 'Phụ thu tối đa (%)',
    applicable_room_types TEXT COMMENT 'Loại phòng áp dụng (JSON array hoặc ALL)',
    start_date DATE COMMENT 'Ngày bắt đầu áp dụng',
    end_date DATE COMMENT 'Ngày kết thúc áp dụng (NULL = vô thời hạn)',
    description TEXT COMMENT 'Mô tả phụ thu',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động',
    applies_consistently BOOLEAN DEFAULT TRUE COMMENT 'Áp dụng nhất quán cho tất cả hạng phòng',
    customer_notification_required BOOLEAN DEFAULT TRUE COMMENT 'Yêu cầu thông báo khách khi đặt',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Bảng phụ thu cuối tuần (10-15%) áp dụng nhất quán';

-- Bảng giá đặc biệt theo sự kiện
CREATE TABLE event_pricing (
    event_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã sự kiện',
    event_name VARCHAR(200) NOT NULL COMMENT 'Tên sự kiện',
    event_type ENUM('festival', 'conference', 'holiday', 'local_event', 'special_occasion') COMMENT 'Loại sự kiện',
    start_date DATE NOT NULL COMMENT 'Ngày bắt đầu sự kiện',
    end_date DATE NOT NULL COMMENT 'Ngày kết thúc sự kiện',
    price_multiplier DECIMAL(4,2) DEFAULT 1.00 COMMENT 'Hệ số nhân giá',
    occupancy_threshold DECIMAL(3,2) DEFAULT 0.80 COMMENT 'Ngưỡng công suất áp dụng (0.80 = 80%)',
    min_nights INT DEFAULT 1 COMMENT 'Số đêm tối thiểu',
    blackout_dates TEXT COMMENT 'Ngày không áp dụng trong khoảng thời gian',
    applicable_room_types TEXT COMMENT 'Loại phòng áp dụng (JSON array)',
    description TEXT COMMENT 'Mô tả sự kiện',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động',
    auto_apply BOOLEAN DEFAULT FALSE COMMENT 'Tự động áp dụng khi đạt điều kiện',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Bảng giá đặc biệt theo sự kiện và công suất';

-- Bảng ưu đãi khách đoàn nhiều người và hợp đồng dài hạn
CREATE TABLE group_contracts (
    contract_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã hợp đồng',
    contract_name VARCHAR(200) NOT NULL COMMENT 'Tên hợp đồng/công ty',
    contract_type ENUM('corporate', 'group_booking', 'travel_agent', 'long_stay', 'government') COMMENT 'Loại hợp đồng',
    company_name VARCHAR(200) COMMENT 'Tên công ty/tổ chức',
    contact_person VARCHAR(100) COMMENT 'Người liên hệ',
    contact_email VARCHAR(100) COMMENT 'Email liên hệ',
    contact_phone VARCHAR(20) COMMENT 'Số điện thoại',
    
    -- Điều kiện ưu đãi
    min_rooms_per_booking INT DEFAULT 1 COMMENT 'Số phòng tối thiểu mỗi lần đặt',
    min_nights_per_booking INT DEFAULT 1 COMMENT 'Số đêm tối thiểu mỗi lần đặt',
    min_bookings_per_year INT DEFAULT 0 COMMENT 'Số lần đặt tối thiểu mỗi năm',
    min_revenue_per_year BIGINT DEFAULT 0 COMMENT 'Doanh thu tối thiểu mỗi năm (VND)',
    
    -- Mức ưu đãi
    discount_type ENUM('percentage', 'fixed_amount', 'tiered') DEFAULT 'percentage' COMMENT 'Loại chiết khấu',
    discount_value DECIMAL(10,2) NOT NULL COMMENT 'Giá trị chiết khấu',
    max_discount_amount BIGINT COMMENT 'Số tiền giảm tối đa (VND)',
    
    -- Thời gian hợp đồng
    start_date DATE NOT NULL COMMENT 'Ngày bắt đầu hợp đồng',
    end_date DATE NOT NULL COMMENT 'Ngày kết thúc hợp đồng',
    
    -- Điều kiện bổ sung
    applicable_room_types TEXT COMMENT 'Loại phòng áp dụng (JSON array)',
    applicable_seasons TEXT COMMENT 'Mùa giá áp dụng (JSON array)',
    blackout_dates TEXT COMMENT 'Ngày không áp dụng ưu đãi',
    special_terms TEXT COMMENT 'Điều khoản đặc biệt',
    description TEXT COMMENT 'Mô tả chi tiết hợp đồng',
    
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động',
    auto_apply BOOLEAN DEFAULT TRUE COMMENT 'Tự động áp dụng khi đủ điều kiện',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Bảng hợp đồng ưu đãi khách đoàn và dài hạn';

-- Bảng chính sách giá lưu trú dài ngày
CREATE TABLE long_stay_pricing (
    long_stay_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã lưu trú dài ngày',
    policy_name VARCHAR(100) NOT NULL COMMENT 'Tên chính sách',
    min_nights INT NOT NULL COMMENT 'Số đêm tối thiểu',
    max_nights INT COMMENT 'Số đêm tối đa (NULL = không giới hạn)',
    discount_type ENUM('percentage', 'fixed_per_night', 'tiered') DEFAULT 'percentage' COMMENT 'Loại giảm giá',
    discount_value DECIMAL(10,2) NOT NULL COMMENT 'Giá trị giảm giá',
    
    -- Điều kiện áp dụng
    applicable_room_types TEXT COMMENT 'Loại phòng áp dụng (JSON array)',
    valid_from DATE COMMENT 'Có hiệu lực từ ngày',
    valid_to DATE COMMENT 'Có hiệu lực đến ngày',
    applicable_seasons TEXT COMMENT 'Mùa giá áp dụng',
    
    -- Dịch vụ bổ sung cho lưu trú dài ngày
    includes_breakfast BOOLEAN DEFAULT FALSE COMMENT 'Bao gồm ăn sáng',
    includes_housekeeping_frequency INT DEFAULT 1 COMMENT 'Tần suất dọn phòng (ngày)',
    includes_laundry BOOLEAN DEFAULT FALSE COMMENT 'Bao gồm giặt ủi',
    includes_airport_transfer BOOLEAN DEFAULT FALSE COMMENT 'Bao gồm đưa đón sân bay',
    
    description TEXT COMMENT 'Mô tả chính sách',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Bảng chính sách giá lưu trú dài ngày';

-- Bảng lịch sử thay đổi giá (để tracking)
CREATE TABLE price_history (
    history_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã lịch sử',
    room_type_id INT NOT NULL COMMENT 'Mã loại phòng',
    date_applicable DATE NOT NULL COMMENT 'Ngày áp dụng giá',
    base_price_single BIGINT COMMENT 'Giá gốc 1 người',
    base_price_double BIGINT COMMENT 'Giá gốc 2 người',
    final_price_single BIGINT COMMENT 'Giá cuối 1 người (sau tất cả adjustment)',
    final_price_double BIGINT COMMENT 'Giá cuối 2 người (sau tất cả adjustment)',
    
    -- Các yếu tố ảnh hưởng giá
    season_multiplier DECIMAL(4,2) DEFAULT 1.00 COMMENT 'Hệ số mùa giá',
    weekend_surcharge BIGINT DEFAULT 0 COMMENT 'Phụ thu cuối tuần',
    event_multiplier DECIMAL(4,2) DEFAULT 1.00 COMMENT 'Hệ số sự kiện',
    occupancy_rate DECIMAL(3,2) COMMENT 'Tỷ lệ lấp đầy tại thời điểm tính giá',
    
    -- Metadata
    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tính giá',
    pricing_rules_applied TEXT COMMENT 'Các rule đã áp dụng (JSON)',
    notes TEXT COMMENT 'Ghi chú về tính giá',
    
    FOREIGN KEY (room_type_id) REFERENCES room_types(type_id),
    INDEX idx_room_date (room_type_id, date_applicable),
    INDEX idx_date_occupancy (date_applicable, occupancy_rate)
) COMMENT 'Bảng lịch sử và tracking giá phòng';

-- Bảng công suất phòng theo ngày (để pricing động)
CREATE TABLE daily_occupancy (
    occupancy_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính',
    room_type_id INT NOT NULL COMMENT 'Mã loại phòng',
    date_applicable DATE NOT NULL COMMENT 'Ngày áp dụng',
    total_rooms INT NOT NULL COMMENT 'Tổng số phòng loại này',
    occupied_rooms INT DEFAULT 0 COMMENT 'Số phòng đã đặt',
    available_rooms INT COMMENT 'Số phòng còn trống',
    occupancy_rate DECIMAL(3,2) COMMENT 'Tỷ lệ lấp đầy (0.00-1.00)',
    
    -- Dự báo (cho pricing proactive)
    forecasted_occupancy DECIMAL(3,2) COMMENT 'Dự báo lấp đầy cho ngày này',
    price_adjustment_factor DECIMAL(4,2) DEFAULT 1.00 COMMENT 'Hệ số điều chỉnh giá dựa vào occupancy',
    
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (room_type_id) REFERENCES room_types(type_id),
    UNIQUE KEY unique_room_date (room_type_id, date_applicable),
    INDEX idx_date_occupancy (date_applicable, occupancy_rate)
) COMMENT 'Bảng theo dõi công suất theo ngày cho dynamic pricing';

