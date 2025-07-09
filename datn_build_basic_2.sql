-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th7 09, 2025 lúc 03:09 AM
-- Phiên bản máy phục vụ: 8.0.30
-- Phiên bản PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `datn_build_basic_2`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `amenities`
--

CREATE TABLE `amenities` (
  `amenity_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon_lib` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `amenities`
--

INSERT INTO `amenities` (`amenity_id`, `name`, `icon`, `icon_lib`, `category`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Điều hòa không khí', 'Snowflake', 'lucide', 'basic', 'Hệ thống điều hòa nhiệt độ hiện đại', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(2, 'TV truyền hình cáp', 'Tv2', 'lucide', 'entertainment', 'Smart TV màn hình phẳng với truyền hình cáp', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(3, 'Minibar với nước miễn phí', 'CupSoda', 'lucide', 'basic', 'Minibar với 2 chai nước suối, trà, cà phê miễn phí', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(4, 'WiFi miễn phí', 'Wifi', 'lucide', 'connectivity', 'Kết nối wifi tốc độ cao 24/7', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(5, 'Két sắt', 'Lock', 'lucide', 'security', 'Két sắt điện tử bảo mật cá nhân', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(6, 'Vòi sen riêng biệt', 'ShowerHead', 'lucide', 'bathroom', 'Vòi sen áp lực cao riêng biệt với vòi sen', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(7, 'Bồn tắm riêng biệt', 'Bath', 'lucide', 'bathroom', 'Bồn tắm ngâm người lớn riêng biệt với vòi sen', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(8, 'Phòng tắm rộng rãi', 'Bath', 'lucide', 'bathroom', 'Phòng tắm hiện đại với diện tích rộng rãi', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(9, 'Đồ vệ sinh cao cấp', 'Soap', 'antd', 'bathroom', 'Bộ amenities cao cấp đầy đủ', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(10, 'Máy sấy tóc', 'Wind', 'lucide', 'bathroom', 'Máy sấy tóc công suất cao', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(11, 'Cửa kính kịch trần', 'Window', 'lucide', 'view', 'Cửa kính lớn từ trần tới sàn tạo view panoramic', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(12, 'View thành phố', 'Building2', 'lucide', 'view', 'Tầm nhìn toàn cảnh ra thành phố', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(13, 'Tường kính bao quanh', 'City', 'lucide', 'view', 'Thiết kế tường kính tạo tầm nhìn 360 độ', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(14, 'Vị trí góc yên tĩnh', 'EyeOff', 'lucide', 'comfort', 'Phòng ở vị trí góc tòa nhà, không bị làm phiền', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(15, 'Thiết kế hiện đại nâu-trắng', 'Palette', 'lucide', 'comfort', 'Nội thất hiện đại với gam màu nâu ấm và trắng', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(16, 'Sàn gạch nâu với thảm xám vàng', 'Image', 'antd', 'comfort', 'Sàn nhà lát gạch nâu nhạt với thảm xám đậm có hoa văn vàng', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(17, 'Nội thất sang trọng', 'Sofa', 'lucide', 'comfort', 'Đồ nội thất cao cấp, thiết kế tinh tế', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(18, 'Tường ốp gỗ', 'Log', 'lucide', 'comfort', 'Tường ốp gỗ cao cấp tạo không gian ấm cúng', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(19, 'Trang trí vải nỉ xám đậm', 'Fabric', 'antd', 'comfort', 'Tấm vải nỉ mềm mại màu xám đậm trang trí tường', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(20, 'Tông màu trắng nâu trầm', 'Palette', 'lucide', 'comfort', 'Phối màu chủ đạo trắng và nâu trầm tạo không gian nhẹ nhàng', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(21, 'Phòng khách riêng biệt', 'Home', 'antd', 'comfort', 'Khu vực phòng khách tách biệt với giường ngủ', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(22, 'Ghế sofa cao cấp', 'Sofa', 'lucide', 'comfort', 'Bộ sofa thoải mái trong phòng khách', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(23, 'Khu vực làm việc', 'Briefcase', 'lucide', 'comfort', 'Bàn làm việc gỗ cao cấp với ghế nệm da', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(24, 'Bàn gỗ với ghế nệm da', 'Chair', 'lucide', 'comfort', 'Bàn làm việc bằng gỗ đặc với ghế bọc da cao cấp', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(25, 'Thiết kế mở', 'Unlock', 'lucide', 'comfort', 'Không gian mở rộng rãi, thoáng đãng', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(26, 'Quyền truy cập Executive Lounge', 'Crown', 'antd', 'service', 'Lounge VIP tầng 33 dành riêng cho khách The Level', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(27, 'Trà cà phê miễn phí Lounge', 'CoffeeOutlined', 'antd', 'service', 'Thưởng thức trà, cà phê, bánh quy miễn phí (09:30–22:00)', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(28, 'Happy hour đồ uống có cồn', 'Wine', 'lucide', 'service', 'Happy hour với đồ uống có cồn (17:30–19:00)', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(29, 'Check-in/out riêng tư VIP', 'Bell', 'antd', 'service', 'Nhận/trả phòng riêng tư tại sảnh VIP', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(30, '3h sử dụng phòng họp miễn phí', 'Calendar', 'antd', 'service', '3 giờ sử dụng phòng họp miễn phí', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(31, 'Bộ sách tô màu chánh niệm', 'Book', 'lucide', 'service', 'Bộ sách tô màu thư giãn tinh thần', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(32, 'Dịch vụ trà cà phê cao cấp', 'Coffee', 'lucide', 'service', 'Dịch vụ trà và cà phê cao cấp phục vụ tại phòng', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(33, 'Dịch vụ phòng ưu tiên', 'Star', 'antd', 'service', 'Room service được ưu tiên xử lý nhanh', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(34, 'Không gian riêng tư cao cấp', 'Lock', 'antd', 'service', 'Môi trường riêng tư và thanh bình hơn', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(35, 'Ga trải giường cao cấp', 'BedDouble', 'lucide', 'comfort', 'Ga cotton Ai Cập thread count cao', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(36, 'Gối memory foam', 'Pillow', 'lucide', 'comfort', 'Gối êm ái hỗ trợ giấc ngủ tốt', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(37, 'Rèm cửa blackout', 'Moon', 'antd', 'comfort', 'Rèm che ánh sáng hoàn toàn', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(38, 'Áo choàng tắm cotton', 'Bathrobe', 'antd', 'comfort', 'Áo choàng tắm cotton 100% cao cấp', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(39, 'Dép đi trong phòng', 'Slippers', 'lucide', 'comfort', 'Dép đi trong phòng bằng vải cao cấp', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(40, 'Cổng sạc USB đa năng', 'Usb', 'lucide', 'connectivity', 'Ổ cắm USB đa năng cho các thiết bị điện tử', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(41, 'Điện thoại bàn quốc tế', 'Phone', 'lucide', 'connectivity', 'Điện thoại bàn quốc tế', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(42, 'Hệ thống âm thanh Bluetooth', 'Speaker', 'lucide', 'entertainment', 'Hệ thống âm thanh Bluetooth', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(43, 'Dịch vụ phòng 24/7', 'RoomService', 'antd', 'service', 'Dịch vụ phòng 24/7', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(44, 'Dọn phòng 2 lần/ngày', 'Broom', 'lucide', 'service', 'Dọn phòng 2 lần/ngày', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(45, 'Butler cá nhân', 'User', 'antd', 'service', 'Butler cá nhân', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10'),
(46, 'Champagne chào mừng', 'Wine', 'lucide', 'service', 'Champagne chào mừng', 1, '2025-06-25 21:06:10', '2025-06-25 21:06:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `table_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `record_id` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bed_types`
--

CREATE TABLE `bed_types` (
  `id` int NOT NULL COMMENT 'Khóa chính',
  `type_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên loại giường',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả loại giường',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Danh sách các loại giường';

--
-- Đang đổ dữ liệu cho bảng `bed_types`
--

INSERT INTO `bed_types` (`id`, `type_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'King', 'hh', 1, '2025-06-10 00:16:30', '2025-06-26 03:21:52'),
(2, 'Twin', '2 giường đơn', 1, '2025-06-26 03:22:13', '2025-06-26 03:22:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking`
--

CREATE TABLE `booking` (
  `booking_id` int NOT NULL COMMENT 'Khóa chính, mã đặt phòng',
  `booking_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Khóa ngoại, mã người dùng (nếu có)',
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn phòng',
  `check_in_date` date NOT NULL COMMENT 'Ngày nhận phòng',
  `check_out_date` date NOT NULL COMMENT 'Ngày trả phòng',
  `total_price_vnd` decimal(15,2) NOT NULL COMMENT 'Tổng giá (VND)',
  `guest_count` int NOT NULL COMMENT 'Số khách',
  `adults` int NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Trạng thái đặt phòng',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `quantity` int DEFAULT NULL,
  `room_type_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  `guest_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tên khách',
  `guest_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Email khách',
  `guest_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số điện thoại khách',
  `room_id` int DEFAULT NULL,
  `children` int DEFAULT NULL,
  `children_age` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin đặt phòng';

--
-- Đang đổ dữ liệu cho bảng `booking`
--

INSERT INTO `booking` (`booking_id`, `booking_code`, `user_id`, `option_id`, `check_in_date`, `check_out_date`, `total_price_vnd`, `guest_count`, `adults`, `status`, `notes`, `quantity`, `room_type_id`, `created_at`, `updated_at`, `guest_name`, `guest_email`, `guest_phone`, `room_id`, `children`, `children_age`) VALUES
(23, 'LAVISHSTAY_509999', NULL, NULL, '2025-07-01', '2025-07-02', 2400000.00, 2, 0, 'cancelled', '', 2, NULL, '2025-07-01 04:08:52', '2025-07-04 02:26:20', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', 255, NULL, NULL),
(24, 'LAVISHYSTAY_931923', 2, 'OPT10', '2025-07-04', '2025-07-09', 999999.00, 2, 0, 'confirmed', '', 3, NULL, '2025-07-04 03:41:38', '2025-07-07 02:55:21', 'húhu', 'quyen@gmai.comđ', '231443342423', 1, 2, '3'),
(25, 'LVS20250707030928246', NULL, NULL, '2025-07-07', '2025-07-08', 2880000.00, 2, 1, 'confirmed', '', 1, NULL, '2025-07-06 20:09:28', '2025-07-06 20:09:28', 'qeweqw', 'reception@hotel.com', '0335920306', NULL, 1, '4'),
(26, 'LVS20250707031018433', NULL, NULL, '2025-07-07', '2025-07-11', 5760000.00, 2, 1, 'confirmed', '', 1, NULL, '2025-07-06 20:10:18', '2025-07-06 20:10:18', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', NULL, 1, '4'),
(27, 'LVS20250707031110789', NULL, NULL, '2025-07-07', '2025-07-11', 5760000.00, 2, 1, 'confirmed', '', 1, NULL, '2025-07-06 20:11:10', '2025-07-06 20:11:10', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', NULL, 1, '10'),
(31, 'LVS31050405', NULL, NULL, '2025-07-09', '2025-07-10', 2000000.00, 2, 2, 'confirmed', '', NULL, NULL, '2025-07-07 22:04:05', '2025-07-07 22:04:05', 'Nguyen Van Test', 'test@email.com', '0123456789', NULL, 0, NULL),
(32, 'LVS32050513', NULL, NULL, '2025-07-09', '2025-07-10', 2000000.00, 2, 2, 'confirmed', '', NULL, NULL, '2025-07-07 22:05:13', '2025-07-07 22:05:13', 'Nguyen Van Test', 'test@email.com', '0123456789', NULL, 0, NULL),
(33, 'LVS33050538', NULL, NULL, '2025-07-09', '2025-07-10', 2000000.00, 2, 2, 'confirmed', '', NULL, NULL, '2025-07-07 22:05:38', '2025-07-07 22:05:38', 'Nguyen Van Test', 'test@email.com', '0123456789', NULL, 0, NULL),
(34, 'LVS34050642', NULL, NULL, '2025-07-07', '2025-07-08', 3200000.00, 3, 3, 'confirmed', '', NULL, NULL, '2025-07-07 22:06:42', '2025-07-07 22:06:42', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(35, 'LVS35050702', NULL, NULL, '2025-07-07', '2025-07-08', 3200000.00, 3, 3, 'confirmed', '', NULL, NULL, '2025-07-07 22:07:02', '2025-07-07 22:07:02', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(36, 'LVS36050814', NULL, NULL, '2025-07-07', '2025-07-08', 3200000.00, 3, 3, 'confirmed', '', NULL, NULL, '2025-07-07 22:08:14', '2025-07-07 22:08:14', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(37, 'LVS37051044', NULL, NULL, '2025-07-07', '2025-07-08', 2300000.00, 3, 3, 'pending', '', NULL, NULL, '2025-07-07 22:10:44', '2025-07-07 22:10:44', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', NULL, 0, NULL),
(38, 'LVS38051120', NULL, NULL, '2025-07-07', '2025-07-08', 2300000.00, 3, 3, 'pending', '', NULL, NULL, '2025-07-07 22:11:20', '2025-07-07 22:11:20', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(39, 'LVS39063958', NULL, NULL, '2025-07-07', '2025-07-08', 6200000.00, 2, 2, 'pending', '', NULL, NULL, '2025-07-07 23:39:57', '2025-07-07 23:39:58', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(40, 'LVS40064052', NULL, NULL, '2025-07-07', '2025-07-08', 6200000.00, 2, 2, 'pending', '', NULL, NULL, '2025-07-07 23:40:52', '2025-07-07 23:40:52', 'qeweqw', 'reception@hotel.com', '0987654321', NULL, 0, NULL),
(41, 'LVS41073901', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 2, 2, 'pending', '', NULL, NULL, '2025-07-08 00:39:01', '2025-07-08 00:39:01', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(42, 'LVS42074259', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 2, 2, 'pending', '', NULL, NULL, '2025-07-08 00:42:59', '2025-07-08 00:42:59', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(43, 'LVS43074325', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 2, 2, 'pending', '', NULL, NULL, '2025-07-08 00:43:25', '2025-07-08 00:43:25', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(44, 'LVS44074700', NULL, NULL, '2025-07-07', '2025-07-08', 7320000.00, 7, 7, 'pending', '', NULL, NULL, '2025-07-08 00:47:00', '2025-07-08 00:47:00', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(45, 'LVS45075351', NULL, NULL, '2025-07-07', '2025-07-08', 8640000.00, 13, 13, 'pending', '', NULL, NULL, '2025-07-08 00:53:51', '2025-07-08 00:53:51', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(46, 'LVS46075512', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 6, 6, 'pending', '', NULL, NULL, '2025-07-08 00:55:12', '2025-07-08 00:55:12', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(51, 'LVS51083510', NULL, NULL, '2025-07-07', '2025-07-08', 2880000.00, 11, 6, 'pending', NULL, NULL, NULL, '2025-07-08 01:35:10', '2025-07-08 01:35:10', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[]'),
(53, 'LVS53093059', NULL, NULL, '2025-07-09', '2025-07-10', 4320000.00, 13, 8, 'pending', NULL, NULL, NULL, '2025-07-08 02:30:59', '2025-07-08 02:30:59', 'Nguyen Van Test', 'test@gmail.com', '0987654321', NULL, 5, '[]'),
(54, 'LVS54093118', NULL, NULL, '2025-07-09', '2025-07-10', 1200000.00, 2, 2, 'pending', NULL, NULL, NULL, '2025-07-08 02:31:18', '2025-07-08 02:31:18', 'Test User', 'test@test.com', '0123456789', NULL, 0, '[]'),
(56, 'LVS56094825', NULL, NULL, '2025-07-09', '2025-07-10', 1200000.00, 5, 2, 'pending', NULL, NULL, NULL, '2025-07-08 02:48:25', '2025-07-08 02:48:25', 'Test User Full', 'test@test.com', '0123456789', NULL, 3, '[[{\"age\": 8}, {\"age\": 10}, {\"age\": 5}]]'),
(60, 'LVS60104819', NULL, NULL, '2025-07-07', '2025-07-08', 4320000.00, 13, 8, 'pending', NULL, NULL, NULL, '2025-07-08 03:48:19', '2025-07-08 03:48:19', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[[{\"id\": \"room_0_child_1\", \"age\": 8}, {\"id\": \"room_0_child_2\", \"age\": 8}, {\"id\": \"room_0_child_3\", \"age\": 8}], [{\"id\": \"room_1_child_1\", \"age\": 8}, {\"id\": \"room_1_child_2\", \"age\": 8}], []]'),
(61, 'LVS61105534', NULL, NULL, '2025-07-07', '2025-07-08', 4320000.00, 13, 8, 'pending', NULL, NULL, NULL, '2025-07-08 03:55:34', '2025-07-08 03:55:34', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[[{\"id\": \"room_0_child_1\", \"age\": 8}, {\"id\": \"room_0_child_2\", \"age\": 8}, {\"id\": \"room_0_child_3\", \"age\": 8}], [{\"id\": \"room_1_child_1\", \"age\": 8}, {\"id\": \"room_1_child_2\", \"age\": 8}], []]'),
(62, 'LVS62153758', NULL, NULL, '2025-07-07', '2025-07-08', 4320000.00, 13, 8, 'pending', NULL, NULL, NULL, '2025-07-08 08:37:58', '2025-07-08 08:37:58', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[[{\"id\": \"room_0_child_1\", \"age\": 8}, {\"id\": \"room_0_child_2\", \"age\": 8}, {\"id\": \"room_0_child_3\", \"age\": 8}], [{\"id\": \"room_1_child_1\", \"age\": 8}, {\"id\": \"room_1_child_2\", \"age\": 8}], []]'),
(63, 'LVS63162115', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 2, 'confirmed', NULL, NULL, NULL, '2025-07-08 09:21:15', '2025-07-08 09:45:21', 'Huỳnh Thị Bích Tuyền', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '[[]]'),
(64, 'LVS64164554', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 2, 'confirmed', NULL, NULL, NULL, '2025-07-08 09:45:54', '2025-07-08 09:46:51', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '[[]]'),
(65, 'LVS65165011', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 2, 'confirmed', NULL, NULL, NULL, '2025-07-08 09:50:11', '2025-07-08 09:50:54', 'qeweqw', 'quyenjpn@gmail.com', '333241324342', NULL, 0, '[[]]'),
(66, 'LVS66165335', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 2, 'confirmed', NULL, NULL, NULL, '2025-07-08 09:53:35', '2025-07-08 09:54:06', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '[[]]');

--
-- Bẫy `booking`
--
DELIMITER $$
CREATE TRIGGER `trg_update_booked_rooms` AFTER UPDATE ON `booking` FOR EACH ROW BEGIN
  -- Biến điều khiển
  DECLARE done INT DEFAULT 0;
  DECLARE start_date DATE;
  DECLARE end_date DATE;
  DECLARE v_room_type_id INT;
  DECLARE cur_date DATE;

  -- CURSOR để duyệt từng dòng booking_rooms → room → room_type
  DECLARE cur CURSOR FOR
    SELECT br.check_in_date, br.check_out_date, r.room_type_id
    FROM booking_rooms br
    JOIN room r ON r.room_id = br.room_id
    WHERE br.booking_id = NEW.booking_id;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

  -- Nếu chuyển từ trạng thái khác → confirmed
  IF OLD.status <> 'confirmed' AND NEW.status = 'confirmed' THEN
    OPEN cur;
    read_loop: LOOP
      FETCH cur INTO start_date, end_date, v_room_type_id;
      IF done THEN
        LEAVE read_loop;
      END IF;

      SET cur_date = start_date;
      WHILE cur_date < end_date DO
        UPDATE room_occupancy
        SET booked_rooms = booked_rooms + 1
        WHERE room_type_id = v_room_type_id AND date = cur_date;
        SET cur_date = DATE_ADD(cur_date, INTERVAL 1 DAY);
      END WHILE;

    END LOOP;
    CLOSE cur;
  END IF;

  -- Nếu chuyển từ trạng thái khác → completed
  SET done = 0; -- reset biến done
  IF OLD.status <> 'completed' AND NEW.status = 'completed' THEN
    OPEN cur;
    read_loop2: LOOP
      FETCH cur INTO start_date, end_date, v_room_type_id;
      IF done THEN
        LEAVE read_loop2;
      END IF;

      SET cur_date = start_date;
      WHILE cur_date < end_date DO
        UPDATE room_occupancy
        SET booked_rooms = GREATEST(booked_rooms - 1, 0)
        WHERE room_type_id = v_room_type_id AND date = cur_date;
        SET cur_date = DATE_ADD(cur_date, INTERVAL 1 DAY);
      END WHILE;

    END LOOP;
    CLOSE cur;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_extensions`
--

CREATE TABLE `booking_extensions` (
  `extension_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `new_check_out_date` date NOT NULL,
  `additional_fee_vnd` decimal(15,2) DEFAULT '0.00',
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_reschedules`
--

CREATE TABLE `booking_reschedules` (
  `reschedule_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `new_check_in_date` date NOT NULL,
  `new_check_out_date` date NOT NULL,
  `additional_fee_vnd` decimal(15,2) DEFAULT '0.00',
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_rooms`
--

CREATE TABLE `booking_rooms` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `booking_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `option_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `option_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `option_price` decimal(15,2) DEFAULT NULL,
  `representative_id` int DEFAULT NULL,
  `adults` int DEFAULT NULL,
  `children` int DEFAULT NULL,
  `children_age` json DEFAULT NULL,
  `price_per_night` bigint NOT NULL,
  `nights` int NOT NULL,
  `total_price` bigint NOT NULL,
  `check_in_date` date DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `booking_rooms`
--

INSERT INTO `booking_rooms` (`id`, `booking_id`, `booking_code`, `room_id`, `option_id`, `option_name`, `option_price`, `representative_id`, `adults`, `children`, `children_age`, `price_per_night`, `nights`, `total_price`, `check_in_date`, `check_out_date`, `created_at`, `updated_at`) VALUES
(17, 23, 'LAVISHSTAY_509999', 255, NULL, NULL, NULL, 21, 0, NULL, NULL, 1200000, 1, 1200000, '2025-07-01', '2025-07-02', '2025-07-01 04:08:52', '2025-07-01 04:08:52'),
(18, 23, 'LAVISHSTAY_509999', 256, NULL, NULL, NULL, 22, 0, NULL, NULL, 1200000, 1, 1200000, '2025-07-01', '2025-07-02', '2025-07-01 04:08:52', '2025-07-01 04:08:52'),
(19, 25, 'LVS20250707030928246', 1, NULL, NULL, NULL, 23, 0, NULL, NULL, 1440000, -1, -1440000, '2025-07-07', '2025-07-08', '2025-07-06 20:09:28', '2025-07-06 20:09:28'),
(20, 26, 'LVS20250707031018433', 15, NULL, NULL, NULL, 24, 0, NULL, NULL, 1440000, -4, -5760000, '2025-07-07', '2025-07-11', '2025-07-06 20:10:18', '2025-07-06 20:10:18'),
(21, 27, 'LVS20250707031110789', 15, NULL, NULL, NULL, 25, 0, NULL, NULL, 1440000, -4, -5760000, '2025-07-07', '2025-07-11', '2025-07-06 20:11:10', '2025-07-06 20:11:10'),
(24, 31, 'LVS31050405', 1, NULL, NULL, NULL, NULL, 0, NULL, NULL, 2000000, 1, 2000000, '2025-07-09', '2025-07-10', '2025-07-07 22:04:05', '2025-07-07 22:04:05'),
(25, 32, 'LVS32050513', 1, NULL, NULL, NULL, NULL, 0, NULL, NULL, 2000000, 1, 2000000, '2025-07-09', '2025-07-10', '2025-07-07 22:05:13', '2025-07-07 22:05:13'),
(26, 33, 'LVS33050538', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 2000000, 1, 2000000, '2025-07-09', '2025-07-10', '2025-07-07 22:05:38', '2025-07-07 22:05:38'),
(27, 34, 'LVS34050642', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 3200000, 1, 3200000, '2025-07-07', '2025-07-08', '2025-07-07 22:06:42', '2025-07-07 22:06:42'),
(28, 35, 'LVS35050702', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 3200000, 1, 3200000, '2025-07-07', '2025-07-08', '2025-07-07 22:07:02', '2025-07-07 22:07:02'),
(29, 36, 'LVS36050814', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 3200000, 1, 3200000, '2025-07-07', '2025-07-08', '2025-07-07 22:08:14', '2025-07-07 22:08:14'),
(30, 37, 'LVS37051044', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 2300000, 1, 2300000, '2025-07-07', '2025-07-08', '2025-07-07 22:10:44', '2025-07-07 22:10:44'),
(31, 38, 'LVS38051120', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 2300000, 1, 2300000, '2025-07-07', '2025-07-08', '2025-07-07 22:11:20', '2025-07-07 22:11:20'),
(32, 39, 'LVS39063958', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 6200000, 1, 6200000, '2025-07-07', '2025-07-08', '2025-07-07 23:39:58', '2025-07-07 23:39:58'),
(33, 40, 'LVS40064052', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 6200000, 1, 6200000, '2025-07-07', '2025-07-08', '2025-07-07 23:40:52', '2025-07-07 23:40:52'),
(34, 41, 'LVS41073901', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 00:39:01', '2025-07-08 00:39:01'),
(35, 42, 'LVS42074259', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 00:42:59', '2025-07-08 00:42:59'),
(36, 43, 'LVS43074325', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 00:43:25', '2025-07-08 00:43:25'),
(37, 44, 'LVS44074700', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 2440000, 1, 2440000, '2025-07-07', '2025-07-08', '2025-07-08 00:47:00', '2025-07-08 00:47:00'),
(38, 44, 'LVS44074700', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 2440000, 1, 2440000, '2025-07-07', '2025-07-08', '2025-07-08 00:47:00', '2025-07-08 00:47:00'),
(39, 44, 'LVS44074700', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 2440000, 1, 2440000, '2025-07-07', '2025-07-08', '2025-07-08 00:47:00', '2025-07-08 00:47:00'),
(40, 45, 'LVS45075351', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 00:53:51', '2025-07-08 00:53:51'),
(41, 45, 'LVS45075351', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 00:53:51', '2025-07-08 00:53:51'),
(42, 45, 'LVS45075351', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 00:53:51', '2025-07-08 00:53:51'),
(43, 45, 'LVS45075351', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 00:53:51', '2025-07-08 00:53:51'),
(44, 45, 'LVS45075351', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 00:53:51', '2025-07-08 00:53:51'),
(45, 45, 'LVS45075351', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 00:53:51', '2025-07-08 00:53:51'),
(46, 46, 'LVS46075512', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 00:55:12', '2025-07-08 00:55:12'),
(47, 51, 'LVS51083510', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 01:35:10', '2025-07-08 01:35:10'),
(48, 51, 'LVS51083510', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 01:35:10', '2025-07-08 01:35:10'),
(54, 56, 'LVS56094825', 255, 'OPT8', 'Deluxe King Room', 1200000.00, NULL, 2, 3, '[8, 10, 5]', 1200000, 1, 1200000, '2025-07-09', '2025-07-10', '2025-07-08 02:48:25', '2025-07-08 02:48:25'),
(58, 60, 'LVS60104819', 1, 'BOOK-LVS60104819-R1-1', 'Standard Package', 1440000.00, NULL, 2, 3, '[8, 8, 8]', 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 03:48:19', '2025-07-08 03:48:19'),
(59, 60, 'LVS60104819', 1, 'BOOK-LVS60104819-R1-2', 'Standard Package', 1440000.00, NULL, 4, 2, '[8, 8]', 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 03:48:19', '2025-07-08 03:48:19'),
(60, 60, 'LVS60104819', 1, 'BOOK-LVS60104819-R1-3', 'Standard Package', 1440000.00, NULL, 2, 0, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 03:48:19', '2025-07-08 03:48:19'),
(61, 61, 'LVS61105534', 1, 'BOOK-LVS61105534-R1-1', 'Standard Package', 1440000.00, NULL, 2, 3, '[8, 8, 8]', 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 03:55:34', '2025-07-08 03:55:34'),
(62, 61, 'LVS61105534', 1, 'BOOK-LVS61105534-R1-2', 'Standard Package', 1440000.00, NULL, 4, 2, '[8, 8]', 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 03:55:34', '2025-07-08 03:55:34'),
(63, 61, 'LVS61105534', 1, 'BOOK-LVS61105534-R1-3', 'Standard Package', 1440000.00, NULL, 2, 0, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 03:55:34', '2025-07-08 03:55:34'),
(64, 62, 'LVS62153758', 1, 'BOOK-LVS62153758-R1-1', 'Standard Package', 1440000.00, NULL, 2, 3, '[8, 8, 8]', 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 08:37:58', '2025-07-08 08:37:58'),
(65, 62, 'LVS62153758', 1, 'BOOK-LVS62153758-R1-2', 'Standard Package', 1440000.00, NULL, 4, 2, '[8, 8]', 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 08:37:58', '2025-07-08 08:37:58'),
(66, 62, 'LVS62153758', 1, 'BOOK-LVS62153758-R1-3', 'Standard Package', 1440000.00, NULL, 2, 0, NULL, 1440000, 1, 1440000, '2025-07-07', '2025-07-08', '2025-07-08 08:37:58', '2025-07-08 08:37:58'),
(67, 63, 'LVS63162115', 1, 'BOOK-LVS63162115-R1-1', 'Standard Package', 11000.00, NULL, 2, 0, NULL, 11000, 1, 11000, '2025-07-07', '2025-07-08', '2025-07-08 09:21:15', '2025-07-08 09:21:15'),
(68, 64, 'LVS64164554', 1, 'BOOK-LVS64164554-R1-1', 'Standard Package', 11000.00, NULL, 2, 0, NULL, 11000, 1, 11000, '2025-07-07', '2025-07-08', '2025-07-08 09:45:54', '2025-07-08 09:45:54'),
(69, 65, 'LVS65165011', 1, 'BOOK-LVS65165011-R1-1', 'Standard Package', 11000.00, NULL, 2, 0, NULL, 11000, 1, 11000, '2025-07-07', '2025-07-08', '2025-07-08 09:50:11', '2025-07-08 09:50:11'),
(70, 66, 'LVS66165335', 1, 'BOOK-LVS66165335-R1-1', 'Standard Package', 11000.00, NULL, 2, 0, NULL, 11000, 1, 11000, '2025-07-07', '2025-07-08', '2025-07-08 09:53:35', '2025-07-08 09:53:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cancellation_policies`
--

CREATE TABLE `cancellation_policies` (
  `policy_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `free_cancellation_days` int DEFAULT NULL COMMENT 'Số ngày trước check-in được hủy miễn phí',
  `penalty_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Phạt hủy (%)',
  `penalty_fixed_amount_vnd` decimal(15,2) DEFAULT NULL COMMENT 'Phạt hủy cố định (VND)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cancellation_policies`
--

INSERT INTO `cancellation_policies` (`policy_id`, `name`, `free_cancellation_days`, `penalty_percentage`, `penalty_fixed_amount_vnd`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Hủy miễn phí 7 ngày', 7, 0.00, 200000.00, 'Hủy miễn phí nếu trước 7 ngày', 1, '2025-06-11 02:26:26', '2025-06-11 01:24:04'),
(2, 'Hủy có phí', 2, 50.00, NULL, 'Phạt 50% nếu hủy trong vòng 2 ngày', 0, '2025-06-11 02:26:26', '2025-06-13 00:23:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `check_out_policies`
--

CREATE TABLE `check_out_policies` (
  `policy_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `early_check_out_fee_vnd` decimal(15,2) DEFAULT NULL,
  `late_check_out_fee_vnd` decimal(15,2) DEFAULT NULL,
  `late_check_out_max_hours` int DEFAULT NULL COMMENT 'Số giờ tối đa trả phòng muộn',
  `early_check_out_max_hours` int DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `check_out_policies`
--

INSERT INTO `check_out_policies` (`policy_id`, `name`, `early_check_out_fee_vnd`, `late_check_out_fee_vnd`, `late_check_out_max_hours`, `early_check_out_max_hours`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Trả phòng muộn sau 4 giờ', 0.00, 200000.00, 4, NULL, 'Phí 200,000 VND nếu trả phòng muộn tối đa 4 giờ', 1, '2025-06-11 02:36:00', '2025-06-16 18:37:57'),
(3, 'e', NULL, 0.00, NULL, 4, 'e', 1, '2025-06-16 18:40:33', '2025-06-16 18:40:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `check_out_requests`
--

CREATE TABLE `check_out_requests` (
  `request_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `requested_check_out_time` datetime NOT NULL,
  `fee_vnd` decimal(15,2) DEFAULT '0.00',
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `currency`
--

CREATE TABLE `currency` (
  `currency_code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Khóa chính, mã tiền tệ (VND, USD, v.v.)',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên tiền tệ',
  `exchange_rate` decimal(10,4) DEFAULT NULL COMMENT 'Tỷ giá so với VND',
  `symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Ký hiệu tiền tệ (₫, $, v.v.)',
  `format` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Định dạng (ví dụ: {amount} ₫)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin tiền tệ và tỷ giá';

--
-- Đang đổ dữ liệu cho bảng `currency`
--

INSERT INTO `currency` (`currency_code`, `name`, `exchange_rate`, `symbol`, `format`) VALUES
('USD', 'US Dollar', 0.0000, '$', '${amount}'),
('VND', 'Vietnamese Dong', 1.0000, '₫', '{amount} ₫');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `datafeeds`
--

CREATE TABLE `datafeeds` (
  `id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` float DEFAULT NULL,
  `dataset_name` tinyint DEFAULT NULL,
  `data_type` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `deposit_policies`
--

CREATE TABLE `deposit_policies` (
  `policy_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deposit_percentage` decimal(5,2) DEFAULT NULL,
  `deposit_fixed_amount_vnd` decimal(15,2) DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `deposit_policies`
--

INSERT INTO `deposit_policies` (`policy_id`, `name`, `deposit_percentage`, `deposit_fixed_amount_vnd`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Đặt cọc 50%', 50.00, NULL, 'Yêu cầu đặt cọc 50% tổng giá', 1, '2025-06-11 02:24:24', '2025-06-11 02:24:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dynamic_pricing_rules`
--

CREATE TABLE `dynamic_pricing_rules` (
  `rule_id` int NOT NULL,
  `room_type_id` int DEFAULT NULL,
  `occupancy_threshold` decimal(5,2) NOT NULL COMMENT 'Ngưỡng tỷ lệ lấp đầy (%)',
  `price_adjustment` decimal(5,2) NOT NULL COMMENT 'Tỷ lệ điều chỉnh giá (%)',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `priority` int NOT NULL DEFAULT '5' COMMENT 'Mức độ ưu tiên (1 là cao nhất)',
  `is_exclusive` tinyint(1) DEFAULT '0' COMMENT 'Quy tắc độc quyền khi bật flag'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dynamic_pricing_rules`
--

INSERT INTO `dynamic_pricing_rules` (`rule_id`, `room_type_id`, `occupancy_threshold`, `price_adjustment`, `is_active`, `created_at`, `updated_at`, `priority`, `is_exclusive`) VALUES
(1, 1, 80.00, 10.00, 1, '2025-06-11 02:44:33', '2025-06-29 19:50:57', 5, 0),
(2, 1, 90.00, 20.00, 1, '2025-06-11 02:44:33', '2025-06-11 02:44:33', 5, 0),
(3, 2, 80.00, 20.00, 1, '2025-06-14 03:41:08', '2025-06-14 03:42:03', 5, 0),
(4, 2, 90.00, 30.00, 1, '2025-06-14 04:17:32', '2025-06-14 04:17:32', 5, 0),
(5, 4, 70.00, 7.00, 1, '2025-06-29 21:11:10', '2025-06-29 21:11:21', 5, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `events`
--

CREATE TABLE `events` (
  `event_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `events`
--

INSERT INTO `events` (`event_id`, `name`, `start_date`, `end_date`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Lễ hội pháo hoa Đà Nẵng', '2025-06-28', '2025-07-07', 'Sự kiện pháo hoa quốc tế', 1, '2025-06-11 02:20:27', '2025-06-29 07:11:13'),
(3, 'Sự kiện có 1 0 2', '2025-06-30', '2025-07-01', '102', 1, '2025-06-29 11:36:55', '2025-06-29 11:36:55'),
(4, 'Nguyễn Anh Đức', '2025-07-02', '2025-07-03', 't', 1, '2025-06-29 09:45:40', '2025-06-29 09:45:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `faqs`
--

CREATE TABLE `faqs` (
  `faq_id` int NOT NULL,
  `question_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Câu hỏi (tiếng Anh)',
  `question_vi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Câu hỏi (tiếng Việt)',
  `answer_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Câu trả lời (tiếng Anh)',
  `answer_vi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Câu trả lời (tiếng Việt)',
  `sort_order` int DEFAULT '0' COMMENT 'Thứ tự sắp xếp câu hỏi',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động (0: không, 1: có)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo câu hỏi',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật câu hỏi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu trữ câu hỏi thường gặp và câu trả lời';

--
-- Đang đổ dữ liệu cho bảng `faqs`
--

INSERT INTO `faqs` (`faq_id`, `question_en`, `question_vi`, `answer_en`, `answer_vi`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Do you serve breakfast?', 'Họ có phục vụ bữa sáng không?', 'Yes, we offer an excellent buffet breakfast from 6:30 AM to 10:30 AM daily with both international and Vietnamese cuisine.', 'Có, chúng tôi cung cấp bữa sáng buffet tuyệt hảo từ 6:30 đến 10:30 hàng ngày với ẩm thực quốc tế và Việt Nam.', 5, 1, '2025-05-23 02:50:42', '2025-06-02 03:21:17'),
(2, 'Is parking available?', 'Chỗ nghỉ có chỗ đỗ xe không?', 'Yes, we provide complimentary self-parking for hotel guests. Valet parking is also available for an additional charge.', 'Có, chúng tôi cung cấp chỗ đỗ xe tự phục vụ miễn phí cho khách khách sạn. Dịch vụ đỗ xe có người phục vụ cũng có sẵn với phí bổ sung.', 3, 1, '2025-05-23 02:50:42', '2025-06-02 03:33:00'),
(3, 'Do you provide airport shuttle service?', 'Chỗ nghỉ có dịch vụ đưa đón sân bay không?', 'Yes, we offer airport transfer service for $25 per trip. Please contact our concierge to arrange your transfer.', 'Có, chúng tôi cung cấp dịch vụ đưa đón sân bay với giá $25 mỗi chuyến. Vui lòng liên hệ với lễ tân để sắp xếp chuyến đi.', 10, 1, '2025-05-23 02:50:42', '2025-06-02 03:33:16'),
(4, 'What is your WiFi ?', 'Chỗ nghỉ có  Wi-Fi ra sao?', 'High-speed WiFi is complimentary throughout the hotel including all guest rooms and public areas.', 'Wi-Fi tốc độ cao miễn phí trong toàn bộ khách sạn bao gồm tất cả các phòng khách và khu vực công cộng.', 0, 1, '2025-05-23 02:50:42', '2025-06-12 00:57:25'),
(7, 'Am i handsome?', 'Tôi có đẹp trai không?', 'Yes Sirrrrr', 'Chắc chắn  rồi broooo', 2, 1, '2025-06-02 02:14:43', '2025-06-02 03:01:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `flexible_pricing_rules`
--

CREATE TABLE `flexible_pricing_rules` (
  `rule_id` int NOT NULL,
  `room_type_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã loại phòng (NULL: áp dụng cho tất cả loại phòng)',
  `rule_type` enum('weekend','event','holiday','season') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại quy tắc: cuối tuần, sự kiện, ngày lễ, mùa',
  `days_of_week` json DEFAULT NULL COMMENT 'Danh sách ngày cuối tuần áp dụng (JSON, dùng cho rule_type=weekend)',
  `event_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã sự kiện (dùng cho rule_type=event)',
  `holiday_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã ngày lễ (dùng cho rule_type=holiday)',
  `season_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tên mùa (dùng cho rule_type=season)',
  `start_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu áp dụng (dùng cho rule_type=season hoặc giới hạn thời gian)',
  `end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc áp dụng (dùng cho rule_type=season hoặc giới hạn thời gian)',
  `price_adjustment` decimal(5,2) NOT NULL COMMENT 'Tỷ lệ điều chỉnh giá (%, dương để tăng, âm để giảm)',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái kích hoạt',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `priority` int NOT NULL DEFAULT '5' COMMENT 'Mức độ ưu tiên (1 là cao nhất)',
  `is_exclusive` tinyint(1) DEFAULT '0' COMMENT 'Quy tắc độc quyền khi bật flag'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Quy tắc giá linh động cho cuối tuần, sự kiện, ngày lễ, mùa';

--
-- Đang đổ dữ liệu cho bảng `flexible_pricing_rules`
--

INSERT INTO `flexible_pricing_rules` (`rule_id`, `room_type_id`, `rule_type`, `days_of_week`, `event_id`, `holiday_id`, `season_name`, `start_date`, `end_date`, `price_adjustment`, `is_active`, `created_at`, `updated_at`, `priority`, `is_exclusive`) VALUES
(4, NULL, 'holiday', NULL, NULL, 1, NULL, NULL, NULL, 30.00, 0, '2025-06-23 03:03:08', '2025-06-29 02:34:28', 1, 1),
(5, NULL, 'season', NULL, NULL, NULL, 'Mùa cao điểm', '2025-06-01', '2025-08-31', 20.00, 1, '2025-06-23 03:03:08', '2025-06-26 14:47:48', 3, 0),
(12, NULL, 'weekend', '\"[\\\"Saturday\\\",\\\"Sunday\\\"]\"', NULL, NULL, NULL, NULL, NULL, 10.00, 1, '2025-06-23 00:45:55', '2025-06-29 07:29:36', 4, 0),
(18, NULL, 'event', NULL, 3, NULL, NULL, '2025-06-30', '2025-07-01', -6.00, 1, '2025-06-29 05:02:45', '2025-06-29 09:09:13', 5, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `floors`
--

CREATE TABLE `floors` (
  `floor_id` int NOT NULL COMMENT 'Khóa chính, mã tầng',
  `floor_number` int NOT NULL COMMENT 'Số tầng (1-34)',
  `floor_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên tầng (Tầng trệt, Tầng 1, etc.)',
  `floor_type` enum('ground','residential','service','special','penthouse') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'residential' COMMENT 'Loại tầng',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả tầng và tiện ích đặc biệt',
  `facilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Các tiện ích có trên tầng này',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Tầng có hoạt động không',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng quản lý các tầng của khách sạn';

--
-- Đang đổ dữ liệu cho bảng `floors`
--

INSERT INTO `floors` (`floor_id`, `floor_number`, `floor_name`, `floor_type`, `description`, `facilities`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tầng Trệt', 'ground', 'Tầng tiếp đón khách và hội nghị', 'Lobby, Lobby Bar, Reception, Ballroom (900 khách), 3 phòng họp (50 khách mỗi phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(2, 2, 'Tầng 2', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(3, 3, 'Tầng 3', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(4, 4, 'Tầng 4', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(5, 5, 'Tầng 5', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(6, 6, 'Tầng 6', 'service', 'Tầng nhà hàng', 'Orchid Restaurant - Buffet Á-Âu (260 khách)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(7, 7, 'Tầng 7', 'service', 'Tầng tiện ích thể thao', 'Hồ bơi trong nhà (6:00-20:00), Spa YHI, Phòng gym (6:00-22:00)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(8, 8, 'Tầng 8', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(9, 9, 'Tầng 9', 'residential', 'Tầng phòng nghỉ', 'Deluxe Room (15 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(10, 10, 'Tầng 10', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(11, 11, 'Tầng 11', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(12, 12, 'Tầng 12', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(13, 13, 'Tầng 13', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(14, 14, 'Tầng 14', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(15, 15, 'Tầng 15', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(16, 16, 'Tầng 16', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(17, 17, 'Tầng 17', 'residential', 'Tầng phòng nghỉ cao cấp', 'Premium Corner (12 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(18, 18, 'Tầng 18', 'residential', 'Tầng The Level Premium', 'The Level Premium (12 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(19, 19, 'Tầng 19', 'residential', 'Tầng The Level Premium', 'The Level Premium (12 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(20, 20, 'Tầng 20', 'residential', 'Tầng The Level Premium', 'The Level Premium (12 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(21, 21, 'Tầng 21', 'residential', 'Tầng The Level Premium Corner', 'The Level Premium Corner (8 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(22, 22, 'Tầng 22', 'residential', 'Tầng The Level Premium Corner', 'The Level Premium Corner (8 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(23, 23, 'Tầng 23', 'residential', 'Tầng The Level Premium Corner', 'The Level Premium Corner (8 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(24, 24, 'Tầng 24', 'residential', 'Tầng The Level Premium Corner', 'The Level Premium Corner (8 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(25, 25, 'Tầng 25', 'residential', 'Tầng The Level Suite', 'The Level Suite (7 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(26, 26, 'Tầng 26', 'residential', 'Tầng The Level Suite', 'The Level Suite (7 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(27, 27, 'Tầng 27', 'residential', 'Tầng The Level Suite', 'The Level Suite (6 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(28, 28, 'Tầng 28', 'residential', 'Tầng Suite', 'Suite (5 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(29, 29, 'Tầng 29', 'residential', 'Tầng Suite', 'Suite (5 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(30, 30, 'Tầng 30', 'residential', 'Tầng Suite', 'Suite (5 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(31, 31, 'Tầng 31', 'residential', 'Tầng Suite', 'Suite (5 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(32, 32, 'Tầng 32', 'penthouse', 'Tầng Presidential Suite', 'Presidential Suite (1 phòng)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(33, 33, 'Tầng 33', 'special', 'Tầng Panoramic Lounge', 'Panoramic Lounge VIP (36 khách) - Chỉ dành cho khách The Level', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(34, 34, 'Tầng 34', 'special', 'Tầng cao nhất', 'Lotus Restaurant (A la carte), SkyView Bar (360° view), Sảnh sự kiện ngoài trời (300 khách)', 1, '2025-06-24 12:00:15', '2025-06-24 12:00:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `guests`
--

CREATE TABLE `guests` (
  `guest_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `gender` enum('male','female','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dob` date DEFAULT NULL COMMENT 'Ngày sinh',
  `nationality` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Quốc tịch',
  `passport_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số hộ chiếu / CMND',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `holidays`
--

CREATE TABLE `holidays` (
  `holiday_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `holidays`
--

INSERT INTO `holidays` (`holiday_id`, `name`, `start_date`, `end_date`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Tết Nguyên Đán', '2025-01-29', '2025-01-31', 'Tết Âm lịch Việt Nam', 1, '2025-06-11 02:21:39', '2025-06-13 09:05:57'),
(2, 'Quốc khánh', '2025-09-02', NULL, 'Ngày Quốc khánh Việt Nam', 1, '2025-06-11 02:21:39', '2025-06-11 02:21:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hotel`
--

CREATE TABLE `hotel` (
  `hotel_id` int NOT NULL COMMENT 'Khóa chính, mã khách sạn',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên khách sạn',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Địa chỉ khách sạn',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả khách sạn'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin khách sạn';

--
-- Đang đổ dữ liệu cho bảng `hotel`
--

INSERT INTO `hotel` (`hotel_id`, `name`, `address`, `description`) VALUES
(1, 'Mường Thanh Thanh Hóa', 'Thanh Hóa', 'Khách sạn Mường Thanh');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hotel_rating`
--

CREATE TABLE `hotel_rating` (
  `hotel_id` int NOT NULL COMMENT 'Khóa chính, mã khách sạn',
  `overall_rating` decimal(3,1) NOT NULL COMMENT 'Điểm đánh giá tổng thể',
  `total_reviews` int NOT NULL COMMENT 'Tổng số lượt đánh giá',
  `rating_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mô tả đánh giá',
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Vị trí khách sạn',
  `cleanliness` decimal(3,1) DEFAULT NULL COMMENT 'Đánh giá sự sạch sẽ',
  `location_rating` decimal(3,1) DEFAULT NULL COMMENT 'Đánh giá vị trí',
  `facilities` decimal(3,1) DEFAULT NULL COMMENT 'Đánh giá cơ sở vật chất',
  `service` decimal(3,1) DEFAULT NULL COMMENT 'Đánh giá dịch vụ',
  `value_for_money` decimal(3,1) DEFAULT NULL COMMENT 'Đánh giá giá trị đồng tiền'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu đánh giá tổng quan khách sạn';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `language`
--

CREATE TABLE `language` (
  `language_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Khóa chính, mã ngôn ngữ (vi, en, v.v.)',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên ngôn ngữ (Vietnamese, English, v.v.)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách ngôn ngữ hỗ trợ';

--
-- Đang đổ dữ liệu cho bảng `language`
--

INSERT INTO `language` (`language_code`, `name`) VALUES
('en_EN', 'English'),
('vi-VN', 'Vietnamese');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `meal_types`
--

CREATE TABLE `meal_types` (
  `id` int NOT NULL COMMENT 'Khóa chính',
  `type_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên loại bữa ăn',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả bữa ăn',
  `base_price_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Giá cơ bản (VND)',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Danh sách các loại bữa ăn';

--
-- Đang đổ dữ liệu cho bảng `meal_types`
--

INSERT INTO `meal_types` (`id`, `type_name`, `description`, `base_price_vnd`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Không', 'Khôngg', 0.00, 1, '2025-06-09 20:37:04', '2025-06-09 20:37:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_03_23_163443_create_sessions_table', 1),
(6, '2022_05_11_154250_create_datafeeds_table', 1),
(7, '2025_06_12_074026_create_table_translation_table', 2),
(8, '2025_06_16_014026_change_type_column_in_checkout_requests', 3),
(9, '2025_06_16_094513_add_early_checkout_fields_to_check_out_policies_table', 3),
(10, '2025_06_21_100528_fix_bookings_room_foreign_keys', 4),
(11, '2025_07_01_075329_add_booking_code_to_booking_rooms_and_representatives', 5),
(12, '2025_07_08_092023_add_option_id_to_booking_rooms_table', 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('cancellation','extension','reschedule','transfer','check_out') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','sent','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment`
--

CREATE TABLE `payment` (
  `payment_id` int NOT NULL COMMENT 'Khóa chính, mã thanh toán',
  `booking_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã đặt phòng',
  `amount_vnd` decimal(15,2) NOT NULL COMMENT 'Số tiền thanh toán (VND)',
  `payment_type` enum('deposit','full','qr_code','at_hotel','pay_now_with_vietQR') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại thanh toán',
  `status` enum('pending','completed','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Trạng thái thanh toán',
  `transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mã giao dịch (từ cổng thanh toán)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin thanh toán';

--
-- Đang đổ dữ liệu cho bảng `payment`
--

INSERT INTO `payment` (`payment_id`, `booking_id`, `amount_vnd`, `payment_type`, `status`, `transaction_id`, `created_at`, `updated_at`) VALUES
(1, 31, 2000000.00, 'at_hotel', 'completed', NULL, '2025-07-07 22:04:05', '2025-07-07 22:04:05'),
(2, 32, 2000000.00, 'at_hotel', 'completed', NULL, '2025-07-07 22:05:13', '2025-07-07 22:05:13'),
(3, 33, 2000000.00, 'at_hotel', 'completed', NULL, '2025-07-07 22:05:38', '2025-07-07 22:05:38'),
(4, 34, 3200000.00, 'at_hotel', 'completed', NULL, '2025-07-07 22:06:42', '2025-07-07 22:06:42'),
(5, 35, 3200000.00, 'at_hotel', 'completed', NULL, '2025-07-07 22:07:02', '2025-07-07 22:07:02'),
(6, 36, 3200000.00, 'at_hotel', 'completed', NULL, '2025-07-07 22:08:14', '2025-07-07 22:08:14'),
(7, 37, 2300000.00, 'at_hotel', 'completed', NULL, '2025-07-07 22:10:44', '2025-07-07 22:10:44'),
(8, 38, 2300000.00, 'at_hotel', 'completed', NULL, '2025-07-07 22:11:20', '2025-07-07 22:11:20'),
(9, 39, 6200000.00, 'at_hotel', 'completed', NULL, '2025-07-07 23:39:58', '2025-07-07 23:39:58'),
(10, 40, 6200000.00, 'at_hotel', 'completed', NULL, '2025-07-07 23:40:52', '2025-07-07 23:40:52'),
(11, 41, 1440000.00, 'at_hotel', 'completed', NULL, '2025-07-08 00:39:01', '2025-07-08 00:39:01'),
(12, 42, 1440000.00, 'at_hotel', 'pending', NULL, '2025-07-08 00:42:59', '2025-07-08 00:42:59'),
(13, 43, 1440000.00, 'at_hotel', 'pending', NULL, '2025-07-08 00:43:25', '2025-07-08 00:43:25'),
(14, 44, 7320000.00, 'at_hotel', 'pending', NULL, '2025-07-08 00:47:00', '2025-07-08 00:47:00'),
(15, 45, 8640000.00, 'at_hotel', 'pending', NULL, '2025-07-08 00:53:51', '2025-07-08 00:53:51'),
(16, 46, 1440000.00, 'at_hotel', 'pending', NULL, '2025-07-08 00:55:12', '2025-07-08 00:55:12'),
(17, 51, 2880000.00, 'at_hotel', 'pending', NULL, '2025-07-08 01:35:10', '2025-07-08 01:35:10'),
(18, 53, 4320000.00, 'full', 'pending', NULL, '2025-07-08 02:30:59', '2025-07-08 02:30:59'),
(19, 54, 1200000.00, 'full', 'pending', NULL, '2025-07-08 02:31:18', '2025-07-08 02:31:18'),
(20, 56, 1200000.00, 'full', 'pending', NULL, '2025-07-08 02:48:25', '2025-07-08 02:48:25'),
(21, 60, 4320000.00, 'full', 'pending', NULL, '2025-07-08 03:48:19', '2025-07-08 03:48:19'),
(22, 61, 4320000.00, 'full', 'pending', NULL, '2025-07-08 03:55:34', '2025-07-08 03:55:34'),
(23, 62, 4320000.00, 'full', 'pending', NULL, '2025-07-08 08:37:58', '2025-07-08 08:37:58'),
(24, 63, 11000.00, 'full', 'completed', 'FT25190925069385', '2025-07-08 09:21:15', '2025-07-08 09:45:21'),
(25, 64, 11000.00, 'full', 'completed', 'FT25190115612302', '2025-07-08 09:45:54', '2025-07-08 09:46:51'),
(26, 65, 11000.00, 'full', 'completed', 'FT25190175100273', '2025-07-08 09:50:11', '2025-07-08 09:50:54'),
(27, 66, 11000.00, 'full', 'completed', 'FT25190456258100', '2025-07-08 09:53:35', '2025-07-08 09:54:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`, `updated_at`, `parent_id`) VALUES
(1, 'quan_ly_user', 'Quản lý user', '2025-06-25 03:58:44', '2025-06-25 03:58:44', NULL),
(2, 'quan_ly_nhan_vien', 'Quản lý nhân viên', '2025-06-25 03:58:44', '2025-06-25 03:58:52', 1),
(3, 'quan_ly_khach_hang', 'Quản lý khách hàng', '2025-06-25 03:58:44', '2025-06-25 03:58:55', 1),
(4, 'vai_tro_&&_quyen', 'Quản lý vai trò và phân quyền', '2025-06-25 04:03:48', '2025-06-25 04:03:48', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pricing_config`
--

CREATE TABLE `pricing_config` (
  `config_id` int NOT NULL,
  `max_price_increase_percentage` decimal(5,2) DEFAULT '40.00' COMMENT 'Giới hạn tăng giá tối đa (%)',
  `max_absolute_price_vnd` decimal(15,2) DEFAULT '3000000.00' COMMENT 'Giới hạn giá trần tuyệt đối (VND)',
  `use_exclusive_rule` tinyint(1) DEFAULT '0' COMMENT 'Bật chế độ chỉ lấy quy tắc ưu tiên cao nhất',
  `exclusive_rule_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Loại quy tắc độc quyền (event, holiday, season, weekend, occupancy)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `pricing_config`
--

INSERT INTO `pricing_config` (`config_id`, `max_price_increase_percentage`, `max_absolute_price_vnd`, `use_exclusive_rule`, `exclusive_rule_type`, `created_at`, `updated_at`) VALUES
(1, 40.00, 3000000.00, 0, NULL, '2025-06-26 14:47:48', '2025-06-26 14:47:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pricing_history`
--

CREATE TABLE `pricing_history` (
  `history_id` bigint UNSIGNED NOT NULL,
  `room_type_id` int UNSIGNED DEFAULT NULL COMMENT 'Khóa ngoại, mã loại phòng',
  `option_id` int UNSIGNED DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn phòng',
  `applied_date` date NOT NULL COMMENT 'Ngày áp dụng giá',
  `base_price` decimal(12,2) NOT NULL COMMENT 'Giá gốc',
  `adjusted_price` decimal(12,2) NOT NULL COMMENT 'Giá sau điều chỉnh',
  `applied_rules` json DEFAULT NULL COMMENT 'Danh sách quy tắc đã áp dụng (JSON)',
  `occupancy_rate` decimal(5,2) DEFAULT NULL COMMENT 'Tỷ lệ lấp đầy tại thời điểm áp dụng (%)',
  `pricing_mechanism` enum('cumulative','highest_priority','exclusive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cumulative' COMMENT 'Cơ chế tính giá',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú bổ sung',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `representatives`
--

CREATE TABLE `representatives` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `booking_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_id` int NOT NULL,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_card` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `representatives`
--

INSERT INTO `representatives` (`id`, `booking_id`, `booking_code`, `room_id`, `full_name`, `phone_number`, `email`, `id_card`, `created_at`, `updated_at`) VALUES
(21, 23, 'LAVISHSTAY_509999', 255, 'qeweqw', '0335920306', 'quyenjpn@gmail.com', 'qweqweqweqwe', '2025-07-01 04:08:52', '2025-07-01 04:08:52'),
(22, 23, 'LAVISHSTAY_509999', 256, 'qeweqw', '0335920306', 'quyenjpn@gmail.com', 'qweqweqweqwe', '2025-07-01 04:08:52', '2025-07-01 04:08:52'),
(23, 25, 'LVS20250707030928246', 1, 'qeweqw', '0335920306', 'reception@hotel.com', 'qweqweqweqwe', '2025-07-06 20:09:28', '2025-07-06 20:09:28'),
(24, 26, 'LVS20250707031018433', 15, 'qeweqw', '0335920306', 'quyenjpn@gmail.com', 'qweqweqweqwe', '2025-07-06 20:10:18', '2025-07-06 20:10:18'),
(25, 27, 'LVS20250707031110789', 15, 'qeweqw', '0335920306', 'quyenjpn@gmail.com', 'qweqweqweqwe', '2025-07-06 20:11:10', '2025-07-06 20:11:10'),
(27, 31, 'LVS31050405', 1, 'Nguyen Van Test', '0123456789', 'test@email.com', '123456789', '2025-07-07 22:04:05', '2025-07-07 22:04:05'),
(28, 32, 'LVS32050513', 1, 'Nguyen Van Test', '0123456789', 'test@email.com', '123456789', '2025-07-07 22:05:13', '2025-07-07 22:05:13'),
(29, 33, 'LVS33050538', 1, 'Nguyen Van Test', '0123456789', 'test@email.com', '123456789', '2025-07-07 22:05:38', '2025-07-07 22:05:38'),
(30, 34, 'LVS34050642', 6, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-07 22:06:42', '2025-07-07 22:06:42'),
(31, 35, 'LVS35050702', 6, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-07 22:07:02', '2025-07-07 22:07:02'),
(32, 36, 'LVS36050814', 6, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-07 22:08:14', '2025-07-07 22:08:14'),
(33, 37, 'LVS37051044', 2, 'qeweqw', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-07 22:10:44', '2025-07-07 22:10:44'),
(34, 38, 'LVS38051120', 2, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-07 22:11:20', '2025-07-07 22:11:20'),
(35, 39, 'LVS39063958', 7, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-07 23:39:58', '2025-07-07 23:39:58'),
(36, 40, 'LVS40064052', 7, 'qeweqw', '0987654321', 'reception@hotel.com', '', '2025-07-07 23:40:52', '2025-07-07 23:40:52'),
(37, 41, 'LVS41073901', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:39:01', '2025-07-08 00:39:01'),
(38, 42, 'LVS42074259', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:42:59', '2025-07-08 00:42:59'),
(39, 43, 'LVS43074325', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:43:25', '2025-07-08 00:43:25'),
(40, 44, 'LVS44074700', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:47:00', '2025-07-08 00:47:00'),
(41, 45, 'LVS45075351', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:53:51', '2025-07-08 00:53:51'),
(42, 46, 'LVS46075512', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:55:12', '2025-07-08 00:55:12'),
(43, 51, 'LVS51083510', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 01:35:10', '2025-07-08 01:35:10'),
(44, 53, 'LVS53093059', 255, 'Nguyen Van Test', '0987654321', 'test@gmail.com', '', '2025-07-08 02:30:59', '2025-07-08 02:30:59'),
(45, 54, 'LVS54093118', 255, 'Test User', '0123456789', 'test@test.com', '', '2025-07-08 02:31:18', '2025-07-08 02:31:18'),
(46, 56, 'LVS56094825', 255, 'Test User Full', '0123456789', 'test@test.com', '', '2025-07-08 02:48:25', '2025-07-08 02:48:25'),
(47, 60, 'LVS60104819', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 03:48:19', '2025-07-08 03:48:19'),
(48, 61, 'LVS61105534', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 03:55:34', '2025-07-08 03:55:34'),
(49, 62, 'LVS62153758', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 08:37:58', '2025-07-08 08:37:58'),
(50, 63, 'LVS63162115', 1, 'Huỳnh Thị Bích Tuyền', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 09:21:15', '2025-07-08 09:21:15'),
(51, 64, 'LVS64164554', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 09:45:54', '2025-07-08 09:45:54'),
(52, 65, 'LVS65165011', 1, 'qeweqw', '333241324342', 'quyenjpn@gmail.com', '', '2025-07-08 09:50:11', '2025-07-08 09:50:11'),
(53, 66, 'LVS66165335', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 09:53:35', '2025-07-08 09:53:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Quản trị hệ thống', '2025-06-15 19:52:24', '2025-06-15 19:52:24'),
(2, 'manager', 'Quản lý điều hành', '2025-06-15 19:52:24', '2025-06-15 19:52:24'),
(3, 'receptionist', 'Lễ tân', '2025-06-15 19:52:24', '2025-06-15 19:52:24'),
(4, 'guest', 'Khách hàng thông thường', '2025-06-15 19:52:24', '2025-06-15 19:52:24'),
(9, 'Bảo vệ', 'Bảo vệ an ninh khách sạnn', '2025-06-23 23:19:47', '2025-06-27 07:38:03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_user`
--

CREATE TABLE `role_user` (
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room`
--

CREATE TABLE `room` (
  `room_id` int NOT NULL COMMENT 'Khóa chính, mã phòng',
  `room_type_id` int NOT NULL COMMENT 'Khóa ngoại, mã loại phòng',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên phòng',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh chính',
  `floor_id` int DEFAULT NULL COMMENT 'Tầng của phòng',
  `bed_type_fixed` int DEFAULT NULL COMMENT 'Loại giường mặc định',
  `status` enum('available','occupied','maintenance','cleaning','deposited','no_show','check_in','check_out') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chi tiết phòng',
  `last_cleaned` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin phòng';

--
-- Đang đổ dữ liệu cho bảng `room`
--

INSERT INTO `room` (`room_id`, `room_type_id`, `name`, `image`, `floor_id`, `bed_type_fixed`, `status`, `description`, `last_cleaned`, `created_at`, `updated_at`) VALUES
(1, 1, '0201', NULL, 2, 1, 'deposited', NULL, NULL, '2025-06-24 12:00:15', '2025-07-07 03:14:47'),
(2, 1, '0202', NULL, 2, 2, 'occupied', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(3, 1, '0203', NULL, 2, 1, 'maintenance', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(4, 1, '0204', NULL, 2, 2, 'cleaning', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(5, 1, '0205', NULL, 2, 1, 'deposited', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(6, 1, '0206', NULL, 2, 2, 'no_show', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(7, 1, '0207', NULL, 2, 1, 'check_in', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(8, 1, '0208', NULL, 2, 2, 'check_out', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(9, 1, '0209', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(10, 1, '0210', NULL, 2, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(11, 1, '0211', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(12, 1, '0212', NULL, 2, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(13, 1, '0213', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(14, 1, '0214', NULL, 2, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(15, 1, '0215', NULL, 2, 1, 'deposited', NULL, NULL, '2025-06-24 12:00:15', '2025-07-07 03:11:10'),
(16, 1, '0301', NULL, 3, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(17, 1, '0302', NULL, 3, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(18, 1, '0303', NULL, 3, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(19, 1, '0304', NULL, 3, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(20, 1, '0305', NULL, 3, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(21, 1, '0306', NULL, 3, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(22, 1, '0307', NULL, 3, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(23, 1, '0308', NULL, 3, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(24, 1, '0309', NULL, 3, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(25, 1, '0310', NULL, 3, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(26, 1, '0311', NULL, 3, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(27, 1, '0312', NULL, 3, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(28, 1, '0313', NULL, 3, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(29, 1, '0314', NULL, 3, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(30, 1, '0315', NULL, 3, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(31, 1, '0401', NULL, 4, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(32, 1, '0402', NULL, 4, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(33, 1, '0403', NULL, 4, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(34, 1, '0404', NULL, 4, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(35, 1, '0405', NULL, 4, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(36, 1, '0406', NULL, 4, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(37, 1, '0407', NULL, 4, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(38, 1, '0408', NULL, 4, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(39, 1, '0409', NULL, 4, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(40, 1, '0410', NULL, 4, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(41, 1, '0411', NULL, 4, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(42, 1, '0412', NULL, 4, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(43, 1, '0413', NULL, 4, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(44, 1, '0414', NULL, 4, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(45, 1, '0415', NULL, 4, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-07-07 02:33:21'),
(46, 1, '0501', NULL, 5, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(47, 1, '0502', NULL, 5, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(48, 1, '0503', NULL, 5, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(49, 1, '0504', NULL, 5, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(50, 1, '0505', NULL, 5, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(51, 1, '0506', NULL, 5, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(52, 1, '0507', NULL, 5, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(53, 1, '0508', NULL, 5, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(54, 1, '0509', NULL, 5, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(55, 1, '0510', NULL, 5, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(56, 1, '0511', NULL, 5, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(57, 1, '0512', NULL, 5, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(58, 1, '0513', NULL, 5, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(59, 1, '0514', NULL, 5, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(60, 1, '0515', NULL, 5, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(61, 1, '0801', NULL, 8, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(62, 1, '0802', NULL, 8, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(63, 1, '0803', NULL, 8, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(64, 1, '0804', NULL, 8, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(65, 1, '0805', NULL, 8, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(66, 1, '0806', NULL, 8, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(67, 1, '0807', NULL, 8, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(68, 1, '0808', NULL, 8, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(69, 1, '0809', NULL, 8, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(70, 1, '0810', NULL, 8, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(71, 1, '0811', NULL, 8, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(72, 1, '0812', NULL, 8, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(73, 1, '0813', NULL, 8, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(74, 1, '0814', NULL, 8, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(75, 1, '0815', NULL, 8, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(76, 1, '0901', NULL, 9, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(77, 1, '0902', NULL, 9, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(78, 1, '0903', NULL, 9, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(79, 1, '0904', NULL, 9, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(80, 1, '0905', NULL, 9, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(81, 1, '0906', NULL, 9, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(82, 1, '0907', NULL, 9, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(83, 1, '0908', NULL, 9, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(84, 1, '0909', NULL, 9, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(85, 1, '0910', NULL, 9, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(86, 1, '0911', NULL, 9, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(87, 1, '0912', NULL, 9, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(88, 1, '0913', NULL, 9, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(89, 1, '0914', NULL, 9, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(90, 1, '0915', NULL, 9, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(91, 2, '1001', NULL, 10, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(92, 2, '1002', NULL, 10, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(93, 2, '1003', NULL, 10, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(94, 2, '1004', NULL, 10, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(95, 2, '1005', NULL, 10, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(96, 2, '1006', NULL, 10, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(97, 2, '1007', NULL, 10, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(98, 2, '1008', NULL, 10, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(99, 2, '1009', NULL, 10, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(100, 2, '1010', NULL, 10, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(101, 2, '1011', NULL, 10, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(102, 2, '1012', NULL, 10, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(103, 2, '1101', NULL, 11, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(104, 2, '1102', NULL, 11, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(105, 2, '1103', NULL, 11, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(106, 2, '1104', NULL, 11, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(107, 2, '1105', NULL, 11, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(108, 2, '1106', NULL, 11, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(109, 2, '1107', NULL, 11, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(110, 2, '1108', NULL, 11, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(111, 2, '1109', NULL, 11, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(112, 2, '1110', NULL, 11, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(113, 2, '1111', NULL, 11, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(114, 2, '1112', NULL, 11, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(115, 2, '1201', NULL, 12, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(116, 2, '1202', NULL, 12, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(117, 2, '1203', NULL, 12, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(118, 2, '1204', NULL, 12, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(119, 2, '1205', NULL, 12, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(120, 2, '1206', NULL, 12, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(121, 2, '1207', NULL, 12, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(122, 2, '1208', NULL, 12, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(123, 2, '1209', NULL, 12, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(124, 2, '1210', NULL, 12, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(125, 2, '1211', NULL, 12, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(126, 2, '1212', NULL, 12, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(127, 2, '1301', NULL, 13, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(128, 2, '1302', NULL, 13, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(129, 2, '1303', NULL, 13, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(130, 2, '1304', NULL, 13, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(131, 2, '1305', NULL, 13, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(132, 2, '1306', NULL, 13, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(133, 2, '1307', NULL, 13, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(134, 2, '1308', NULL, 13, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(135, 2, '1309', NULL, 13, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(136, 2, '1310', NULL, 13, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(137, 2, '1311', NULL, 13, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(138, 2, '1312', NULL, 13, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(139, 2, '1401', NULL, 14, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(140, 2, '1402', NULL, 14, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(141, 2, '1403', NULL, 14, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(142, 2, '1404', NULL, 14, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(143, 2, '1405', NULL, 14, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(144, 2, '1406', NULL, 14, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(145, 2, '1407', NULL, 14, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(146, 2, '1408', NULL, 14, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(147, 2, '1409', NULL, 14, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(148, 2, '1410', NULL, 14, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(149, 2, '1411', NULL, 14, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(150, 2, '1412', NULL, 14, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(151, 2, '1501', NULL, 15, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(152, 2, '1502', NULL, 15, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(153, 2, '1503', NULL, 15, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(154, 2, '1504', NULL, 15, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(155, 2, '1505', NULL, 15, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(156, 2, '1506', NULL, 15, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(157, 2, '1507', NULL, 15, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(158, 2, '1508', NULL, 15, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(159, 2, '1509', NULL, 15, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(160, 2, '1510', NULL, 15, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(161, 2, '1511', NULL, 15, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(162, 2, '1512', NULL, 15, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(163, 2, '1601', NULL, 16, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(164, 2, '1602', NULL, 16, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(165, 2, '1603', NULL, 16, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(166, 2, '1604', NULL, 16, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(167, 2, '1605', NULL, 16, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(168, 2, '1606', NULL, 16, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(169, 2, '1607', NULL, 16, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(170, 2, '1608', NULL, 16, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(171, 2, '1609', NULL, 16, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(172, 2, '1610', NULL, 16, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(173, 2, '1611', NULL, 16, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(174, 2, '1612', NULL, 16, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(175, 2, '1701', NULL, 17, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(176, 2, '1702', NULL, 17, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(177, 2, '1703', NULL, 17, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(178, 2, '1704', NULL, 17, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(179, 2, '1705', NULL, 17, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(180, 2, '1706', NULL, 17, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(181, 2, '1707', NULL, 17, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(182, 2, '1708', NULL, 17, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(183, 2, '1709', NULL, 17, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(184, 2, '1710', NULL, 17, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(185, 2, '1711', NULL, 17, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(186, 2, '1712', NULL, 17, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(187, 3, '1801', NULL, 18, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(188, 3, '1802', NULL, 18, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(189, 3, '1803', NULL, 18, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(190, 3, '1804', NULL, 18, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(191, 3, '1805', NULL, 18, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(192, 3, '1806', NULL, 18, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(193, 3, '1807', NULL, 18, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(194, 3, '1808', NULL, 18, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(195, 3, '1809', NULL, 18, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(196, 3, '1810', NULL, 18, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(197, 3, '1811', NULL, 18, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(198, 3, '1812', NULL, 18, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(199, 3, '1901', NULL, 19, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(200, 3, '1902', NULL, 19, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(201, 3, '1903', NULL, 19, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(202, 3, '1904', NULL, 19, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(203, 3, '1905', NULL, 19, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(204, 3, '1906', NULL, 19, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(205, 3, '1907', NULL, 19, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(206, 3, '1908', NULL, 19, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(207, 3, '1909', NULL, 19, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(208, 3, '1910', NULL, 19, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(209, 3, '1911', NULL, 19, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(210, 3, '1912', NULL, 19, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(211, 3, '2001', NULL, 20, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(212, 3, '2002', NULL, 20, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(213, 3, '2003', NULL, 20, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(214, 3, '2004', NULL, 20, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(215, 3, '2005', NULL, 20, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(216, 3, '2006', NULL, 20, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(217, 3, '2007', NULL, 20, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(218, 3, '2008', NULL, 20, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(219, 3, '2009', NULL, 20, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(220, 3, '2010', NULL, 20, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(221, 3, '2011', NULL, 20, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(222, 3, '2012', NULL, 20, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(223, 4, '2101', NULL, 21, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(224, 4, '2102', NULL, 21, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(225, 4, '2103', NULL, 21, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(226, 4, '2104', NULL, 21, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(227, 4, '2105', NULL, 21, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(228, 4, '2106', NULL, 21, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(229, 4, '2107', NULL, 21, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(230, 4, '2108', NULL, 21, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(231, 4, '2201', NULL, 22, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(232, 4, '2202', NULL, 22, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(233, 4, '2203', NULL, 22, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(234, 4, '2204', NULL, 22, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(235, 4, '2205', NULL, 22, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(236, 4, '2206', NULL, 22, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(237, 4, '2207', NULL, 22, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(238, 4, '2208', NULL, 22, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(239, 4, '2301', NULL, 23, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(240, 4, '2302', NULL, 23, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(241, 4, '2303', NULL, 23, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(242, 4, '2304', NULL, 23, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(243, 4, '2305', NULL, 23, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(244, 4, '2306', NULL, 23, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(245, 4, '2307', NULL, 23, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(246, 4, '2308', NULL, 23, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(247, 4, '2401', NULL, 24, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(248, 4, '2402', NULL, 24, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(249, 4, '2403', NULL, 24, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(250, 4, '2404', NULL, 24, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(251, 4, '2405', NULL, 24, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(252, 4, '2406', NULL, 24, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(253, 4, '2407', NULL, 24, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(254, 4, '2408', NULL, 24, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(255, 5, '2501', NULL, 25, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(256, 5, '2502', NULL, 25, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(257, 5, '2503', NULL, 25, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(258, 5, '2504', NULL, 25, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(259, 5, '2505', NULL, 25, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(260, 5, '2506', NULL, 25, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(261, 5, '2507', NULL, 25, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(262, 5, '2601', NULL, 26, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(263, 5, '2602', NULL, 26, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(264, 5, '2603', NULL, 26, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(265, 5, '2604', NULL, 26, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(266, 5, '2605', NULL, 26, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(267, 5, '2606', NULL, 26, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(268, 5, '2607', NULL, 26, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(269, 5, '2701', NULL, 27, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(270, 5, '2702', NULL, 27, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(271, 5, '2703', NULL, 27, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(272, 5, '2704', NULL, 27, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(273, 5, '2705', NULL, 27, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(274, 5, '2706', NULL, 27, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(275, 6, '2801', NULL, 28, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(276, 6, '2802', NULL, 28, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(277, 6, '2803', NULL, 28, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(278, 6, '2804', NULL, 28, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(279, 6, '2805', NULL, 28, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(280, 6, '2901', NULL, 29, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(281, 6, '2902', NULL, 29, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(282, 6, '2903', NULL, 29, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(283, 6, '2904', NULL, 29, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(284, 6, '2905', NULL, 29, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(285, 6, '3001', NULL, 30, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(286, 6, '3002', NULL, 30, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(287, 6, '3003', NULL, 30, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(288, 6, '3004', NULL, 30, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(289, 6, '3005', NULL, 30, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(290, 6, '3101', NULL, 31, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(291, 6, '3102', NULL, 31, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(292, 6, '3103', NULL, 31, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(293, 6, '3104', NULL, 31, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(294, 6, '3105', NULL, 31, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(295, 7, '3201', NULL, 32, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_availability`
--

CREATE TABLE `room_availability` (
  `availability_id` int NOT NULL COMMENT 'Khóa chính, mã lịch',
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn phòng',
  `date` date NOT NULL COMMENT 'Ngày áp dụng',
  `total_rooms` int NOT NULL COMMENT 'Tổng số phòng',
  `available_rooms` int NOT NULL COMMENT 'Số phòng còn trống',
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu lịch đặt phòng theo ngày';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_bed_types`
--

CREATE TABLE `room_bed_types` (
  `room_id` int NOT NULL COMMENT 'Khóa chính',
  `bed_type_id` int NOT NULL COMMENT 'Khóa ngoại, mã loại giường',
  `quantity` int DEFAULT '1' COMMENT 'Số lượng giường loại này',
  `is_default` tinyint(1) DEFAULT '0' COMMENT 'Có phải tùy chọn mặc định',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Bảng trung gian: Phòng - Loại giường';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_meal_types`
--

CREATE TABLE `room_meal_types` (
  `room_id` int NOT NULL COMMENT 'Khóa chính, khóa ngoại',
  `is_default` tinyint(1) DEFAULT '0',
  `meal_type_id` int NOT NULL COMMENT 'Khóa ngoại, mã loại bữa ăn',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Bảng trung gian: Phòng - Loại bữa ăn';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_occupancy`
--

CREATE TABLE `room_occupancy` (
  `occupancy_id` int NOT NULL,
  `room_type_id` int NOT NULL,
  `date` date NOT NULL,
  `total_rooms` int NOT NULL COMMENT 'Tổng số phòng của loại phòng',
  `booked_rooms` int NOT NULL COMMENT 'Số phòng đã được đặt',
  `occupancy_rate` decimal(5,2) GENERATED ALWAYS AS (((`booked_rooms` / `total_rooms`) * 100)) STORED COMMENT 'Tỷ lệ lấp đầy (%)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_occupancy`
--

INSERT INTO `room_occupancy` (`occupancy_id`, `room_type_id`, `date`, `total_rooms`, `booked_rooms`, `created_at`, `updated_at`) VALUES
(11, 1, '2025-07-01', 90, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(12, 2, '2025-07-01', 96, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(13, 3, '2025-07-01', 20, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(14, 4, '2025-07-01', 36, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(15, 5, '2025-07-01', 20, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(16, 6, '2025-07-01', 32, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(17, 7, '2025-07-01', 1, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_option`
--

CREATE TABLE `room_option` (
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Khóa chính, mã tùy chọn',
  `room_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã phòng',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên tùy chọn',
  `price_per_night_vnd` decimal(15,2) NOT NULL COMMENT 'Giá mỗi đêm (VND)',
  `max_guests` int NOT NULL COMMENT 'Số khách tối đa',
  `min_guests` int NOT NULL COMMENT 'Số khách tối thiểu',
  `cancellation_policy_type` enum('free','non_refundable','partial_refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cancellation_penalty` decimal(5,2) DEFAULT NULL COMMENT 'Phạt hủy (%)',
  `cancellation_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mô tả chính sách hủy',
  `free_until` datetime DEFAULT NULL COMMENT 'Hủy miễn phí đến thời điểm',
  `payment_policy_type` enum('pay_now','pay_at_hotel','pay_partial') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `payment_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mô tả thanh toán',
  `urgency_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Thông báo khan hiếm',
  `most_popular` tinyint(1) DEFAULT '0' COMMENT 'Tùy chọn phổ biến nhất',
  `recommended` tinyint(1) DEFAULT '0' COMMENT 'Tùy chọn được đề xuất',
  `meal_type` int DEFAULT NULL COMMENT 'Khóa ngoại, mã bữa ăn',
  `bed_type` int DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn giường',
  `recommendation_score` decimal(5,2) DEFAULT NULL,
  `deposit_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Phần trăm đặt cọc (%)',
  `deposit_fixed_amount_vnd` decimal(15,2) DEFAULT NULL COMMENT 'Số tiền đặt cọc cố định (VND)',
  `deposit_policy_id` int DEFAULT NULL,
  `cancellation_policy_id` int DEFAULT NULL,
  `package_id` int DEFAULT NULL,
  `adjusted_price` decimal(15,2) DEFAULT NULL COMMENT 'Giá sau khi áp dụng các quy tắc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu tùy chọn giá và dịch vụ của phòng';

--
-- Đang đổ dữ liệu cho bảng `room_option`
--

INSERT INTO `room_option` (`option_id`, `room_id`, `name`, `price_per_night_vnd`, `max_guests`, `min_guests`, `cancellation_policy_type`, `cancellation_penalty`, `cancellation_description`, `free_until`, `payment_policy_type`, `payment_description`, `urgency_message`, `most_popular`, `recommended`, `meal_type`, `bed_type`, `recommendation_score`, `deposit_percentage`, `deposit_fixed_amount_vnd`, `deposit_policy_id`, `cancellation_policy_id`, `package_id`, `adjusted_price`) VALUES
('BOOK-LVS60104819-R1-1', 1, 'Standard Package', 1440000.00, 5, 2, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00),
('BOOK-LVS60104819-R1-2', 1, 'Standard Package', 1440000.00, 6, 4, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00),
('BOOK-LVS60104819-R1-3', 1, 'Standard Package', 1440000.00, 2, 2, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00),
('BOOK-LVS61105534-R1-1', 1, 'Standard Package', 1440000.00, 5, 2, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00),
('BOOK-LVS61105534-R1-2', 1, 'Standard Package', 1440000.00, 6, 4, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00),
('BOOK-LVS61105534-R1-3', 1, 'Standard Package', 1440000.00, 2, 2, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00),
('BOOK-LVS62153758-R1-1', 1, 'Standard Package', 1440000.00, 5, 2, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00),
('BOOK-LVS62153758-R1-2', 1, 'Standard Package', 1440000.00, 6, 4, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00),
('BOOK-LVS62153758-R1-3', 1, 'Standard Package', 1440000.00, 2, 2, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00),
('BOOK-LVS63162115-R1-1', 1, 'Standard Package', 11000.00, 2, 2, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00),
('BOOK-LVS64164554-R1-1', 1, 'Standard Package', 11000.00, 2, 2, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00),
('BOOK-LVS65165011-R1-1', 1, 'Standard Package', 11000.00, 2, 2, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00),
('BOOK-LVS66165335-R1-1', 1, 'Standard Package', 11000.00, 2, 2, 'free', 0.00, 'Free cancellation until check-in date', '2025-07-06 17:00:00', 'pay_now', 'Pay online before arrival', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00),
('OPT10', 257, 'Premium Corner King', 2000000.00, 2, 1, 'free', 0.00, 'Hủy miễn phí trong 7 ngày', '2025-06-25 23:59:59', 'pay_now', 'Thanh toán trước 50%', 'Phòng cao cấp, đặt ngay!', 1, 1, 1, 1, 95.00, 50.00, 1000000.00, 1, 1, 2, 2000000.00),
('OPT11', 258, 'The Level Premium King', 2500000.00, 2, 1, 'free', 0.00, 'Hủy miễn phí trong 7 ngày', '2025-06-25 23:59:59', 'pay_now', 'Thanh toán trước 50%', 'Chỉ còn 1 phòng!', 0, 1, 1, 1, 88.00, 50.00, 1250000.00, 1, 1, 3, 2500000.00),
('OPT12', 259, 'The Level Premium Corner Twin', 2800000.00, 2, 1, 'free', 0.00, 'Hủy miễn phí trong 7 ngày', '2025-06-25 23:59:59', 'pay_now', 'Thanh toán trước 50%', 'Phòng cao cấp, đặt ngay!', 1, 1, 1, 2, 92.00, 50.00, 1400000.00, 1, 1, 4, 2800000.00),
('OPT13', 261, 'Suite King', 4000000.00, 2, 1, 'free', 0.00, 'Hủy miễn phí trong 7 ngày', '2025-06-25 23:59:59', 'pay_now', 'Thanh toán trước 50%', 'Phòng cao cấp nhất!', 1, 1, 1, 1, 99.00, 50.00, 2000000.00, 1, 1, 6, 4000000.00),
('OPT6', 260, 'The Level Suite King', 3500000.00, 2, 1, 'free', 0.00, 'Hủy miễn phí trong 7 ngày', '2025-06-25 23:59:59', 'pay_now', 'Thanh toán trước 50%', 'Phòng sang trọng, đặt ngay!', 1, 1, 1, 1, 97.00, 50.00, 1750000.00, 1, 1, 5, 3500000.00),
('OPT8', 255, 'Deluxe King Room', 1200000.00, 2, 1, 'free', 0.00, 'Hủy miễn phí trong 7 ngày', '2025-06-25 23:59:59', 'pay_now', 'Thanh toán trước 50%', 'Chỉ còn 3 phòng!', 1, 1, 1, 1, 90.00, 50.00, 600000.00, 1, 1, 1, 1200000.00),
('OPT9', 256, 'Deluxe Twin Room', 1300000.00, 2, 1, 'free', 0.00, 'Hủy miễn phí trong 7 ngày', '2025-06-25 23:59:59', 'pay_now', 'Thanh toán trước 50%', 'Chỉ còn 2 phòng!', 0, 1, 1, 2, 85.00, 50.00, 650000.00, 1, 1, 1, 1300000.00),
('OPT_PRES_1', 295, 'Presidential Suite King', 10000000.00, 4, 2, 'free', 0.00, 'Hủy miễn phí trong 7 ngày', '2025-06-25 23:59:59', 'pay_now', 'Thanh toán trước 50%', 'Phòng Tổng thống, đặt ngay!', 1, 1, 1, 1, 100.00, 50.00, 5000000.00, 1, 1, 7, 10000000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_option_promotion`
--

CREATE TABLE `room_option_promotion` (
  `promotion_id` int NOT NULL COMMENT 'Khóa chính, mã khuyến mãi',
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn',
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại khuyến mãi (hot, limited)',
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Thông điệp khuyến mãi',
  `discount` decimal(5,2) DEFAULT NULL COMMENT 'Mức giảm giá (%)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu khuyến mãi của tùy chọn phòng';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_price_history`
--

CREATE TABLE `room_price_history` (
  `price_history_id` int NOT NULL,
  `room_type_id` int NOT NULL,
  `date` date NOT NULL,
  `base_price` decimal(15,2) NOT NULL COMMENT 'Giá cơ bản',
  `adjusted_price` decimal(15,2) NOT NULL COMMENT 'Giá sau điều chỉnh',
  `applied_rules` json DEFAULT NULL COMMENT 'Danh sách quy tắc áp dụng (ID và loại quy tắc)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_price_history`
--

INSERT INTO `room_price_history` (`price_history_id`, `room_type_id`, `date`, `base_price`, `adjusted_price`, `applied_rules`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-06-27', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-26 21:53:28', '2025-06-26 21:53:28'),
(2, 2, '2025-07-01', 1500000.00, 1710000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-26 21:55:01', '2025-07-03 01:59:50'),
(3, 2, '2025-07-02', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-26 21:55:01', '2025-06-29 02:35:55'),
(4, 2, '2025-07-03', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-26 21:55:01', '2025-06-29 02:35:55'),
(5, 2, '2025-07-04', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":3,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":80}}]\"', '2025-06-26 21:55:01', '2025-07-03 03:12:38'),
(6, 2, '2025-07-31', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-26 21:59:08', '2025-06-29 21:43:29'),
(7, 2, '2025-08-01', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":3,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":87}}]\"', '2025-06-26 21:59:08', '2025-06-29 21:43:29'),
(8, 1, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 02:47:01', '2025-06-28 02:47:01'),
(9, 1, '2025-06-29', 1200000.00, 1680000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":1,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":83}}]\"', '2025-06-28 02:47:01', '2025-06-29 09:09:46'),
(10, 1, '2025-06-30', 1200000.00, 1368000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:01', '2025-06-29 09:09:46'),
(11, 1, '2025-07-01', 1200000.00, 1368000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:01', '2025-07-03 01:59:50'),
(12, 1, '2025-07-02', 1200000.00, 1440000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:01', '2025-06-29 09:09:46'),
(13, 1, '2025-07-03', 1200000.00, 1440000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:01', '2025-06-29 09:09:46'),
(14, 3, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 02:47:03', '2025-06-28 02:47:03'),
(15, 3, '2025-06-29', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 02:47:03', '2025-06-29 04:30:12'),
(16, 3, '2025-06-30', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:03', '2025-06-29 21:13:26'),
(17, 3, '2025-07-01', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:03', '2025-07-03 01:59:50'),
(18, 3, '2025-07-02', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:03', '2025-06-29 04:30:12'),
(19, 3, '2025-07-03', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:03', '2025-06-29 04:30:12'),
(20, 7, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 02:47:08', '2025-06-28 02:47:08'),
(21, 7, '2025-06-29', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 02:47:08', '2025-06-29 02:30:36'),
(22, 7, '2025-06-30', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:08', '2025-06-29 23:54:20'),
(23, 7, '2025-07-01', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:08', '2025-07-03 01:59:50'),
(24, 7, '2025-07-02', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:08', '2025-06-29 02:30:36'),
(25, 7, '2025-07-03', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:08', '2025-06-29 02:30:36'),
(26, 6, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:40:23', '2025-06-28 03:40:23'),
(27, 6, '2025-06-29', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:40:23', '2025-06-29 02:33:36'),
(28, 6, '2025-06-30', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:40:23', '2025-07-02 03:28:15'),
(29, 6, '2025-07-01', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:40:23', '2025-07-03 01:59:50'),
(30, 6, '2025-07-02', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:40:23', '2025-06-29 02:33:36'),
(31, 6, '2025-07-03', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:40:23', '2025-06-29 02:33:36'),
(32, 2, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:40:24', '2025-06-28 03:40:24'),
(33, 2, '2025-06-29', 1500000.00, 1950000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:40:24', '2025-06-29 05:15:28'),
(34, 2, '2025-06-30', 1500000.00, 1710000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:40:24', '2025-06-29 20:51:47'),
(35, 4, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:42:30', '2025-06-28 03:42:30'),
(36, 4, '2025-06-29', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:42:30', '2025-06-29 02:34:36'),
(37, 4, '2025-06-30', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:42:30', '2025-06-29 20:52:43'),
(38, 4, '2025-07-01', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:42:30', '2025-07-03 01:59:50'),
(39, 4, '2025-07-02', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:42:30', '2025-07-01 01:46:03'),
(40, 4, '2025-07-03', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:42:30', '2025-07-02 04:22:05'),
(41, 4, '2025-07-04', 1000000.00, 1270000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":74}}]\"', '2025-06-28 23:26:04', '2025-07-03 03:12:38'),
(42, 6, '2025-07-04', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:29:06', '2025-06-29 02:33:36'),
(43, 3, '2025-07-04', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:29:18', '2025-06-29 04:30:12'),
(44, 5, '2025-06-29', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 23:31:55', '2025-06-29 02:33:38'),
(45, 5, '2025-06-30', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:31:55', '2025-07-02 03:28:15'),
(46, 5, '2025-07-01', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:31:55', '2025-07-03 01:59:50'),
(47, 5, '2025-07-02', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:31:55', '2025-06-29 02:33:38'),
(48, 5, '2025-07-03', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:31:55', '2025-06-29 02:33:38'),
(49, 5, '2025-07-04', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:31:55', '2025-06-29 02:33:38'),
(50, 3, '2025-07-05', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-29 00:47:05', '2025-06-29 04:30:12'),
(51, 1, '2025-07-04', 1200000.00, 1560000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":1,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":87}}]\"', '2025-06-29 02:29:55', '2025-07-03 03:12:38'),
(52, 1, '2025-07-05', 1200000.00, 1680000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":2,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"90.00\\\",\\\"current_occupancy\\\":91}}]\"', '2025-06-29 02:29:55', '2025-07-04 04:52:35'),
(53, 2, '2025-07-05', 1500000.00, 1950000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-29 02:30:20', '2025-07-04 04:52:36'),
(54, 7, '2025-07-04', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-29 02:30:36', '2025-06-29 02:30:36'),
(55, 7, '2025-07-05', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-29 02:30:36', '2025-06-29 02:30:36'),
(56, 6, '2025-07-05', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-29 02:33:36', '2025-06-29 02:33:36'),
(57, 5, '2025-07-05', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-29 02:33:38', '2025-06-29 02:33:38'),
(58, 4, '2025-07-05', 1000000.00, 1370000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":74}}]\"', '2025-06-29 02:34:36', '2025-07-04 04:52:36'),
(59, 2, '2025-07-06', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":3,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":86}}]\"', '2025-06-29 20:51:47', '2025-07-06 20:50:35'),
(60, 1, '2025-07-06', 1200000.00, 1560000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-29 20:52:16', '2025-07-05 00:36:49'),
(61, 4, '2025-07-06', 1000000.00, 1370000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":85}}]\"', '2025-06-29 20:52:43', '2025-07-06 20:50:35'),
(62, 3, '2025-07-06', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-29 21:13:26', '2025-06-29 21:13:26'),
(63, 2, '2025-07-30', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-29 21:43:29', '2025-06-29 21:43:29'),
(64, 7, '2025-07-06', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-29 23:54:20', '2025-06-29 23:54:20'),
(65, 2, '2025-07-07', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-02 04:26:19', '2025-07-02 04:26:19'),
(66, 2, '2025-07-08', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-02 04:26:19', '2025-07-02 04:26:19'),
(67, 5, '2025-07-06', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-04 19:42:25', '2025-07-04 19:42:25'),
(68, 6, '2025-07-06', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-04 19:42:25', '2025-07-04 19:42:25'),
(69, 1, '2025-07-07', 1200000.00, 1440000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-05 20:44:48', '2025-07-05 20:44:48'),
(70, 3, '2025-07-07', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-05 20:44:48', '2025-07-05 20:44:48'),
(71, 4, '2025-07-07', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-05 20:44:48', '2025-07-06 09:22:02'),
(72, 5, '2025-07-07', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-05 20:44:48', '2025-07-05 20:44:48'),
(73, 6, '2025-07-07', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-05 20:44:48', '2025-07-05 20:44:48'),
(74, 7, '2025-07-07', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-05 20:44:48', '2025-07-05 20:44:48'),
(75, 1, '2025-07-08', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-06 19:24:58', '2025-07-08 08:49:44'),
(76, 3, '2025-07-08', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-06 19:24:58', '2025-07-06 19:24:58'),
(77, 4, '2025-07-08', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-06 19:24:58', '2025-07-08 01:33:40'),
(78, 5, '2025-07-08', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-06 19:24:58', '2025-07-06 19:24:58');
INSERT INTO `room_price_history` (`price_history_id`, `room_type_id`, `date`, `base_price`, `adjusted_price`, `applied_rules`, `created_at`, `updated_at`) VALUES
(79, 6, '2025-07-08', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-06 19:24:58', '2025-07-06 19:24:58'),
(80, 7, '2025-07-08', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-06 19:24:58', '2025-07-06 19:24:58'),
(81, 1, '2025-07-09', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-07 19:14:30', '2025-07-08 09:01:58'),
(82, 2, '2025-07-09', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-07 19:14:30', '2025-07-07 19:14:30'),
(83, 3, '2025-07-09', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-07 19:14:30', '2025-07-07 19:14:30'),
(84, 4, '2025-07-09', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-07 19:14:30', '2025-07-07 19:14:30'),
(85, 5, '2025-07-09', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-07 19:14:30', '2025-07-07 19:14:30'),
(86, 6, '2025-07-09', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-07 19:14:30', '2025-07-07 19:14:30'),
(87, 7, '2025-07-09', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-07 19:14:30', '2025-07-07 19:14:30'),
(88, 1, '2025-07-10', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34'),
(89, 2, '2025-07-10', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34'),
(90, 3, '2025-07-10', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34'),
(91, 4, '2025-07-10', 1000000.00, 1270000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":70}}]\"', '2025-07-08 19:31:34', '2025-07-08 20:05:39'),
(92, 5, '2025-07-10', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34'),
(93, 6, '2025-07-10', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34'),
(94, 7, '2025-07-10', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_transfers`
--

CREATE TABLE `room_transfers` (
  `transfer_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `old_room_id` int NOT NULL,
  `new_room_id` int NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `additional_fee_vnd` decimal(15,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_types`
--

CREATE TABLE `room_types` (
  `room_type_id` int NOT NULL,
  `room_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `total_room` int NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `room_area` int NOT NULL,
  `view` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rating` int DEFAULT '0',
  `max_guests` int DEFAULT '0',
  `is_active` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_types`
--

INSERT INTO `room_types` (`room_type_id`, `room_code`, `name`, `description`, `total_room`, `base_price`, `room_area`, `view`, `rating`, `max_guests`, `is_active`) VALUES
(1, 'deluxe', 'Deluxe Room', 'Phòng giường đôi rộng rãi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng cùng bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, tủ để quần áo cũng như tầm nhìn ra thành phố.', 90, 5000.00, 32, 'ABC', 0, 2, 1),
(2, 'premium_corner', 'Premium Corner', 'Phòng giường đôi rộng rãi này có máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm.', 96, 1500000.00, 64, 'BCD', 0, 2, 1),
(3, 'the_level_premium', 'The Level Premium', 'Phòng giường đôi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, máy pha cà phê cũng như tầm nhìn ra thành phố.', 36, 1000000.00, 92, 'BBJ', 0, 2, 1),
(4, 'the_level_premium_corner', 'The Level Premium Corner', 'Phòng giường đôi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, máy pha cà phê cũng như tầm nhìn ra thành phố.\n\n', 32, 1000000.00, 20, 'WD', 0, 2, 1),
(5, 'the_level_suite', 'The Level Suite', 'Phòng giường đôi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, máy pha cà phê cũng như tầm nhìn ra thành phố. Căn này được trang bị 1 giường.\n\n', 20, 1000000.00, 48, 'FFS', 0, 2, 1),
(6, 'suite', 'Suite', 'Suite rộng rãi này được bố trí 1 phòng ngủ, khu vực ghế ngồi và 1 phòng tắm với buồng tắm đứng cùng bồn tắm. Suite này có máy điều hòa, TV màn hình phẳng, tường cách âm, minibar, khu vực ăn uống cũng như tầm nhìn ra thành phố. Căn này được trang bị 1 giường.', 20, 1000000.00, 45, 'TDG', 0, 2, 1),
(7, 'presidential_suite', 'Presidential Suite', 'Suite rộng rãi này được bố trí 1 phòng khách, 2 phòng ngủ riêng biệt và 2 phòng tắm với buồng tắm đứng cùng đồ vệ sinh cá nhân miễn phí. Suite này có máy điều hòa, khu vực ghế ngồi với TV màn hình phẳng, tường cách âm, minibar, máy pha cà phê cũng như khu vực ăn uống. Căn này được trang bị 2 giường.', 1, 1000000.00, 10, 'OKO', 0, 4, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_type_amenity`
--

CREATE TABLE `room_type_amenity` (
  `room_type_id` int NOT NULL,
  `amenity_id` int NOT NULL,
  `is_highlighted` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_type_amenity`
--

INSERT INTO `room_type_amenity` (`room_type_id`, `amenity_id`, `is_highlighted`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(1, 2, 1, NULL, NULL),
(1, 3, 1, NULL, NULL),
(1, 4, 1, NULL, NULL),
(1, 5, 0, NULL, NULL),
(1, 6, 0, NULL, NULL),
(1, 7, 0, NULL, NULL),
(1, 8, 1, NULL, NULL),
(1, 9, 0, NULL, NULL),
(1, 10, 0, NULL, NULL),
(1, 11, 1, NULL, NULL),
(1, 12, 1, NULL, NULL),
(1, 15, 1, NULL, NULL),
(1, 35, 0, NULL, NULL),
(1, 36, 0, NULL, NULL),
(1, 37, 0, NULL, NULL),
(1, 38, 0, NULL, NULL),
(1, 39, 0, NULL, NULL),
(1, 40, 0, NULL, NULL),
(1, 41, 0, NULL, NULL),
(1, 43, 0, NULL, NULL),
(1, 44, 0, NULL, NULL),
(2, 1, 1, NULL, NULL),
(2, 2, 1, NULL, NULL),
(2, 3, 1, NULL, NULL),
(2, 4, 1, NULL, NULL),
(2, 5, 0, NULL, NULL),
(2, 6, 0, NULL, NULL),
(2, 7, 0, NULL, NULL),
(2, 8, 1, NULL, NULL),
(2, 9, 0, NULL, NULL),
(2, 10, 0, NULL, NULL),
(2, 11, 0, NULL, NULL),
(2, 12, 0, NULL, NULL),
(2, 13, 1, NULL, NULL),
(2, 14, 1, NULL, NULL),
(2, 16, 1, NULL, NULL),
(2, 17, 1, NULL, NULL),
(2, 35, 0, NULL, NULL),
(2, 36, 0, NULL, NULL),
(2, 37, 0, NULL, NULL),
(2, 38, 0, NULL, NULL),
(2, 39, 0, NULL, NULL),
(2, 40, 0, NULL, NULL),
(2, 41, 0, NULL, NULL),
(2, 42, 0, NULL, NULL),
(2, 43, 0, NULL, NULL),
(2, 44, 0, NULL, NULL),
(3, 1, 1, NULL, NULL),
(3, 2, 1, NULL, NULL),
(3, 3, 0, NULL, NULL),
(3, 4, 1, NULL, NULL),
(3, 5, 0, NULL, NULL),
(3, 6, 0, NULL, NULL),
(3, 7, 0, NULL, NULL),
(3, 8, 0, NULL, NULL),
(3, 9, 0, NULL, NULL),
(3, 10, 0, NULL, NULL),
(3, 11, 0, NULL, NULL),
(3, 12, 0, NULL, NULL),
(3, 26, 1, NULL, NULL),
(3, 27, 1, NULL, NULL),
(3, 28, 1, NULL, NULL),
(3, 29, 1, NULL, NULL),
(3, 30, 0, NULL, NULL),
(3, 31, 0, NULL, NULL),
(3, 32, 0, NULL, NULL),
(3, 33, 0, NULL, NULL),
(3, 34, 1, NULL, NULL),
(3, 35, 0, NULL, NULL),
(3, 36, 0, NULL, NULL),
(3, 37, 0, NULL, NULL),
(3, 38, 0, NULL, NULL),
(3, 39, 0, NULL, NULL),
(3, 40, 0, NULL, NULL),
(3, 41, 0, NULL, NULL),
(3, 42, 0, NULL, NULL),
(3, 44, 0, NULL, NULL),
(4, 1, 1, NULL, NULL),
(4, 2, 1, NULL, NULL),
(4, 3, 0, NULL, NULL),
(4, 4, 1, NULL, NULL),
(4, 5, 0, NULL, NULL),
(4, 6, 0, NULL, NULL),
(4, 7, 0, NULL, NULL),
(4, 8, 0, NULL, NULL),
(4, 9, 0, NULL, NULL),
(4, 10, 0, NULL, NULL),
(4, 11, 0, NULL, NULL),
(4, 12, 0, NULL, NULL),
(4, 13, 1, NULL, NULL),
(4, 14, 1, NULL, NULL),
(4, 16, 0, NULL, NULL),
(4, 17, 0, NULL, NULL),
(4, 26, 1, NULL, NULL),
(4, 27, 1, NULL, NULL),
(4, 28, 1, NULL, NULL),
(4, 29, 1, NULL, NULL),
(4, 30, 0, NULL, NULL),
(4, 31, 0, NULL, NULL),
(4, 32, 0, NULL, NULL),
(4, 33, 0, NULL, NULL),
(4, 34, 0, NULL, NULL),
(4, 35, 0, NULL, NULL),
(4, 36, 0, NULL, NULL),
(4, 37, 0, NULL, NULL),
(4, 38, 0, NULL, NULL),
(4, 39, 0, NULL, NULL),
(4, 40, 0, NULL, NULL),
(4, 41, 0, NULL, NULL),
(4, 42, 0, NULL, NULL),
(4, 43, 0, NULL, NULL),
(4, 46, 0, NULL, NULL),
(5, 1, 1, NULL, NULL),
(5, 2, 1, NULL, NULL),
(5, 3, 0, NULL, NULL),
(5, 4, 1, NULL, NULL),
(5, 5, 0, NULL, NULL),
(5, 6, 0, NULL, NULL),
(5, 7, 0, NULL, NULL),
(5, 8, 0, NULL, NULL),
(5, 9, 0, NULL, NULL),
(5, 10, 0, NULL, NULL),
(5, 11, 0, NULL, NULL),
(5, 12, 0, NULL, NULL),
(5, 18, 0, NULL, NULL),
(5, 19, 0, NULL, NULL),
(5, 20, 0, NULL, NULL),
(5, 21, 1, NULL, NULL),
(5, 22, 1, NULL, NULL),
(5, 23, 1, NULL, NULL),
(5, 24, 1, NULL, NULL),
(5, 25, 1, NULL, NULL),
(5, 26, 1, NULL, NULL),
(5, 27, 0, NULL, NULL),
(5, 28, 0, NULL, NULL),
(5, 29, 0, NULL, NULL),
(5, 30, 0, NULL, NULL),
(5, 31, 0, NULL, NULL),
(5, 32, 0, NULL, NULL),
(5, 33, 0, NULL, NULL),
(5, 34, 0, NULL, NULL),
(5, 35, 0, NULL, NULL),
(5, 36, 0, NULL, NULL),
(5, 37, 0, NULL, NULL),
(5, 38, 0, NULL, NULL),
(5, 39, 0, NULL, NULL),
(5, 40, 0, NULL, NULL),
(5, 41, 0, NULL, NULL),
(5, 42, 0, NULL, NULL),
(5, 43, 0, NULL, NULL),
(5, 44, 0, NULL, NULL),
(5, 45, 0, NULL, NULL),
(5, 46, 0, NULL, NULL),
(6, 1, 1, NULL, NULL),
(6, 2, 1, NULL, NULL),
(6, 3, 0, NULL, NULL),
(6, 4, 1, NULL, NULL),
(6, 5, 0, NULL, NULL),
(6, 6, 0, NULL, NULL),
(6, 7, 0, NULL, NULL),
(6, 8, 0, NULL, NULL),
(6, 9, 0, NULL, NULL),
(6, 10, 0, NULL, NULL),
(6, 11, 0, NULL, NULL),
(6, 12, 0, NULL, NULL),
(6, 18, 0, NULL, NULL),
(6, 19, 0, NULL, NULL),
(6, 20, 0, NULL, NULL),
(6, 21, 1, NULL, NULL),
(6, 22, 1, NULL, NULL),
(6, 23, 1, NULL, NULL),
(6, 24, 1, NULL, NULL),
(6, 25, 1, NULL, NULL),
(6, 35, 0, NULL, NULL),
(6, 36, 0, NULL, NULL),
(6, 37, 0, NULL, NULL),
(6, 38, 0, NULL, NULL),
(6, 39, 0, NULL, NULL),
(6, 40, 0, NULL, NULL),
(6, 41, 0, NULL, NULL),
(6, 42, 0, NULL, NULL),
(6, 43, 0, NULL, NULL),
(6, 44, 0, NULL, NULL),
(6, 45, 0, NULL, NULL),
(7, 1, 1, NULL, NULL),
(7, 2, 1, NULL, NULL),
(7, 3, 0, NULL, NULL),
(7, 4, 1, NULL, NULL),
(7, 5, 0, NULL, NULL),
(7, 6, 0, NULL, NULL),
(7, 7, 0, NULL, NULL),
(7, 8, 0, NULL, NULL),
(7, 9, 0, NULL, NULL),
(7, 10, 0, NULL, NULL),
(7, 11, 0, NULL, NULL),
(7, 13, 0, NULL, NULL),
(7, 18, 0, NULL, NULL),
(7, 19, 0, NULL, NULL),
(7, 20, 0, NULL, NULL),
(7, 21, 1, NULL, NULL),
(7, 22, 1, NULL, NULL),
(7, 23, 1, NULL, NULL),
(7, 24, 1, NULL, NULL),
(7, 25, 1, NULL, NULL),
(7, 35, 0, NULL, NULL),
(7, 36, 0, NULL, NULL),
(7, 37, 0, NULL, NULL),
(7, 38, 0, NULL, NULL),
(7, 39, 0, NULL, NULL),
(7, 40, 0, NULL, NULL),
(7, 41, 0, NULL, NULL),
(7, 42, 0, NULL, NULL),
(7, 43, 0, NULL, NULL),
(7, 44, 0, NULL, NULL),
(7, 45, 1, NULL, NULL),
(7, 46, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_type_image`
--

CREATE TABLE `room_type_image` (
  `image_id` int NOT NULL COMMENT 'Khóa chính, mã ảnh',
  `room_type_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã phòng',
  `alt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh thư mục gốc',
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh API',
  `is_main` tinyint(1) DEFAULT '0' COMMENT 'Ảnh chính',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách ảnh của phòng';

--
-- Đang đổ dữ liệu cho bảng `room_type_image`
--

INSERT INTO `room_type_image` (`image_id`, `room_type_id`, `alt_text`, `image_path`, `image_url`, `is_main`, `created_at`, `updated_at`) VALUES
(5, 1, NULL, 'https://kksapahotel.com/uploads/images/VQK_2153%20(1).jpg', 'https://kksapahotel.com/uploads/images/VQK_2153%20(1).jpg', 1, '2025-06-08 10:21:06', '2025-06-27 03:09:56'),
(23, 3, 'Phòng Suite (Suite Room) - Ảnh 1', '/storage/room-types/3/1750921018_0_685cef3ad7560.jpg', '/storage/room-types/3/1750921018_0_685cef3ad7560.jpg', 1, '2025-06-25 23:56:58', '2025-06-25 23:56:58'),
(24, 1, 'Phòng Loại Sang (Deluxe Room) - Ảnh 1', '/storage/room-types/1/1751019383_0_685e6f77855e9.jpg', NULL, 0, '2025-06-27 03:16:23', '2025-06-27 03:16:23'),
(26, 1, 'Phòng Loại Sang (Deluxe Room) - Ảnh 1', '/storage/room-types/1/1751021451_0_685e778bb4f50.png', 'http://127.0.0.1:8888/storage/room-types/1/1751021451_0_685e778bb4f50.png', 0, '2025-06-27 03:50:51', '2025-06-27 03:50:51'),
(27, 1, 'Phòng Loại Sang (Deluxe Room) - Ảnh 1', '/storage/room-types/1/1751021649_0_685e785114e87.jpg', 'http://127.0.0.1:8888/storage/room-types/1/1751021649_0_685e785114e87.jpg', 0, '2025-06-27 03:54:09', '2025-06-27 03:54:09'),
(28, 2, 'Premium Corner - Ảnh 1', '/storage/room-types/2/1751595760_0_68673af077c40.png', NULL, 1, '2025-07-03 19:22:41', '2025-07-03 19:22:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_type_package`
--

CREATE TABLE `room_type_package` (
  `package_id` int NOT NULL,
  `room_type_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price_modifier_vnd` decimal(15,2) DEFAULT '0.00',
  `include_all_services` tinyint(1) DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_type_package`
--

INSERT INTO `room_type_package` (`package_id`, `room_type_id`, `name`, `price_modifier_vnd`, `include_all_services`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Standard Package', 5000.00, 0, 'Gói tiêu chuẩn cho Deluxe Room', 1, '2025-07-02 09:00:56', '2025-07-08 15:40:48'),
(2, 2, 'Premium Package', 500000.00, 0, 'Gói cao cấp cho Premium Corner', 1, '2025-07-02 09:00:56', '2025-07-02 09:00:56'),
(3, 3, 'VIP Package', 1000000.00, 0, 'Gói VIP cho The Level Premium', 1, '2025-07-02 09:00:56', '2025-07-02 09:00:56'),
(4, 4, 'Corner Package', 1200000.00, 0, 'Gói góc cao cấp cho The Level Premium Corner', 1, '2025-07-02 09:00:56', '2025-07-02 09:00:56'),
(5, 5, 'Suite Package', 1500000.00, 0, 'Gói suite cho The Level Suite', 1, '2025-07-02 09:00:56', '2025-07-02 09:00:56'),
(6, 6, 'Luxury Package', 2000000.00, 0, 'Gói sang trọng cho Suite', 1, '2025-07-02 09:00:56', '2025-07-02 09:00:56'),
(7, 7, 'Presidential Package', 5000000.00, 0, 'Gói Tổng thống cao cấp', 1, '2025-07-02 09:12:55', '2025-07-02 09:12:55'),
(8, 1, 'Premium Package', 1000000.00, 0, 'Gói trung bình của phòng loại 1', 1, '2025-07-06 16:21:55', '2025-07-06 16:21:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_type_package_services`
--

CREATE TABLE `room_type_package_services` (
  `id` int NOT NULL,
  `package_id` int NOT NULL,
  `service_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_type_service`
--

CREATE TABLE `room_type_service` (
  `id` int NOT NULL,
  `room_type_id` int NOT NULL,
  `service_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_type_service`
--

INSERT INTO `room_type_service` (`id`, `room_type_id`, `service_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-06-26 02:47:15', '2025-06-26 02:47:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `services`
--

CREATE TABLE `services` (
  `service_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `price_vnd` decimal(15,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Ví dụ: lần, ngày, giờ, kg',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `services`
--

INSERT INTO `services` (`service_id`, `name`, `description`, `price_vnd`, `unit`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Ăn uống tại phòng', 'Khách có thể đặt các món ăn, đồ uống từ thực đơn của nhà hàng khách sạn và được nhân viên mang đến tận phòng.', 5000.00, '1', 1, '2025-06-25 16:25:18', '2025-07-08 15:46:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('l9P8X17i9knsKm6Se44m0KLTEnn5ZOciaQiIDrJI', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNDhTVTZlZEk2YXFNMW1QclUwd3plRnoxdE5WemY0MXYyWjdkRzhqSCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjEwMDoiaHR0cDovLzEyNy4wLjAuMTo4ODg4L2FkbWluL3ByaWNpbmcvcHJldmlldz9lbmRfZGF0ZT0yMDI1LTA3LTA2JnJvb21fdHlwZV9pZD0xJnN0YXJ0X2RhdGU9MjAyNS0wNi0zMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1751266505),
('s4gQ9sUWJ0Ae5bQNRClgaNTK7XkmKmfxeBJRaER6', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiam9TRU1GTE96bmVpUmMxOUJDcWpDZ2lEdWJWdGpteWFKUWNad0djUyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTI3LjAuMC4xOjg4ODgvYWRtaW4vcm9vbXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1751818702);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `table_translation`
--

CREATE TABLE `table_translation` (
  `id` bigint UNSIGNED NOT NULL,
  `table_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `table_translation`
--

INSERT INTO `table_translation` (`id`, `table_name`, `display_name`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'amenities', 'Tiện ích', 0, NULL, NULL),
(3, 'currency', 'Tiền tệ', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `translation`
--

CREATE TABLE `translation` (
  `translation_id` int NOT NULL COMMENT 'Khóa chính, mã bản dịch',
  `table_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên bảng (room, hotel, v.v.)',
  `column_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên cột (name, description, v.v.)',
  `record_id` int NOT NULL COMMENT 'Mã bản ghi',
  `language_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Mã ngôn ngữ (vi, en, v.v.)',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Giá trị bản dịch'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu bản dịch cho các trường văn bản';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `identity_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số CCCD hoặc số hộ chiếu',
  `role` enum('guest','receptionist','manager','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Vai trò',
  `two_factor_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `current_team_id` bigint UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `address`, `identity_code`, `role`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'NGUYỄN ANH ĐỨC', 'nguyenanhduc2909@gmail.com', NULL, '$2y$12$c2dNZ4nJgjNNQzaupkPYP.qIR6Ax7vkA65tXqK/n/uStI/bAr5haa', '0822153447', 'Thanh Hóa', NULL, 'admin', NULL, NULL, NULL, NULL, NULL, 'profile-photos/DjTR2i1uqXfUMCJURsg6kxS1HZfuCgqHyy5qERDx.jpg', '2025-05-21 01:07:42', '2025-06-11 07:11:24'),
(2, 'Nguyễn Anh Đức', 'nguyenandhduc2909@gmail.com', NULL, '$2y$12$ofny2jH99JRC2egJJaVzLOyRIuw2.5aL93twDg6Zw4hOq0KKWdxAu', '08221534422', 'Thanh Hóa', NULL, 'guest', NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-10 09:47:13', '2025-06-10 09:47:13'),
(3, 'Thu Huyền', 'nguyenanhduc29090@gmail.com', NULL, '$2y$12$sIBuDRsM3GZwHvaxR8xNeeF6UIW1YTu5wCghwC.M63T3qWoUC6gna', '03111512022', 'Tuyên Quang', '035656218945', NULL, NULL, NULL, NULL, NULL, NULL, 'profile-photos/sVoWN7luhQjbSUrhKANRv2sJKh7h1hOc0saidVWn.jpg', '2025-06-27 00:17:18', '2025-06-27 00:17:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `weekend_days`
--

CREATE TABLE `weekend_days` (
  `id` int NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `weekend_days`
--

INSERT INTO `weekend_days` (`id`, `day_of_week`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Monday', 0, '2025-06-14 02:38:18', '2025-06-23 00:42:49'),
(4, 'Tuesday', 0, '2025-06-14 02:38:18', '2025-06-23 00:42:49'),
(5, 'Wednesday', 0, '2025-06-14 02:38:18', '2025-06-23 00:42:49'),
(6, 'Thursday', 0, '2025-06-14 02:38:18', '2025-06-23 00:42:49'),
(7, 'Friday', 1, '2025-06-14 02:38:18', '2025-06-23 00:42:49'),
(8, 'Saturday', 1, '2025-06-14 02:38:18', '2025-06-23 00:42:49'),
(9, 'Sunday', 1, '2025-06-14 02:38:18', '2025-06-23 00:42:49');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`amenity_id`);

--
-- Chỉ mục cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `bed_types`
--
ALTER TABLE `bed_types`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `idx_check_in_out` (`check_in_date`,`check_out_date`),
  ADD KEY `fk_booking_room` (`room_id`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Chỉ mục cho bảng `booking_extensions`
--
ALTER TABLE `booking_extensions`
  ADD PRIMARY KEY (`extension_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `booking_reschedules`
--
ALTER TABLE `booking_reschedules`
  ADD PRIMARY KEY (`reschedule_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `representative_id` (`representative_id`),
  ADD KEY `booking_rooms_option_id_foreign` (`option_id`);

--
-- Chỉ mục cho bảng `cancellation_policies`
--
ALTER TABLE `cancellation_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Chỉ mục cho bảng `check_out_policies`
--
ALTER TABLE `check_out_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Chỉ mục cho bảng `check_out_requests`
--
ALTER TABLE `check_out_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_code`);

--
-- Chỉ mục cho bảng `datafeeds`
--
ALTER TABLE `datafeeds`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `deposit_policies`
--
ALTER TABLE `deposit_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Chỉ mục cho bảng `dynamic_pricing_rules`
--
ALTER TABLE `dynamic_pricing_rules`
  ADD PRIMARY KEY (`rule_id`),
  ADD KEY `room_type_id` (`room_type_id`),
  ADD KEY `idx_dynamic_priority` (`priority`,`is_exclusive`);

--
-- Chỉ mục cho bảng `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`faq_id`);

--
-- Chỉ mục cho bảng `flexible_pricing_rules`
--
ALTER TABLE `flexible_pricing_rules`
  ADD PRIMARY KEY (`rule_id`),
  ADD KEY `flexible_pricing_room_type_id_index` (`room_type_id`),
  ADD KEY `flexible_pricing_event_id_index` (`event_id`),
  ADD KEY `flexible_pricing_holiday_id_index` (`holiday_id`),
  ADD KEY `idx_priority` (`priority`,`is_exclusive`);

--
-- Chỉ mục cho bảng `floors`
--
ALTER TABLE `floors`
  ADD PRIMARY KEY (`floor_id`),
  ADD UNIQUE KEY `floor_number` (`floor_number`);

--
-- Chỉ mục cho bảng `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guest_id`),
  ADD KEY `fk_guests_user` (`user_id`);

--
-- Chỉ mục cho bảng `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`holiday_id`);

--
-- Chỉ mục cho bảng `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`hotel_id`),
  ADD KEY `idx_hotel_id` (`hotel_id`);

--
-- Chỉ mục cho bảng `hotel_rating`
--
ALTER TABLE `hotel_rating`
  ADD PRIMARY KEY (`hotel_id`);

--
-- Chỉ mục cho bảng `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`language_code`);

--
-- Chỉ mục cho bảng `meal_types`
--
ALTER TABLE `meal_types`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `idx_booking_status` (`booking_id`,`status`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_permissions_parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `pricing_config`
--
ALTER TABLE `pricing_config`
  ADD PRIMARY KEY (`config_id`);

--
-- Chỉ mục cho bảng `pricing_history`
--
ALTER TABLE `pricing_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_pricing_history_date_room` (`applied_date`,`room_type_id`),
  ADD KEY `idx_pricing_history_room_date` (`room_type_id`,`applied_date`),
  ADD KEY `idx_pricing_history_date` (`applied_date`),
  ADD KEY `idx_pricing_history_price` (`adjusted_price`),
  ADD KEY `idx_pricing_history_created` (`created_at`);

--
-- Chỉ mục cho bảng `representatives`
--
ALTER TABLE `representatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD UNIQUE KEY `unique_user_role` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Chỉ mục cho bảng `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `idx_room_type_id` (`room_type_id`),
  ADD KEY `bed_type_fixed` (`bed_type_fixed`),
  ADD KEY `floor_id` (`floor_id`);

--
-- Chỉ mục cho bảng `room_availability`
--
ALTER TABLE `room_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `idx_option_date` (`option_id`,`date`);

--
-- Chỉ mục cho bảng `room_bed_types`
--
ALTER TABLE `room_bed_types`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_bed_unique` (`bed_type_id`),
  ADD KEY `idx_bed_type_id` (`bed_type_id`);

--
-- Chỉ mục cho bảng `room_meal_types`
--
ALTER TABLE `room_meal_types`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_meal_unique` (`meal_type_id`),
  ADD KEY `idx_meal_type_id` (`meal_type_id`);

--
-- Chỉ mục cho bảng `room_occupancy`
--
ALTER TABLE `room_occupancy`
  ADD PRIMARY KEY (`occupancy_id`),
  ADD UNIQUE KEY `idx_room_type_date` (`room_type_id`,`date`);

--
-- Chỉ mục cho bảng `room_option`
--
ALTER TABLE `room_option`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `idx_room_id` (`room_id`),
  ADD KEY `bed_type` (`bed_type`),
  ADD KEY `meal_type` (`meal_type`),
  ADD KEY `fk_room_option_deposit_policy` (`deposit_policy_id`),
  ADD KEY `fk_room_option_cancellation_policy` (`cancellation_policy_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Chỉ mục cho bảng `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  ADD PRIMARY KEY (`promotion_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Chỉ mục cho bảng `room_price_history`
--
ALTER TABLE `room_price_history`
  ADD PRIMARY KEY (`price_history_id`),
  ADD UNIQUE KEY `idx_room_type_date` (`room_type_id`,`date`);

--
-- Chỉ mục cho bảng `room_transfers`
--
ALTER TABLE `room_transfers`
  ADD PRIMARY KEY (`transfer_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `old_room_id` (`old_room_id`),
  ADD KEY `new_room_id` (`new_room_id`);

--
-- Chỉ mục cho bảng `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`room_type_id`);

--
-- Chỉ mục cho bảng `room_type_amenity`
--
ALTER TABLE `room_type_amenity`
  ADD PRIMARY KEY (`room_type_id`,`amenity_id`),
  ADD KEY `amenity_id` (`amenity_id`);

--
-- Chỉ mục cho bảng `room_type_image`
--
ALTER TABLE `room_type_image`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `room_image_ibfk_1` (`room_type_id`);

--
-- Chỉ mục cho bảng `room_type_package`
--
ALTER TABLE `room_type_package`
  ADD PRIMARY KEY (`package_id`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Chỉ mục cho bảng `room_type_package_services`
--
ALTER TABLE `room_type_package_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Chỉ mục cho bảng `room_type_service`
--
ALTER TABLE `room_type_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_type_id` (`room_type_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Chỉ mục cho bảng `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `table_translation`
--
ALTER TABLE `table_translation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `table_translation_table_name_unique` (`table_name`);

--
-- Chỉ mục cho bảng `translation`
--
ALTER TABLE `translation`
  ADD PRIMARY KEY (`translation_id`),
  ADD KEY `idx_translation` (`table_name`,`column_name`,`record_id`,`language_code`),
  ADD KEY `language_code` (`language_code`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Chỉ mục cho bảng `weekend_days`
--
ALTER TABLE `weekend_days`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `amenities`
--
ALTER TABLE `amenities`
  MODIFY `amenity_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `bed_types`
--
ALTER TABLE `bed_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã đặt phòng', AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT cho bảng `booking_extensions`
--
ALTER TABLE `booking_extensions`
  MODIFY `extension_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `booking_reschedules`
--
ALTER TABLE `booking_reschedules`
  MODIFY `reschedule_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `booking_rooms`
--
ALTER TABLE `booking_rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT cho bảng `cancellation_policies`
--
ALTER TABLE `cancellation_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `check_out_policies`
--
ALTER TABLE `check_out_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `check_out_requests`
--
ALTER TABLE `check_out_requests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `datafeeds`
--
ALTER TABLE `datafeeds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `deposit_policies`
--
ALTER TABLE `deposit_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `dynamic_pricing_rules`
--
ALTER TABLE `dynamic_pricing_rules`
  MODIFY `rule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `faqs`
--
ALTER TABLE `faqs`
  MODIFY `faq_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `flexible_pricing_rules`
--
ALTER TABLE `flexible_pricing_rules`
  MODIFY `rule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `floors`
--
ALTER TABLE `floors`
  MODIFY `floor_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã tầng', AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `holidays`
--
ALTER TABLE `holidays`
  MODIFY `holiday_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hotel`
--
ALTER TABLE `hotel`
  MODIFY `hotel_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã khách sạn', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `meal_types`
--
ALTER TABLE `meal_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã thanh toán', AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `pricing_config`
--
ALTER TABLE `pricing_config`
  MODIFY `config_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `pricing_history`
--
ALTER TABLE `pricing_history`
  MODIFY `history_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `representatives`
--
ALTER TABLE `representatives`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT cho bảng `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã phòng', AUTO_INCREMENT=296;

--
-- AUTO_INCREMENT cho bảng `room_availability`
--
ALTER TABLE `room_availability`
  MODIFY `availability_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã lịch', AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `room_occupancy`
--
ALTER TABLE `room_occupancy`
  MODIFY `occupancy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  MODIFY `promotion_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã khuyến mãi';

--
-- AUTO_INCREMENT cho bảng `room_price_history`
--
ALTER TABLE `room_price_history`
  MODIFY `price_history_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT cho bảng `room_transfers`
--
ALTER TABLE `room_transfers`
  MODIFY `transfer_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `room_types`
--
ALTER TABLE `room_types`
  MODIFY `room_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `room_type_image`
--
ALTER TABLE `room_type_image`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã ảnh', AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `room_type_package`
--
ALTER TABLE `room_type_package`
  MODIFY `package_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `room_type_package_services`
--
ALTER TABLE `room_type_package_services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `room_type_service`
--
ALTER TABLE `room_type_service`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `table_translation`
--
ALTER TABLE `table_translation`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `translation`
--
ALTER TABLE `translation`
  MODIFY `translation_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã bản dịch', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `weekend_days`
--
ALTER TABLE `weekend_days`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_booking_room` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `booking_extensions`
--
ALTER TABLE `booking_extensions`
  ADD CONSTRAINT `booking_extensions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `booking_reschedules`
--
ALTER TABLE `booking_reschedules`
  ADD CONSTRAINT `booking_reschedules_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD CONSTRAINT `booking_rooms_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  ADD CONSTRAINT `booking_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`),
  ADD CONSTRAINT `booking_rooms_ibfk_3` FOREIGN KEY (`representative_id`) REFERENCES `representatives` (`id`),
  ADD CONSTRAINT `booking_rooms_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `check_out_requests`
--
ALTER TABLE `check_out_requests`
  ADD CONSTRAINT `check_out_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `dynamic_pricing_rules`
--
ALTER TABLE `dynamic_pricing_rules`
  ADD CONSTRAINT `dynamic_pricing_rules_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `flexible_pricing_rules`
--
ALTER TABLE `flexible_pricing_rules`
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_3` FOREIGN KEY (`holiday_id`) REFERENCES `holidays` (`holiday_id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `guests`
--
ALTER TABLE `guests`
  ADD CONSTRAINT `fk_guests_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `hotel_rating`
--
ALTER TABLE `hotel_rating`
  ADD CONSTRAINT `hotel_rating_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`hotel_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `representatives`
--
ALTER TABLE `representatives`
  ADD CONSTRAINT `representatives_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  ADD CONSTRAINT `representatives_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`);

--
-- Ràng buộc cho bảng `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_ibfk_3` FOREIGN KEY (`bed_type_fixed`) REFERENCES `bed_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_ibfk_4` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`floor_number`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ràng buộc cho bảng `room_availability`
--
ALTER TABLE `room_availability`
  ADD CONSTRAINT `room_availability_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `room_bed_types`
--
ALTER TABLE `room_bed_types`
  ADD CONSTRAINT `fk_room_bed_type` FOREIGN KEY (`bed_type_id`) REFERENCES `bed_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_bed_types_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ràng buộc cho bảng `room_meal_types`
--
ALTER TABLE `room_meal_types`
  ADD CONSTRAINT `fk_room_meal_type` FOREIGN KEY (`meal_type_id`) REFERENCES `meal_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_meal_types_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ràng buộc cho bảng `room_occupancy`
--
ALTER TABLE `room_occupancy`
  ADD CONSTRAINT `room_occupancy_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `room_option`
--
ALTER TABLE `room_option`
  ADD CONSTRAINT `fk_room_option_cancellation_policy` FOREIGN KEY (`cancellation_policy_id`) REFERENCES `cancellation_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_room_option_deposit_policy` FOREIGN KEY (`deposit_policy_id`) REFERENCES `deposit_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_option_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_option_ibfk_2` FOREIGN KEY (`bed_type`) REFERENCES `bed_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_option_ibfk_3` FOREIGN KEY (`meal_type`) REFERENCES `meal_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_option_ibfk_4` FOREIGN KEY (`package_id`) REFERENCES `room_type_package` (`package_id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  ADD CONSTRAINT `room_option_promotion_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `room_price_history`
--
ALTER TABLE `room_price_history`
  ADD CONSTRAINT `room_price_history_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `room_transfers`
--
ALTER TABLE `room_transfers`
  ADD CONSTRAINT `room_transfers_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_transfers_ibfk_2` FOREIGN KEY (`old_room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `room_transfers_ibfk_3` FOREIGN KEY (`new_room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT;

--
-- Ràng buộc cho bảng `room_type_amenity`
--
ALTER TABLE `room_type_amenity`
  ADD CONSTRAINT `room_type_amenity_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_type_amenity_ibfk_2` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`amenity_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ràng buộc cho bảng `room_type_image`
--
ALTER TABLE `room_type_image`
  ADD CONSTRAINT `room_type_image_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ràng buộc cho bảng `room_type_package`
--
ALTER TABLE `room_type_package`
  ADD CONSTRAINT `room_type_package_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `room_type_package_services`
--
ALTER TABLE `room_type_package_services`
  ADD CONSTRAINT `room_type_package_services_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `room_type_package` (`package_id`),
  ADD CONSTRAINT `room_type_package_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);

--
-- Ràng buộc cho bảng `room_type_service`
--
ALTER TABLE `room_type_service`
  ADD CONSTRAINT `room_type_service_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_type_service_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `translation`
--
ALTER TABLE `translation`
  ADD CONSTRAINT `translation_ibfk_1` FOREIGN KEY (`language_code`) REFERENCES `language` (`language_code`) ON DELETE CASCADE;

DELIMITER $$
--
-- Sự kiện
--
CREATE DEFINER=`root`@`localhost` EVENT `insert_daily_room_occupancy` ON SCHEDULE EVERY 1 DAY STARTS '2025-07-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
  INSERT INTO room_occupancy (room_type_id, date, total_rooms, booked_rooms, occupancy_rate, created_at, updated_at)
  SELECT
    rt.room_type_id,
    CURRENT_DATE,
    rt.total_room,
    0,
    0,
    NOW(),
    NOW()
  FROM room_types AS rt
  WHERE rt.is_active = 1
    AND NOT EXISTS (
      SELECT 1 FROM room_occupancy ro
      WHERE ro.room_type_id = rt.room_type_id AND ro.date = CURRENT_DATE
    );
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
