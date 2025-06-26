-- =====  AMENITIES DATA =====
-- Dữ liệu tiện nghi cập nhật theo mô tả chi tiết từng hạng phòng


-- Thêm đầy đủ tiện nghi theo mô tả chi tiết-- Giả định: cột `icon_lib` để ghi thư viện sử dụng: 'lucide' hoặc 'antd'
INSERT INTO amenities (name, description, icon, icon_lib, category, is_active) VALUES 

-- === BASIC AMENITIES ===
('Điều hòa không khí', 'Hệ thống điều hòa nhiệt độ hiện đại', 'Snowflake', 'lucide', 'basic', TRUE),
('TV truyền hình cáp', 'Smart TV màn hình phẳng với truyền hình cáp', 'Tv2', 'lucide', 'entertainment', TRUE),
('Minibar với nước miễn phí', 'Minibar với 2 chai nước suối, trà, cà phê miễn phí', 'CupSoda', 'lucide', 'basic', TRUE),
('WiFi miễn phí', 'Kết nối wifi tốc độ cao 24/7', 'Wifi', 'lucide', 'connectivity', TRUE),
('Két sắt', 'Két sắt điện tử bảo mật cá nhân', 'Lock', 'lucide', 'security', TRUE),

-- === BATHROOM AMENITIES ===
('Vòi sen riêng biệt', 'Vòi sen áp lực cao riêng biệt với vòi sen', 'ShowerHead', 'lucide', 'bathroom', TRUE),
('Bồn tắm riêng biệt', 'Bồn tắm ngâm người lớn riêng biệt với vòi sen', 'Bath', 'lucide', 'bathroom', TRUE),
('Phòng tắm rộng rãi', 'Phòng tắm hiện đại với diện tích rộng rãi', 'Bath', 'lucide', 'bathroom', TRUE),
('Đồ vệ sinh cao cấp', 'Bộ amenities cao cấp đầy đủ', 'Soap', 'antd', 'bathroom', TRUE),
('Máy sấy tóc', 'Máy sấy tóc công suất cao', 'Wind', 'lucide', 'bathroom', TRUE),

-- === VIEW & DESIGN AMENITIES ===
('Cửa kính kịch trần', 'Cửa kính lớn từ trần tới sàn tạo view panoramic', 'Window', 'lucide', 'view', TRUE),
('View thành phố', 'Tầm nhìn toàn cảnh ra thành phố', 'Building2', 'lucide', 'view', TRUE),
('Tường kính bao quanh', 'Thiết kế tường kính tạo tầm nhìn 360 độ', 'City', 'lucide', 'view', TRUE),
('Vị trí góc yên tĩnh', 'Phòng ở vị trí góc tòa nhà, không bị làm phiền', 'EyeOff', 'lucide', 'comfort', TRUE),

-- === DESIGN & COMFORT ===
('Thiết kế hiện đại nâu-trắng', 'Nội thất hiện đại với gam màu nâu ấm và trắng', 'Palette', 'lucide', 'comfort', TRUE),
('Sàn gạch nâu với thảm xám vàng', 'Sàn nhà lát gạch nâu nhạt với thảm xám đậm có hoa văn vàng', 'Image', 'antd', 'comfort', TRUE),
('Nội thất sang trọng', 'Đồ nội thất cao cấp, thiết kế tinh tế', 'Sofa', 'lucide', 'comfort', TRUE),
('Tường ốp gỗ', 'Tường ốp gỗ cao cấp tạo không gian ấm cúng', 'Log', 'lucide', 'comfort', TRUE),
('Trang trí vải nỉ xám đậm', 'Tấm vải nỉ mềm mại màu xám đậm trang trí tường', 'Fabric', 'antd', 'comfort', TRUE),
('Tông màu trắng nâu trầm', 'Phối màu chủ đạo trắng và nâu trầm tạo không gian nhẹ nhàng', 'Palette', 'lucide', 'comfort', TRUE),

-- === SUITE SPECIFIC AMENITIES ===
('Phòng khách riêng biệt', 'Khu vực phòng khách tách biệt với giường ngủ', 'Home', 'antd', 'comfort', TRUE),
('Ghế sofa cao cấp', 'Bộ sofa thoải mái trong phòng khách', 'Sofa', 'lucide', 'comfort', TRUE),
('Khu vực làm việc', 'Bàn làm việc gỗ cao cấp với ghế nệm da', 'Briefcase', 'lucide', 'comfort', TRUE),
('Bàn gỗ với ghế nệm da', 'Bàn làm việc bằng gỗ đặc với ghế bọc da cao cấp', 'Chair', 'lucide', 'comfort', TRUE),
('Thiết kế mở', 'Không gian mở rộng rãi, thoáng đãng', 'Unlock', 'lucide', 'comfort', TRUE),

-- === EXCLUSIVE SERVICES ===
('Quyền truy cập Executive Lounge', 'Lounge VIP tầng 33 dành riêng cho khách The Level', 'Crown', 'antd', 'service', TRUE),
('Trà cà phê miễn phí Lounge', 'Thưởng thức trà, cà phê, bánh quy miễn phí (09:30–22:00)', 'CoffeeOutlined', 'antd', 'service', TRUE),
('Happy hour đồ uống có cồn', 'Happy hour với đồ uống có cồn (17:30–19:00)', 'Wine', 'lucide', 'service', TRUE),
('Check-in/out riêng tư VIP', 'Nhận/trả phòng riêng tư tại sảnh VIP', 'Bell', 'antd', 'service', TRUE),
('3h sử dụng phòng họp miễn phí', '3 giờ sử dụng phòng họp miễn phí', 'Calendar', 'antd', 'service', TRUE),
('Bộ sách tô màu chánh niệm', 'Bộ sách tô màu thư giãn tinh thần', 'Book', 'lucide', 'service', TRUE),
('Dịch vụ trà cà phê cao cấp', 'Dịch vụ trà và cà phê cao cấp phục vụ tại phòng', 'Coffee', 'lucide', 'service', TRUE),
('Dịch vụ phòng ưu tiên', 'Room service được ưu tiên xử lý nhanh', 'Star', 'antd', 'service', TRUE),
('Không gian riêng tư cao cấp', 'Môi trường riêng tư và thanh bình hơn', 'Lock', 'antd', 'service', TRUE),

-- === PREMIUM AMENITIES ===
('Ga trải giường cao cấp', 'Ga cotton Ai Cập thread count cao', 'BedDouble', 'lucide', 'comfort', TRUE),
('Gối memory foam', 'Gối êm ái hỗ trợ giấc ngủ tốt', 'Pillow', 'lucide', 'comfort', TRUE),
('Rèm cửa blackout', 'Rèm che ánh sáng hoàn toàn', 'Moon', 'antd', 'comfort', TRUE),
('Áo choàng tắm cotton', 'Áo choàng tắm cotton 100% cao cấp', 'Bathrobe', 'antd', 'comfort', TRUE),
('Dép đi trong phòng', 'Dép đi trong phòng bằng vải cao cấp', 'Slippers', 'lucide', 'comfort', TRUE),
('Cổng sạc USB đa năng', 'Ổ cắm USB đa năng cho các thiết bị điện tử', 'Usb', 'lucide', 'connectivity', TRUE),
('Điện thoại bàn quốc tế', 'Điện thoại bàn quốc tế', 'Phone', 'lucide', 'connectivity', TRUE),
('Hệ thống âm thanh Bluetooth', 'Hệ thống âm thanh Bluetooth', 'Speaker', 'lucide', 'entertainment', TRUE),
('Dịch vụ phòng 24/7', 'Dịch vụ phòng 24/7', 'RoomService', 'antd', 'service', TRUE),
('Dọn phòng 2 lần/ngày', 'Dọn phòng 2 lần/ngày', 'Broom', 'lucide', 'service', TRUE),
('Butler cá nhân', 'Butler cá nhân', 'User', 'antd', 'service', TRUE),
('Champagne chào mừng', 'Champagne chào mừng', 'Wine', 'lucide', 'service', TRUE);

-- ===== ROOM TYPE AMENITIES MAPPING =====

-- === DELUXE ROOM (type_id = 1) ===
INSERT INTO room_type_amenities (room_type_id, amenity_id, is_main_amenity) VALUES 
-- Tiện nghi chính Deluxe Room
(1, 1, TRUE),   -- Điều hòa không khí
(1, 2, TRUE),   -- TV truyền hình cáp  
(1, 3, TRUE),   -- Minibar với nước miễn phí
(1, 4, TRUE),   -- WiFi miễn phí
(1, 8, TRUE),   -- Phòng tắm rộng rãi
(1, 11, TRUE),  -- Cửa kính kịch trần
(1, 12, TRUE),  -- View thành phố
(1, 15, TRUE),  -- Thiết kế hiện đại nâu-trắng

-- Tiện nghi phụ Deluxe Room
(1, 5, FALSE),  -- Két sắt
(1, 6, FALSE),  -- Vòi sen riêng biệt
(1, 7, FALSE),  -- Bồn tắm riêng biệt
(1, 9, FALSE),  -- Đồ vệ sinh cao cấp
(1, 10, FALSE), -- Máy sấy tóc
(1, 35, FALSE), -- Ga trải giường cao cấp
(1, 36, FALSE), -- Gối memory foam
(1, 37, FALSE), -- Rèm cửa blackout
(1, 38, FALSE), -- Áo choàng tắm cotton
(1, 39, FALSE), -- Dép đi trong phòng
(1, 40, FALSE), -- Cổng sạc USB đa năng
(1, 41, FALSE), -- Điện thoại bàn quốc tế
(1, 43, FALSE), -- Dịch vụ phòng 24/7
(1, 44, FALSE); -- Dọn phòng 2 lần/ngày

-- === PREMIUM CORNER (type_id = 2) ===
INSERT INTO room_type_amenities (room_type_id, amenity_id, is_main_amenity) VALUES 
-- Tiện nghi chính Premium Corner
(2, 1, TRUE),   -- Điều hòa không khí
(2, 2, TRUE),   -- TV truyền hình cáp
(2, 3, TRUE),   -- Minibar với nước miễn phí  
(2, 4, TRUE),   -- WiFi miễn phí
(2, 8, TRUE),   -- Phòng tắm rộng rãi (hiện đại)
(2, 13, TRUE),  -- Tường kính bao quanh
(2, 14, TRUE),  -- Vị trí góc yên tĩnh
(2, 16, TRUE),  -- Sàn gạch nâu với thảm xám vàng
(2, 17, TRUE),  -- Nội thất sang trọng

-- Kế thừa từ Deluxe + bổ sung
(2, 5, FALSE),  -- Két sắt
(2, 6, FALSE),  -- Vòi sen riêng biệt
(2, 7, FALSE),  -- Bồn tắm riêng biệt
(2, 9, FALSE),  -- Đồ vệ sinh cao cấp
(2, 10, FALSE), -- Máy sấy tóc
(2, 11, FALSE), -- Cửa kính kịch trần
(2, 12, FALSE), -- View thành phố
(2, 35, FALSE), -- Ga trải giường cao cấp
(2, 36, FALSE), -- Gối memory foam
(2, 37, FALSE), -- Rèm cửa blackout
(2, 38, FALSE), -- Áo choàng tắm cotton
(2, 39, FALSE), -- Dép đi trong phòng
(2, 40, FALSE), -- Cổng sạc USB đa năng
(2, 41, FALSE), -- Điện thoại bàn quốc tế
(2, 42, FALSE), -- Hệ thống âm thanh Bluetooth
(2, 43, FALSE), -- Dịch vụ phòng 24/7
(2, 44, FALSE); -- Dọn phòng 2 lần/ngày

-- === THE LEVEL PREMIUM (type_id = 3) ===
INSERT INTO room_type_amenities (room_type_id, amenity_id, is_main_amenity) VALUES 
-- Tiện nghi chính The Level Premium
(3, 1, TRUE),   -- Điều hòa không khí
(3, 2, TRUE),   -- TV truyền hình cáp
(3, 4, TRUE),   -- WiFi miễn phí
(3, 26, TRUE),  -- Quyền truy cập Executive Lounge
(3, 27, TRUE),  -- Trà cà phê miễn phí Lounge
(3, 28, TRUE),  -- Happy hour đồ uống có cồn
(3, 29, TRUE),  -- Check-in/out riêng tư VIP
(3, 34, TRUE),  -- Không gian riêng tư cao cấp

-- Kế thừa tất cả từ Premium Corner + thêm
(3, 3, FALSE),  -- Minibar với nước miễn phí
(3, 5, FALSE),  -- Két sắt
(3, 6, FALSE),  -- Vòi sen riêng biệt
(3, 7, FALSE),  -- Bồn tắm riêng biệt
(3, 8, FALSE),  -- Phòng tắm rộng rãi
(3, 9, FALSE),  -- Đồ vệ sinh cao cấp
(3, 10, FALSE), -- Máy sấy tóc
(3, 11, FALSE), -- Cửa kính kịch trần
(3, 12, FALSE), -- View thành phố
(3, 30, FALSE), -- 3h sử dụng phòng họp miễn phí
(3, 31, FALSE), -- Bộ sách tô màu chánh niệm
(3, 32, FALSE), -- Dịch vụ trà cà phê cao cấp
(3, 33, FALSE), -- Dịch vụ phòng ưu tiên
(3, 35, FALSE), -- Ga trải giường cao cấp
(3, 36, FALSE), -- Gối memory foam
(3, 37, FALSE), -- Rèm cửa blackout
(3, 38, FALSE), -- Áo choàng tắm cotton
(3, 39, FALSE), -- Dép đi trong phòng
(3, 40, FALSE), -- Cổng sạc USB đa năng
(3, 41, FALSE), -- Điện thoại bàn quốc tế
(3, 42, FALSE), -- Hệ thống âm thanh Bluetooth
(3, 44, FALSE); -- Dọn phòng 2 lần/ngày

-- === THE LEVEL PREMIUM CORNER (type_id = 4) ===
INSERT INTO room_type_amenities (room_type_id, amenity_id, is_main_amenity) VALUES 
-- Kế thừa tất cả từ The Level Premium + Premium Corner
(4, 1, TRUE),   -- Điều hòa không khí
(4, 2, TRUE),   -- TV truyền hình cáp
(4, 4, TRUE),   -- WiFi miễn phí
(4, 13, TRUE),  -- Tường kính bao quanh
(4, 14, TRUE),  -- Vị trí góc yên tĩnh
(4, 26, TRUE),  -- Quyền truy cập Executive Lounge
(4, 27, TRUE),  -- Trà cà phê miễn phí Lounge
(4, 28, TRUE),  -- Happy hour đồ uống có cồn
(4, 29, TRUE),  -- Check-in/out riêng tư VIP

-- Tất cả tiện nghi khác
(4, 3, FALSE),  -- Minibar với nước miễn phí
(4, 5, FALSE),  -- Két sắt
(4, 6, FALSE),  -- Vòi sen riêng biệt
(4, 7, FALSE),  -- Bồn tắm riêng biệt
(4, 8, FALSE),  -- Phòng tắm rộng rãi
(4, 9, FALSE),  -- Đồ vệ sinh cao cấp
(4, 10, FALSE), -- Máy sấy tóc
(4, 11, FALSE), -- Cửa kính kịch trần
(4, 12, FALSE), -- View thành phố
(4, 16, FALSE), -- Sàn gạch nâu với thảm xám vàng
(4, 17, FALSE), -- Nội thất sang trọng
(4, 30, FALSE), -- 3h sử dụng phòng họp miễn phí
(4, 31, FALSE), -- Bộ sách tô màu chánh niệm
(4, 32, FALSE), -- Dịch vụ trà cà phê cao cấp
(4, 33, FALSE), -- Dịch vụ phòng ưu tiên
(4, 34, FALSE), -- Không gian riêng tư cao cấp
(4, 35, FALSE), -- Ga trải giường cao cấp
(4, 36, FALSE), -- Gối memory foam
(4, 37, FALSE), -- Rèm cửa blackout
(4, 38, FALSE), -- Áo choàng tắm cotton
(4, 39, FALSE), -- Dép đi trong phòng
(4, 40, FALSE), -- Cổng sạc USB đa năng
(4, 41, FALSE), -- Điện thoại bàn quốc tế
(4, 42, FALSE), -- Hệ thống âm thanh Bluetooth
(4, 43, FALSE), -- Dịch vụ phòng 24/7
(4, 46, FALSE); -- Champagne chào mừng

-- === THE LEVEL SUITE (type_id = 5) ===
INSERT INTO room_type_amenities (room_type_id, amenity_id, is_main_amenity) VALUES 
-- Tiện nghi chính Suite
(5, 1, TRUE),   -- Điều hòa không khí
(5, 2, TRUE),   -- TV truyền hình cáp
(5, 4, TRUE),   -- WiFi miễn phí
(5, 21, TRUE),  -- Phòng khách riêng biệt
(5, 22, TRUE),  -- Ghế sofa cao cấp
(5, 23, TRUE),  -- Khu vực làm việc
(5, 24, TRUE),  -- Bàn gỗ với ghế nệm da
(5, 25, TRUE),  -- Thiết kế mở
(5, 26, TRUE),  -- Quyền truy cập Executive Lounge

-- Tất cả tiện nghi khác từ The Level Premium Corner + Suite specific
(5, 3, FALSE),  -- Minibar với nước miễn phí
(5, 5, FALSE),  -- Két sắt
(5, 6, FALSE),  -- Vòi sen riêng biệt
(5, 7, FALSE),  -- Bồn tắm riêng biệt
(5, 8, FALSE),  -- Phòng tắm rộng rãi
(5, 9, FALSE),  -- Đồ vệ sinh cao cấp
(5, 10, FALSE), -- Máy sấy tóc
(5, 11, FALSE), -- Cửa kính kịch trần
(5, 12, FALSE), -- View thành phố
(5, 18, FALSE), -- Tường ốp gỗ
(5, 19, FALSE), -- Trang trí vải nỉ xám đậm
(5, 20, FALSE), -- Tông màu trắng nâu trầm
(5, 27, FALSE), -- Trà cà phê miễn phí Lounge
(5, 28, FALSE), -- Happy hour đồ uống có cồn
(5, 29, FALSE), -- Check-in/out riêng tư VIP
(5, 30, FALSE), -- 3h sử dụng phòng họp miễn phí
(5, 31, FALSE), -- Bộ sách tô màu chánh niệm
(5, 32, FALSE), -- Dịch vụ trà cà phê cao cấp
(5, 33, FALSE), -- Dịch vụ phòng ưu tiên
(5, 34, FALSE), -- Không gian riêng tư cao cấp
(5, 35, FALSE), -- Ga trải giường cao cấp
(5, 36, FALSE), -- Gối memory foam
(5, 37, FALSE), -- Rèm cửa blackout
(5, 38, FALSE), -- Áo choàng tắm cotton
(5, 39, FALSE), -- Dép đi trong phòng
(5, 40, FALSE), -- Cổng sạc USB đa năng
(5, 41, FALSE), -- Điện thoại bàn quốc tế
(5, 42, FALSE), -- Hệ thống âm thanh Bluetooth
(5, 43, FALSE), -- Dịch vụ phòng 24/7
(5, 44, FALSE), -- Dọn phòng 2 lần/ngày
(5, 45, FALSE), -- Butler cá nhân
(5, 46, FALSE); -- Champagne chào mừng

-- === SUITE (type_id = 6) ===
INSERT INTO room_type_amenities (room_type_id, amenity_id, is_main_amenity) VALUES 
-- Tiện nghi chính Suite thường
(6, 1, TRUE),   -- Điều hòa không khí
(6, 2, TRUE),   -- TV truyền hình cáp
(6, 4, TRUE),   -- WiFi miễn phí
(6, 21, TRUE),  -- Phòng khách riêng biệt
(6, 22, TRUE),  -- Ghế sofa cao cấp
(6, 23, TRUE),  -- Khu vực làm việc
(6, 24, TRUE),  -- Bàn gỗ với ghế nệm da
(6, 25, TRUE),  -- Thiết kế mở

-- Tiện nghi Suite (không có The Level benefits)
(6, 3, FALSE),  -- Minibar với nước miễn phí
(6, 5, FALSE),  -- Két sắt
(6, 6, FALSE),  -- Vòi sen riêng biệt
(6, 7, FALSE),  -- Bồn tắm riêng biệt
(6, 8, FALSE),  -- Phòng tắm rộng rãi
(6, 9, FALSE),  -- Đồ vệ sinh cao cấp
(6, 10, FALSE), -- Máy sấy tóc
(6, 11, FALSE), -- Cửa kính kịch trần
(6, 12, FALSE), -- View thành phố
(6, 18, FALSE), -- Tường ốp gỗ
(6, 19, FALSE), -- Trang trí vải nỉ xám đậm
(6, 20, FALSE), -- Tông màu trắng nâu trầm
(6, 35, FALSE), -- Ga trải giường cao cấp
(6, 36, FALSE), -- Gối memory foam
(6, 37, FALSE), -- Rèm cửa blackout
(6, 38, FALSE), -- Áo choàng tắm cotton
(6, 39, FALSE), -- Dép đi trong phòng
(6, 40, FALSE), -- Cổng sạc USB đa năng
(6, 41, FALSE), -- Điện thoại bàn quốc tế
(6, 42, FALSE), -- Hệ thống âm thanh Bluetooth
(6, 43, FALSE), -- Dịch vụ phòng 24/7
(6, 44, FALSE), -- Dọn phòng 2 lần/ngày
(6, 45, FALSE); -- Butler cá nhân

-- === PRESIDENTIAL SUITE (type_id = 7) ===
INSERT INTO room_type_amenities (room_type_id, amenity_id, is_main_amenity) VALUES 
-- Tất cả tiện nghi cao cấp nhất
(7, 1, TRUE),   -- Điều hòa không khí
(7, 2, TRUE),   -- TV truyền hình cáp
(7, 4, TRUE),   -- WiFi miễn phí
(7, 21, TRUE),  -- Phòng khách riêng biệt
(7, 22, TRUE),  -- Ghế sofa cao cấp
(7, 23, TRUE),  -- Khu vực làm việc
(7, 24, TRUE),  -- Bàn gỗ với ghế nệm da
(7, 25, TRUE),  -- Thiết kế mở
(7, 45, TRUE),  -- Butler cá nhân
(7, 46, TRUE),  -- Champagne chào mừng

-- Tất cả tiện nghi khác
(7, 3, FALSE),  -- Minibar với nước miễn phí
(7, 5, FALSE),  -- Két sắt
(7, 6, FALSE),  -- Vòi sen riêng biệt
(7, 7, FALSE),  -- Bồn tắm riêng biệt
(7, 8, FALSE),  -- Phòng tắm rộng rãi
(7, 9, FALSE),  -- Đồ vệ sinh cao cấp
(7, 10, FALSE), -- Máy sấy tóc
(7, 11, FALSE), -- Cửa kính kịch trần
(7, 13, FALSE), -- Tường kính bao quanh
(7, 18, FALSE), -- Tường ốp gỗ
(7, 19, FALSE), -- Trang trí vải nỉ xám đậm
(7, 20, FALSE), -- Tông màu trắng nâu trầm
(7, 35, FALSE), -- Ga trải giường cao cấp
(7, 36, FALSE), -- Gối memory foam
(7, 37, FALSE), -- Rèm cửa blackout
(7, 38, FALSE), -- Áo choàng tắm cotton
(7, 39, FALSE), -- Dép đi trong phòng
(7, 40, FALSE), -- Cổng sạc USB đa năng
(7, 41, FALSE), -- Điện thoại bàn quốc tế
(7, 42, FALSE), -- Hệ thống âm thanh Bluetooth
(7, 43, FALSE), -- Dịch vụ phòng 24/7
(7, 44, FALSE); -- Dọn phòng 2 lần/ngày

