-- ===== BOOKING & SERVICES SCHEMA =====
-- Cấu trúc quản lý đặt phòng và dịch vụ

-- Bảng gói ăn uống
CREATE TABLE meal_plans (
    meal_plan_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã gói ăn',
    plan_name VARCHAR(50) NOT NULL COMMENT 'Tên gói ăn',
    description TEXT COMMENT 'Mô tả gói ăn',
    breakfast_included BOOLEAN DEFAULT FALSE COMMENT 'Bao gồm ăn sáng',
    dinner_included BOOLEAN DEFAULT FALSE COMMENT 'Bao gồm ăn tối',
    breakfast_price BIGINT DEFAULT 0 COMMENT 'Giá ăn sáng (VND)',
    dinner_price BIGINT DEFAULT 0 COMMENT 'Giá ăn tối (VND)',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối'
) COMMENT 'Bảng quản lý gói ăn uống';

-- Bảng đặt phòng (tích hợp thông tin khách hàng)
CREATE TABLE bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã đặt phòng',
    user_id INT NULL COMMENT 'Mã người dùng (nếu có tài khoản đăng ký)',
    
    -- Thông tin khách hàng (bắt buộc cho cả user và guest)
    guest_full_name VARCHAR(100) NOT NULL COMMENT 'Họ tên đầy đủ khách hàng',
    guest_email VARCHAR(100) NOT NULL COMMENT 'Email liên hệ',
    guest_phone VARCHAR(20) NOT NULL COMMENT 'Số điện thoại',
    guest_id_number VARCHAR(50) NOT NULL COMMENT 'Số CCCD/Hộ chiếu',
    guest_nationality VARCHAR(50) DEFAULT 'Vietnamese' COMMENT 'Quốc tịch',
    guest_address TEXT COMMENT 'Địa chỉ',
    
    -- Thông tin đặt phòng
    room_id INT NOT NULL COMMENT 'Mã phòng được đặt',
    check_in_date DATE NOT NULL COMMENT 'Ngày nhận phòng',
    check_out_date DATE NOT NULL COMMENT 'Ngày trả phòng',
    total_nights INT NOT NULL COMMENT 'Tổng số đêm lưu trú',
    adults_count INT DEFAULT 1 COMMENT 'Số người lớn',
    children_count INT DEFAULT 0 COMMENT 'Số trẻ em',
    
    -- Giá cả và phí
    room_price_per_night BIGINT NOT NULL COMMENT 'Giá phòng mỗi đêm (VND)',
    meal_plan_id INT DEFAULT 1 COMMENT 'Mã gói ăn uống',
    meal_plan_total_fee BIGINT DEFAULT 0 COMMENT 'Tổng phí gói ăn uống (VND)',
    extra_bed_fee BIGINT DEFAULT 0 COMMENT 'Phí giường phụ (VND)',
    extra_guest_fee BIGINT DEFAULT 0 COMMENT 'Phí khách thêm (VND)',
    
    -- Tổng tiền
    room_subtotal BIGINT NOT NULL COMMENT 'Tiền phòng (room_price_per_night * total_nights)',
    services_subtotal BIGINT DEFAULT 0 COMMENT 'Tổng tiền dịch vụ thêm',
    gross_total BIGINT NOT NULL COMMENT 'Tổng tiền trước thuế',
    tax_amount BIGINT DEFAULT 0 COMMENT 'Tiền thuế VAT (VND)',
    final_total BIGINT NOT NULL COMMENT 'Tổng tiền cuối cùng (bao gồm thuế)',
    
    -- Thanh toán
    deposit_amount BIGINT DEFAULT 0 COMMENT 'Tiền cọc đã thu (VND)',
    deposit_percentage DECIMAL(5,2) DEFAULT 30.00 COMMENT 'Tỷ lệ đặt cọc được áp dụng (%)',
    required_deposit_amount BIGINT DEFAULT 0 COMMENT 'Số tiền cọc yêu cầu (VND)',
    deposit_deadline TIMESTAMP NULL COMMENT 'Hạn chót đặt cọc',
    payment_method ENUM('cash', 'bank_transfer', 'credit_card', 'vietqr', 'vnpay', 'momo' , 'zalopay') COMMENT 'Phương thức thanh toán',
    booking_status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'no_show', 'overbooked') DEFAULT 'pending' COMMENT 'Trạng thái đặt phòng',
    
    -- XỬ LÝ CÁC TRƯỜNG HỢP ĐẶC BIỆT
    -- No-show handling
    no_show_deadline TIMESTAMP NULL COMMENT 'Thời hạn giữ phòng (sau đó sẽ xử lý no-show)',
    no_show_processed BOOLEAN DEFAULT FALSE COMMENT 'Đã xử lý no-show chưa',
    no_show_fee BIGINT DEFAULT 0 COMMENT 'Phí no-show (VND)',
    
    -- Overbooking handling
    is_overbooked BOOLEAN DEFAULT FALSE COMMENT 'Đặt phòng bị overbooking',
    alternative_room_offered VARCHAR(200) COMMENT 'Phòng thay thế được đề xuất',
    compensation_amount BIGINT DEFAULT 0 COMMENT 'Tiền bồi thường overbooking (VND)',
    upgrade_offered VARCHAR(200) COMMENT 'Phòng upgrade được đề xuất miễn phí',
    
    -- Priority handling (Diamond customers, loyalty members)
    level_id INT NOT NULL COMMENT 'Tham chiếu đến cấp độ trong bảng member_levels',

    booking_source ENUM('direct', 'website', 'phone', 'email', 'walk_in') DEFAULT 'direct' COMMENT 'Nguồn đặt phòng',
    -- giải thích các nguồn đặt phòng:
    -- direct: Đặt trực tiếp tại khách sạn
    -- website: Đặt qua website chính thức của khách sạn
    -- phone: Đặt qua điện thoại
    -- email: Đặt qua email
    -- walk_in: Khách đến trực tiếp và đặt phòng
    -- Yêu cầu đặc biệt
    special_requests TEXT COMMENT 'Yêu cầu đặc biệt từ khách',
    internal_notes TEXT COMMENT 'Ghi chú nội bộ của nhân viên',
    
    -- Voucher và khuyến mãi
    voucher_code VARCHAR(20) COMMENT 'Mã voucher được sử dụng',
    voucher_discount_amount BIGINT DEFAULT 0 COMMENT 'Số tiền giảm giá từ voucher (VND)',
    
    -- Hóa đơn VAT
    requires_vat_invoice BOOLEAN DEFAULT FALSE COMMENT 'Yêu cầu xuất hóa đơn VAT',
    vat_company_name VARCHAR(200) COMMENT 'Tên công ty xuất hóa đơn VAT',
    vat_company_address TEXT COMMENT 'Địa chỉ công ty',
    vat_tax_code VARCHAR(50) COMMENT 'Mã số thuế công ty',
    vat_invoice_issued BOOLEAN DEFAULT FALSE COMMENT 'Đã xuất hóa đơn VAT',
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo đặt phòng',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    
    -- Business policies tracking
    early_checkout_date DATE NULL COMMENT 'Ngày checkout sớm (nếu có)',
    early_checkout_fee BIGINT DEFAULT 0 COMMENT 'Phí checkout sớm (VND)',
    early_checkout_refund BIGINT DEFAULT 0 COMMENT 'Số tiền hoàn lại khi checkout sớm (VND)',
    late_checkout_time TIME NULL COMMENT 'Thời gian checkout muộn (nếu có)',
    late_checkout_fee BIGINT DEFAULT 0 COMMENT 'Phí checkout muộn (VND)',
    
    -- Room change tracking
    room_changes_count INT DEFAULT 0 COMMENT 'Số lần đổi phòng',
    last_room_change_date TIMESTAMP NULL COMMENT 'Lần đổi phòng cuối cùng',
    room_change_fee_total BIGINT DEFAULT 0 COMMENT 'Tổng phí đổi phòng (VND)',
    
    -- Policy applications
    applied_deposit_policy VARCHAR(100) COMMENT 'Chính sách đặt cọc được áp dụng',
    applied_pricing_policies TEXT COMMENT 'Các chính sách giá được áp dụng (JSON)',
    policy_exemptions TEXT COMMENT 'Các miễn trừ chính sách (JSON)',
    
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (room_id) REFERENCES rooms(room_id),
    FOREIGN KEY (meal_plan_id) REFERENCES meal_plans(meal_plan_id),
      FOREIGN KEY (level_id) REFERENCES member_levels(level_id)
) COMMENT 'Bảng quản lý đặt phòng (tích hợp thông tin khách hàng)';

-- Bảng dịch vụ
CREATE TABLE services (
    service_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã dịch vụ',
    service_name VARCHAR(100) NOT NULL COMMENT 'Tên dịch vụ',
    category ENUM('spa', 'restaurant', 'room_service', 'laundry', 'minibar', 'fitness', 'pool', 'other') COMMENT 'Loại dịch vụ',
    -- giải thích các loại dịch vụ:
    -- spa: Dịch vụ spa và làm đẹp
    -- restaurant: Dịch vụ nhà hàng
    -- room_service: Dịch vụ phục vụ phòng
    -- laundry: Dịch vụ giặt ủi
    -- minibar: Dịch vụ minibar trong phòng
    -- fitness: Dịch vụ thể dục thể thao (gym, yoga)
    -- pool: Dịch vụ hồ bơi
    -- other: Các dịch vụ khác không thuộc các loại trên
    price BIGINT NOT NULL COMMENT 'Giá dịch vụ (VND)',
    unit VARCHAR(20) DEFAULT 'lần' COMMENT 'Đơn vị tính',
    description TEXT COMMENT 'Mô tả chi tiết dịch vụ',
    operating_hours VARCHAR(50) COMMENT 'Giờ hoạt động',
    advance_booking_required BOOLEAN DEFAULT FALSE COMMENT 'Yêu cầu đặt trước',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối'
) COMMENT 'Bảng quản lý dịch vụ khách sạn';

-- Bảng sử dụng dịch vụ
CREATE TABLE service_usage (
    usage_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã sử dụng dịch vụ',
    booking_id INT NOT NULL COMMENT 'Mã đặt phòng',
    service_id INT NOT NULL COMMENT 'Mã dịch vụ sử dụng',
    quantity INT DEFAULT 1 COMMENT 'Số lượng sử dụng',
    unit_price BIGINT NOT NULL COMMENT 'Giá đơn vị (VND)',
    total_price BIGINT NOT NULL COMMENT 'Tổng tiền (VND)',
    usage_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian sử dụng',
    staff_served INT COMMENT 'Nhân viên phục vụ',
    notes TEXT COMMENT 'Ghi chú sử dụng dịch vụ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
    FOREIGN KEY (service_id) REFERENCES services(service_id),
    FOREIGN KEY (staff_served) REFERENCES users(user_id)
) COMMENT 'Bảng quản lý việc sử dụng dịch vụ';
