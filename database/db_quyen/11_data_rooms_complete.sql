-- ===== COMPLETE ROOMS DATA =====
-- Insert đầy đủ 295 phòng với kiểu giường cố định

/* 
Sơ Đồ Tầng Tiện Ích & Dịch Vụ (Không Có Phòng)
Tầng Thấp
•	Tầng 1:
o	Lobby chính + Lobby Bar (đồ uống nhẹ)
o	Lễ tân
o	Hội nghị: 1 Ballroom + 3 phòng họp (sức chứa 900 khách)
Tầng Trung
•	Tầng 6:
o	Nhà hàng Orchid - Buffet Á-Âu (260 khách)
•	Tầng 7:
o	Khu thư giãn tổng hợp:
	Bể bơi trong nhà (nước ấm, sâu 1.2m, 6:00-20:00)
	Spa YHI/Vincharm (massage, xông hơi)
	Phòng gym (6:00-22:00)
Tầng Cao
•	Tầng 33:
o	Panoramic Lounge (36 khách VIP, không gian riêng tư)
•	Tầng 34:
o	Nhà hàng Lotus (a la carte)
o	Skyview Bar (view 360° thành phố)
o	Sảnh sự kiện ngoài trời (sức chứa 300 khách)

Tổng Số Phòng
•	Deluxe Room: 6 tầng × 15 = 90 phòng
•	Premium Corner: 8 tầng × 12 = 96 phòng
•	The Level Premium: 3 tầng × 12 = 36 phòng
•	The Level Premium Corner: 4 tầng × 8 = 32 phòng
•	The Level Suite: 7 + 7 + 6 = 20 phòng
•	Suite: 4 tầng × 5 = 20 phòng
•	Presidential Suite: 1 phòng
 Tổng số phòng = 295
 */

-- Xóa dữ liệu rooms mẫu cũ
DELETE FROM rooms;
ALTER TABLE rooms AUTO_INCREMENT = 1;

-- INSERT ĐẦY ĐỦ 295 PHÒNG VỚI KIỂU GIƯỜNG CỐ ĐỊNH
-- Quy tắc: Phòng lẻ (01, 03, 05...) = King, Phòng chẵn (02, 04, 06...) = Twin

-- ==== DELUXE ROOM - 90 phòng ====
-- Tầng 2: Phòng 201-215 (15 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('201', 2, 1, 'King', 'available'), ('202', 2, 1, 'Twin', 'available'), ('203', 2, 1, 'King', 'occupied'),
('204', 2, 1, 'Twin', 'available'), ('205', 2, 1, 'King', 'available'), ('206', 2, 1, 'Twin', 'available'),
('207', 2, 1, 'King', 'available'), ('208', 2, 1, 'Twin', 'available'), ('209', 2, 1, 'King', 'available'),
('210', 2, 1, 'Twin', 'available'), ('211', 2, 1, 'King', 'available'), ('212', 2, 1, 'Twin', 'available'),
('213', 2, 1, 'King', 'available'), ('214', 2, 1, 'Twin', 'available'), ('215', 2, 1, 'King', 'available');

-- Tầng 3: Phòng 301-315 (15 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('301', 3, 1, 'King', 'available'), ('302', 3, 1, 'Twin', 'maintenance'), ('303', 3, 1, 'King', 'available'),
('304', 3, 1, 'Twin', 'available'), ('305', 3, 1, 'King', 'available'), ('306', 3, 1, 'Twin', 'available'),
('307', 3, 1, 'King', 'available'), ('308', 3, 1, 'Twin', 'available'), ('309', 3, 1, 'King', 'available'),
('310', 3, 1, 'Twin', 'available'), ('311', 3, 1, 'King', 'available'), ('312', 3, 1, 'Twin', 'available'),
('313', 3, 1, 'King', 'available'), ('314', 3, 1, 'Twin', 'available'), ('315', 3, 1, 'King', 'available');

-- Tầng 4: Phòng 401-415 (15 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('401', 4, 1, 'King', 'available'), ('402', 4, 1, 'Twin', 'available'), ('403', 4, 1, 'King', 'available'),
('404', 4, 1, 'Twin', 'available'), ('405', 4, 1, 'King', 'available'), ('406', 4, 1, 'Twin', 'available'),
('407', 4, 1, 'King', 'available'), ('408', 4, 1, 'Twin', 'available'), ('409', 4, 1, 'King', 'available'),
('410', 4, 1, 'Twin', 'available'), ('411', 4, 1, 'King', 'available'), ('412', 4, 1, 'Twin', 'available'),
('413', 4, 1, 'King', 'available'), ('414', 4, 1, 'Twin', 'available'), ('415', 4, 1, 'King', 'available');

-- Tầng 5: Phòng 501-515 (15 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('501', 5, 1, 'King', 'available'), ('502', 5, 1, 'Twin', 'available'), ('503', 5, 1, 'King', 'available'),
('504', 5, 1, 'Twin', 'available'), ('505', 5, 1, 'King', 'available'), ('506', 5, 1, 'Twin', 'available'),
('507', 5, 1, 'King', 'available'), ('508', 5, 1, 'Twin', 'available'), ('509', 5, 1, 'King', 'available'),
('510', 5, 1, 'Twin', 'available'), ('511', 5, 1, 'King', 'available'), ('512', 5, 1, 'Twin', 'available'),
('513', 5, 1, 'King', 'available'), ('514', 5, 1, 'Twin', 'available'), ('515', 5, 1, 'King', 'available');

-- Tầng 6: Tiện ích - Nhà hàng Orchid (KHÔNG CÓ PHÒNG)
-- Tầng 7: Tiện ích - Spa, Gym, Bể bơi (KHÔNG CÓ PHÒNG)

-- Tầng 8: Phòng 801-815 (15 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('801', 8, 1, 'King', 'available'), ('802', 8, 1, 'Twin', 'available'), ('803', 8, 1, 'King', 'available'),
('804', 8, 1, 'Twin', 'available'), ('805', 8, 1, 'King', 'available'), ('806', 8, 1, 'Twin', 'available'),
('807', 8, 1, 'King', 'available'), ('808', 8, 1, 'Twin', 'available'), ('809', 8, 1, 'King', 'available'),
('810', 8, 1, 'Twin', 'available'), ('811', 8, 1, 'King', 'available'), ('812', 8, 1, 'Twin', 'available'),
('813', 8, 1, 'King', 'available'), ('814', 8, 1, 'Twin', 'available'), ('815', 8, 1, 'King', 'available');

-- Tầng 9: Phòng 901-915 (15 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('901', 9, 1, 'King', 'available'), ('902', 9, 1, 'Twin', 'available'), ('903', 9, 1, 'King', 'available'),
('904', 9, 1, 'Twin', 'available'), ('905', 9, 1, 'King', 'available'), ('906', 9, 1, 'Twin', 'available'),
('907', 9, 1, 'King', 'available'), ('908', 9, 1, 'Twin', 'available'), ('909', 9, 1, 'King', 'available'),
('910', 9, 1, 'Twin', 'available'), ('911', 9, 1, 'King', 'available'), ('912', 9, 1, 'Twin', 'available'),
('913', 9, 1, 'King', 'available'), ('914', 9, 1, 'Twin', 'available'), ('915', 9, 1, 'King', 'available');

-- Tầng 10: Phòng 1001-1012 (12 phòng) - Premium Corner
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('1001', 10, 2, 'King', 'available'), ('1002', 10, 2, 'Twin', 'available'), ('1003', 10, 2, 'King', 'available'),
('1004', 10, 2, 'Twin', 'available'), ('1005', 10, 2, 'King', 'available'), ('1006', 10, 2, 'Twin', 'available'),
('1007', 10, 2, 'King', 'available'), ('1008', 10, 2, 'Twin', 'available'), ('1009', 10, 2, 'King', 'available'),
('1010', 10, 2, 'Twin', 'available'), ('1011', 10, 2, 'King', 'available'), ('1012', 10, 2, 'Twin', 'available');

-- Tầng 11: Phòng 1101-1112 (12 phòng) - Premium Corner
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('1101', 11, 2, 'King', 'available'), ('1102', 11, 2, 'Twin', 'available'), ('1103', 11, 2, 'King', 'available'),
('1104', 11, 2, 'Twin', 'available'), ('1105', 11, 2, 'King', 'available'), ('1106', 11, 2, 'Twin', 'available'),
('1107', 11, 2, 'King', 'available'), ('1108', 11, 2, 'Twin', 'available'), ('1109', 11, 2, 'King', 'available'),
('1110', 11, 2, 'Twin', 'available'), ('1111', 11, 2, 'King', 'available'), ('1112', 11, 2, 'Twin', 'available');

-- ==== PREMIUM CORNER - 96 phòng ====
-- Tầng 12: Phòng 1201-1212 (12 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('1201', 12, 2, 'King', 'available'), ('1202', 12, 2, 'Twin', 'available'), ('1203', 12, 2, 'King', 'available'),
('1204', 12, 2, 'Twin', 'available'), ('1205', 12, 2, 'King', 'available'), ('1206', 12, 2, 'Twin', 'available'),
('1207', 12, 2, 'King', 'available'), ('1208', 12, 2, 'Twin', 'available'), ('1209', 12, 2, 'King', 'available'),
('1210', 12, 2, 'Twin', 'available'), ('1211', 12, 2, 'King', 'available'), ('1212', 12, 2, 'Twin', 'available');

-- Tầng 13: Phòng 1301-1312 (12 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('1301', 13, 2, 'King', 'available'), ('1302', 13, 2, 'Twin', 'available'), ('1303', 13, 2, 'King', 'available'),
('1304', 13, 2, 'Twin', 'available'), ('1305', 13, 2, 'King', 'available'), ('1306', 13, 2, 'Twin', 'available'),
('1307', 13, 2, 'King', 'available'), ('1308', 13, 2, 'Twin', 'available'), ('1309', 13, 2, 'King', 'available'),
('1310', 13, 2, 'Twin', 'available'), ('1311', 13, 2, 'King', 'available'), ('1312', 13, 2, 'Twin', 'available');

-- Tầng 14: Phòng 1401-1412 (12 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('1401', 14, 2, 'King', 'available'), ('1402', 14, 2, 'Twin', 'available'), ('1403', 14, 2, 'King', 'available'),
('1404', 14, 2, 'Twin', 'available'), ('1405', 14, 2, 'King', 'available'), ('1406', 14, 2, 'Twin', 'available'),
('1407', 14, 2, 'King', 'available'), ('1408', 14, 2, 'Twin', 'available'), ('1409', 14, 2, 'King', 'available'),
('1410', 14, 2, 'Twin', 'available'), ('1411', 14, 2, 'King', 'available'), ('1412', 14, 2, 'Twin', 'available');

-- Tầng 15: Phòng 1501-1512 (12 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('1501', 15, 2, 'King', 'available'), ('1502', 15, 2, 'Twin', 'available'), ('1503', 15, 2, 'King', 'available'),
('1504', 15, 2, 'Twin', 'available'), ('1505', 15, 2, 'King', 'available'), ('1506', 15, 2, 'Twin', 'available'),
('1507', 15, 2, 'King', 'available'), ('1508', 15, 2, 'Twin', 'available'), ('1509', 15, 2, 'King', 'available'),
('1510', 15, 2, 'Twin', 'available'), ('1511', 15, 2, 'King', 'available'), ('1512', 15, 2, 'Twin', 'available');

-- Tầng 16: Phòng 1601-1612 (12 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('1601', 16, 2, 'King', 'available'), ('1602', 16, 2, 'Twin', 'available'), ('1603', 16, 2, 'King', 'available'),
('1604', 16, 2, 'Twin', 'available'), ('1605', 16, 2, 'King', 'available'), ('1606', 16, 2, 'Twin', 'available'),
('1607', 16, 2, 'King', 'available'), ('1608', 16, 2, 'Twin', 'available'), ('1609', 16, 2, 'King', 'available'),
('1610', 16, 2, 'Twin', 'available'), ('1611', 16, 2, 'King', 'available'), ('1612', 16, 2, 'Twin', 'available');

-- Tầng 17: Phòng 1701-1712 (12 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('1701', 17, 2, 'King', 'available'), ('1702', 17, 2, 'Twin', 'available'), ('1703', 17, 2, 'King', 'available'),
('1704', 17, 2, 'Twin', 'available'), ('1705', 17, 2, 'King', 'available'), ('1706', 17, 2, 'Twin', 'available'),
('1707', 17, 2, 'King', 'available'), ('1708', 17, 2, 'Twin', 'available'), ('1709', 17, 2, 'King', 'available'),
('1710', 17, 2, 'Twin', 'available'), ('1711', 17, 2, 'King', 'available'), ('1712', 17, 2, 'Twin', 'available');

-- ==== THE LEVEL PREMIUM - 36 phòng ====
-- Tầng 18: Phòng 1801-1812 (12 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('1801', 18, 3, 'King', 'available'), ('1802', 18, 3, 'Twin', 'available'), ('1803', 18, 3, 'King', 'available'),
('1804', 18, 3, 'Twin', 'available'), ('1805', 18, 3, 'King', 'available'), ('1806', 18, 3, 'Twin', 'available'),
('1807', 18, 3, 'King', 'available'), ('1808', 18, 3, 'Twin', 'available'), ('1809', 18, 3, 'King', 'available'),
('1810', 18, 3, 'Twin', 'available'), ('1811', 18, 3, 'King', 'available'), ('1812', 18, 3, 'Twin', 'available');

-- Tầng 19: Phòng 1901-1912 (12 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('1901', 19, 3, 'King', 'occupied'), ('1902', 19, 3, 'Twin', 'available'), ('1903', 19, 3, 'King', 'available'),
('1904', 19, 3, 'Twin', 'available'), ('1905', 19, 3, 'King', 'available'), ('1906', 19, 3, 'Twin', 'available'),
('1907', 19, 3, 'King', 'available'), ('1908', 19, 3, 'Twin', 'available'), ('1909', 19, 3, 'King', 'available'),
('1910', 19, 3, 'Twin', 'available'), ('1911', 19, 3, 'King', 'available'), ('1912', 19, 3, 'Twin', 'available');

-- Tầng 20: Phòng 2001-2012 (12 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('2001', 20, 3, 'King', 'available'), ('2002', 20, 3, 'Twin', 'available'), ('2003', 20, 3, 'King', 'available'),
('2004', 20, 3, 'Twin', 'available'), ('2005', 20, 3, 'King', 'available'), ('2006', 20, 3, 'Twin', 'available'),
('2007', 20, 3, 'King', 'available'), ('2008', 20, 3, 'Twin', 'available'), ('2009', 20, 3, 'King', 'available'),
('2010', 20, 3, 'Twin', 'available'), ('2011', 20, 3, 'King', 'available'), ('2012', 20, 3, 'Twin', 'available');

-- ==== THE LEVEL PREMIUM CORNER - 32 phòng ====
-- Tầng 21: Phòng 2101-2108 (8 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('2101', 21, 4, 'King', 'available'), ('2102', 21, 4, 'Twin', 'available'), ('2103', 21, 4, 'King', 'available'),
('2104', 21, 4, 'Twin', 'available'), ('2105', 21, 4, 'King', 'available'), ('2106', 21, 4, 'Twin', 'available'),
('2107', 21, 4, 'King', 'available'), ('2108', 21, 4, 'Twin', 'available');

-- Tầng 22: Phòng 2201-2208 (8 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('2201', 22, 4, 'King', 'available'), ('2202', 22, 4, 'Twin', 'available'), ('2203', 22, 4, 'King', 'available'),
('2204', 22, 4, 'Twin', 'available'), ('2205', 22, 4, 'King', 'available'), ('2206', 22, 4, 'Twin', 'available'),
('2207', 22, 4, 'King', 'available'), ('2208', 22, 4, 'Twin', 'available');

-- Tầng 23: Phòng 2301-2308 (8 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('2301', 23, 4, 'King', 'available'), ('2302', 23, 4, 'Twin', 'available'), ('2303', 23, 4, 'King', 'available'),
('2304', 23, 4, 'Twin', 'available'), ('2305', 23, 4, 'King', 'available'), ('2306', 23, 4, 'Twin', 'available'),
('2307', 23, 4, 'King', 'available'), ('2308', 23, 4, 'Twin', 'available');

-- Tầng 24: Phòng 2401-2408 (8 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('2401', 24, 4, 'King', 'available'), ('2402', 24, 4, 'Twin', 'available'), ('2403', 24, 4, 'King', 'available'),
('2404', 24, 4, 'Twin', 'available'), ('2405', 24, 4, 'King', 'available'), ('2406', 24, 4, 'Twin', 'available'),
('2407', 24, 4, 'King', 'available'), ('2408', 24, 4, 'Twin', 'available');

-- ==== THE LEVEL SUITE - 20 phòng (chỉ King bed) ====
-- Tầng 25: Phòng 2501-2507 (7 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('2501', 25, 5, 'King', 'available'), ('2502', 25, 5, 'King', 'occupied'), ('2503', 25, 5, 'King', 'available'),
('2504', 25, 5, 'King', 'available'), ('2505', 25, 5, 'King', 'available'), ('2506', 25, 5, 'King', 'available'),
('2507', 25, 5, 'King', 'available');

-- Tầng 26: Phòng 2601-2607 (7 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('2601', 26, 5, 'King', 'available'), ('2602', 26, 5, 'King', 'available'), ('2603', 26, 5, 'King', 'available'),
('2604', 26, 5, 'King', 'available'), ('2605', 26, 5, 'King', 'available'), ('2606', 26, 5, 'King', 'available'),
('2607', 26, 5, 'King', 'available');

-- Tầng 27: Phòng 2701-2706 (6 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('2701', 27, 5, 'King', 'available'), ('2702', 27, 5, 'King', 'available'), ('2703', 27, 5, 'King', 'available'),
('2704', 27, 5, 'King', 'available'), ('2705', 27, 5, 'King', 'available'), ('2706', 27, 5, 'King', 'available');

-- ==== SUITE - 20 phòng (chỉ King bed) ====
-- Tầng 28: Phòng 2801-2805 (5 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('2801', 28, 6, 'King', 'available'), ('2802', 28, 6, 'King', 'available'), ('2803', 28, 6, 'King', 'available'),
('2804', 28, 6, 'King', 'available'), ('2805', 28, 6, 'King', 'available');

-- Tầng 29: Phòng 2901-2905 (5 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('2901', 29, 6, 'King', 'available'), ('2902', 29, 6, 'King', 'available'), ('2903', 29, 6, 'King', 'available'),
('2904', 29, 6, 'King', 'available'), ('2905', 29, 6, 'King', 'available');

-- Tầng 30: Phòng 3001-3005 (5 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('3001', 30, 6, 'King', 'available'), ('3002', 30, 6, 'King', 'available'), ('3003', 30, 6, 'King', 'available'),
('3004', 30, 6, 'King', 'available'), ('3005', 30, 6, 'King', 'available');

-- Tầng 31: Phòng 3101-3105 (5 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('3101', 31, 6, 'King', 'available'), ('3102', 31, 6, 'King', 'available'), ('3103', 31, 6, 'King', 'available'),
('3104', 31, 6, 'King', 'available'), ('3105', 31, 6, 'King', 'available');

-- ==== PRESIDENTIAL SUITE - 1 phòng (chỉ King bed) ====
-- Tầng 32: Phòng 3201 (1 phòng)
INSERT INTO rooms (room_number, floor_id, room_type_id, bed_type_fixed, status) VALUES 
('3201', 32, 7, 'King', 'available');

-- ==== KIỂM TRA KẾT QUẢ ====
-- Tổng cộng: 90 + 96 + 36 + 32 + 20 + 20 + 1 = 295 phòng
-- Deluxe Room: 90 phòng (tầng 2,3,4,5,8,9) - 6 tầng × 15 phòng
-- Premium Corner: 96 phòng (tầng 10-17) - 8 tầng × 12 phòng  
-- The Level Premium: 36 phòng (tầng 18-20) - 3 tầng × 12 phòng
-- The Level Premium Corner: 32 phòng (tầng 21-24) - 4 tầng × 8 phòng
-- The Level Suite: 20 phòng (tầng 25-27) - 7+7+6 phòng
-- Suite: 20 phòng (tầng 28-31) - 4 tầng × 5 phòng
-- Presidential Suite: 1 phòng (tầng 32)
-- 
-- Tầng tiện ích (không có phòng): 1, 6, 7, 33, 34


