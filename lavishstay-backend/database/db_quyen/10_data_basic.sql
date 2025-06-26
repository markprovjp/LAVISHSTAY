-- ===== BASIC DATA =====
-- Dữ liệu cơ bản cho hệ thống

-- Thêm vai trò
INSERT INTO roles (role_name, description) VALUES 
('admin', 'Quản trị viên - Toàn quyền hệ thống'),
('receptionist', 'Nhân viên lễ tân - Quản lý đặt phòng, check-in/out'),
('customer', 'Khách hàng - Đặt phòng và sử dụng dịch vụ');

-- Thêm người dùng mẫu
INSERT INTO users (email, password_hash, full_name, phone, role_id) VALUES 
('admin@hotel.com', '$2y$10$hashpassword1', 'Nguyễn Văn Admin', '0123456789', 1),
('letan@hotel.com', '$2y$10$hashpassword2', 'Trần Thị Lễ Tân', '0987654321', 2),
('customer@gmail.com', '$2y$10$hashpassword3', 'Lê Văn Khách', '0345678901', 3);

-- Thêm thông tin tầng (34 tầng)
INSERT INTO floors (floor_number, floor_name, floor_type, description, facilities) VALUES
(1, 'Tầng Trệt', 'ground', 'Tầng tiếp đón khách và hội nghị', 'Lobby, Lobby Bar, Reception, Ballroom (900 khách), 3 phòng họp (50 khách mỗi phòng)'),
(2, 'Tầng 2', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)'),
(3, 'Tầng 3', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)'),
(4, 'Tầng 4', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)'),
(5, 'Tầng 5', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)'),
(6, 'Tầng 6', 'service', 'Tầng nhà hàng', 'Orchid Restaurant - Buffet Á-Âu (260 khách)'),
(7, 'Tầng 7', 'service', 'Tầng tiện ích thể thao', 'Hồ bơi trong nhà (6:00-20:00), Spa YHI, Phòng gym (6:00-22:00)'),
(8, 'Tầng 8', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)'),
(9, 'Tầng 9', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)'),
(10, 'Tầng 10', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)'),
(11, 'Tầng 11', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)'),
(12, 'Tầng 12', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)'),
(13, 'Tầng 13', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)'),
(14, 'Tầng 14', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)'),
(15, 'Tầng 15', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)'),
(16, 'Tầng 16', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)'),
(17, 'Tầng 17', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)'),
(18, 'Tầng 18', 'residential', 'Tầng The Level Premium', 'The Level Premium (12 phòng)'),
(19, 'Tầng 19', 'residential', 'Tầng The Level Premium', 'The Level Premium (12 phòng)'),
(20, 'Tầng 20', 'residential', 'Tầng The Level Premium', 'The Level Premium (12 phòng)'),
(21, 'Tầng 21', 'residential', 'Tầng The Level Premium Corner', 'The Level Premium Corner (8 phòng)'),
(22, 'Tầng 22', 'residential', 'Tầng The Level Premium Corner', 'The Level Premium Corner (8 phòng)'),
(23, 'Tầng 23', 'residential', 'Tầng The Level Premium Corner', 'The Level Premium Corner (8 phòng)'),
(24, 'Tầng 24', 'residential', 'Tầng The Level Premium Corner', 'The Level Premium Corner (8 phòng)'),
(25, 'Tầng 25', 'residential', 'Tầng The Level Suite', 'The Level Suite (7 phòng)'),
(26, 'Tầng 26', 'residential', 'Tầng The Level Suite', 'The Level Suite (7 phòng)'),
(27, 'Tầng 27', 'residential', 'Tầng The Level Suite', 'The Level Suite (6 phòng)'),
(28, 'Tầng 28', 'residential', 'Tầng Suite', 'Suite (5 phòng)'),
(29, 'Tầng 29', 'residential', 'Tầng Suite', 'Suite (5 phòng)'),
(30, 'Tầng 30', 'residential', 'Tầng Suite', 'Suite (5 phòng)'),
(31, 'Tầng 31', 'residential', 'Tầng Suite', 'Suite (5 phòng)'),
(32, 'Tầng 32', 'penthouse', 'Tầng Presidential Suite', 'Presidential Suite (1 phòng)'),
(33, 'Tầng 33', 'special', 'Tầng Panoramic Lounge', 'Panoramic Lounge VIP (36 khách) - Chỉ dành cho khách The Level'),
(34, 'Tầng 34', 'special', 'Tầng cao nhất', 'Lotus Restaurant (A la carte), SkyView Bar (360° view), Sảnh sự kiện ngoài trời (300 khách)');

-- Thêm loại phòng với đầy đủ thông tin sức chứa và phụ thu
INSERT INTO room_types (
    type_name, area_sqm, standard_occupancy, max_occupancy, view_type, description, 
    base_price_single, base_price_double, 
    surcharge_adult, surcharge_child_6_12, surcharge_child_under_6, surcharge_extra_bed,
    total_rooms_planned
) VALUES 
-- Deluxe Room
('Deluxe Room', 32, 2, 4, 'city', 
 'Phòng giường đôi rộng rãi với đầy đủ tiện nghi cao cấp, tầm nhìn ra thành phố. Sức chứa: 1-2 người lớn + tối đa 2 trẻ em (tổng cộng 4 người). Giường: 1 King hoặc 2 Twin.', 
 1130000, 1480000, 670000, 335000, 0, 1080000, 90),

-- Premium Corner
('Premium Corner', 42, 2, 4, 'corner', 
 'Phòng góc rộng rãi với view panorama. Sức chứa: 1-2 người lớn + tối đa 2 trẻ em (tổng cộng 4 người). Giường: 1 King hoặc 2 Twin.', 
 1390000, 1820000, 670000, 335000, 0, 1080000, 96),

-- The Level Premium
('The Level Premium', 32, 2, 4, 'city', 
 'Quyền truy cập The Level Lounge tầng 33. Sức chứa: 1-2 người lớn + tối đa 2 trẻ em (tổng cộng 4 người). Giường: 1 King hoặc 2 Twin.', 
 1920000, 2500000, 670000, 335000, 0, 1080000, 36),

-- The Level Premium Corner
('The Level Premium Corner', 42, 2, 4, 'corner', 
 'Quyền truy cập The Level Lounge tầng 33. Sức chứa: 1-2 người lớn + tối đa 2 trẻ em (tổng cộng 4 người). Giường: 1 King hoặc 2 Twin.', 
 2150000, 2800000, 670000, 335000, 0, 1080000, 32),

-- The Level Suite
('The Level Suite', 93, 2, 4, 'city', 
 'Suite rộng 93m² với quyền truy cập The Level Lounge tầng 33. Sức chứa: 1-2 người lớn + tối đa 2 trẻ em (tổng cộng 4 người). Giường: 1 King.', 
 2970000, 3870000, 670000, 335000, 0, 1080000, 20),

-- Suite
('Suite', 93, 2, 4, 'city', 
 'Suite rộng 93m² với đầy đủ tiện nghi cao cấp. Sức chứa: 1-2 người lớn + tối đa 2 trẻ em (tổng cộng 4 người). Giường: 1 King.', 
 2150000, 2800000, 670000, 335000, 0, 1080000, 20),

-- Presidential Suite
('Presidential Suite', 270, 4, 10, 'panorama', 
 'Suite Tổng thống rộng 270m² với view panorama. Sức chứa: 4 người lớn + tối đa 6 trẻ em (tổng cộng 10 người). Giường: 2 phòng ngủ, mỗi phòng 1 King.', 
 38990000, 48990000, 670000, 335000, 0, 1080000, 1);



-- Thêm gói ăn uống
INSERT INTO meal_plans (plan_name, description, breakfast_included, dinner_included, breakfast_price, dinner_price) VALUES 
('Không ăn', 'Chỉ ở phòng, không bao gồm bữa ăn', FALSE, FALSE, 0, 0),
('Ăn sáng', 'Bao gồm buffet sáng tại nhà hàng Orchid', TRUE, FALSE, 260000, 0),
('Ăn sáng + Tối', 'Bao gồm buffet sáng và tối tại nhà hàng', TRUE, TRUE, 260000, 450000);

-- ===== DỊCH VỤ MẪU =====
-- Thêm các dịch vụ mẫu để test logic nghiệp vụ
INSERT INTO services (service_name, category, price, unit, description, operating_hours, advance_booking_required) VALUES 
-- Dịch vụ Spa
('Massage thư giãn 60 phút', 'spa', 800000, 'lần', 'Massage toàn thân với tinh dầu thiên nhiên', '09:00-21:00', TRUE),
('Chăm sóc da mặt cơ bản', 'spa', 600000, 'lần', 'Làm sạch và dưỡng ẩm da mặt', '09:00-21:00', TRUE),

-- Dịch vụ nhà hàng
('Room Service 24h', 'room_service', 50000, 'lần', 'Phí giao đồ ăn lên phòng', '24/7', FALSE),
('Buffet sáng bổ sung', 'restaurant', 260000, 'người', 'Buffet sáng cho khách không có gói ăn', '06:00-10:00', FALSE),

-- Dịch vụ giặt ủi
('Giặt ủi thường', 'laundry', 30000, 'kg', 'Giặt ủi quần áo thường ngày', '08:00-20:00', FALSE),
('Giặt ủi express', 'laundry', 50000, 'kg', 'Giặt ủi trong 4 tiếng', '08:00-20:00', FALSE),

-- Dịch vụ thể thao
('Thuê xe đạp', 'fitness', 100000, 'ngày', 'Thuê xe đạp tham quan thành phố', '06:00-18:00', TRUE),
('Huấn luyện viên cá nhân', 'fitness', 500000, 'giờ', 'PT riêng tại phòng gym', '06:00-22:00', TRUE),

-- Dịch vụ khác
('Đưa đón sân bay', 'other', 300000, 'lượt', 'Dịch vụ đưa đón sân bay bằng xe riêng', '24/7', TRUE),
('Thuê xe máy', 'other', 150000, 'ngày', 'Thuê xe máy tự lái', '08:00-18:00', TRUE),
('Tour thành phố nửa ngày', 'other', 800000, 'người', 'Tour tham quan các điểm nổi tiếng', '08:00-17:00', TRUE),

-- Minibar và phí thay thế
('Phí thay thẻ từ', 'other', 50000, 'lần', 'Phí cấp lại thẻ từ do mất hoặc hỏng', '24/7', FALSE),
('Phí thay chìa khóa', 'other', 100000, 'lần', 'Phí cấp lại chìa khóa vật lý', '24/7', FALSE);
