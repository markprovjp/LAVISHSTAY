-- ===== AMENITIES SCHEMA =====
-- Cấu trúc quản lý tiện nghi khách sạn

-- Bảng tiện nghi
CREATE TABLE amenities (
    amenity_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã tiện nghi',
    name VARCHAR(100) NOT NULL COMMENT 'Tên tiện nghi',
    description TEXT COMMENT 'Mô tả chi tiết tiện nghi',
    icon VARCHAR(50) COMMENT 'Icon đại diện cho tiện nghi',
    icon_lib ENUM('lucide', 'antd') DEFAULT 'lucide' COMMENT 'Thư viện icon',   
    category ENUM('basic', 'bathroom', 'entertainment', 'connectivity', 'comfort', 'security', 'kitchen', 'view', 'service'  ) DEFAULT 'basic' COMMENT 'Phân loại tiện nghi',
    -- giải thích các loại tiện nghi:
    -- basic: Tiện nghi cơ bản như điều hòa, TV, minibar
    -- bathroom: Tiện nghi phòng tắm như vòi sen, bồn tắm
    -- entertainment: Tiện nghi giải trí như TV, loa
    -- connectivity: Tiện nghi kết nối như Wi-Fi, cổng sạc
    -- comfort: Tiện nghi thoải mái như ga trải giường, gối
    -- security: Tiện nghi an ninh như két sắt, khóa cửa điện tử
    -- kitchen: Tiện nghi nhà bếp như tủ lạnh, máy pha cà phê
    -- view: Tiện nghi liên quan đến view như cửa sổ lớn, ban công
    -- service: Tiện nghi dịch vụ như dịch vụ phòng, giặt ủ
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối'
) COMMENT 'Bảng quản lý tiện nghi khách sạn';

-- Bảng quan hệ nhiều-nhiều giữa room_types và amenities
CREATE TABLE room_type_amenities (
    room_type_id INT NOT NULL COMMENT 'Mã loại phòng',
    amenity_id INT NOT NULL COMMENT 'Mã tiện nghi',
    is_main_amenity BOOLEAN DEFAULT FALSE COMMENT 'Tiện nghi chính hiển thị nổi bật',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    PRIMARY KEY (room_type_id, amenity_id),
    FOREIGN KEY (room_type_id) REFERENCES room_types(type_id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(amenity_id) ON DELETE CASCADE
) COMMENT 'Bảng quan hệ loại phòng và tiện nghi';
