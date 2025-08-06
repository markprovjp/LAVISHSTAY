-- ===== CORE SCHEMA =====
-- Cấu trúc cơ sở dữ liệu chính cho hệ thống quản lý khách sạn

-- Bảng vai trò người dùng
CREATE TABLE roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã vai trò',
    role_name VARCHAR(50) NOT NULL UNIQUE COMMENT 'Tên vai trò: admin, receptionist, customer',
    description TEXT COMMENT 'Mô tả chi tiết vai trò',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối'
) COMMENT 'Bảng quản lý vai trò người dùng';

-- Bảng người dùng
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã người dùng',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email đăng nhập',
    password_hash VARCHAR(255) NOT NULL COMMENT 'Mật khẩu đã mã hóa',
    full_name VARCHAR(100) NOT NULL COMMENT 'Họ tên đầy đủ',
    phone VARCHAR(20) COMMENT 'Số điện thoại',
    address TEXT COMMENT 'Địa chỉ',
    id_number VARCHAR(50) COMMENT 'Số CCCD/Hộ chiếu',
    role_id INT COMMENT 'Mã vai trò',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo tài khoản',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động của tài khoản',
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
) COMMENT 'Bảng quản lý thông tin người dùng';

-- Bảng tầng khách sạn
CREATE TABLE floors (
    floor_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã tầng',
    floor_number INT NOT NULL UNIQUE COMMENT 'Số tầng (1-34)',
    floor_name VARCHAR(100) COMMENT 'Tên tầng (Tầng trệt, Tầng 1, etc.)',
    floor_type ENUM('ground', 'residential', 'service', 'special', 'penthouse') DEFAULT 'residential' COMMENT 'Loại tầng',
    description TEXT COMMENT 'Mô tả tầng và tiện ích đặc biệt',
    facilities TEXT COMMENT 'Các tiện ích có trên tầng này',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Tầng có hoạt động không',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối'
) COMMENT 'Bảng quản lý các tầng của khách sạn';

-- Bảng loại phòng 
CREATE TABLE room_types (
    type_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã loại phòng',
    type_name VARCHAR(100) NOT NULL COMMENT 'Tên loại phòng',
    area_sqm INT NOT NULL COMMENT 'Diện tích phòng (m²)',
    standard_occupancy INT NOT NULL DEFAULT 2 COMMENT 'Sức chứa tiêu chuẩn (người) - không phụ thu',
    max_occupancy INT NOT NULL COMMENT 'Sức chứa tối đa (người) - có thể phụ thu',
    view_type VARCHAR(50) COMMENT 'Loại view: city, corner, panorama',
    description TEXT COMMENT 'Mô tả đặc điểm phòng',
    base_price_single BIGINT COMMENT 'Giá cơ bản cho 1 người (VND)',
    base_price_double BIGINT COMMENT 'Giá cơ bản cho 2 người (VND)',
    surcharge_adult BIGINT DEFAULT 0 COMMENT 'Phụ thu người lớn thêm (VND/người/đêm)',
    surcharge_child_6_12 BIGINT DEFAULT 0 COMMENT 'Phụ thu trẻ em 6-12 tuổi (VND/người/đêm)',
    surcharge_child_under_6 BIGINT DEFAULT 0 COMMENT 'Phụ thu trẻ em dưới 6 tuổi (VND/người/đêm)',
    surcharge_extra_bed BIGINT DEFAULT 0 COMMENT 'Phụ thu giường phụ (VND/giường/đêm)',
    total_rooms_planned INT DEFAULT 0 COMMENT 'Tổng số phòng theo kế hoạch',
    
    -- ĐÁNH GIÁ TỔNG HỢP TỪ KHÁCH HÀNG
    average_rating DECIMAL(3,2) DEFAULT 0.00 COMMENT 'Điểm đánh giá trung bình (1.00-5.00)',
    total_reviews INT DEFAULT 0 COMMENT 'Tổng số lượt đánh giá',
    rating_updated_at TIMESTAMP NULL COMMENT 'Lần cập nhật rating cuối cùng',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối'
) COMMENT 'Bảng quản lý các loại phòng';

-- Bảng phòng 
CREATE TABLE rooms (
    room_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã phòng',
    room_number VARCHAR(10) NOT NULL UNIQUE COMMENT 'Số phòng',
    floor_id INT NOT NULL COMMENT 'Mã tầng',
    room_type_id INT NOT NULL COMMENT 'Mã loại phòng',
    bed_type_fixed ENUM('King', 'Twin') NOT NULL COMMENT 'Loại giường cố định cho phòng này',
    status ENUM('available', 'occupied', 'maintenance', 'out_of_order') DEFAULT 'available' COMMENT 'Trạng thái phòng', -- giải thích từng trạng thái
    -- available: Phòng còn trống, có thể đặt
    -- occupied: Phòng đang được khách thuê
    -- maintenance: Phòng đang bảo trì, không thể đặt
    -- out_of_order: Phòng không sử dụng được
    last_cleaned TIMESTAMP COMMENT 'Lần dọn phòng cuối cùng',
    notes TEXT COMMENT 'Ghi chú về phòng',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    FOREIGN KEY (floor_id) REFERENCES floors(floor_id),
    FOREIGN KEY (room_type_id) REFERENCES room_types(type_id)
) COMMENT 'Bảng quản lý phòng - đã chuẩn hóa, chứa đủ thông tin tầng/loại phòng/giường';

