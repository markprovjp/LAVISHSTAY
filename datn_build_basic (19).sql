-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th8 14, 2025 lúc 04:45 AM
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
-- Cơ sở dữ liệu: `datn_build_basic`
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

--
-- Đang đổ dữ liệu cho bảng `audit_logs`
--

INSERT INTO `audit_logs` (`log_id`, `user_id`, `action`, `table_name`, `record_id`, `description`, `created_at`) VALUES
(2, NULL, 'Room Transfer', 'booking', 24, 'Transferred from room 20 to room 20', '2025-08-02 16:21:40'),
(3, NULL, 'Room Transfer', 'booking', 24, 'Transferred from room 20 to room 20', '2025-08-02 16:33:47'),
(4, NULL, 'Room Transfer', 'booking', 24, 'Transferred from room 20 to room 20', '2025-08-02 16:35:08'),
(5, NULL, 'Room Transfer', 'booking', 24, 'Transferred from room 20 to room 20', '2025-08-02 16:40:34'),
(6, NULL, 'Room Transfer', 'booking', 24, 'Transferred from room 20 to room 20', '2025-08-02 16:40:36'),
(7, NULL, 'Room Transfer', 'booking', 24, 'Transferred from room 20 to room 20', '2025-08-02 16:40:39'),
(8, NULL, 'Room Transfer', 'booking', 24, 'Transferred from room 20 to room 20', '2025-08-03 04:19:40'),
(9, NULL, 'Room Transfer', 'booking', 24, 'Transferred from room 20 to room 20', '2025-08-03 04:19:46'),
(10, NULL, 'Room Transfer', 'booking', 24, 'Transferred from room 92 to room 92', '2025-08-03 04:22:14'),
(11, NULL, 'Room Transfer', 'booking', 24, 'Transferred from rooms 95 to rooms 95', '2025-08-03 11:18:19'),
(12, NULL, 'Reschedule Booking', 'booking', 24, 'Rescheduled booking from 2025-08-10 00:00:00 to 2025-08-10 and 2025-08-15 00:00:00 to 2025-08-15', '2025-08-03 16:23:41'),
(13, NULL, 'Reschedule Booking', 'booking', 24, 'Rescheduled booking from 2025-08-10 00:00:00 to 2025-08-10 and 2025-08-15 00:00:00 to 2025-08-15', '2025-08-03 16:24:41'),
(14, NULL, 'Reschedule Booking', 'booking', 24, 'Rescheduled booking from 2025-08-10 00:00:00 to 2025-08-10 and 2025-08-15 00:00:00 to 2025-08-15', '2025-08-03 16:25:27');

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
  `guest_count` int DEFAULT NULL COMMENT 'Số khách',
  `status` enum('Pending','Confirmed','Operational','Completed','Cancelled','Cancelled With Penalty','Unsuccessful') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Trạng thái đặt phòng',
  `booking_source` enum('website','phone','walk_in','agent','online_travel_agency') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Nguồn đặt',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `quantity` int DEFAULT NULL,
  `payment_policy` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
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

INSERT INTO `booking` (`booking_id`, `booking_code`, `user_id`, `option_id`, `check_in_date`, `check_out_date`, `total_price_vnd`, `guest_count`, `status`, `booking_source`, `notes`, `quantity`, `payment_policy`, `room_type_id`, `created_at`, `updated_at`, `guest_name`, `guest_email`, `guest_phone`, `room_id`, `children`, `children_age`) VALUES
(23, 'LAVISHSTAY_509999', NULL, NULL, '2025-07-01', '2025-07-02', 2400000.00, 2, 'Cancelled', NULL, '', 2, NULL, NULL, '2025-07-01 04:08:52', '2025-07-04 02:26:20', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', 255, NULL, NULL),
(24, 'LAVISHYSTAY_931923', 2, 'OPT10', '2025-08-10', '2025-08-15', 2825000.00, 2, 'Operational', NULL, '', 3, NULL, 2, '2025-07-04 03:41:38', '2025-08-03 16:25:27', 'húhu', 'quyen@gmai.comđ', '231443342423', 2, 2, '3'),
(25, 'LVS20250707030928246', NULL, NULL, '2025-07-07', '2025-07-08', 2880000.00, 2, 'Confirmed', NULL, '', 1, NULL, NULL, '2025-07-06 20:09:28', '2025-07-06 20:09:28', 'qeweqw', 'reception@hotel.com', '0335920306', NULL, 1, '4'),
(26, 'LVS20250707031018433', NULL, NULL, '2025-07-07', '2025-07-11', 5760000.00, 2, 'Confirmed', NULL, '', 1, NULL, NULL, '2025-07-06 20:10:18', '2025-07-06 20:10:18', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', NULL, 1, '4'),
(27, 'LVS20250707031110789', NULL, NULL, '2025-07-07', '2025-07-11', 5760000.00, 2, 'Confirmed', NULL, '', 1, NULL, NULL, '2025-07-06 20:11:10', '2025-07-06 20:11:10', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', NULL, 1, '10'),
(31, 'LVS31050405', NULL, NULL, '2025-07-09', '2025-07-10', 2000000.00, 2, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:04:05', '2025-07-07 22:04:05', 'Nguyen Van Test', 'test@email.com', '0123456789', NULL, 0, NULL),
(32, 'LVS32050513', NULL, NULL, '2025-07-09', '2025-07-10', 2000000.00, 2, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:05:13', '2025-07-07 22:05:13', 'Nguyen Van Test', 'test@email.com', '0123456789', NULL, 0, NULL),
(33, 'LVS33050538', NULL, NULL, '2025-07-09', '2025-07-10', 2000000.00, 2, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:05:38', '2025-07-07 22:05:38', 'Nguyen Van Test', 'test@email.com', '0123456789', NULL, 0, NULL),
(34, 'LVS34050642', NULL, NULL, '2025-07-07', '2025-07-08', 3200000.00, 3, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:06:42', '2025-07-07 22:06:42', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(35, 'LVS35050702', NULL, NULL, '2025-07-07', '2025-07-08', 3200000.00, 3, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:07:02', '2025-07-07 22:07:02', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(36, 'LVS36050814', NULL, NULL, '2025-07-07', '2025-07-08', 3200000.00, 3, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:08:14', '2025-07-07 22:08:14', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(37, 'LVS37051044', NULL, NULL, '2025-07-07', '2025-07-08', 2300000.00, 3, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-07 22:10:44', '2025-07-07 22:10:44', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', NULL, 0, NULL),
(38, 'LVS38051120', NULL, NULL, '2025-07-07', '2025-07-08', 2300000.00, 3, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-07 22:11:20', '2025-07-07 22:11:20', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(39, 'LVS39063958', NULL, NULL, '2025-07-07', '2025-07-08', 6200000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-07 23:39:57', '2025-07-07 23:39:58', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(40, 'LVS40064052', NULL, NULL, '2025-07-07', '2025-07-08', 6200000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-07 23:40:52', '2025-07-07 23:40:52', 'qeweqw', 'reception@hotel.com', '0987654321', NULL, 0, NULL),
(41, 'LVS41073901', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:39:01', '2025-07-08 00:39:01', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(42, 'LVS42074259', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:42:59', '2025-07-08 00:42:59', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(43, 'LVS43074325', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:43:25', '2025-07-08 00:43:25', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(44, 'LVS44074700', NULL, NULL, '2025-07-07', '2025-07-08', 7320000.00, 7, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:47:00', '2025-07-08 00:47:00', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(45, 'LVS45075351', NULL, NULL, '2025-07-07', '2025-07-08', 8640000.00, 13, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:53:51', '2025-07-08 00:53:51', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(46, 'LVS46075512', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 6, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:55:12', '2025-07-08 00:55:12', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(51, 'LVS51083510', NULL, NULL, '2025-07-07', '2025-07-08', 2880000.00, 11, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 01:35:10', '2025-07-08 01:35:10', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[]'),
(53, 'LVS53093059', NULL, NULL, '2025-07-09', '2025-07-10', 4320000.00, 13, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 02:30:59', '2025-07-08 02:30:59', 'Nguyen Van Test', 'test@gmail.com', '0987654321', NULL, 5, '[]'),
(54, 'LVS54093118', NULL, NULL, '2025-07-09', '2025-07-10', 1200000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 02:31:18', '2025-07-08 02:31:18', 'Test User', 'test@test.com', '0123456789', NULL, 0, '[]'),
(56, 'LVS56094825', NULL, NULL, '2025-07-09', '2025-07-10', 1200000.00, 5, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 02:48:25', '2025-07-08 02:48:25', 'Test User Full', 'test@test.com', '0123456789', NULL, 3, '[[{\"age\": 8}, {\"age\": 10}, {\"age\": 5}]]'),
(60, 'LVS60104819', NULL, NULL, '2025-07-07', '2025-07-08', 4320000.00, 13, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 03:48:19', '2025-07-08 03:48:19', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[[{\"id\": \"room_0_child_1\", \"age\": 8}, {\"id\": \"room_0_child_2\", \"age\": 8}, {\"id\": \"room_0_child_3\", \"age\": 8}], [{\"id\": \"room_1_child_1\", \"age\": 8}, {\"id\": \"room_1_child_2\", \"age\": 8}], []]'),
(61, 'LVS61105534', NULL, NULL, '2025-07-07', '2025-07-08', 4320000.00, 13, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 03:55:34', '2025-07-08 03:55:34', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[[{\"id\": \"room_0_child_1\", \"age\": 8}, {\"id\": \"room_0_child_2\", \"age\": 8}, {\"id\": \"room_0_child_3\", \"age\": 8}], [{\"id\": \"room_1_child_1\", \"age\": 8}, {\"id\": \"room_1_child_2\", \"age\": 8}], []]'),
(62, 'LVS62153758', NULL, NULL, '2025-07-07', '2025-07-08', 4320000.00, 13, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 08:37:58', '2025-07-08 08:37:58', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[[{\"id\": \"room_0_child_1\", \"age\": 8}, {\"id\": \"room_0_child_2\", \"age\": 8}, {\"id\": \"room_0_child_3\", \"age\": 8}], [{\"id\": \"room_1_child_1\", \"age\": 8}, {\"id\": \"room_1_child_2\", \"age\": 8}], []]'),
(63, 'LVS63162115', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 09:21:15', '2025-07-08 09:45:21', 'Huỳnh Thị Bích Tuyền', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '[[]]'),
(64, 'LVS64164554', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 09:45:54', '2025-07-08 09:46:51', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '[[]]'),
(65, 'LVS65165011', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 09:50:11', '2025-07-08 09:50:54', 'qeweqw', 'quyenjpn@gmail.com', '333241324342', NULL, 0, '[[]]'),
(66, 'LVS66165335', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 09:53:35', '2025-07-08 09:54:06', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '[[]]'),
(67, 'LVS67031840', NULL, NULL, '2025-07-08', '2025-07-09', 11000.00, 1, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 20:18:40', '2025-07-08 20:19:17', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '[[]]'),
(68, 'LVS68032045', NULL, NULL, '2025-07-08', '2025-07-10', 22000.00, 3, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 20:20:45', '2025-07-08 20:22:08', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 1, '[[{\"id\": \"room_0_child_1\", \"age\": 8}]]'),
(69, 'LVS69050107', NULL, NULL, '2025-07-08', '2025-07-11', 33000.00, 3, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 22:01:07', '2025-07-08 22:01:07', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 1, '[[{\"id\": \"room_0_child_1\", \"age\": 8}]]'),
(75, 'LVS75070930', NULL, NULL, '2025-07-08', '2025-07-09', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-09 00:09:30', '2025-07-09 00:09:30', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 1, '[[{\"id\": \"room_0_child_1\", \"age\": 8}]]'),
(76, 'LVS76073559', NULL, NULL, '2025-07-08', '2025-07-09', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-09 00:35:59', '2025-07-09 00:35:59', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 1, '[[{\"id\": \"room_0_child_1\", \"age\": 8}]]'),
(77, 'LVS77082516', NULL, NULL, '2025-07-08', '2025-07-09', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-09 01:25:16', '2025-07-09 01:25:16', '明心', 'quyenjpn@gmail.com', '12342342341', NULL, 1, '[[{\"id\": \"room_0_child_1\", \"age\": 8}]]'),
(79, 'LVS79072153', NULL, NULL, '2025-07-13', '2025-07-14', 6200000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 00:21:53', '2025-07-14 00:21:53', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(80, 'LVS80072418', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-14 00:24:18', '2025-07-14 00:26:13', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(81, 'LVS81091621', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:16:21', '2025-07-14 02:17:03', '明心', 'quyenjpn@gmail.com', '123412341234', NULL, 0, '{\"totals\": {\"nights\": 1, \"taxAmount\": 0, \"finalTotal\": 11000, \"roomsTotal\": 11000, \"serviceFee\": 0, \"breakfastTotal\": 0, \"discountAmount\": 0}, \"rooms_data\": [{\"adults\": 2, \"room_id\": \"1\", \"bed_type\": null, \"children\": 0, \"policies\": {\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}, \"meal_type\": null, \"option_id\": \"pkg-1\", \"guest_name\": \"明心\", \"package_id\": \"1\", \"room_price\": 11000, \"guest_email\": \"quyenjpn@gmail.com\", \"guest_phone\": \"123412341234\", \"option_name\": \"Standard Package\", \"recommended\": 1, \"children_age\": [], \"most_popular\": 0, \"option_price\": 11000, \"payment_policy\": \"Đặt cọc 30% giá trị booking\", \"urgency_message\": null, \"check_out_policy\": \"Check-out tiêu chuẩn 12:00\", \"deposit_percentage\": \"30.00\", \"penalty_percentage\": \"0.00\", \"cancellation_policy\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"deposit_fixed_amount\": \"0.00\", \"penalty_fixed_amount\": \"200000.00\", \"recommendation_score\": null, \"free_cancellation_days\": 7, \"standard_check_out_time\": \"12:00:00\"}], \"payment_method\": \"vietqr\"}'),
(82, 'LVS82092729', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:27:29', '2025-07-14 02:27:29', '明心', 'quyenjpn@gmail.com', '123412342', NULL, 0, '{\"totals\": {\"nights\": 1, \"taxAmount\": 0, \"finalTotal\": 11000, \"roomsTotal\": 11000, \"serviceFee\": 0, \"breakfastTotal\": 0, \"discountAmount\": 0}, \"rooms_data\": [{\"adults\": 2, \"room_id\": \"1\", \"bed_type\": null, \"children\": 0, \"policies\": {\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}, \"meal_type\": null, \"option_id\": \"pkg-1\", \"guest_name\": \"明心\", \"package_id\": \"1\", \"room_price\": 11000, \"guest_email\": \"quyenjpn@gmail.com\", \"guest_phone\": \"123412342\", \"option_name\": \"Standard Package\", \"recommended\": 1, \"children_age\": [], \"most_popular\": 0, \"option_price\": 11000, \"payment_policy\": \"Đặt cọc 30% giá trị booking\", \"urgency_message\": null, \"check_out_policy\": \"Check-out tiêu chuẩn 12:00\", \"deposit_percentage\": \"30.00\", \"penalty_percentage\": \"0.00\", \"cancellation_policy\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"deposit_fixed_amount\": \"0.00\", \"penalty_fixed_amount\": \"200000.00\", \"recommendation_score\": null, \"free_cancellation_days\": 7, \"standard_check_out_time\": \"12:00:00\"}], \"payment_method\": \"vietqr\"}'),
(85, 'LVS85093648', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:36:48', '2025-07-14 02:36:48', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '{\"totals\": {\"nights\": 1, \"taxAmount\": 0, \"finalTotal\": 11000, \"roomsTotal\": 11000, \"serviceFee\": 0, \"breakfastTotal\": 0, \"discountAmount\": 0}, \"rooms_data\": [{\"adults\": 2, \"room_id\": \"1\", \"bed_type\": null, \"children\": 0, \"policies\": {\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}, \"meal_type\": null, \"option_id\": \"pkg-1\", \"guest_name\": \"明心\", \"package_id\": \"1\", \"room_price\": 11000, \"guest_email\": \"quyenjpn@gmail.com\", \"guest_phone\": \"0987654321\", \"option_name\": \"Standard Package\", \"recommended\": 1, \"children_age\": [], \"most_popular\": 0, \"option_price\": 11000, \"payment_policy\": \"Đặt cọc 30% giá trị booking\", \"urgency_message\": null, \"check_out_policy\": \"Check-out tiêu chuẩn 12:00\", \"deposit_percentage\": \"30.00\", \"penalty_percentage\": \"0.00\", \"cancellation_policy\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"deposit_fixed_amount\": \"0.00\", \"penalty_fixed_amount\": \"200000.00\", \"recommendation_score\": null, \"free_cancellation_days\": 7, \"standard_check_out_time\": \"12:00:00\"}], \"payment_method\": \"vietqr\"}'),
(88, 'LVS88094850', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:48:50', '2025-07-14 02:49:53', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(89, 'LVS89103511', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 03:35:11', '2025-07-14 03:35:11', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(90, 'LVS90104127', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 03:41:27', '2025-07-14 03:41:27', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(91, 'LVS91104507', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 03:45:07', '2025-07-14 03:45:07', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(92, 'LVS92105428', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-14 03:54:28', '2025-07-14 11:05:54', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(93, 'LVS93105832', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 03:58:32', '2025-07-14 03:58:32', '明心', 'quyenjpn@gmail.com', '23413421243', NULL, 0, NULL),
(94, 'LVS94111645', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, '12341234', NULL, NULL, NULL, '2025-07-14 04:16:45', '2025-07-14 04:21:04', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(95, 'LVS95112222', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, 'hú hsu loo lô', NULL, NULL, NULL, '2025-07-14 04:22:22', '2025-07-14 04:22:29', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(96, 'LVS96112503', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, 'sdffsdfdfgdfgsd', NULL, NULL, NULL, '2025-07-14 04:25:03', '2025-07-14 04:25:53', '明心', 'quyenjpn@gmail.com', '1234123412431', NULL, 0, NULL),
(97, 'LVS97113050', NULL, NULL, '2025-07-13', '2025-07-14', 132000.00, 5, 'Pending', NULL, '412324311234', NULL, NULL, NULL, '2025-07-14 04:30:50', '2025-07-14 04:30:50', '明心', 'quyenjpn@gmail.com', '124314232134', NULL, 0, NULL),
(98, 'LVS98114231', NULL, NULL, '2025-07-13', '2025-07-14', 132000.00, 5, 'Confirmed', NULL, '11234234123', NULL, NULL, NULL, '2025-07-14 04:42:31', '2025-07-14 04:42:51', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(99, 'LVS99114449', NULL, NULL, '2025-07-13', '2025-07-14', 132000.00, 5, 'Confirmed', NULL, '12341234', NULL, NULL, NULL, '2025-07-14 04:44:49', '2025-07-14 04:44:57', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, 0, NULL),
(100, 'LVS100023425', NULL, NULL, '2025-07-14', '2025-07-15', 132000.00, 5, 'Confirmed', NULL, '123r1243', NULL, NULL, NULL, '2025-07-14 19:34:25', '2025-07-14 19:35:46', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(101, 'LVS101023558', NULL, NULL, '2025-07-14', '2025-07-15', 132000.00, 5, 'Confirmed', NULL, '123412342314', NULL, NULL, NULL, '2025-07-14 19:35:58', '2025-07-14 19:37:12', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(102, 'LVS102024015', NULL, NULL, '2025-07-14', '2025-07-15', 132000.00, 5, 'Confirmed', NULL, '1234123421', NULL, NULL, NULL, '2025-07-14 19:40:15', '2025-07-14 19:40:18', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(103, 'LVS103024501', NULL, NULL, '2025-07-14', '2025-07-15', 132000.00, 5, 'Confirmed', NULL, '1341234123', NULL, NULL, NULL, '2025-07-14 19:45:01', '2025-07-14 19:45:43', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(104, 'LVS104024936', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '1234', NULL, NULL, NULL, '2025-07-14 19:49:36', '2025-07-14 19:49:44', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(105, 'LVS105025917', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '1234', NULL, NULL, NULL, '2025-07-14 19:59:17', '2025-07-14 20:00:09', '明心', 'quyenjpn@gmail.com', '1234', NULL, 0, NULL),
(106, 'LVS106030509', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '23452', NULL, NULL, NULL, '2025-07-14 20:05:09', '2025-07-14 20:05:12', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(107, 'LVS107030523', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '253425432543', NULL, NULL, NULL, '2025-07-14 20:05:23', '2025-07-14 20:08:36', '明心', 'quyenjpn@gmail.com', '2354', NULL, 0, NULL),
(108, 'LVS108031734', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, '1234234', NULL, NULL, NULL, '2025-07-14 20:17:34', '2025-07-14 20:17:34', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(109, 'LVS109033233', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, '123414231423', NULL, NULL, NULL, '2025-07-14 20:32:33', '2025-07-14 20:32:33', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(110, 'LVS110041104', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, '31241234', NULL, NULL, NULL, '2025-07-14 21:11:04', '2025-07-14 21:11:04', '明心', 'quyenjpn@gmail.com', '12341234', NULL, 0, NULL),
(111, 'LVS111042232', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 21:22:32', '2025-07-14 21:22:32', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL),
(112, 'LVS112044511', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '2341', NULL, NULL, NULL, '2025-07-14 21:45:11', '2025-07-14 22:05:39', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(113, 'LVS113070418', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '324234', NULL, NULL, NULL, '2025-07-15 00:04:18', '2025-07-15 00:04:49', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(114, 'LVS114070529', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:05:29', '2025-07-15 00:07:55', '明心', 'quyenjpn@gmail.com', '1234124312341', NULL, NULL, NULL),
(115, 'LVS115071036', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:10:36', '2025-07-15 00:10:36', '明心', 'quyenjpn@gmail.com', '2134', NULL, NULL, NULL),
(116, 'LVS116072010', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:20:10', '2025-07-15 00:20:10', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(117, 'LVS117072552', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:25:52', '2025-07-15 00:25:52', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(118, 'LVS118072800', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:27:59', '2025-07-15 00:28:00', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(119, 'LVS119075227', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:52:27', '2025-07-15 00:52:27', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(120, 'LVS120085204', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 01:52:04', '2025-07-15 01:52:04', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(121, 'LVS121091522', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-15 02:15:22', '2025-07-15 02:30:14', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(124, 'LVS124093516', NULL, NULL, '2025-07-14', '2025-07-16', 22000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 02:35:16', '2025-07-15 02:35:16', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(125, 'LVS125105711', NULL, NULL, '2025-07-15', '2025-07-16', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 03:57:11', '2025-07-15 03:57:11', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(126, 'LVS126023058', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 19:30:58', '2025-07-15 19:30:58', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(127, 'LVS127025346', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-15 19:53:46', '2025-07-15 19:54:17', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(128, 'LVS128025435', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Operational', NULL, NULL, NULL, NULL, NULL, '2025-07-15 19:54:35', '2025-07-18 12:57:45', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(129, 'LVS129030846', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Operational', NULL, 'hihihihi', NULL, NULL, NULL, '2025-07-15 20:08:46', '2025-07-18 12:57:45', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(130, 'LVS130033257', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Operational', NULL, NULL, NULL, NULL, NULL, '2025-07-15 20:32:57', '2025-07-18 12:57:45', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(131, 'LVS131033527', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-15 20:35:27', '2025-07-15 20:36:09', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(132, 'LVS132033857', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Confirmed', NULL, '1234', NULL, NULL, NULL, '2025-07-15 20:38:57', '2025-07-15 20:39:30', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL),
(133, 'LVS133070932', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-16 00:09:32', '2025-07-16 00:10:00', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(134, 'LVS134032119', NULL, NULL, '2025-07-17', '2025-07-18', 11000.00, 1, 'Pending', NULL, 'qeqweqwqwe', NULL, NULL, NULL, '2025-07-16 20:21:19', '2025-07-16 20:21:19', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(136, 'LVS20250717034906701', NULL, NULL, '2025-07-17', '2025-07-24', 42000.00, 1, 'Confirmed', NULL, NULL, 1, NULL, NULL, '2025-07-16 20:49:06', '2025-07-16 20:49:06', 'Quyền Nguyễn Văn', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(137, 'LVS137075500', NULL, NULL, '2025-07-17', '2025-07-18', 5510000.00, 5, 'Confirmed', NULL, 'bich tuyen', NULL, NULL, 6, '2025-07-17 00:55:00', '2025-07-18 09:29:12', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(138, 'LVS138081920', NULL, NULL, '2025-07-17', '2025-07-18', 5400000.00, 4, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-17 01:19:20', '2025-07-17 01:20:38', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(139, 'LVS139082730', NULL, NULL, '2025-07-17', '2025-07-18', 5400000.00, 4, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-17 01:27:30', '2025-07-17 01:28:01', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(140, 'LVS140103856', NULL, NULL, '2025-07-17', '2025-07-18', 8650000.00, 13, 'Confirmed', NULL, 'test', NULL, NULL, NULL, '2025-07-17 03:38:56', '2025-07-17 03:39:28', 'Quyền', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(141, 'LVS141104347', NULL, NULL, '2025-07-17', '2025-07-18', 8650000.00, 13, 'Confirmed', NULL, 'trtyrtrytyre', NULL, NULL, NULL, '2025-07-17 03:43:47', '2025-07-17 03:44:07', '明têttetetete', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(142, 'LVS142120903', NULL, NULL, '2025-07-17', '2025-07-18', 8650000.00, 13, 'Confirmed', NULL, 'bich tuyen cute', NULL, NULL, 6, '2025-07-17 05:09:03', '2025-07-18 09:05:00', 'test', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(143, 'LVS143125143', NULL, NULL, '2025-08-18', '2025-08-21', 15450000.00, 10, 'Cancelled', NULL, NULL, NULL, NULL, NULL, '2025-07-18 05:51:43', '2025-07-18 19:10:32', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(144, 'LVS144031538', NULL, NULL, '2025-08-18', '2025-08-21', 20850000.00, 10, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-18 20:15:38', '2025-07-18 20:16:40', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(145, 'LVS145032559', NULL, NULL, '2025-08-18', '2025-08-21', 20850000.00, 10, 'Pending', NULL, 'kokoko', NULL, NULL, NULL, '2025-07-18 20:25:59', '2025-07-18 20:25:59', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(146, 'LVS146033153', NULL, NULL, '2025-08-18', '2025-08-21', 20850000.00, 10, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-18 20:31:53', '2025-07-18 20:31:53', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(147, 'LVS147033726', NULL, NULL, '2025-08-18', '2025-08-21', 1716000.00, 10, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-18 20:37:26', '2025-07-18 20:37:26', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(148, 'LVS148033746', NULL, NULL, '2025-08-18', '2025-08-21', 20850000.00, 10, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-18 20:37:46', '2025-07-18 20:37:46', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(149, 'LVS149034419', NULL, NULL, '2025-08-14', '2025-08-16', 20850000.00, 10, 'Cancelled', NULL, 'thgisch', NULL, NULL, 6, '2025-07-18 20:44:19', '2025-07-31 13:56:40', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(150, 'LVS150034633', NULL, NULL, '2025-08-18', '2025-08-21', 20850000.00, 10, 'Cancelled', NULL, NULL, NULL, NULL, 6, '2025-07-18 20:46:33', '2025-07-31 13:57:03', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(151, 'LVS151023546', NULL, NULL, '2025-08-18', '2025-08-22', 27800000.00, 10, 'Cancelled', NULL, NULL, NULL, NULL, 6, '2025-07-19 19:35:46', '2025-07-31 13:57:29', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(152, 'LVS152023648', NULL, NULL, '2025-08-18', '2025-08-22', 10248000.00, 10, 'Confirmed', NULL, NULL, NULL, NULL, 1, '2025-07-19 19:36:48', '2025-07-19 19:37:24', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(153, 'LVS153025209', NULL, NULL, '2025-08-18', '2025-08-22', 2288000.00, 10, 'Cancelled', NULL, NULL, NULL, NULL, 1, '2025-07-19 19:52:09', '2025-07-31 13:37:24', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL),
(154, 'LVS154095008', NULL, NULL, '2025-07-22', '2025-07-23', 11000.00, 2, 'Pending', NULL, 'v', NULL, NULL, 1, '2025-07-21 02:50:08', '2025-07-21 02:50:08', 'Đào Tùng Dưn', 'dun@gmail.com', '02151651121', NULL, NULL, NULL),
(155, 'LVS155092059', NULL, NULL, '2025-07-28', '2025-07-29', 22000.00, 6, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-07-28 02:20:59', '2025-07-28 02:20:59', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL),
(156, 'LVS156093654', NULL, NULL, '2025-07-28', '2025-07-29', 22000.00, 6, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-07-28 02:36:54', '2025-07-28 02:36:54', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL),
(157, 'LVS157124518', NULL, NULL, '2025-07-28', '2025-07-29', 22000.00, 6, 'Pending', NULL, 'q', NULL, NULL, 1, '2025-07-28 05:45:18', '2025-07-28 05:45:18', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL),
(158, 'LVS158124550', NULL, NULL, '2025-07-28', '2025-07-29', 22000.00, 6, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-07-28 05:45:50', '2025-07-28 05:45:50', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL),
(159, 'LVS159132746', NULL, NULL, '2025-07-28', '2025-07-29', 22000.00, 6, 'Pending', NULL, NULL, NULL, NULL, 1, '2025-07-28 06:27:46', '2025-07-28 06:27:46', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL),
(160, 'LVS160021712', NULL, NULL, '2025-08-01', '2025-08-05', 49414.40, 6, 'Operational', NULL, 'za', NULL, NULL, 1, '2025-07-28 19:17:12', '2025-08-02 15:11:50', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL);

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
  `new_check_in_date` date DEFAULT NULL,
  `new_check_out_date` date DEFAULT NULL,
  `new_room_id` int DEFAULT NULL,
  `new_option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reschedule_policy_id` int DEFAULT NULL,
  `price_difference_vnd` decimal(15,2) DEFAULT NULL,
  `payment_id` int DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `suggested_rooms` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `processed_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `booking_reschedules`
--

INSERT INTO `booking_reschedules` (`reschedule_id`, `booking_id`, `new_check_in_date`, `new_check_out_date`, `new_room_id`, `new_option_id`, `reschedule_policy_id`, `price_difference_vnd`, `payment_id`, `status`, `reason`, `suggested_rooms`, `created_at`, `updated_at`, `processed_by`) VALUES
(2, 24, '2025-08-10', '2025-08-15', 2, 'OPT10', 5, -5175000.00, 118, 'Approved', 'Thay đổi kế hoạch cá nhân', '\"[]\"', '2025-08-03 16:23:41', '2025-08-03 16:23:41', NULL),
(3, 24, '2025-08-10', '2025-08-15', 4, 'OPT10', 5, 0.00, NULL, 'Approved', 'Thay đổi kế hoạch cá nhân', '\"[]\"', '2025-08-03 16:24:41', '2025-08-03 16:24:41', NULL),
(4, 24, '2025-08-10', '2025-08-15', 2, 'OPT10', 5, 0.00, NULL, 'Approved', 'Thay đổi kế hoạch cá nhân', '\"[]\"', '2025-08-03 16:25:27', '2025-08-03 16:25:27', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_rooms`
--

CREATE TABLE `booking_rooms` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `booking_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `option_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
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
(70, 66, 'LVS66165335', 1, 'BOOK-LVS66165335-R1-1', 'Standard Package', 11000.00, NULL, 2, 0, NULL, 11000, 1, 11000, '2025-07-07', '2025-07-08', '2025-07-08 09:53:35', '2025-07-08 09:53:35'),
(71, 67, 'LVS67031840', 1, 'BOOK-LVS67031840-R1-1', 'Standard Package', 11000.00, NULL, 1, 0, NULL, 11000, 1, 11000, '2025-07-08', '2025-07-09', '2025-07-08 20:18:40', '2025-07-08 20:18:40'),
(72, 68, 'LVS68032045', 1, 'BOOK-LVS68032045-R1-1', 'Standard Package', 11000.00, NULL, 2, 1, '[8]', 11000, 2, 22000, '2025-07-08', '2025-07-10', '2025-07-08 20:20:45', '2025-07-08 20:20:45'),
(73, 69, 'LVS69050107', 1, 'BOOK-LVS69050107-R1-1', 'Standard Package', 11000.00, NULL, 2, 1, '[8]', 11000, 3, 33000, '2025-07-08', '2025-07-11', '2025-07-08 22:01:07', '2025-07-08 22:01:07'),
(76, 75, 'LVS75070930', 1, 'BOOK-LVS75070930-R1-1', 'Standard Package', 11000.00, NULL, 1, 1, '[{\"id\": \"room_0_child_1\", \"age\": 8}]', 11000, 1, 11000, '2025-07-08', '2025-07-09', '2025-07-09 00:09:30', '2025-07-09 00:09:30'),
(77, 76, 'LVS76073559', 1, 'BOOK-LVS76073559-R1-1', 'Standard Package', 11000.00, NULL, 1, 1, '[{\"id\": \"room_0_child_1\", \"age\": 8}]', 11000, 1, 11000, '2025-07-08', '2025-07-09', '2025-07-09 00:35:59', '2025-07-09 00:35:59'),
(78, 77, 'LVS77082516', 1, 'BOOK-LVS77082516-R1-1', 'Standard Package', 11000.00, NULL, 1, 1, '[{\"id\": \"room_0_child_1\", \"age\": 8}]', 11000, 1, 11000, '2025-07-08', '2025-07-09', '2025-07-09 01:25:16', '2025-07-09 01:25:16'),
(79, 79, 'LVS79072153', 7, 'BOOK-LVS79072153-R7-1', 'Presidential Package', 6200000.00, 63, 2, 0, NULL, 6200000, 1, 6200000, '2025-07-13', '2025-07-14', '2025-07-14 00:21:53', '2025-07-14 00:21:53'),
(80, 80, 'LVS80072418', 1, 'BOOK-LVS80072418-R1-1', 'Standard Package', 11000.00, 64, 2, 0, NULL, 11000, 1, 11000, '2025-07-13', '2025-07-14', '2025-07-14 00:24:18', '2025-07-14 00:24:18'),
(81, 88, 'LVS88094850', 1, 'BOOK-LVS88094850-R1-1', 'Standard Package', 11000.00, 72, 2, 0, NULL, 11000, 1, 11000, '2025-07-13', '2025-07-14', '2025-07-14 02:48:50', '2025-07-14 02:48:50'),
(82, 92, 'LVS92105428', 1, 'BOOK-LVS92105428-R1-1', 'Standard Package', 11000.00, 73, 2, 0, NULL, 11000, 1, 11000, '2025-07-13', '2025-07-14', '2025-07-14 03:54:28', '2025-07-14 03:54:28'),
(83, 93, 'LVS93105832', 1, 'BOOK-LVS93105832-R1-1', 'Standard Package', 11000.00, 74, 2, 0, NULL, 11000, 1, 11000, '2025-07-13', '2025-07-14', '2025-07-14 03:58:32', '2025-07-14 03:58:32'),
(87, 94, 'LVS94111645', 1, 'BOOK-LVS94111645-R1-1', 'Standard Package', 11000.00, 78, 2, 0, NULL, 11000, 1, 11000, '2025-07-13', '2025-07-14', '2025-07-14 04:21:04', '2025-07-14 04:21:04'),
(88, 95, 'LVS95112222', 1, 'BOOK-LVS95112222-R1-1', 'Standard Package', 11000.00, 79, 2, 0, NULL, 11000, 1, 11000, '2025-07-13', '2025-07-14', '2025-07-14 04:22:29', '2025-07-14 04:22:29'),
(89, 96, 'LVS96112503', 1, 'BOOK-LVS96112503-R1-1', 'Standard Package', 11000.00, 80, 2, 0, NULL, 11000, 1, 11000, '2025-07-13', '2025-07-14', '2025-07-14 04:25:53', '2025-07-14 04:25:53'),
(90, 98, 'LVS98114231', 1, 'BOOK-LVS98114231-R1-1', 'Standard Package', 11000.00, 81, 2, 0, NULL, 132000, 1, 132000, '2025-07-13', '2025-07-14', '2025-07-14 04:42:51', '2025-07-14 04:42:51'),
(91, 98, 'LVS98114231', 1, 'BOOK-LVS98114231-R1-2', 'Standard Package', 11000.00, 81, 2, 1, NULL, 132000, 1, 132000, '2025-07-13', '2025-07-14', '2025-07-14 04:42:51', '2025-07-14 04:42:51'),
(92, 99, 'LVS99114449', 1, 'BOOK-LVS99114449-R1-1', 'Standard Package', 11000.00, 82, 2, 0, NULL, 132000, 1, 132000, '2025-07-13', '2025-07-14', '2025-07-14 04:44:57', '2025-07-14 04:44:57'),
(93, 99, 'LVS99114449', 1, 'BOOK-LVS99114449-R1-2', 'Standard Package', 11000.00, 82, 2, 1, NULL, 132000, 1, 132000, '2025-07-13', '2025-07-14', '2025-07-14 04:44:57', '2025-07-14 04:44:57'),
(94, 100, 'LVS100023425', 1, 'BOOK-LVS100023425-R1-1', 'Standard Package', 11000.00, 83, 2, 0, NULL, 132000, 1, 132000, '2025-07-14', '2025-07-15', '2025-07-14 19:35:46', '2025-07-14 19:35:46'),
(95, 100, 'LVS100023425', 1, 'BOOK-LVS100023425-R1-2', 'Standard Package', 11000.00, 83, 2, 1, NULL, 132000, 1, 132000, '2025-07-14', '2025-07-15', '2025-07-14 19:35:46', '2025-07-14 19:35:46'),
(96, 103, 'LVS103024501', 1, 'BOOK-LVS103024501-R1-1', 'Standard Package', 11000.00, 86, 2, 0, NULL, 132000, 1, 132000, '2025-07-14', '2025-07-15', '2025-07-14 19:45:43', '2025-07-14 19:45:43'),
(97, 103, 'LVS103024501', 1, 'BOOK-LVS103024501-R1-2', 'Standard Package', 11000.00, 86, 2, 1, NULL, 132000, 1, 132000, '2025-07-14', '2025-07-15', '2025-07-14 19:45:43', '2025-07-14 19:45:43'),
(98, 104, 'LVS104024936', 1, 'BOOK-LVS104024936-R1-1', 'Standard Package', 11000.00, 87, 2, 0, NULL, 11000, 1, 11000, '2025-07-14', '2025-07-15', '2025-07-14 19:49:44', '2025-07-14 19:49:44'),
(99, 105, 'LVS105025917', 1, 'BOOK-LVS105025917-R1-1', 'Standard Package', 11000.00, 88, 2, 0, NULL, 11000, 1, 11000, '2025-07-14', '2025-07-15', '2025-07-14 19:59:17', '2025-07-14 19:59:17'),
(100, 106, 'LVS106030509', 1, 'BOOK-LVS106030509-R1-1', 'Standard Package', 11000.00, 89, 2, 0, NULL, 11000, 1, 11000, '2025-07-14', '2025-07-15', '2025-07-14 20:05:12', '2025-07-14 20:05:12'),
(101, 107, 'LVS107030523', 1, 'BOOK-LVS107030523-R1-1', 'Standard Package', 11000.00, 90, 2, 0, NULL, 11000, 1, 11000, '2025-07-14', '2025-07-15', '2025-07-14 20:08:35', '2025-07-14 20:08:35'),
(109, 113, 'LVS113070418', 1, 'BOOK-LVS113070418-R1-1', 'Standard Package', 11000.00, 98, 2, 0, NULL, 11000, 1, 11000, '2025-07-14', '2025-07-15', '2025-07-15 00:04:49', '2025-07-15 00:04:49'),
(110, 114, 'LVS114070529', 1, 'BOOK-LVS114070529-R1-1', 'Standard Package', 11000.00, 99, 2, 0, NULL, 11000, 1, 11000, '2025-07-14', '2025-07-15', '2025-07-15 00:07:55', '2025-07-15 00:07:55'),
(111, 121, 'LVS121091522', 1, 'BOOK-LVS121091522-R1-1', 'Standard Package', 11000.00, 100, 2, 0, NULL, 11000, 1, 11000, '2025-07-14', '2025-07-15', '2025-07-15 02:15:22', '2025-07-15 02:15:22'),
(112, 124, 'LVS124093516', 1, 'BOOK-LVS124093516-R1-1', 'Standard Package', 11000.00, 103, 2, 0, NULL, 22000, 2, 44000, '2025-07-14', '2025-07-16', '2025-07-15 02:35:16', '2025-07-15 02:35:16'),
(113, 127, 'LVS127025346', 1, 'BOOK-LVS127025346-R1-1', 'Standard Package', 11000.00, 104, 2, 0, NULL, 11000, 1, 11000, '2025-07-16', '2025-07-17', '2025-07-15 19:54:17', '2025-07-15 19:54:17'),
(114, 129, 'LVS129030846', 1, 'BOOK-LVS129030846-R1-1', 'Standard Package', 11000.00, 105, 2, 0, NULL, 11000, 1, 11000, '2025-07-16', '2025-07-17', '2025-07-15 20:09:37', '2025-07-15 20:09:37'),
(115, 130, 'LVS130033257', 1, 'BOOK-LVS130033257-R1-1', 'Standard Package', 11000.00, 106, 2, 0, NULL, 11000, 1, 11000, '2025-07-16', '2025-07-17', '2025-07-15 20:33:11', '2025-07-15 20:33:11'),
(116, 131, 'LVS131033527', 1, 'BOOK-LVS131033527-R1-1', 'Standard Package', 11000.00, 107, 2, 0, NULL, 11000, 1, 11000, '2025-07-16', '2025-07-17', '2025-07-15 20:36:09', '2025-07-15 20:36:09'),
(117, 132, 'LVS132033857', 1, 'BOOK-LVS132033857-R1-1', 'Standard Package', 11000.00, 108, 2, 0, NULL, 11000, 1, 11000, '2025-07-16', '2025-07-17', '2025-07-15 20:39:30', '2025-07-15 20:39:30'),
(118, 133, 'LVS133070932', 1, 'BOOK-LVS133070932-R1-1', 'Standard Package', 11000.00, 109, 2, 0, NULL, 11000, 1, 11000, '2025-07-16', '2025-07-17', '2025-07-16 00:10:00', '2025-07-16 00:10:00'),
(120, 136, 'LVS20250717034906701', 4, NULL, NULL, NULL, 111, NULL, NULL, NULL, 6000, -7, -42000, '2025-07-17', '2025-07-24', '2025-07-16 20:49:06', '2025-07-16 20:49:06'),
(121, 137, 'LVS137075500', 5, 'BOOK-LVS137075500-R5-1', 'Suite Package', 2700000.00, 112, 2, 1, NULL, 5510000, 1, 5510000, '2025-07-17', '2025-07-18', '2025-07-17 00:56:06', '2025-07-17 00:56:06'),
(122, 137, 'LVS137075500', 5, 'BOOK-LVS137075500-R5-2', 'Suite Package', 2700000.00, 112, 2, 0, NULL, 5510000, 1, 5510000, '2025-07-17', '2025-07-18', '2025-07-17 00:56:06', '2025-07-17 00:56:06'),
(123, 138, 'LVS138081920', NULL, 'BOOK-LVS138081920-R5-1', 'Suite Package', 2700000.00, 113, 2, 0, NULL, 5400000, 1, 5400000, '2025-07-17', '2025-07-18', '2025-07-17 01:20:32', '2025-07-17 09:42:58'),
(124, 138, 'LVS138081920', NULL, 'BOOK-LVS138081920-R5-2', 'Suite Package', 2700000.00, 113, 2, 0, NULL, 5400000, 1, 5400000, '2025-07-17', '2025-07-18', '2025-07-17 01:20:32', '2025-07-17 09:42:58'),
(125, 141, 'LVS141104347', NULL, 'BOOK-LVS141104347-R5-1', 'Suite Package', 2700000.00, 114, 2, 4, NULL, 8650000, 1, 8650000, '2025-07-17', '2025-07-18', '2025-07-17 03:44:03', '2025-07-17 03:44:03'),
(126, 141, 'LVS141104347', NULL, 'BOOK-LVS141104347-R5-2', 'Suite Package', 2700000.00, 114, 3, 2, NULL, 8650000, 1, 8650000, '2025-07-17', '2025-07-18', '2025-07-17 03:44:03', '2025-07-17 03:44:03'),
(127, 141, 'LVS141104347', NULL, 'BOOK-LVS141104347-R5-3', 'Suite Package', 2700000.00, 114, 2, 0, NULL, 8650000, 1, 8650000, '2025-07-17', '2025-07-18', '2025-07-17 03:44:03', '2025-07-17 03:44:03'),
(128, 142, 'LVS142120903', 255, 'BOOK-LVS142120903-R5-1', 'Suite Package', 2700000.00, 115, 2, 4, NULL, 8650000, 1, 8650000, '2025-07-17', '2025-07-18', '2025-07-17 05:10:26', '2025-07-18 05:57:45'),
(129, 142, 'LVS142120903', 256, 'BOOK-LVS142120903-R5-2', 'Suite Package', 2700000.00, 115, 3, 2, NULL, 8650000, 1, 8650000, '2025-07-17', '2025-07-18', '2025-07-17 05:10:26', '2025-07-18 05:57:45'),
(130, 142, 'LVS142120903', 257, 'BOOK-LVS142120903-R5-3', 'Suite Package', 2700000.00, 115, 2, 0, NULL, 8650000, 1, 8650000, '2025-07-17', '2025-07-18', '2025-07-17 05:10:26', '2025-07-18 05:57:45'),
(131, 144, 'LVS144031538', NULL, 'BOOK-LVS144031538-R6-1', 'Luxury Package', 3200000.00, 116, 2, 4, NULL, 20850000, 3, 62550000, '2025-08-18', '2025-08-21', '2025-07-18 20:16:34', '2025-07-18 20:16:34'),
(132, 144, 'LVS144031538', NULL, 'BOOK-LVS144031538-R6-2', 'Luxury Package', 3200000.00, 116, 2, 2, NULL, 20850000, 3, 62550000, '2025-08-18', '2025-08-21', '2025-07-18 20:16:34', '2025-07-18 20:16:34'),
(133, 151, 'LVS151023546', NULL, 'BOOK-LVS151023546-R6-1', 'Luxury Package', 3200000.00, 117, 2, 4, NULL, 27800000, 4, 111200000, '2025-08-18', '2025-08-22', '2025-07-19 19:36:12', '2025-07-19 19:36:12'),
(134, 151, 'LVS151023546', NULL, 'BOOK-LVS151023546-R6-2', 'Luxury Package', 3200000.00, 117, 2, 2, NULL, 27800000, 4, 111200000, '2025-08-18', '2025-08-22', '2025-07-19 19:36:12', '2025-07-19 19:36:12'),
(135, 152, 'LVS152023648', NULL, 'BOOK-LVS152023648-R1-1', 'Premium Package', 1006000.00, 118, 2, 4, NULL, 10248000, 4, 40992000, '2025-08-18', '2025-08-22', '2025-07-19 19:37:20', '2025-07-19 19:37:20'),
(136, 152, 'LVS152023648', NULL, 'BOOK-LVS152023648-R1-2', 'Premium Package', 1006000.00, 118, 2, 2, NULL, 10248000, 4, 40992000, '2025-08-18', '2025-08-22', '2025-07-19 19:37:20', '2025-07-19 19:37:20'),
(137, 153, 'LVS153025209', NULL, 'BOOK-LVS153025209-R1-1', 'Standard Package', 11000.00, 119, 2, 4, NULL, 2288000, 4, 9152000, '2025-08-18', '2025-08-22', '2025-07-19 19:53:06', '2025-07-19 19:53:06'),
(138, 153, 'LVS153025209', NULL, 'BOOK-LVS153025209-R1-2', 'Standard Package', 11000.00, 119, 2, 2, NULL, 2288000, 4, 9152000, '2025-08-18', '2025-08-22', '2025-07-19 19:53:06', '2025-07-19 19:53:06'),
(144, 24, NULL, 2, 'OPT10', NULL, NULL, NULL, NULL, NULL, NULL, 505000, 5, 2525000, '2025-08-10', '2025-08-15', '2025-08-03 16:25:27', '2025-08-03 16:25:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_room_children`
--

CREATE TABLE `booking_room_children` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_room_id` int UNSIGNED NOT NULL,
  `age` int NOT NULL,
  `child_index` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `booking_room_children`
--

INSERT INTO `booking_room_children` (`id`, `booking_room_id`, `age`, `child_index`, `created_at`, `updated_at`) VALUES
(1, 91, 8, 0, '2025-07-14 04:42:51', '2025-07-14 04:42:51'),
(2, 93, 8, 0, '2025-07-14 04:44:57', '2025-07-14 04:44:57'),
(3, 95, 8, 0, '2025-07-14 19:35:46', '2025-07-14 19:35:46'),
(4, 97, 8, 0, '2025-07-14 19:45:43', '2025-07-14 19:45:43'),
(5, 121, 8, 0, '2025-07-17 00:56:06', '2025-07-17 00:56:06'),
(6, 125, 8, 0, '2025-07-17 03:44:03', '2025-07-17 03:44:03'),
(7, 125, 3, 1, '2025-07-17 03:44:03', '2025-07-17 03:44:03'),
(8, 125, 8, 2, '2025-07-17 03:44:03', '2025-07-17 03:44:03'),
(9, 125, 8, 3, '2025-07-17 03:44:03', '2025-07-17 03:44:03'),
(10, 126, 8, 0, '2025-07-17 03:44:03', '2025-07-17 03:44:03'),
(11, 126, 12, 1, '2025-07-17 03:44:03', '2025-07-17 03:44:03'),
(12, 128, 8, 0, '2025-07-17 05:10:26', '2025-07-17 05:10:26'),
(13, 128, 3, 1, '2025-07-17 05:10:26', '2025-07-17 05:10:26'),
(14, 128, 8, 2, '2025-07-17 05:10:26', '2025-07-17 05:10:26'),
(15, 128, 8, 3, '2025-07-17 05:10:26', '2025-07-17 05:10:26'),
(16, 129, 8, 0, '2025-07-17 05:10:26', '2025-07-17 05:10:26'),
(17, 129, 12, 1, '2025-07-17 05:10:26', '2025-07-17 05:10:26'),
(18, 131, 8, 0, '2025-07-18 20:16:34', '2025-07-18 20:16:34'),
(19, 131, 3, 1, '2025-07-18 20:16:34', '2025-07-18 20:16:34'),
(20, 131, 8, 2, '2025-07-18 20:16:34', '2025-07-18 20:16:34'),
(21, 131, 8, 3, '2025-07-18 20:16:34', '2025-07-18 20:16:34'),
(22, 132, 8, 0, '2025-07-18 20:16:34', '2025-07-18 20:16:34'),
(23, 132, 12, 1, '2025-07-18 20:16:34', '2025-07-18 20:16:34'),
(24, 133, 8, 0, '2025-07-19 19:36:12', '2025-07-19 19:36:12'),
(25, 133, 3, 1, '2025-07-19 19:36:12', '2025-07-19 19:36:12'),
(26, 133, 8, 2, '2025-07-19 19:36:12', '2025-07-19 19:36:12'),
(27, 133, 8, 3, '2025-07-19 19:36:12', '2025-07-19 19:36:12'),
(28, 134, 8, 0, '2025-07-19 19:36:12', '2025-07-19 19:36:12'),
(29, 134, 12, 1, '2025-07-19 19:36:12', '2025-07-19 19:36:12'),
(30, 135, 8, 0, '2025-07-19 19:37:20', '2025-07-19 19:37:20'),
(31, 135, 3, 1, '2025-07-19 19:37:20', '2025-07-19 19:37:20'),
(32, 135, 8, 2, '2025-07-19 19:37:20', '2025-07-19 19:37:20'),
(33, 135, 8, 3, '2025-07-19 19:37:20', '2025-07-19 19:37:20'),
(34, 136, 8, 0, '2025-07-19 19:37:20', '2025-07-19 19:37:20'),
(35, 136, 12, 1, '2025-07-19 19:37:20', '2025-07-19 19:37:20'),
(36, 137, 8, 0, '2025-07-19 19:53:06', '2025-07-19 19:53:06'),
(37, 137, 3, 1, '2025-07-19 19:53:06', '2025-07-19 19:53:06'),
(38, 137, 8, 2, '2025-07-19 19:53:06', '2025-07-19 19:53:06'),
(39, 137, 8, 3, '2025-07-19 19:53:06', '2025-07-19 19:53:06'),
(40, 138, 8, 0, '2025-07-19 19:53:06', '2025-07-19 19:53:06'),
(41, 138, 12, 1, '2025-07-19 19:53:06', '2025-07-19 19:53:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cancellation_policies`
--

CREATE TABLE `cancellation_policies` (
  `policy_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `free_cancellation_days` int DEFAULT NULL COMMENT 'Số ngày trước check-in được hủy miễn phí',
  `penalty_days` int DEFAULT NULL COMMENT 'số ngày hủy sau khi đặt phòng sẽ bị phạt.',
  `penalty_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Phạt hủy (%)',
  `penalty_fixed_amount_vnd` decimal(15,2) DEFAULT NULL COMMENT 'Phạt hủy cố định (VND)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `priority` int NOT NULL DEFAULT '0',
  `conditions` json DEFAULT NULL,
  `applies_to_weekend` tinyint(1) NOT NULL DEFAULT '0',
  `applies_to_holiday` tinyint(1) NOT NULL DEFAULT '0',
  `min_booking_amount` decimal(15,2) DEFAULT NULL,
  `max_booking_amount` decimal(15,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cancellation_policies`
--

INSERT INTO `cancellation_policies` (`policy_id`, `name`, `free_cancellation_days`, `penalty_days`, `penalty_percentage`, `penalty_fixed_amount_vnd`, `description`, `priority`, `conditions`, `applies_to_weekend`, `applies_to_holiday`, `min_booking_amount`, `max_booking_amount`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Hủy có phí', NULL, 3, 50.00, NULL, 'Phạt 50% nếu hủy trong vòng 2 ngày', 0, NULL, 0, 0, NULL, NULL, 1, '2025-06-11 02:26:26', '2025-07-31 13:54:54'),
(10, 'Hủy miễn phí 7 ngày', 7, NULL, 0.00, 200000.00, 'Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k', 10, NULL, 0, 0, NULL, NULL, 1, '2025-07-12 09:51:47', '2025-07-12 09:51:47'),
(11, 'Hủy miễn phí 3 ngày - Lễ tết', 3, NULL, 50.00, 0.00, 'Áp dụng cho ngày lễ tết, hủy trước 3 ngày', 20, NULL, 0, 1, NULL, NULL, 1, '2025-07-12 09:51:47', '2025-07-31 12:45:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cancellation_requests`
--

CREATE TABLE `cancellation_requests` (
  `request_id` int NOT NULL COMMENT 'Khóa chính, mã yêu cầu hủy',
  `booking_id` int NOT NULL COMMENT 'Khóa ngoại, mã đặt phòng',
  `cancellation_policy_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã chính sách hủy áp dụng',
  `status` enum('Pending','Approved','Rejected','Processed','Refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending' COMMENT 'Trạng thái yêu cầu hủy',
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian yêu cầu hủy',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Lý do hủy do khách hàng cung cấp',
  `cancellation_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí hủy (VND), nếu có',
  `refund_amount_vnd` decimal(15,2) DEFAULT NULL COMMENT 'Số tiền hoàn lại (VND), nếu có',
  `refund_status` enum('NotApplicable','Pending','Completed','Failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'NotApplicable' COMMENT 'Trạng thái hoàn tiền',
  `refund_transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mã giao dịch hoàn tiền (nếu có)',
  `processed_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian xử lý yêu cầu',
  `processed_by` bigint UNSIGNED DEFAULT NULL COMMENT 'Khóa ngoại, ID nhân viên xử lý (nếu có)',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Ghi chú bổ sung từ hệ thống hoặc nhân viên',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu trữ các yêu cầu hủy phòng';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `check_in_policies`
--

CREATE TABLE `check_in_policies` (
  `policy_id` int NOT NULL COMMENT 'Khóa chính, mã chính sách nhận phòng',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên chính sách',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chính sách',
  `standard_check_in_time` time NOT NULL COMMENT 'Thời gian nhận phòng tiêu chuẩn',
  `early_check_in_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí nhận phòng sớm (VND)',
  `early_check_in_max_hours` int DEFAULT NULL COMMENT 'Số giờ tối đa cho phép nhận phòng sớm',
  `late_check_in_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí nhận phòng muộn (VND)',
  `late_check_in_max_hours` int DEFAULT NULL COMMENT 'Số giờ tối đa cho phép nhận phòng muộn',
  `applies_to_holiday` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho ngày lễ (1: Có, 0: Không)',
  `applies_to_weekend` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho cuối tuần (1: Có, 0: Không)',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Danh sách chính sách nhận phòng';

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
  `priority` int NOT NULL DEFAULT '0',
  `conditions` json DEFAULT NULL,
  `applies_to_weekend` tinyint(1) NOT NULL DEFAULT '0',
  `applies_to_holiday` tinyint(1) NOT NULL DEFAULT '0',
  `standard_check_out_time` time NOT NULL DEFAULT '12:00:00',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `check_out_policies`
--

INSERT INTO `check_out_policies` (`policy_id`, `name`, `early_check_out_fee_vnd`, `late_check_out_fee_vnd`, `late_check_out_max_hours`, `early_check_out_max_hours`, `description`, `priority`, `conditions`, `applies_to_weekend`, `applies_to_holiday`, `standard_check_out_time`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Trả phòng muộn sau 4 giờ', 0.00, 200000.00, 4, NULL, 'Phí 200,000 VND nếu trả phòng muộn tối đa 4 giờ', 0, NULL, 0, 0, '12:00:00', 1, '2025-06-11 02:36:00', '2025-06-16 18:37:57'),
(3, 'e', NULL, 0.00, NULL, 4, 'e', 0, NULL, 0, 0, '12:00:00', 1, '2025-06-16 18:40:33', '2025-06-16 18:40:56'),
(4, 'Check-out tiêu chuẩn', 0.00, 500000.00, 2, 4, 'Check-out tiêu chuẩn 12:00', 0, NULL, 0, 0, '12:00:00', 1, '2025-07-12 09:51:47', '2025-07-12 09:51:47');

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

--
-- Đang đổ dữ liệu cho bảng `check_out_requests`
--

INSERT INTO `check_out_requests` (`request_id`, `booking_id`, `type`, `requested_check_out_time`, `fee_vnd`, `status`, `created_at`, `updated_at`) VALUES
(1, 26, 'early', '2025-07-11 03:00:00', 0.00, 'approved', '2025-07-09 00:29:58', '2025-07-09 00:29:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `children_surcharges`
--

CREATE TABLE `children_surcharges` (
  `id` bigint UNSIGNED NOT NULL,
  `min_age` tinyint UNSIGNED NOT NULL,
  `max_age` tinyint UNSIGNED NOT NULL,
  `is_free` tinyint(1) NOT NULL DEFAULT '0',
  `count_as_adult` tinyint(1) NOT NULL DEFAULT '0',
  `requires_extra_bed` tinyint(1) NOT NULL DEFAULT '0',
  `surcharge_amount_vnd` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `booking_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `children_surcharges`
--

INSERT INTO `children_surcharges` (`id`, `min_age`, `max_age`, `is_free`, `count_as_adult`, `requires_extra_bed`, `surcharge_amount_vnd`, `created_at`, `updated_at`, `booking_id`) VALUES
(1, 0, 6, 1, 0, 0, NULL, '2025-07-10 03:09:55', '2025-07-10 06:13:23', NULL),
(2, 7, 12, 0, 0, 0, 110000, '2025-07-10 03:09:55', '2025-07-11 08:24:32', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Người dùng khởi tạo cuộc trò chuyện',
  `client_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã định danh của người không đăng nhập',
  `is_bot_only` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Chỉ là chat với bot',
  `handover_to_user_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Chuyển tiếp cho nhân viên nếu cần',
  `status` enum('open','active','closed','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open' COMMENT 'Trạng thái hội thoại',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `conversations`
--

INSERT INTO `conversations` (`id`, `user_id`, `client_token`, `is_bot_only`, `handover_to_user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 0, NULL, 'active', '2025-07-21 10:31:45', '2025-07-21 10:31:45'),
(2, NULL, 'a18c23b1-cc1e-4d3f-bc9a-bc01f153d88f', 0, NULL, 'active', '2025-07-21 10:31:45', '2025-07-21 10:31:45'),
(3, NULL, '87182c0f-5517-4644-b130-a85c2ce9eaef', 0, NULL, 'pending', '2025-07-21 23:13:03', '2025-07-21 23:13:03'),
(4, NULL, 'e6d96243-6af4-4673-bece-4624324838d0', 1, NULL, 'active', '2025-07-23 00:15:47', '2025-07-28 04:41:33');

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
  `priority` int NOT NULL DEFAULT '0',
  `conditions` json DEFAULT NULL,
  `applies_to_weekend` tinyint(1) NOT NULL DEFAULT '0',
  `applies_to_holiday` tinyint(1) NOT NULL DEFAULT '0',
  `min_days_before_checkin` int DEFAULT NULL,
  `min_booking_amount` decimal(15,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `deposit_policies`
--

INSERT INTO `deposit_policies` (`policy_id`, `name`, `deposit_percentage`, `deposit_fixed_amount_vnd`, `description`, `priority`, `conditions`, `applies_to_weekend`, `applies_to_holiday`, `min_days_before_checkin`, `min_booking_amount`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Đặt cọc 50%', 50.00, NULL, 'Yêu cầu đặt cọc 50% tổng giá haha', 0, NULL, 0, 0, NULL, NULL, 1, '2025-06-11 02:24:24', '2025-07-10 03:55:39'),
(10, 'Đặt cọc 20%', 20.00, 0.00, 'Đặt cọc 20% giá trị booking', 0, NULL, 0, 0, NULL, NULL, 1, '2025-07-12 09:51:47', '2025-07-21 03:41:18'),
(11, 'Đặt cọc 50% - Lễ tết', 50.00, 0.00, 'Đặt cọc 50% cho ngày lễ tết', 0, NULL, 0, 0, NULL, NULL, 1, '2025-07-12 09:51:47', '2025-07-12 09:51:47');

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
-- Cấu trúc bảng cho bảng `extension_policies`
--

CREATE TABLE `extension_policies` (
  `policy_id` int NOT NULL COMMENT 'Khóa chính, mã chính sách gia hạn',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên chính sách',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chính sách gia hạn',
  `max_extension_days` int DEFAULT NULL COMMENT 'Số ngày tối đa được phép gia hạn, NULL nếu không giới hạn',
  `extension_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí gia hạn cố định (VND)',
  `extension_percentage` decimal(5,2) DEFAULT '0.00' COMMENT 'Phí gia hạn theo phần trăm giá phòng',
  `min_days_before_checkout` int DEFAULT NULL COMMENT 'Số ngày tối thiểu trước ngày trả phòng để gia hạn',
  `applies_to_holiday` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho ngày lễ',
  `applies_to_weekend` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho cuối tuần',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Danh sách chính sách gia hạn đặt phòng';

--
-- Đang đổ dữ liệu cho bảng `extension_policies`
--

INSERT INTO `extension_policies` (`policy_id`, `name`, `description`, `max_extension_days`, `extension_fee_vnd`, `extension_percentage`, `min_days_before_checkout`, `applies_to_holiday`, `applies_to_weekend`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Gia hạn tiêu chuẩn', 'Cho phép gia hạn không giới hạn ngày nếu phòng trống, phí cố định 500,000 VND mỗi lần gia hạn', NULL, 500000.00, 0.00, 2, 0, 0, 1, '2025-08-01 12:05:00', '2025-08-01 12:05:00'),
(2, 'Gia hạn cuối tuần', 'Gia hạn tối đa 2 ngày vào cuối tuần với 10% giá phòng mỗi ngày', 2, 0.00, 10.00, 1, 0, 1, 1, '2025-08-01 12:05:00', '2025-08-01 12:05:00'),
(3, 'Gia hạn ngày lễ', 'Gia hạn tối đa 1 ngày vào ngày lễ với phí cố định 1,000,000 VND', 1, 1000000.00, 0.00, 3, 1, 0, 1, '2025-08-01 12:05:00', '2025-08-01 12:05:00'),
(4, 'Gia hạn mùa cao điểm', 'Gia hạn tối đa 3 ngày trong mùa cao điểm với 15% giá phòng mỗi ngày', 3, 0.00, 15.00, 2, 1, 1, 1, '2025-08-01 12:05:00', '2025-08-01 12:05:00'),
(5, 'Gia hạn ngắn ngày', 'Gia hạn tối đa 1 ngày vào ngày thường với phí cố định 300,000 VND', 1, 300000.00, 0.00, 1, 0, 0, 1, '2025-08-01 12:05:00', '2025-08-01 12:05:00'),
(6, 'Gia hạn dài ngày', 'Cho phép gia hạn không giới hạn ngày nếu phòng trống, phí 5% giá phòng mỗi ngày', NULL, 0.00, 5.00, 1, 0, 0, 1, '2025-08-01 12:05:00', '2025-08-01 12:54:04'),
(7, 'Gia hạn sự kiện đặc biệt', 'Gia hạn tối đa 2 ngày trong các sự kiện lớn với phí 1,500,000 VND mỗi lần', 2, 1500000.00, 0.00, 4, 1, 0, 1, '2025-08-01 12:05:00', '2025-08-01 12:05:00'),
(8, 'Gia hạn linh hoạt', 'Gia hạn không giới hạn ngày nếu phòng trống, kết hợp phí cố định 200,000 VND và 3% giá phòng mỗi ngày', NULL, 200000.00, 3.00, 2, 0, 0, 1, '2025-08-01 12:05:00', '2025-08-01 12:05:00'),
(9, 'Gia hạn cuối tuần ngày lễ', 'Gia hạn tối đa 1 ngày vào cuối tuần trùng ngày lễ với phí 1,200,000 VND', 1, 1200000.00, 0.00, 3, 1, 1, 1, '2025-08-01 12:05:00', '2025-08-01 12:05:00'),
(10, 'Gia hạn thấp điểm', 'Gia hạn không giới hạn ngày trong mùa thấp điểm với phí cố định 250,000 VND mỗi lần', NULL, 250000.00, 0.00, 1, 0, 0, 1, '2025-08-01 12:05:00', '2025-08-01 12:05:00'),
(11, 'Gia hạn ngày lễ linh hoạt', 'Cho phép gia hạn dài ngày vào ngày lễ với phí cố định 800,000 VND', NULL, 800000.00, 0.00, 1, 1, 1, 1, '2025-08-01 12:51:00', '2025-08-01 12:51:00'),
(12, 'Gia hạn mặc định', 'Chính sách gia hạn mặc định cho mọi trường hợp, phí 10% giá phòng', NULL, 0.00, 10.00, NULL, 0, 0, 1, '2025-08-01 12:51:00', '2025-08-01 12:51:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `extension_requests`
--

CREATE TABLE `extension_requests` (
  `request_id` int NOT NULL COMMENT 'Khóa chính, mã yêu cầu gia hạn',
  `booking_id` int NOT NULL COMMENT 'Khóa ngoại, mã đặt phòng',
  `extension_policy_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã chính sách gia hạn',
  `new_check_out_date` date NOT NULL COMMENT 'Ngày trả phòng mới',
  `extension_days` int NOT NULL COMMENT 'Số ngày gia hạn',
  `extension_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí gia hạn (VND)',
  `status` enum('Pending','Approved','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending' COMMENT 'Trạng thái yêu cầu',
  `processed_by` bigint UNSIGNED DEFAULT NULL COMMENT 'Khóa ngoại, mã người xử lý',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Ghi chú yêu cầu',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu trữ yêu cầu gia hạn đặt phòng';

--
-- Đang đổ dữ liệu cho bảng `extension_requests`
--

INSERT INTO `extension_requests` (`request_id`, `booking_id`, `extension_policy_id`, `new_check_out_date`, `extension_days`, `extension_fee_vnd`, `status`, `processed_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, 160, 12, '2025-08-10', -8, 5866.67, 'Approved', NULL, NULL, '2025-08-01 12:59:25', '2025-08-01 12:59:25'),
(2, 160, 12, '2025-08-10', -8, 7430.93, 'Approved', NULL, NULL, '2025-08-01 13:03:34', '2025-08-01 13:03:34'),
(3, 160, 6, '2025-08-10', 8, 14118.40, 'Approved', NULL, NULL, '2025-08-01 13:08:43', '2025-08-01 13:08:43');

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
  `priority` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo câu hỏi',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật câu hỏi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu trữ câu hỏi thường gặp và câu trả lời';

--
-- Đang đổ dữ liệu cho bảng `faqs`
--

INSERT INTO `faqs` (`faq_id`, `question_en`, `question_vi`, `answer_en`, `answer_vi`, `sort_order`, `is_active`, `priority`, `created_at`, `updated_at`) VALUES
(1, 'Do you serve breakfast?', 'Họ có phục vụ bữa sáng không?', 'Yes, we offer an excellent buffet breakfast from 6:30 AM to 10:30 AM daily with both international and Vietnamese cuisine.', 'Có, chúng tôi cung cấp bữa sáng buffet tuyệt hảo từ 6:30 đến 10:30 hàng ngày với ẩm thực quốc tế và Việt Nam.', 5, 1, NULL, '2025-05-23 02:50:42', '2025-06-02 03:21:17'),
(2, 'Is parking available?', 'Chỗ nghỉ có chỗ đỗ xe không?', 'Yes, we provide complimentary self-parking for hotel guests. Valet parking is also available for an additional charge.', 'Có, chúng tôi cung cấp chỗ đỗ xe tự phục vụ miễn phí cho khách khách sạn. Dịch vụ đỗ xe có người phục vụ cũng có sẵn với phí bổ sung.', 3, 1, NULL, '2025-05-23 02:50:42', '2025-06-02 03:33:00'),
(3, 'Do you provide airport shuttle service?', 'Chỗ nghỉ có dịch vụ đưa đón sân bay không?', 'Yes, we offer airport transfer service for $25 per trip. Please contact our concierge to arrange your transfer.', 'Có, chúng tôi cung cấp dịch vụ đưa đón sân bay với giá $25 mỗi chuyến. Vui lòng liên hệ với lễ tân để sắp xếp chuyến đi.', 10, 1, NULL, '2025-05-23 02:50:42', '2025-06-02 03:33:16'),
(4, 'What is your WiFi ?', 'Chỗ nghỉ có  Wi-Fi ra sao?', 'High-speed WiFi is complimentary throughout the hotel including all guest rooms and public areas.', 'Wi-Fi tốc độ cao miễn phí trong toàn bộ khách sạn bao gồm tất cả các phòng khách và khu vực công cộng.', 0, 1, NULL, '2025-05-23 02:50:42', '2025-06-12 00:57:25'),
(7, 'Am i handsome?', 'Tôi có đẹp trai không?', 'Yes Sirrrrr', 'Chắc chắn  rồi broooo', 2, 1, NULL, '2025-06-02 02:14:43', '2025-06-02 03:01:20');

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
-- Cấu trúc bảng cho bảng `media_files`
--

CREATE TABLE `media_files` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'Khóa chính của file ảnh/media',
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên file gốc (ví dụ: khachsan1.jpg)',
  `filepath` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn file (ví dụ: /storage/media/khachsan1.jpg)',
  `alt_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thuộc tính ALT – giúp SEO hình ảnh',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề ảnh hiển thị khi hover',
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại file (ví dụ: image/jpeg, image/webp...)',
  `size` int DEFAULT NULL COMMENT 'Dung lượng file tính bằng byte',
  `used_in` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ngữ cảnh sử dụng (ví dụ: news, banner, home)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm upload',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quản lý file media (ảnh đại diện, ảnh nội dung...) hỗ trợ SEO hình ảnh';

--
-- Đang đổ dữ liệu cho bảng `media_files`
--

INSERT INTO `media_files` (`id`, `filename`, `filepath`, `alt_text`, `title`, `type`, `size`, `used_in`, `created_at`, `updated_at`) VALUES
(1, '1753807880_screenshot-1png.png', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Hình ảnh bài viết', 'Hình ảnh bài viết', 'image/png', 60413, 'news', '2025-07-29 16:51:20', '2025-08-12 04:28:03'),
(2, '1753807896_logopng.png', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Hình ảnh bài viết', 'Hình ảnh bài viết', 'image/png', 1818956, 'news', '2025-07-29 16:51:36', '2025-08-12 04:28:06'),
(3, '1753852598_logopng.png', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Hình ảnh bài viết', 'Hình ảnh bài viết', 'image/png', 1818956, 'news', '2025-07-30 05:16:38', '2025-08-12 04:28:08'),
(4, '1754039682_screenshot-1png.png', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Hình ảnh bài viết', 'Hình ảnh bài viết', 'image/png', 60413, 'news', '2025-08-01 09:14:42', '2025-08-12 04:28:10'),
(5, 'hotel-lobby.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Sảnh khách sạn sang trọng với thiết kế hiện đại', 'Sảnh Khách Sạn LavishStay', 'image/jpeg', 2048576, 'news', '2025-08-11 09:37:45', '2025-08-12 04:28:11'),
(6, 'deluxe-room.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Phòng deluxe với view biển tuyệt đẹp', 'Phòng Deluxe Sea View', 'image/jpeg', 1876543, 'news', '2025-08-11 09:37:45', '2025-08-12 04:28:13'),
(7, 'restaurant-dining.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Nhà hàng với không gian ấm cúng và món ăn tinh tế', 'Nhà Hàng LavishStay', 'image/jpeg', 1654321, 'news', '2025-08-11 09:37:45', '2025-08-12 04:28:15'),
(8, 'swimming-pool.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Hồ bơi infinity với view toàn cảnh thành phố', 'Hồ Bơi Infinity', 'image/jpeg', 2234567, 'news', '2025-08-11 09:37:45', '2025-08-12 04:28:16'),
(9, 'spa-treatment.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Phòng spa với không gian thư giãn và massage', 'Spa & Massage', 'image/jpeg', 1987654, 'news', '2025-08-11 09:37:45', '2025-08-12 04:28:19'),
(10, 'beach-view.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Bãi biển tuyệt đẹp với cát trắng và nước trong xanh', 'Bãi Biển Paradise', 'image/jpeg', 2345678, 'news', '2025-08-11 09:37:45', '2025-08-12 04:28:21'),
(11, 'conference-room.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Phòng hội nghị hiện đại với thiết bị công nghệ cao', 'Phòng Hội Nghị', 'image/jpeg', 1765432, 'news', '2025-08-11 09:37:45', '2025-08-12 04:28:24'),
(12, 'fitness-center.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Phòng gym với thiết bị tập luyện hiện đại', 'Trung Tâm Thể Dục', 'image/jpeg', 1456789, 'news', '2025-08-11 09:37:45', '2025-08-12 04:28:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `sender_type` enum('user','staff','bot','guest') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` bigint UNSIGNED DEFAULT NULL COMMENT 'user_id nếu là người dùng, staff_id nếu là nhân viên, null nếu là bot',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung tin nhắn',
  `is_from_bot` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_read` tinyint DEFAULT '0',
  `message_type` enum('text','image','file','system') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `sender_type`, `sender_id`, `message`, `is_from_bot`, `created_at`, `updated_at`, `is_read`, `message_type`, `metadata`) VALUES
(1, 1, 'user', 1, 'Khách sạn có chỗ đậu ô tô không?', 0, '2025-07-21 10:23:21', '2025-07-21 23:12:52', 1, NULL, NULL),
(2, 1, 'staff', 1, 'Dạ có, bãi đậu xe miễn phí ạ!', 0, '2025-07-21 10:25:21', '2025-07-21 10:25:21', 0, NULL, NULL),
(3, 2, 'guest', NULL, 'Check-in lúc 13h được không bạn?', 0, '2025-07-21 10:27:21', '2025-07-21 23:12:54', 1, NULL, NULL),
(4, 2, 'staff', 1, 'Dạ, thời gian check-in tiêu chuẩn là từ 14h, tuy nhiên bên em có thể hỗ trợ nếu phòng sẵn sàng.', 0, '2025-07-21 10:29:21', '2025-07-21 10:29:21', 0, NULL, NULL),
(5, 3, 'guest', NULL, 'Chào bạn', 0, '2025-07-21 23:13:03', '2025-07-23 00:15:05', 1, NULL, NULL),
(6, 3, 'staff', NULL, 'Câu hỏi của bạn đã được chuyển đến nhân viên hỗ trợ. Chúng tôi sẽ trả lời sớm nhất có thể.', 1, '2025-07-21 23:13:03', '2025-07-21 23:13:03', 0, 'system', NULL),
(7, 3, 'guest', NULL, 'Xin chào', 0, '2025-07-23 00:10:55', '2025-07-23 00:15:05', 1, NULL, NULL),
(8, 3, 'guest', NULL, 'fjej', 0, '2025-07-23 00:10:58', '2025-07-23 00:15:05', 1, NULL, NULL),
(9, 3, 'guest', NULL, 'fef', 0, '2025-07-23 00:11:00', '2025-07-23 00:15:05', 1, NULL, NULL),
(10, 3, 'guest', NULL, 'uktu', 0, '2025-07-23 00:14:53', '2025-07-23 00:15:05', 1, NULL, NULL),
(11, 3, 'guest', NULL, 'pụ;', 0, '2025-07-23 00:14:54', '2025-07-23 00:15:05', 1, NULL, NULL),
(12, 3, 'guest', NULL, 'Chào bạn', 0, '2025-07-23 00:15:17', '2025-07-23 00:15:19', 1, NULL, NULL),
(13, 4, 'guest', NULL, 'Hiiii', 0, '2025-07-23 00:15:47', '2025-07-28 04:41:07', 1, NULL, NULL),
(14, 3, 'guest', NULL, 'Hii', 0, '2025-07-23 00:16:49', '2025-07-23 00:18:33', 1, NULL, NULL),
(15, 3, 'guest', NULL, 'êf', 0, '2025-07-23 00:18:39', '2025-07-23 00:19:29', 1, NULL, NULL),
(16, 3, 'guest', NULL, 'Heloo', 0, '2025-07-23 00:18:52', '2025-07-23 00:19:29', 1, NULL, NULL),
(17, 3, 'guest', NULL, 'e', 0, '2025-07-23 00:20:39', '2025-07-23 00:21:04', 1, NULL, NULL),
(18, 3, 'guest', NULL, 'Helooo', 0, '2025-07-23 00:21:38', '2025-07-23 00:23:05', 1, NULL, NULL),
(19, 3, 'guest', NULL, 'Hi', 0, '2025-07-23 00:23:14', '2025-07-23 00:23:36', 1, NULL, NULL),
(20, 3, 'guest', NULL, 'Hi', 0, '2025-07-23 00:23:39', '2025-07-23 00:23:47', 1, NULL, NULL),
(21, 3, 'guest', NULL, 'Hii', 0, '2025-07-23 00:24:33', '2025-07-23 00:24:33', 0, NULL, NULL),
(22, 3, 'staff', NULL, 'Câu hỏi của bạn đã được chuyển đến nhân viên hỗ trợ. Chúng tôi sẽ trả lời sớm nhất có thể.', 1, '2025-07-23 00:24:33', '2025-07-23 00:24:33', 0, 'system', NULL),
(23, 1, 'staff', 1, 'Có máy bay không', 0, '2025-07-23 00:29:05', '2025-07-23 00:29:05', 0, NULL, NULL),
(24, 4, 'staff', 1, 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ xử lý yêu cầu của bạn sớm nhất có thể.', 0, '2025-07-28 04:41:33', '2025-07-28 04:41:33', 0, NULL, NULL);

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
(12, '2025_07_08_092023_add_option_id_to_booking_rooms_table', 6),
(13, '2025_07_09_040618_add_google_fields_to_users_table', 7),
(14, '2025_07_10_100745_create_children_surcharges_table', 8),
(15, '2025_07_11_152058_add_requires_extra_bed_to_children_surcharges_table', 9),
(16, '2025_07_12_164643_enhance_policy_tables', 10),
(17, '2025_07_21_094709_create_conversations_table', 11),
(18, '2025_07_21_094715_create_messages_table', 11),
(19, '2025_07_21_094809_add_client_token_to_conversations_table', 11),
(20, '2025_07_21_145143_create_faqs_table', 12),
(21, '2024_01_15_100000_create_news_comments_table', 13),
(22, '2024_01_15_110000_create_news_user_actions_table', 13);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news`
--

CREATE TABLE `news` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'Khóa chính, mã bài viết',
  `slug` varchar(255) NOT NULL COMMENT 'Đường dẫn không dấu, duy nhất cho mỗi bài viết (SEO)',
  `title` varchar(255) NOT NULL COMMENT 'Tiêu đề bài viết',
  `summary` text COMMENT 'Tóm tắt ngắn nội dung bài viết',
  `content` longtext COMMENT 'Nội dung chi tiết bài viết (HTML)',
  `tags` json DEFAULT NULL COMMENT 'Danh sách tag (mảng string, phục vụ tìm kiếm, phân loại)',
  `thumbnail_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID ảnh đại diện (liên kết media_files)',
  `author_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID tác giả (liên kết users)',
  `category_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID chuyên mục/danh mục (liên kết news_categories)',
  `meta_title` varchar(255) DEFAULT NULL COMMENT 'Tiêu đề SEO (meta title)',
  `meta_description` text COMMENT 'Mô tả SEO (meta description)',
  `meta_keywords` varchar(255) DEFAULT NULL COMMENT 'Từ khóa SEO (meta keywords)',
  `canonical_url` varchar(255) DEFAULT NULL COMMENT 'URL chuẩn SEO (canonical)',
  `schema_json` json DEFAULT NULL COMMENT 'Dữ liệu cấu trúc SEO (schema.org, dạng JSON)',
  `views` int DEFAULT '0' COMMENT 'Số lượt xem bài viết',
  `status` tinyint DEFAULT '1' COMMENT 'Trạng thái bài viết (1: hiển thị, 0: ẩn, nháp...)',
  `published_at` datetime DEFAULT NULL COMMENT 'Thời điểm xuất bản',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật',
  `is_featured` tinyint(1) DEFAULT '0' COMMENT 'Bài viết nổi bật (1: featured, 0: thường)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `news`
--

INSERT INTO `news` (`id`, `slug`, `title`, `summary`, `content`, `tags`, `thumbnail_id`, `author_id`, `category_id`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `schema_json`, `views`, `status`, `published_at`, `created_at`, `updated_at`, `is_featured`) VALUES
(1, 'kham-pha-khong-gian-sang-trong-tai-lavishstay-resort', 'Khám Phá Không Gian Sang Trọng Tại LavishStay Resort', 'Trải nghiệm không gian nghỉ dưỡng đẳng cấp với thiết kế hiện đại và dịch vụ 5 sao tại LavishStay Resort.', '<p>LavishStay Resort mang đến cho du khách một trải nghiệm nghỉ dưỡng đẳng cấp với không gian sang trọng và dịch vụ tận tâm. Tọa lạc tại vị trí đắc địa, resort sở hữu kiến trúc hiện đại hòa quyện với thiên nhiên.</p><p>Các phòng nghỉ được thiết kế tinh tế với đầy đủ tiện nghi cao cấp, mang đến sự thoải mái tối đa cho khách hàng. Từ phòng Deluxe đến Suite Presidential, mỗi không gian đều được chăm chút kỹ lưỡng về từng chi tiết.</p><p>Resort còn sở hữu hệ thống tiện ích đa dạng bao gồm nhà hàng fine dining, spa cao cấp, hồ bơi infinity và trung tâm thể dục hiện đại.</p>', '[\"resort\", \"luxury\", \"accommodation\", \"travel\"]', 6, 3, 2, 'LavishStay Resort - Không Gian Nghỉ Dưỡng Đẳng Cấp 5 Sao', 'Khám phá LavishStay Resort với không gian sang trọng, dịch vụ 5 sao và trải nghiệm nghỉ dưỡng đẳng cấp. Đặt phòng ngay để nhận ưu đãi đặc biệt.', 'lavishstay, resort, luxury hotel, 5 star, nghỉ dưỡng, khách sạn cao cấp', '/news/kham-pha-khong-gian-sang-trong-tai-lavishstay-resort', '{\"@type\": \"Article\", \"author\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"@context\": \"https://schema.org\", \"headline\": \"Khám Phá Không Gian Sang Trọng Tại LavishStay Resort\", \"publisher\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"description\": \"Trải nghiệm không gian nghỉ dưỡng đẳng cấp với thiết kế hiện đại và dịch vụ 5 sao tại LavishStay Resort.\"}', 2806, 1, '2025-07-29 16:37:45', '2025-08-11 09:37:45', '2025-08-14 03:41:46', 1),
(2, 'uu-dai-mua-he-2024-giam-gia-len-den-40-phan-tram', 'Ưu Đãi Mùa Hè 2024 - Giảm Giá Lên Đến 40%', 'Chương trình ưu đãi mùa hè đặc biệt với mức giảm giá lên đến 40% cho tất cả các hạng phòng tại LavishStay.', '<p>Mùa hè đã đến và LavishStay mang đến chương trình ưu đãi đặc biệt dành cho tất cả du khách. Với mức giảm giá lên đến 40%, đây là cơ hội tuyệt vời để bạn trải nghiệm kỳ nghỉ trong mơ.</p><h3>Ưu đãi bao gồm:</h3><ul><li>Giảm 40% cho phòng Suite và Presidential</li><li>Giảm 30% cho phòng Deluxe và Superior</li><li>Giảm 20% cho tất cả dịch vụ spa</li><li>Buffet sáng miễn phí cho trẻ em dưới 12 tuổi</li><li>Late check-out đến 14:00 miễn phí</li></ul><p>Chương trình có hiệu lực từ ngày 1/6 đến 31/8/2024. Áp dụng cho các đêm nghỉ từ Chủ Nhật đến Thứ Năm.</p>', '[\"promotion\", \"summer\", \"discount\", \"offer\"]', 7, 3, 3, 'Ưu Đãi Mùa Hè 2024 - Giảm Đến 40% Tại LavishStay Resort', 'Đừng bỏ lỡ chương trình ưu đãi mùa hè với giảm giá lên đến 40% tất cả hạng phòng. Đặt ngay để nhận ưu đãi tốt nhất!', 'ưu đãi, khuyến mãi, giảm giá, mùa hè, summer promotion', '/news/uu-dai-mua-he-2024-giam-gia-len-den-40-phan-tram', '{\"@type\": \"Article\", \"author\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"@context\": \"https://schema.org\", \"headline\": \"Ưu Đãi Mùa Hè 2024 - Giảm Giá Lên Đến 40%\", \"publisher\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"description\": \"Chương trình ưu đãi mùa hè đặc biệt với mức giảm giá lên đến 40% cho tất cả các hạng phòng tại LavishStay.\"}', 4327, 1, '2025-08-03 16:37:45', '2025-08-11 09:37:45', '2025-08-11 09:37:45', 0),
(3, 'top-10-dia-diem-du-lich-khong-the-bo-qua-gan-lavishstay', 'Top 10 Địa Điểm Du Lịch Không Thể Bỏ Qua Gần LavishStay', 'Khám phá những địa điểm du lịch hấp dẫn xung quanh khu vực LavishStay Resort với hướng dẫn chi tiết từ A đến Z.', '<p>Khi lưu trú tại LavishStay Resort, bạn sẽ có cơ hội khám phá nhiều địa điểm du lịch tuyệt vời xung quanh. Dưới đây là danh sách 10 địa điểm không thể bỏ qua:</p><h3>1. Bãi Biển Paradise</h3><p>Chỉ cách resort 5 phút đi bộ, bãi biển Paradise với làn nước trong xanh và bãi cát trắng mịn là nơi lý tưởng để thư giãn và tắm nắng.</p><h3>2. Chợ Đêm Địa Phương</h3><p>Trải nghiệm văn hóa địa phương qua những món ăn đường phố đặc sắc và các sản phẩm thủ công truyền thống.</p><h3>3. Đảo San Hô</h3><p>Tour lặn ngắm san hô với nhiều loài cá nhiệt đới đầy màu sắc, phù hợp cho cả người mới bắt đầu và chuyên nghiệp.</p><p>... và còn 7 địa điểm thú vị khác đang chờ bạn khám phá!</p>', '[\"travel guide\", \"attractions\", \"tourism\", \"local\"]', 2, 7, 7, 'Top 10 Địa Điểm Du Lịch Gần LavishStay Resort - Hướng Dẫn Chi Tiết', 'Khám phá 10 địa điểm du lịch tuyệt vời xung quanh LavishStay Resort. Hướng dẫn đầy đủ về các hoạt động và điểm tham quan không thể bỏ qua.', 'du lịch, điểm tham quan, hướng dẫn, tourism, attractions, travel guide', '/news/top-10-dia-diem-du-lich-khong-the-bo-qua-gan-lavishstay', '{\"@type\": \"Article\", \"author\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"@context\": \"https://schema.org\", \"headline\": \"Top 10 Địa Điểm Du Lịch Không Thể Bỏ Qua Gần LavishStay\", \"publisher\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"description\": \"Khám phá những địa điểm du lịch hấp dẫn xung quanh khu vực LavishStay Resort với hướng dẫn chi tiết từ A đến Z.\"}', 3655, 1, '2025-08-04 16:37:45', '2025-08-11 09:37:45', '2025-08-14 04:16:44', 0),
(4, 'grand-opening-le-khai-truong-nha-hang-rooftop-moi', 'Grand Opening - Lễ Khai Trương Nhà Hàng Rooftop Mới', 'Tham gia lễ khai trương nhà hàng rooftop mới với không gian 360 độ và thực đơn fine dining độc đáo.', '<p>LavishStay Resort hân hạnh giới thiệu nhà hàng rooftop mới - Sky Lounge với tầm nhìn 360 độ tuyệt đẹp ra toàn thành phố và biển cả.</p><h3>Điểm đặc biệt của Sky Lounge:</h3><ul><li>Không gian mở với tầm nhìn panoramic</li><li>Thực đơn fusion cuisine do chef Michelin star thiết kế</li><li>Bar cocktail với hơn 200 loại đồ uống cao cấp</li><li>Live music mỗi tối từ 19:00-22:00</li><li>Không gian riêng tư cho các sự kiện đặc biệt</li></ul><p>Lễ khai trương sẽ diễn ra vào 20:00 ngày 15/12/2024 với sự tham gia của các celebrity và food blogger nổi tiếng. Khách mời sẽ được thưởng thức cocktail welcome drink và canapé miễn phí.</p>', '[\"event\", \"restaurant\", \"opening\", \"rooftop\"]', 7, 2, 7, 'Khai Trương Sky Lounge - Nhà Hàng Rooftop Đẳng Cấp Tại LavishStay', 'Tham gia lễ khai trương Sky Lounge - nhà hàng rooftop với tầm nhìn 360 độ và thực đơn fine dining độc đáo tại LavishStay Resort.', 'nhà hàng rooftop, khai trương, sky lounge, fine dining, event', '/news/grand-opening-le-khai-truong-nha-hang-rooftop-moi', '{\"@type\": \"Article\", \"author\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"@context\": \"https://schema.org\", \"headline\": \"Grand Opening - Lễ Khai Trương Nhà Hàng Rooftop Mới\", \"publisher\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"description\": \"Tham gia lễ khai trương nhà hàng rooftop mới với không gian 360 độ và thực đơn fine dining độc đáo.\"}', 6308, 1, '2025-08-01 16:37:45', '2025-08-11 09:37:45', '2025-08-12 06:26:14', 0),
(5, 'thuc-don-mua-dong-dac-biet-huong-vi-am-thuc-chau-a', 'Thực Đơn Mùa Đông Đặc Biệt - Hương Vị Âm Thực Châu Á', 'Khám phá thực đơn mùa đông với những món ăn truyền thống châu Á được chế biến bởi đội ngũ chef chuyên nghiệp.', '<p>Mùa đông đã đến và LavishStay Restaurant mang đến thực đơn đặc biệt với hương vị ấm áp của ẩm thực châu Á truyền thống.</p><h3>Món khai vị:</h3><ul><li>Dumpling tôm hấp với sốt gừng</li><li>Salad đu đủ Thái cay nhẹ</li><li>Chả cá Lã Vọng truyền thống</li></ul><h3>Món chính:</h3><ul><li>Lẩu Thái tôm hùm chua cay</li><li>Bún bò Huế chính hiệu</li><li>Cơm niêu Singapore với tôm rang</li><li>Mì Udon Nhật Bản nước dashi đậm đà</li></ul><h3>Tráng miệng:</h3><ul><li>Chè đậu xanh nước cốt dừa</li><li>Mochi ice cream vị matcha</li><li>Bánh flan caramen</li></ul><p>Thực đơn có hiệu lực từ 1/12/2024 đến 28/2/2025. Đặt bàn trước để được ưu tiên phục vụ.</p>', '[\"cuisine\", \"asian food\", \"winter menu\", \"restaurant\"]', 11, 6, 4, 'Thực Đơn Mùa Đông Châu Á - Ẩm Thực Đặc Sắc Tại LavishStay', 'Thưởng thức thực đơn mùa đông đặc biệt với hương vị ẩm thực châu Á truyền thống tại nhà hàng LavishStay Resort.', 'ẩm thực châu á, thực đơn mùa đông, nhà hàng, món ăn đặc sắc', '/news/thuc-don-mua-dong-dac-biet-huong-vi-am-thuc-chau-a', '{\"@type\": \"Article\", \"author\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"@context\": \"https://schema.org\", \"headline\": \"Thực Đơn Mùa Đông Đặc Biệt - Hương Vị Âm Thực Châu Á\", \"publisher\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"description\": \"Khám phá thực đơn mùa đông với những món ăn truyền thống châu Á được chế biến bởi đội ngũ chef chuyên nghiệp.\"}', 1294, 1, '2025-07-17 16:37:45', '2025-08-11 09:37:45', '2025-08-13 03:56:26', 1),
(6, 'bai-viet-mau-so-6', 'Bài viết mẫu số 6', 'Đây là bài viết mẫu số 6 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 6.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 1, 3, 7, 'Bài viết mẫu số 6', 'Mô tả bài viết mẫu số 6', 'sample, test, demo', '/news/bai-viet-mau-so-6', '[]', 122, 1, '2025-07-28 16:37:45', '2025-08-11 09:37:45', '2025-08-11 09:37:45', 0),
(7, 'bai-viet-mau-so-7', 'Bài viết mẫu số 7', 'Đây là bài viết mẫu số 7 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 7.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 11, 6, 5, 'Bài viết mẫu số 7', 'Mô tả bài viết mẫu số 7', 'sample, test, demo', '/news/bai-viet-mau-so-7', '[]', 168, 1, '2025-06-25 16:37:45', '2025-08-11 09:37:45', '2025-08-11 09:37:45', 0),
(8, 'bai-viet-mau-so-8', 'Bài viết mẫu số 8', 'Đây là bài viết mẫu số 8 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 8.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 7, 2, 3, 'Bài viết mẫu số 8', 'Mô tả bài viết mẫu số 8', 'sample, test, demo', '/news/bai-viet-mau-so-8', '[]', 512, 1, '2025-08-08 16:37:45', '2025-08-11 09:37:45', '2025-08-14 04:41:07', 0),
(9, 'bai-viet-mau-so-9', 'Bài viết mẫu số 9', 'Đây là bài viết mẫu số 9 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 9.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 12, 7, 3, 'Bài viết mẫu số 9', 'Mô tả bài viết mẫu số 9', 'sample, test, demo', '/news/bai-viet-mau-so-9', '[]', 118, 1, '2025-07-05 16:37:45', '2025-08-11 09:37:45', '2025-08-11 09:37:45', 0),
(10, 'bai-viet-mau-so-10', 'Bài viết mẫu số 10', 'Đây là bài viết mẫu số 10 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 10.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 11, 3, 1, 'Bài viết mẫu số 10', 'Mô tả bài viết mẫu số 10', 'sample, test, demo', '/news/bai-viet-mau-so-10', '[]', 418, 1, '2025-07-18 16:37:45', '2025-08-11 09:37:45', '2025-08-11 09:37:45', 0),
(11, 'bai-viet-mau-so-11', 'Bài viết mẫu số 11', 'Đây là bài viết mẫu số 11 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 11.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 2, 2, 6, 'Bài viết mẫu số 11', 'Mô tả bài viết mẫu số 11', 'sample, test, demo', '/news/bai-viet-mau-so-11', '[]', 136, 1, '2025-06-12 16:37:45', '2025-08-11 09:37:45', '2025-08-11 09:37:45', 0),
(12, 'bai-viet-mau-so-12', 'Bài viết mẫu số 12', 'Đây là bài viết mẫu số 12 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 12.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 10, 5, 2, 'Bài viết mẫu số 12', 'Mô tả bài viết mẫu số 12', 'sample, test, demo', '/news/bai-viet-mau-so-12', '[]', 383, 1, '2025-07-26 16:37:45', '2025-08-11 09:37:45', '2025-08-11 09:37:45', 0),
(13, 'bai-viet-mau-so-13', 'Bài viết mẫu số 13', 'Đây là bài viết mẫu số 13 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 13.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 5, 1, 3, 'Bài viết mẫu số 13', 'Mô tả bài viết mẫu số 13', 'sample, test, demo', '/news/bai-viet-mau-so-13', '[]', 211, 1, '2025-07-13 16:37:46', '2025-08-11 09:37:46', '2025-08-11 09:37:46', 0),
(14, 'bai-viet-mau-so-14', 'Bài viết mẫu số 14', 'Đây là bài viết mẫu số 14 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 14.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 1, 5, 2, 'Bài viết mẫu số 14', 'Mô tả bài viết mẫu số 14', 'sample, test, demo', '/news/bai-viet-mau-so-14', '[]', 425, 1, '2025-07-31 16:37:46', '2025-08-11 09:37:46', '2025-08-12 06:36:13', 0),
(15, 'bai-viet-mau-so-15', 'Bài viết mẫu số 15', 'Đây là bài viết mẫu số 15 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 15.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 4, 5, 6, 'Bài viết mẫu số 15', 'Mô tả bài viết mẫu số 15', 'sample, test, demo', '/news/bai-viet-mau-so-15', '[]', 390, 1, '2025-06-20 16:37:46', '2025-08-11 09:37:46', '2025-08-11 09:37:46', 0),
(16, 'bai-viet-mau-so-16', 'Bài viết mẫu số 16', 'Đây là bài viết mẫu số 16 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 16.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 9, 1, 5, 'Bài viết mẫu số 16', 'Mô tả bài viết mẫu số 16', 'sample, test, demo', '/news/bai-viet-mau-so-16', '[]', 255, 1, '2025-08-07 16:37:46', '2025-08-11 09:37:46', '2025-08-12 07:00:21', 0),
(17, 'bai-viet-mau-so-17', 'Bài viết mẫu số 17', 'Đây là bài viết mẫu số 17 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 17.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 5, 3, 6, 'Bài viết mẫu số 17', 'Mô tả bài viết mẫu số 17', 'sample, test, demo', '/news/bai-viet-mau-so-17', '[]', 352, 1, '2025-07-31 16:37:46', '2025-08-11 09:37:46', '2025-08-14 03:50:29', 0),
(18, 'bai-viet-mau-so-18', 'Bài viết mẫu số 18', 'Đây là bài viết mẫu số 18 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 18.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 8, 5, 4, 'Bài viết mẫu số 18', 'Mô tả bài viết mẫu số 18', 'sample, test, demo', '/news/bai-viet-mau-so-18', '[]', 602, 1, '2025-07-21 16:37:46', '2025-08-11 09:37:46', '2025-08-11 09:37:46', 0),
(19, 'bai-viet-mau-so-19', 'Bài viết mẫu số 19', 'Đây là bài viết mẫu số 19 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 19.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 10, 7, 6, 'Bài viết mẫu số 19', 'Mô tả bài viết mẫu số 19', 'sample, test, demo', '/news/bai-viet-mau-so-19', '[]', 229, 1, '2025-07-08 16:37:46', '2025-08-11 09:37:46', '2025-08-11 09:37:46', 0),
(20, 'bai-viet-mau-so-20', 'Bài viết mẫu số 20', 'Đây là bài viết mẫu số 20 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 20.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 1, 2, 6, 'Bài viết mẫu số 20', 'Mô tả bài viết mẫu số 20', 'sample, test, demo', '/news/bai-viet-mau-so-20', '[]', 246, 1, '2025-08-09 16:37:46', '2025-08-11 09:37:46', '2025-08-14 02:22:32', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news1`
--

CREATE TABLE `news1` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'Khóa chính, định danh bài viết',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug URL thân thiện SEO – không dấu, không trùng',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Nội dung đầy đủ bài viết (có thể chứa HTML, ảnh...)',
  `thumbnail_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID ảnh đại diện – liên kết đến bảng media_files',
  `author_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID người tạo bài viết – liên kết bảng users',
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thẻ <title> tùy chỉnh cho SEO (nếu không để trống thì dùng thay title)',
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả bài viết hiển thị trong kết quả tìm kiếm Google',
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Từ khóa SEO cách nhau bằng dấu phẩy (,)',
  `canonical_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL chuẩn để tránh trùng lặp nội dung',
  `schema_json` json DEFAULT NULL COMMENT 'Dữ liệu Schema.org ở dạng JSON-LD để Google hiểu nội dung',
  `views` int DEFAULT '0' COMMENT 'Số lượt xem bài viết',
  `status` tinyint DEFAULT '1' COMMENT '1: Hiển thị, 0: Ẩn bài viết',
  `published_at` datetime DEFAULT NULL COMMENT 'Ngày giờ bài viết được xuất bản',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật',
  `category_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu bài viết tin tức chuẩn SEO cho website khách sạn có phân quyền người viết';

--
-- Đang đổ dữ liệu cho bảng `news1`
--

INSERT INTO `news1` (`id`, `slug`, `content`, `thumbnail_id`, `author_id`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `schema_json`, `views`, `status`, `published_at`, `created_at`, `updated_at`, `category_id`) VALUES
(1, 'beb', '<p>brefbhref</p>', 4, 1, 'beb', 'bẻber', 'bẻb', NULL, NULL, 0, 1, '2025-08-15 16:14:00', '2025-08-01 09:14:55', '2025-08-01 09:14:55', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news_categories`
--

CREATE TABLE `news_categories` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'Khóa chính chuyên mục',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên chuyên mục (ví dụ: Ưu đãi, Tin tức, Hướng dẫn...)',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug URL của chuyên mục (không dấu)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả ngắn giúp định nghĩa mục đích chuyên mục',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh mục tin tức phân loại nội dung';

--
-- Đang đổ dữ liệu cho bảng `news_categories`
--

INSERT INTO `news_categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Nguyễn Anh Đức', 'nguyen-anh-duc', 'ny', '2025-07-29 16:30:24', '2025-07-29 16:30:24'),
(2, 'Tin Tức Khách Sạn', 'tin-tuc-khach-san', 'Các tin tức mới nhất về khách sạn và dịch vụ', '2025-08-11 09:37:45', '2025-08-11 09:37:45'),
(3, 'Ưu Đãi & Khuyến Mãi', 'uu-dai-khuyen-mai', 'Thông tin về các chương trình ưu đãi, khuyến mãi đặc biệt', '2025-08-11 09:37:45', '2025-08-11 09:37:45'),
(4, 'Hướng Dẫn Du Lịch', 'huong-dan-du-lich', 'Các bài viết hướng dẫn du lịch, địa điểm tham quan', '2025-08-11 09:37:45', '2025-08-11 09:37:45'),
(5, 'Sự Kiện', 'su-kien', 'Thông tin về các sự kiện, lễ hội, hoạt động tại khách sạn', '2025-08-11 09:37:45', '2025-08-11 09:37:45'),
(6, 'Ẩm Thực', 'am-thuc', 'Giới thiệu về ẩm thực, nhà hàng và các món ăn đặc sắc', '2025-08-11 09:37:45', '2025-08-11 09:37:45'),
(7, 'Tips & Tricks', 'tips-tricks', 'Các mẹo và kinh nghiệm hữu ích cho khách du lịch', '2025-08-11 09:37:45', '2025-08-11 09:37:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news_comments`
--

CREATE TABLE `news_comments` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'Khóa chính',
  `news_id` bigint UNSIGNED NOT NULL COMMENT 'ID bài viết (liên kết news)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người dùng (liên kết users)',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung bình luận',
  `likes` int NOT NULL DEFAULT '0' COMMENT 'Số lượt thích',
  `parent_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID bình luận cha (cho reply)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `news_comments`
--

INSERT INTO `news_comments` (`id`, `news_id`, `user_id`, `content`, `likes`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 'Staff thân thiện, phòng ốc sạch sẽ. Sẽ quay lại lần sau.', 15, NULL, '2025-07-19 09:37:46', '2025-08-11 09:37:46'),
(2, 1, 2, 'Bài viết rất hay và bổ ích! Cảm ơn admin đã chia sẻ.', 12, NULL, '2025-08-03 09:37:46', '2025-08-11 09:37:46'),
(3, 1, 3, 'Gần trung tâm thương mại không ạ?', 20, NULL, '2025-07-17 09:37:46', '2025-08-11 09:37:46'),
(4, 1, 8, 'Giá có hợp lý không ạ? Có ưu đãi gì cho khách lần đầu không?', 12, NULL, '2025-07-27 09:37:46', '2025-08-11 09:37:46'),
(5, 1, 7, 'Pet-friendly không ạ?', 13, NULL, '2025-07-14 09:37:46', '2025-08-11 09:37:46'),
(6, 1, 8, 'Chúng tôi chào đón thú cưng với phụ phí 200.000 VND/đêm.', 2, 5, '2025-08-06 09:37:46', '2025-08-11 09:37:46'),
(7, 1, 3, 'Có shuttle bus đến sân bay không ạ?', 22, NULL, '2025-08-04 09:37:46', '2025-08-11 09:37:46'),
(8, 1, 2, 'Tôi đã ở đây rồi và thật sự rất hài lòng với dịch vụ.', 16, NULL, '2025-07-22 09:37:46', '2025-08-11 09:37:46'),
(9, 2, 5, 'WiFi có nhanh không? Tôi cần làm việc online.', 19, NULL, '2025-07-27 09:37:46', '2025-08-11 09:37:46'),
(10, 2, 8, 'Late checkout đến 2:00 PM miễn phí, sau đó tính phí 50% giá phòng.', 5, 9, '2025-07-25 09:37:46', '2025-08-11 09:37:46'),
(11, 2, 7, 'Late checkout có tính phí không?', 3, NULL, '2025-07-16 09:37:46', '2025-08-11 09:37:46'),
(12, 2, 7, 'Phòng gym mở cửa 24/7 với đầy đủ thiết bị hiện đại.', 2, 11, '2025-07-31 09:37:46', '2025-08-11 09:37:46'),
(13, 2, 3, 'Pool và spa rất tuyệt vời!', 0, NULL, '2025-07-12 09:37:46', '2025-08-11 09:37:46'),
(14, 2, 8, 'Phòng gym mở cửa 24/7 với đầy đủ thiết bị hiện đại.', 9, 13, '2025-07-28 09:37:46', '2025-08-11 09:37:46'),
(15, 3, 5, 'Thông tin rất hữu ích, tôi sẽ book phòng ngay hôm nay.', 8, NULL, '2025-07-17 09:37:46', '2025-08-11 09:37:46'),
(16, 3, 8, 'Rất cảm ơn bạn đã tin tưởng và lựa chọn dịch vụ của chúng tôi.', 2, 15, '2025-08-10 09:37:46', '2025-08-11 09:37:46'),
(17, 3, 6, 'Bữa sáng buffet đa dạng và ngon miệng.', 20, NULL, '2025-07-25 09:37:46', '2025-08-11 09:37:46'),
(18, 3, 7, 'Giá có hợp lý không ạ? Có ưu đãi gì cho khách lần đầu không?', 23, NULL, '2025-08-04 09:37:46', '2025-08-11 09:37:46'),
(19, 3, 8, 'Staff thân thiện, phòng ốc sạch sẽ. Sẽ quay lại lần sau.', 6, NULL, '2025-07-15 09:37:46', '2025-08-11 09:37:46'),
(20, 3, 7, 'Tôi đã ở đây rồi và thật sự rất hài lòng với dịch vụ.', 11, NULL, '2025-07-17 09:37:46', '2025-08-11 09:37:46'),
(21, 3, 5, 'Staff thân thiện, phòng ốc sạch sẽ. Sẽ quay lại lần sau.', 17, NULL, '2025-07-16 09:37:46', '2025-08-11 09:37:46'),
(22, 4, 6, 'WiFi có nhanh không? Tôi cần làm việc online.', 2, NULL, '2025-08-05 09:37:46', '2025-08-11 09:37:46'),
(23, 4, 8, 'Có tour du lịch địa phương không?', 20, NULL, '2025-07-14 09:37:46', '2025-08-11 09:37:46'),
(24, 4, 5, 'Có dịch vụ giặt ủi với thời gian hoàn thành trong 24 giờ.', 9, 23, '2025-08-07 09:37:46', '2025-08-11 09:37:46'),
(25, 4, 7, 'Có dịch vụ giặt ủi không?', 5, NULL, '2025-08-03 09:37:46', '2025-08-11 09:37:46'),
(26, 4, 3, 'Parking hoàn toàn miễn phí trong suốt thời gian lưu trú.', 4, 25, '2025-08-07 09:37:46', '2025-08-11 09:37:46'),
(27, 4, 3, 'Pet-friendly không ạ?', 13, NULL, '2025-07-28 09:37:46', '2025-08-11 09:37:46'),
(28, 5, 6, 'Giá có hợp lý không ạ? Có ưu đãi gì cho khách lần đầu không?', 24, NULL, '2025-07-27 09:37:46', '2025-08-11 09:37:46'),
(29, 5, 6, 'Pet-friendly không ạ?', 5, NULL, '2025-07-15 09:37:46', '2025-08-11 09:37:46'),
(30, 5, 8, 'View từ phòng rất đẹp, đặc biệt là lúc sunset.', 16, NULL, '2025-07-25 09:37:46', '2025-08-11 09:37:46'),
(31, 5, 8, 'Phòng gym có đầy đủ thiết bị không?', 7, NULL, '2025-07-18 09:37:46', '2025-08-11 09:37:46'),
(32, 5, 1, 'Bữa sáng buffet đa dạng và ngon miệng.', 24, NULL, '2025-07-23 09:37:46', '2025-08-11 09:37:46'),
(33, 5, 2, 'Parking hoàn toàn miễn phí trong suốt thời gian lưu trú.', 10, 32, '2025-07-29 09:37:46', '2025-08-11 09:37:46'),
(34, 5, 5, 'Có shuttle bus đến sân bay không ạ?', 23, NULL, '2025-08-01 09:37:46', '2025-08-11 09:37:46'),
(35, 5, 1, 'Địa điểm tuyệt vời cho kỳ nghỉ gia đình!', 12, NULL, '2025-07-21 09:37:46', '2025-08-11 09:37:46'),
(36, 5, 7, 'Gần trung tâm thương mại không ạ?', 14, NULL, '2025-07-15 09:37:46', '2025-08-11 09:37:46'),
(37, 6, 5, 'Có shuttle bus đến sân bay không ạ?', 14, NULL, '2025-07-22 09:37:46', '2025-08-11 09:37:46'),
(38, 6, 2, 'Phòng gym có đầy đủ thiết bị không?', 22, NULL, '2025-07-31 09:37:46', '2025-08-11 09:37:46'),
(39, 6, 5, 'WiFi có nhanh không? Tôi cần làm việc online.', 14, NULL, '2025-07-27 09:37:46', '2025-08-11 09:37:46'),
(40, 6, 3, 'Có dịch vụ giặt ủi không?', 18, NULL, '2025-07-31 09:37:46', '2025-08-11 09:37:46'),
(41, 6, 7, 'Có dịch vụ giặt ủi với thời gian hoàn thành trong 24 giờ.', 3, 40, '2025-08-05 09:37:46', '2025-08-11 09:37:46'),
(42, 6, 5, 'Giá có hợp lý không ạ? Có ưu đãi gì cho khách lần đầu không?', 23, NULL, '2025-08-08 09:37:46', '2025-08-11 09:37:46'),
(43, 6, 3, 'Parking hoàn toàn miễn phí trong suốt thời gian lưu trú.', 4, 42, '2025-08-03 09:37:46', '2025-08-11 09:37:46'),
(44, 6, 1, 'Có dịch vụ giặt ủi không?', 0, NULL, '2025-08-07 09:37:46', '2025-08-11 09:37:46'),
(45, 6, 2, 'Chúng tôi chào đón thú cưng với phụ phí 200.000 VND/đêm.', 3, 44, '2025-07-22 09:37:46', '2025-08-11 09:37:46'),
(46, 6, 6, 'Late checkout có tính phí không?', 25, NULL, '2025-07-26 09:37:46', '2025-08-11 09:37:46'),
(47, 6, 1, 'Rất cảm ơn bạn đã tin tưởng và lựa chọn dịch vụ của chúng tôi.', 5, 46, '2025-07-20 09:37:46', '2025-08-11 09:37:46'),
(48, 6, 8, 'Bữa sáng buffet đa dạng và ngon miệng.', 9, NULL, '2025-07-15 09:37:46', '2025-08-11 09:37:46'),
(49, 7, 3, 'Có shuttle bus đến sân bay không ạ?', 14, NULL, '2025-07-25 09:37:46', '2025-08-11 09:37:46'),
(50, 7, 7, 'Có dịch vụ giặt ủi với thời gian hoàn thành trong 24 giờ.', 10, 49, '2025-07-28 09:37:46', '2025-08-11 09:37:46'),
(51, 7, 1, 'Có shuttle bus đến sân bay không ạ?', 15, NULL, '2025-08-03 09:37:46', '2025-08-11 09:37:46'),
(52, 7, 8, 'Có dịch vụ giặt ủi với thời gian hoàn thành trong 24 giờ.', 6, 51, '2025-07-22 09:37:46', '2025-08-11 09:37:46'),
(53, 7, 1, 'Late checkout có tính phí không?', 16, NULL, '2025-07-21 09:37:46', '2025-08-11 09:37:46'),
(54, 7, 8, 'Chúng tôi chào đón thú cưng với phụ phí 200.000 VND/đêm.', 3, 53, '2025-07-20 09:37:46', '2025-08-11 09:37:46'),
(55, 7, 6, 'Có dịch vụ giặt ủi không?', 25, NULL, '2025-08-06 09:37:46', '2025-08-11 09:37:46'),
(56, 7, 8, 'Late checkout đến 2:00 PM miễn phí, sau đó tính phí 50% giá phòng.', 6, 55, '2025-07-17 09:37:46', '2025-08-11 09:37:46'),
(57, 8, 1, 'Tôi đã ở đây rồi và thật sự rất hài lòng với dịch vụ.', 9, NULL, '2025-08-07 09:37:46', '2025-08-11 09:37:46'),
(58, 8, 1, 'Parking hoàn toàn miễn phí trong suốt thời gian lưu trú.', 7, 57, '2025-07-24 09:37:46', '2025-08-11 09:37:46'),
(59, 8, 5, 'Tôi đã ở đây rồi và thật sự rất hài lòng với dịch vụ.', 16, NULL, '2025-07-22 09:37:46', '2025-08-11 09:37:46'),
(60, 8, 8, 'Pool và spa rất tuyệt vời!', 11, NULL, '2025-07-19 09:37:46', '2025-08-11 09:37:46'),
(61, 8, 6, 'WiFi tốc độ cao miễn phí trong toàn bộ khu resort.', 1, 60, '2025-08-10 09:37:46', '2025-08-11 09:37:46'),
(62, 8, 6, 'Gần trung tâm thương mại không ạ?', 25, NULL, '2025-07-21 09:37:46', '2025-08-11 09:37:46'),
(63, 8, 5, 'Cảm ơn bạn! Chúng tôi rất vui khi nhận được feedback tích cực.', 0, 62, '2025-07-30 09:37:46', '2025-08-11 09:37:46'),
(64, 9, 5, 'Bài viết rất hay và bổ ích! Cảm ơn admin đã chia sẻ.', 24, NULL, '2025-08-02 09:37:46', '2025-08-11 09:37:46'),
(65, 9, 3, 'Rất cảm ơn bạn đã tin tưởng và lựa chọn dịch vụ của chúng tôi.', 5, 64, '2025-07-29 09:37:46', '2025-08-11 09:37:46'),
(66, 9, 1, 'WiFi có nhanh không? Tôi cần làm việc online.', 12, NULL, '2025-08-10 09:37:46', '2025-08-11 09:37:46'),
(67, 9, 5, 'Rất cảm ơn bạn đã tin tưởng và lựa chọn dịch vụ của chúng tôi.', 1, 66, '2025-07-21 09:37:46', '2025-08-11 09:37:46'),
(68, 9, 3, 'Bữa sáng buffet đa dạng và ngon miệng.', 13, NULL, '2025-07-30 09:37:46', '2025-08-11 09:37:46'),
(69, 9, 7, 'Giá có hợp lý không ạ? Có ưu đãi gì cho khách lần đầu không?', 20, NULL, '2025-07-16 09:37:46', '2025-08-11 09:37:46'),
(70, 9, 5, 'Shuttle bus miễn phí từ 6:00 AM đến 10:00 PM, cách 30 phút một chuyến.', 0, 69, '2025-08-05 09:37:46', '2025-08-11 09:37:46'),
(71, 10, 5, 'Có shuttle bus đến sân bay không ạ?', 5, NULL, '2025-08-10 09:37:46', '2025-08-11 09:37:46'),
(72, 10, 7, 'Tôi đã ở đây rồi và thật sự rất hài lòng với dịch vụ.', 20, NULL, '2025-08-09 09:37:46', '2025-08-11 09:37:46'),
(73, 10, 5, 'Có tour du lịch địa phương không?', 23, NULL, '2025-07-22 09:37:46', '2025-08-11 09:37:46'),
(74, 10, 1, 'WiFi tốc độ cao miễn phí trong toàn bộ khu resort.', 0, 73, '2025-07-18 09:37:46', '2025-08-11 09:37:46'),
(75, 11, 6, 'Bữa sáng buffet đa dạng và ngon miệng.', 8, NULL, '2025-07-26 09:37:46', '2025-08-11 09:37:46'),
(76, 11, 8, 'Staff thân thiện, phòng ốc sạch sẽ. Sẽ quay lại lần sau.', 6, NULL, '2025-07-25 09:37:46', '2025-08-11 09:37:46'),
(77, 11, 2, 'Có tour du lịch địa phương không?', 6, NULL, '2025-08-08 09:37:46', '2025-08-11 09:37:46'),
(78, 11, 7, 'WiFi có nhanh không? Tôi cần làm việc online.', 4, NULL, '2025-07-19 09:37:46', '2025-08-11 09:37:46'),
(79, 11, 1, 'Khách sạn nhìn có vẻ sang trọng quá, mình phải đi thử mới được.', 19, NULL, '2025-07-26 09:37:46', '2025-08-11 09:37:46'),
(80, 11, 8, 'Chào bạn! Hiện tại chúng tôi có chương trình ưu đãi 20% cho khách lần đầu đặt phòng.', 10, 79, '2025-07-26 09:37:46', '2025-08-11 09:37:46'),
(81, 11, 7, 'Giá có hợp lý không ạ? Có ưu đãi gì cho khách lần đầu không?', 7, NULL, '2025-08-05 09:37:46', '2025-08-11 09:37:46'),
(82, 11, 6, 'WiFi có nhanh không? Tôi cần làm việc online.', 19, NULL, '2025-08-05 09:37:46', '2025-08-11 09:37:46'),
(83, 11, 2, 'Phòng gym mở cửa 24/7 với đầy đủ thiết bị hiện đại.', 10, 82, '2025-08-10 09:37:46', '2025-08-11 09:37:46'),
(84, 12, 8, 'Thông tin rất hữu ích, tôi sẽ book phòng ngay hôm nay.', 11, NULL, '2025-07-16 09:37:46', '2025-08-11 09:37:46'),
(85, 12, 1, 'Gần trung tâm thương mại không ạ?', 12, NULL, '2025-07-15 09:37:46', '2025-08-11 09:37:46'),
(86, 12, 6, 'Late checkout có tính phí không?', 20, NULL, '2025-07-12 09:37:46', '2025-08-11 09:37:46'),
(87, 12, 3, 'Pet-friendly không ạ?', 11, NULL, '2025-07-13 09:37:46', '2025-08-11 09:37:46'),
(88, 13, 2, 'Parking miễn phí không? Tôi sẽ lái xe đến.', 25, NULL, '2025-07-25 09:37:46', '2025-08-11 09:37:46'),
(89, 13, 8, 'Pool và spa rất tuyệt vời!', 1, NULL, '2025-07-30 09:37:46', '2025-08-11 09:37:46'),
(90, 13, 6, 'Parking miễn phí không? Tôi sẽ lái xe đến.', 9, NULL, '2025-07-27 09:37:46', '2025-08-11 09:37:46'),
(91, 13, 5, 'Late checkout đến 2:00 PM miễn phí, sau đó tính phí 50% giá phòng.', 7, 90, '2025-07-17 09:37:46', '2025-08-11 09:37:46'),
(92, 13, 5, 'Late checkout có tính phí không?', 5, NULL, '2025-07-31 09:37:46', '2025-08-11 09:37:46'),
(93, 13, 8, 'WiFi tốc độ cao miễn phí trong toàn bộ khu resort.', 5, 92, '2025-08-06 09:37:46', '2025-08-11 09:37:46'),
(94, 13, 6, 'Late checkout có tính phí không?', 18, NULL, '2025-07-16 09:37:46', '2025-08-11 09:37:46'),
(95, 13, 2, 'View từ phòng rất đẹp, đặc biệt là lúc sunset.', 7, NULL, '2025-07-22 09:37:46', '2025-08-11 09:37:46'),
(96, 13, 6, 'Có shuttle bus đến sân bay không ạ?', 22, NULL, '2025-07-22 09:37:46', '2025-08-11 09:37:46'),
(97, 14, 3, 'Bài viết rất hay và bổ ích! Cảm ơn admin đã chia sẻ.', 1, NULL, '2025-07-26 09:37:46', '2025-08-11 09:37:46'),
(98, 14, 6, 'Bữa sáng buffet đa dạng và ngon miệng.', 15, NULL, '2025-07-26 09:37:46', '2025-08-11 09:37:46'),
(99, 14, 6, 'Chào bạn! Hiện tại chúng tôi có chương trình ưu đãi 20% cho khách lần đầu đặt phòng.', 3, 98, '2025-07-27 09:37:46', '2025-08-11 09:37:46'),
(100, 14, 7, 'Late checkout có tính phí không?', 2, NULL, '2025-07-17 09:37:46', '2025-08-11 09:37:46'),
(101, 14, 6, 'Rất cảm ơn bạn đã tin tưởng và lựa chọn dịch vụ của chúng tôi.', 9, 100, '2025-07-17 09:37:46', '2025-08-11 09:37:46'),
(102, 15, 1, 'Pool và spa rất tuyệt vời!', 24, NULL, '2025-08-06 09:37:46', '2025-08-11 09:37:46'),
(103, 15, 2, 'Chào bạn! Hiện tại chúng tôi có chương trình ưu đãi 20% cho khách lần đầu đặt phòng.', 4, 102, '2025-07-30 09:37:46', '2025-08-11 09:37:46'),
(104, 15, 8, 'Giá có hợp lý không ạ? Có ưu đãi gì cho khách lần đầu không?', 13, NULL, '2025-07-20 09:37:46', '2025-08-11 09:37:46'),
(105, 15, 6, 'Khách sạn nhìn có vẻ sang trọng quá, mình phải đi thử mới được.', 19, NULL, '2025-07-29 09:37:46', '2025-08-11 09:37:46'),
(106, 15, 6, 'Chào bạn! Hiện tại chúng tôi có chương trình ưu đãi 20% cho khách lần đầu đặt phòng.', 2, 105, '2025-08-03 09:37:46', '2025-08-11 09:37:46'),
(107, 16, 7, 'Địa điểm tuyệt vời cho kỳ nghỉ gia đình!', 15, NULL, '2025-07-29 09:37:46', '2025-08-11 09:37:46'),
(108, 16, 7, 'Khách sạn nhìn có vẻ sang trọng quá, mình phải đi thử mới được.', 10, NULL, '2025-07-21 09:37:46', '2025-08-11 09:37:46'),
(109, 16, 1, 'Phòng gym có đầy đủ thiết bị không?', 14, NULL, '2025-08-06 09:37:46', '2025-08-11 09:37:46'),
(110, 16, 8, 'Có shuttle bus đến sân bay không ạ?', 4, NULL, '2025-07-12 09:37:46', '2025-08-11 09:37:46'),
(111, 16, 8, 'Phòng gym mở cửa 24/7 với đầy đủ thiết bị hiện đại.', 0, 110, '2025-08-04 09:37:46', '2025-08-11 09:37:46'),
(112, 17, 3, 'Bài viết rất hay và bổ ích! Cảm ơn admin đã chia sẻ.', 17, NULL, '2025-07-22 09:37:46', '2025-08-11 09:37:46'),
(113, 17, 8, 'Thông tin rất hữu ích, tôi sẽ book phòng ngay hôm nay.', 12, NULL, '2025-07-19 09:37:46', '2025-08-11 09:37:46'),
(114, 17, 8, 'Parking miễn phí không? Tôi sẽ lái xe đến.', 15, NULL, '2025-07-31 09:37:46', '2025-08-11 09:37:46'),
(115, 17, 1, 'Gần trung tâm thương mại không ạ?', 6, NULL, '2025-07-30 09:37:46', '2025-08-11 09:37:46'),
(116, 17, 5, 'Có tour du lịch địa phương không?', 9, NULL, '2025-07-23 09:37:46', '2025-08-11 09:37:46'),
(117, 17, 2, 'Phòng gym mở cửa 24/7 với đầy đủ thiết bị hiện đại.', 5, 116, '2025-07-17 09:37:46', '2025-08-11 09:37:46'),
(118, 17, 3, 'View từ phòng rất đẹp, đặc biệt là lúc sunset.', 7, NULL, '2025-08-02 09:37:46', '2025-08-11 09:37:46'),
(119, 17, 7, 'Chào bạn! Hiện tại chúng tôi có chương trình ưu đãi 20% cho khách lần đầu đặt phòng.', 5, 118, '2025-08-08 09:37:46', '2025-08-11 09:37:46'),
(120, 17, 7, 'Parking miễn phí không? Tôi sẽ lái xe đến.', 3, NULL, '2025-08-02 09:37:46', '2025-08-11 09:37:46'),
(121, 18, 6, 'Phòng gym có đầy đủ thiết bị không?', 0, NULL, '2025-07-13 09:37:46', '2025-08-11 09:37:46'),
(122, 18, 5, 'Rất cảm ơn bạn đã tin tưởng và lựa chọn dịch vụ của chúng tôi.', 6, 121, '2025-07-26 09:37:46', '2025-08-11 09:37:46'),
(123, 18, 7, 'Parking miễn phí không? Tôi sẽ lái xe đến.', 2, NULL, '2025-08-07 09:37:46', '2025-08-11 09:37:46'),
(124, 18, 8, 'Rất cảm ơn bạn đã tin tưởng và lựa chọn dịch vụ của chúng tôi.', 6, 123, '2025-07-27 09:37:46', '2025-08-11 09:37:46'),
(125, 18, 1, 'Parking miễn phí không? Tôi sẽ lái xe đến.', 18, NULL, '2025-08-07 09:37:46', '2025-08-11 09:37:46'),
(126, 18, 1, 'Địa điểm tuyệt vời cho kỳ nghỉ gia đình!', 8, NULL, '2025-07-16 09:37:46', '2025-08-11 09:37:46'),
(127, 18, 2, 'Pet-friendly không ạ?', 23, NULL, '2025-07-17 09:37:46', '2025-08-11 09:37:46'),
(128, 19, 6, 'View từ phòng rất đẹp, đặc biệt là lúc sunset.', 9, NULL, '2025-07-31 09:37:46', '2025-08-11 09:37:46'),
(129, 19, 8, 'Rất cảm ơn bạn đã tin tưởng và lựa chọn dịch vụ của chúng tôi.', 5, 128, '2025-07-19 09:37:46', '2025-08-11 09:37:46'),
(130, 19, 7, 'Parking miễn phí không? Tôi sẽ lái xe đến.', 5, NULL, '2025-07-18 09:37:46', '2025-08-11 09:37:46'),
(131, 19, 7, 'Late checkout đến 2:00 PM miễn phí, sau đó tính phí 50% giá phòng.', 0, 130, '2025-07-19 09:37:46', '2025-08-11 09:37:46'),
(132, 19, 8, 'Có shuttle bus đến sân bay không ạ?', 3, NULL, '2025-08-05 09:37:46', '2025-08-11 09:37:46'),
(133, 19, 6, 'Late checkout đến 2:00 PM miễn phí, sau đó tính phí 50% giá phòng.', 8, 132, '2025-08-07 09:37:46', '2025-08-11 09:37:46'),
(134, 19, 6, 'Pet-friendly không ạ?', 13, NULL, '2025-07-20 09:37:46', '2025-08-11 09:37:46'),
(135, 19, 3, 'Có shuttle bus đến sân bay không ạ?', 25, NULL, '2025-07-12 09:37:46', '2025-08-11 09:37:46'),
(136, 20, 8, 'Check-in nhanh chóng, không phải chờ đợi.', 11, NULL, '2025-08-07 09:37:46', '2025-08-11 09:37:46'),
(137, 20, 1, 'Late checkout có tính phí không?', 16, NULL, '2025-07-25 09:37:46', '2025-08-11 09:37:46'),
(138, 20, 1, 'Chúng tôi chào đón thú cưng với phụ phí 200.000 VND/đêm.', 10, 137, '2025-08-03 09:37:46', '2025-08-11 09:37:46'),
(139, 20, 7, 'Parking miễn phí không? Tôi sẽ lái xe đến.', 23, NULL, '2025-07-24 09:37:46', '2025-08-11 09:37:46'),
(140, 20, 3, 'Pool và spa rất tuyệt vời!', 16, NULL, '2025-07-29 09:37:46', '2025-08-11 09:37:46'),
(141, 20, 2, 'Pet-friendly không ạ?', 15, NULL, '2025-07-21 09:37:46', '2025-08-11 09:37:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news_user_actions`
--

CREATE TABLE `news_user_actions` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'Khóa chính',
  `news_id` bigint UNSIGNED NOT NULL COMMENT 'ID bài viết (liên kết news)',
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID người dùng (liên kết users)',
  `is_liked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Có thích hay không',
  `is_bookmarked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Có bookmark hay không',
  `rating` double DEFAULT NULL COMMENT 'Đánh giá 1-5 sao',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `news_user_actions`
--

INSERT INTO `news_user_actions` (`id`, `news_id`, `user_id`, `is_liked`, `is_bookmarked`, `rating`, `created_at`) VALUES
(1, 1, 1, 1, 1, NULL, '2025-07-30 09:37:46'),
(2, 1, 3, 1, 0, NULL, '2025-08-03 09:37:46'),
(3, 1, 5, 0, 0, 3.7, '2025-08-03 09:37:46'),
(4, 1, 6, 1, 0, 3.6, '2025-08-08 09:37:46'),
(5, 1, 8, 0, 1, 3.8, '2025-07-16 09:37:46'),
(6, 2, 1, 1, 0, NULL, '2025-07-27 09:37:46'),
(7, 2, 6, 1, 0, NULL, '2025-07-26 09:37:46'),
(8, 2, 8, 0, 1, 5.4, '2025-07-28 09:37:46'),
(9, 3, 1, 1, 0, NULL, '2025-08-02 09:37:46'),
(10, 3, 3, 0, 0, NULL, '2025-08-04 09:37:46'),
(11, 3, 5, 1, 0, NULL, '2025-07-20 09:37:46'),
(12, 3, 8, 1, 0, NULL, '2025-07-15 09:37:46'),
(13, 4, 1, 1, 0, NULL, '2025-07-27 09:37:46'),
(14, 4, 2, 1, 0, NULL, '2025-07-19 09:37:46'),
(15, 4, 6, 0, 0, 5.5, '2025-08-07 09:37:46'),
(16, 4, 7, 0, 1, NULL, '2025-08-10 09:37:46'),
(17, 5, 2, 0, 0, 4.9, '2025-07-28 09:37:46'),
(18, 5, 3, 0, 0, NULL, '2025-07-28 09:37:46'),
(19, 5, 5, 0, 0, NULL, '2025-07-29 09:37:46'),
(20, 5, 7, 1, 0, 4.4, '2025-08-04 09:37:46'),
(21, 5, 8, 0, 0, 4.1, '2025-07-28 09:37:46'),
(22, 6, 1, 0, 0, 3.1, '2025-08-05 09:37:46'),
(23, 6, 2, 0, 0, 5.9, '2025-07-15 09:37:46'),
(24, 6, 3, 0, 0, 4.9, '2025-07-18 09:37:46'),
(25, 6, 7, 1, 0, 4.4, '2025-07-25 09:37:46'),
(26, 6, 8, 1, 0, 4.2, '2025-08-10 09:37:46'),
(27, 7, 3, 1, 0, NULL, '2025-08-05 09:37:46'),
(28, 7, 5, 0, 1, 5.2, '2025-07-29 09:37:46'),
(29, 7, 6, 1, 1, 3.3, '2025-08-10 09:37:46'),
(30, 7, 7, 1, 0, NULL, '2025-08-10 09:37:46'),
(31, 7, 8, 1, 0, 5.5, '2025-07-16 09:37:46'),
(32, 8, 3, 1, 0, NULL, '2025-07-12 09:37:46'),
(33, 8, 5, 1, 1, NULL, '2025-08-03 09:37:46'),
(34, 8, 6, 1, 0, NULL, '2025-07-29 09:37:46'),
(35, 9, 2, 0, 0, NULL, '2025-07-24 09:37:46'),
(36, 9, 3, 1, 0, 5.2, '2025-07-19 09:37:46'),
(37, 9, 6, 1, 0, NULL, '2025-07-29 09:37:46'),
(38, 10, 1, 0, 0, 5.6, '2025-07-27 09:37:46'),
(39, 10, 3, 0, 1, 3.2, '2025-07-19 09:37:46'),
(40, 10, 5, 1, 0, 5, '2025-07-19 09:37:46'),
(41, 10, 8, 1, 0, 4.2, '2025-07-17 09:37:46'),
(42, 11, 2, 0, 1, NULL, '2025-07-23 09:37:46'),
(43, 11, 3, 0, 0, 3, '2025-07-16 09:37:46'),
(44, 11, 6, 1, 1, 5.3, '2025-08-06 09:37:46'),
(45, 11, 7, 0, 1, 4, '2025-07-28 09:37:46'),
(46, 11, 8, 0, 0, NULL, '2025-07-27 09:37:46'),
(47, 12, 1, 0, 1, 3.8, '2025-07-20 09:37:46'),
(48, 12, 3, 0, 0, NULL, '2025-07-24 09:37:46'),
(49, 12, 6, 1, 0, 4.5, '2025-07-23 09:37:46'),
(50, 12, 7, 0, 0, NULL, '2025-07-17 09:37:46'),
(51, 12, 8, 1, 0, NULL, '2025-08-01 09:37:46'),
(52, 13, 3, 1, 1, NULL, '2025-07-12 09:37:46'),
(53, 13, 6, 1, 1, NULL, '2025-08-08 09:37:46'),
(54, 13, 8, 0, 0, NULL, '2025-07-17 09:37:46'),
(55, 14, 1, 0, 1, NULL, '2025-07-24 09:37:46'),
(56, 14, 3, 1, 0, 5.5, '2025-08-02 09:37:46'),
(57, 14, 5, 0, 0, NULL, '2025-07-27 09:37:46'),
(58, 14, 8, 0, 0, 5.5, '2025-07-26 09:37:46'),
(59, 15, 1, 0, 0, 4.6, '2025-08-04 09:37:46'),
(60, 15, 3, 0, 0, 6, '2025-07-24 09:37:46'),
(61, 15, 5, 0, 0, NULL, '2025-08-04 09:37:46'),
(62, 15, 7, 1, 0, NULL, '2025-07-28 09:37:46'),
(63, 15, 8, 0, 1, 4.2, '2025-08-01 09:37:46'),
(64, 16, 1, 1, 0, 5.4, '2025-08-04 09:37:46'),
(65, 16, 3, 0, 0, NULL, '2025-07-12 09:37:46'),
(66, 16, 7, 1, 0, NULL, '2025-07-20 09:37:46'),
(67, 16, 8, 1, 0, NULL, '2025-08-09 09:37:46'),
(68, 17, 1, 0, 0, NULL, '2025-07-19 09:37:46'),
(69, 17, 2, 1, 0, NULL, '2025-07-25 09:37:46'),
(70, 17, 3, 1, 1, NULL, '2025-08-04 09:37:46'),
(71, 17, 5, 1, 0, NULL, '2025-07-27 09:37:46'),
(72, 18, 2, 1, 0, NULL, '2025-07-30 09:37:46'),
(73, 18, 5, 0, 0, NULL, '2025-07-23 09:37:46'),
(74, 18, 6, 0, 1, NULL, '2025-08-03 09:37:46'),
(75, 19, 5, 0, 0, NULL, '2025-07-26 09:37:46'),
(76, 19, 6, 0, 0, NULL, '2025-07-22 09:37:46'),
(77, 19, 7, 1, 0, NULL, '2025-08-02 09:37:46'),
(78, 19, 8, 0, 1, NULL, '2025-07-20 09:37:46'),
(79, 20, 1, 0, 0, NULL, '2025-08-09 09:37:46'),
(80, 20, 3, 0, 1, NULL, '2025-08-03 09:37:46'),
(81, 20, 5, 1, 1, NULL, '2025-07-23 09:37:46'),
(82, 20, 6, 1, 0, NULL, '2025-08-01 09:37:46'),
(83, 20, 8, 0, 1, NULL, '2025-07-27 09:37:46');

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
  `payment_type` enum('deposit','full','qr_code','at_hotel','vietqr','refund','additional') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại thanh toán',
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
(27, 66, 11000.00, 'full', 'completed', 'FT25190456258100', '2025-07-08 09:53:35', '2025-07-08 09:54:06'),
(28, 67, 11000.00, 'full', 'completed', 'FT25190204762754', '2025-07-08 20:18:40', '2025-07-08 20:19:17'),
(29, 68, 22000.00, 'full', 'completed', 'FT25190139424549', '2025-07-08 20:20:45', '2025-07-08 20:22:08'),
(30, 69, 33000.00, 'full', 'pending', NULL, '2025-07-08 22:01:07', '2025-07-08 22:01:07'),
(31, 75, 11000.00, 'full', 'pending', NULL, '2025-07-09 00:09:30', '2025-07-09 00:09:30'),
(32, 76, 11000.00, 'full', 'pending', NULL, '2025-07-09 00:35:59', '2025-07-09 00:35:59'),
(33, 77, 11000.00, 'full', 'pending', NULL, '2025-07-09 01:25:16', '2025-07-09 01:25:16'),
(34, 79, 6200000.00, 'full', 'pending', NULL, '2025-07-14 00:21:53', '2025-07-14 00:21:53'),
(35, 80, 11000.00, 'full', 'completed', 'DEV_LVS80072418_1752477973', '2025-07-14 00:24:18', '2025-07-14 00:26:13'),
(36, 81, 11000.00, 'full', 'completed', 'DEV_LVS81091621_1752484623', '2025-07-14 02:16:21', '2025-07-14 02:17:03'),
(37, 82, 11000.00, 'full', 'pending', NULL, '2025-07-14 02:27:29', '2025-07-14 02:27:29'),
(38, 85, 11000.00, 'full', 'pending', NULL, '2025-07-14 02:36:48', '2025-07-14 02:36:48'),
(39, 88, 11000.00, 'full', 'completed', 'DEV_LVS88094850_1752486593', '2025-07-14 02:48:50', '2025-07-14 02:49:53'),
(40, 89, 11000.00, 'full', 'pending', NULL, '2025-07-14 03:35:11', '2025-07-14 03:35:11'),
(41, 90, 11000.00, 'full', 'pending', NULL, '2025-07-14 03:41:27', '2025-07-14 03:41:27'),
(42, 91, 11000.00, 'full', 'pending', NULL, '2025-07-14 03:45:07', '2025-07-14 03:45:07'),
(43, 92, 11000.00, 'full', 'pending', NULL, '2025-07-14 03:54:28', '2025-07-14 03:54:28'),
(44, 93, 11000.00, 'full', 'pending', NULL, '2025-07-14 03:58:32', '2025-07-14 03:58:32'),
(45, 94, 11000.00, 'full', 'completed', 'DEV_LVS94111645_1752492064', '2025-07-14 04:16:45', '2025-07-14 04:21:04'),
(46, 95, 11000.00, 'full', 'completed', 'DEV_LVS95112222_1752492149', '2025-07-14 04:22:22', '2025-07-14 04:22:29'),
(47, 96, 11000.00, 'full', 'completed', 'DEV_LVS96112503_1752492353', '2025-07-14 04:25:03', '2025-07-14 04:25:53'),
(48, 97, 132000.00, 'full', 'pending', NULL, '2025-07-14 04:30:50', '2025-07-14 04:30:50'),
(49, 98, 132000.00, 'full', 'completed', 'DEV_LVS98114231_1752493371', '2025-07-14 04:42:31', '2025-07-14 04:42:51'),
(50, 99, 132000.00, 'full', 'completed', 'DEV_LVS99114449_1752493497', '2025-07-14 04:44:49', '2025-07-14 04:44:57'),
(51, 100, 132000.00, 'full', 'completed', 'DEV_LVS100023425_1752546946', '2025-07-14 19:34:25', '2025-07-14 19:35:46'),
(52, 101, 132000.00, 'full', 'completed', 'DEV_LVS101023558_1752547032', '2025-07-14 19:35:58', '2025-07-14 19:37:12'),
(53, 102, 132000.00, 'full', 'completed', 'DEV_LVS102024015_1752547218', '2025-07-14 19:40:15', '2025-07-14 19:40:18'),
(54, 103, 132000.00, 'full', 'completed', 'DEV_LVS103024501_1752547543', '2025-07-14 19:45:01', '2025-07-14 19:45:43'),
(55, 104, 11000.00, 'full', 'completed', 'DEV_LVS104024936_1752547784', '2025-07-14 19:49:36', '2025-07-14 19:49:44'),
(56, 105, 11000.00, 'full', 'completed', 'DEV_LVS105025917_1752548409', '2025-07-14 19:59:17', '2025-07-14 20:00:09'),
(57, 106, 11000.00, 'full', 'completed', 'DEV_LVS106030509_1752548712', '2025-07-14 20:05:09', '2025-07-14 20:05:12'),
(58, 107, 11000.00, 'full', 'completed', 'DEV_LVS107030523_1752548916', '2025-07-14 20:05:23', '2025-07-14 20:08:36'),
(59, 108, 11000.00, 'full', 'pending', NULL, '2025-07-14 20:17:34', '2025-07-14 20:17:34'),
(60, 109, 11000.00, 'full', 'pending', NULL, '2025-07-14 20:32:33', '2025-07-14 20:32:33'),
(61, 110, 11000.00, 'full', 'pending', NULL, '2025-07-14 21:11:04', '2025-07-14 21:11:04'),
(62, 111, 11000.00, 'full', 'pending', NULL, '2025-07-14 21:22:32', '2025-07-14 21:22:32'),
(63, 112, 11000.00, 'full', 'completed', 'DEV_LVS112044511_1752555939', '2025-07-14 21:45:11', '2025-07-14 22:05:39'),
(64, 113, 11000.00, 'full', 'completed', 'DEV_LVS113070418_1752563089', '2025-07-15 00:04:18', '2025-07-15 00:04:49'),
(65, 114, 11000.00, 'full', 'completed', 'DEV_LVS114070529_1752563275', '2025-07-15 00:05:29', '2025-07-15 00:07:55'),
(66, 115, 11000.00, 'full', 'pending', NULL, '2025-07-15 00:10:36', '2025-07-15 00:10:36'),
(67, 116, 11000.00, 'full', 'pending', NULL, '2025-07-15 00:20:10', '2025-07-15 00:20:10'),
(68, 117, 11000.00, 'full', 'pending', NULL, '2025-07-15 00:25:52', '2025-07-15 00:25:52'),
(69, 118, 11000.00, 'full', 'pending', NULL, '2025-07-15 00:28:00', '2025-07-15 00:28:00'),
(70, 119, 11000.00, 'full', 'pending', NULL, '2025-07-15 00:52:27', '2025-07-15 00:52:27'),
(71, 120, 11000.00, 'full', 'pending', NULL, '2025-07-15 01:52:04', '2025-07-15 01:52:04'),
(72, 121, 11000.00, 'full', 'completed', 'DEV_LVS121091522_1752571814', '2025-07-15 02:15:22', '2025-07-15 02:30:14'),
(73, 124, 22000.00, 'full', 'pending', NULL, '2025-07-15 02:35:16', '2025-07-15 02:35:16'),
(74, 125, 11000.00, 'full', 'pending', NULL, '2025-07-15 03:57:11', '2025-07-15 03:57:11'),
(75, 126, 11000.00, 'full', 'pending', NULL, '2025-07-15 19:30:58', '2025-07-15 19:30:58'),
(76, 127, 11000.00, 'full', 'completed', 'CPAY_LVS127025346_1752634457', '2025-07-15 19:53:46', '2025-07-15 19:54:17'),
(77, 128, 11000.00, 'full', 'pending', NULL, '2025-07-15 19:54:35', '2025-07-15 19:54:35'),
(78, 129, 11000.00, 'full', 'completed', 'CPAY_LVS129030846_1752635377', '2025-07-15 20:08:46', '2025-07-15 20:09:37'),
(79, 130, 11000.00, 'full', 'completed', 'CPAY_LVS130033257_1752636791', '2025-07-15 20:32:57', '2025-07-15 20:33:11'),
(80, 131, 11000.00, 'full', 'completed', 'CPAY_LVS131033527_1752636969', '2025-07-15 20:35:27', '2025-07-15 20:36:09'),
(81, 132, 11000.00, 'full', 'completed', 'CPAY_LVS132033857_1752637170', '2025-07-15 20:38:57', '2025-07-15 20:39:30'),
(82, 133, 11000.00, 'full', 'completed', 'CPAY_LVS133070932_1752649800', '2025-07-16 00:09:32', '2025-07-16 00:10:00'),
(83, 134, 11000.00, 'full', 'pending', NULL, '2025-07-16 20:21:19', '2025-07-16 20:21:19'),
(84, 137, 5510000.00, 'full', 'completed', 'CPAY_LVS137075500_1752738966', '2025-07-17 00:55:00', '2025-07-17 00:56:06'),
(85, 138, 5400000.00, 'full', 'completed', 'CPAY_LVS138081920_1752740432', '2025-07-17 01:19:20', '2025-07-17 01:20:38'),
(86, 139, 5400000.00, 'full', 'completed', 'CPAY_LVS139082730_1752740881', '2025-07-17 01:27:30', '2025-07-17 01:28:01'),
(87, 140, 8650000.00, 'full', 'completed', 'CPAY_LVS140103856_1752748768', '2025-07-17 03:38:56', '2025-07-17 03:39:28'),
(88, 141, 8650000.00, 'full', 'completed', 'CPAY_LVS141104347_1752749043', '2025-07-17 03:43:47', '2025-07-17 03:44:07'),
(89, 142, 8650000.00, 'full', 'completed', 'CPAY_LVS142120903_1752754226', '2025-07-17 05:09:03', '2025-07-17 05:10:30'),
(90, 143, 15450000.00, 'full', 'pending', NULL, '2025-07-18 05:51:43', '2025-07-18 05:51:43'),
(91, 144, 20850000.00, 'full', 'completed', 'CPAY_LVS144031538_1752894994', '2025-07-18 20:15:38', '2025-07-18 20:16:40'),
(92, 145, 20850000.00, 'full', 'pending', NULL, '2025-07-18 20:25:59', '2025-07-18 20:25:59'),
(93, 146, 20850000.00, 'full', 'pending', NULL, '2025-07-18 20:31:53', '2025-07-18 20:31:53'),
(94, 147, 1716000.00, 'full', 'pending', NULL, '2025-07-18 20:37:26', '2025-07-18 20:37:26'),
(95, 148, 20850000.00, 'full', 'pending', NULL, '2025-07-18 20:37:46', '2025-07-18 20:37:46'),
(96, 149, 20850000.00, 'at_hotel', 'pending', NULL, '2025-07-18 20:44:19', '2025-07-18 20:44:19'),
(97, 150, 20850000.00, 'vietqr', 'pending', NULL, '2025-07-18 20:46:33', '2025-07-18 20:46:33'),
(98, 151, 27800000.00, 'vietqr', 'completed', 'CPAY_LVS151023546_1752978972', '2025-07-19 19:35:46', '2025-07-19 19:36:16'),
(99, 152, 10248000.00, 'vietqr', 'completed', 'CPAY_LVS152023648_1752979040', '2025-07-19 19:36:48', '2025-07-19 19:37:24'),
(100, 153, 2288000.00, 'vietqr', 'completed', 'CPAY_LVS153025209_1752979986', '2025-07-19 19:52:09', '2025-07-19 19:53:10'),
(101, 154, 11000.00, 'vietqr', 'pending', NULL, '2025-07-21 02:50:08', '2025-07-21 02:50:08'),
(102, 155, 22000.00, 'vietqr', 'pending', NULL, '2025-07-28 02:20:59', '2025-07-28 02:20:59'),
(103, 156, 22000.00, 'vietqr', 'pending', NULL, '2025-07-28 02:36:54', '2025-07-28 02:36:54'),
(104, 157, 22000.00, 'vietqr', 'pending', NULL, '2025-07-28 05:45:18', '2025-07-28 05:45:18'),
(105, 158, 22000.00, 'vietqr', 'pending', NULL, '2025-07-28 05:45:50', '2025-07-28 05:45:50'),
(106, 159, 22000.00, 'vietqr', 'pending', NULL, '2025-07-28 06:27:46', '2025-07-28 06:27:46'),
(107, 160, 22000.00, 'vietqr', 'pending', NULL, '2025-07-28 19:17:12', '2025-07-28 19:17:12'),
(108, 160, 9414.00, 'refund', 'pending', NULL, '2025-08-02 15:28:33', '2025-08-02 15:28:33'),
(109, 160, 9414.00, 'refund', 'pending', NULL, '2025-08-02 15:29:20', '2025-08-02 15:29:20'),
(110, 24, 959999.00, 'refund', 'pending', NULL, '2025-08-02 15:53:27', '2025-08-02 15:53:27'),
(111, 24, 959999.00, 'refund', 'pending', NULL, '2025-08-02 15:55:40', '2025-08-02 15:55:40'),
(112, 24, 959999.00, 'refund', 'pending', NULL, '2025-08-02 16:21:19', '2025-08-02 16:21:19'),
(113, 24, 959999.00, 'refund', 'pending', NULL, '2025-08-02 16:21:40', '2025-08-02 16:21:40'),
(114, 24, 3980000.00, 'additional', 'pending', NULL, '2025-08-02 16:35:08', '2025-08-02 16:35:08'),
(115, 24, 3980000.00, 'refund', 'pending', NULL, '2025-08-03 04:19:46', '2025-08-03 04:19:46'),
(116, 24, 7960000.00, 'additional', 'pending', NULL, '2025-08-03 04:22:14', '2025-08-03 04:22:14'),
(118, 24, 5175000.00, 'refund', 'pending', NULL, '2025-08-03 16:23:41', '2025-08-03 16:23:41');

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

--
-- Đang đổ dữ liệu cho bảng `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(3, 'App\\Models\\User', 5, 'auth_token', 'cd118426368c486572eb14b9ca92e9453134a0df511a9b1de5a85a82e602d3a3', '[\"*\"]', NULL, NULL, '2025-07-08 21:54:40', '2025-07-08 21:54:40'),
(6, 'App\\Models\\User', 5, 'auth_token', 'b286cf3d87c084d1e0d292089de64ac1004cfed69b04f508a4b5a478b580ce84', '[\"*\"]', NULL, NULL, '2025-07-09 00:05:13', '2025-07-09 00:05:13'),
(14, 'App\\Models\\User', 5, 'auth_token', 'a7dce83486592a96856608a098572e0311c9ba83ed782dbb426e4f692e924e29', '[\"*\"]', NULL, NULL, '2025-07-20 22:09:02', '2025-07-20 22:09:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `policy_applications`
--

CREATE TABLE `policy_applications` (
  `id` int UNSIGNED NOT NULL COMMENT 'ID auto increment',
  `room_type_id` int UNSIGNED DEFAULT NULL COMMENT 'NULL = áp dụng toàn bộ loại phòng; khác NULL = áp dụng cho 1 loại phòng cụ thể',
  `policy_type` enum('cancellation','deposit','check_out') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại chính sách: hủy, đặt cọc, trả phòng',
  `policy_id` int UNSIGNED NOT NULL COMMENT 'ID chính sách cụ thể trong bảng tương ứng (cancellation_policies, deposit_policies, check_out_policies)',
  `applies_to_holiday` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng nếu là ngày lễ (theo holiday_events)?',
  `min_occupancy_percent` tinyint UNSIGNED DEFAULT NULL COMMENT 'Áp dụng nếu tỉ lệ lấp đầy >= giá trị này (%)',
  `max_occupancy_percent` tinyint UNSIGNED DEFAULT NULL COMMENT 'Áp dụng nếu tỉ lệ lấp đầy <= giá trị này (%)',
  `min_days_before_checkin` int UNSIGNED DEFAULT NULL COMMENT 'Áp dụng nếu số ngày hủy >= giá trị này (chỉ dành cho cancellation)',
  `date_from` date DEFAULT NULL COMMENT 'Ngày bắt đầu hiệu lực (nếu áp dụng theo ngày cụ thể)',
  `date_to` date DEFAULT NULL COMMENT 'Ngày kết thúc hiệu lực',
  `priority` tinyint UNSIGNED DEFAULT '1' COMMENT 'Ưu tiên khi có nhiều bản ghi cùng khớp điều kiện',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Có đang được bật không?',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Ánh xạ chính sách theo điều kiện áp dụng như loại phòng, ngày lễ, occupancy, ngày cụ thể';

--
-- Đang đổ dữ liệu cho bảng `policy_applications`
--

INSERT INTO `policy_applications` (`id`, `room_type_id`, `policy_type`, `policy_id`, `applies_to_holiday`, `min_occupancy_percent`, `max_occupancy_percent`, `min_days_before_checkin`, `date_from`, `date_to`, `priority`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'cancellation', 1, 0, NULL, NULL, 7, '2025-07-01', '2025-07-31', 10, 1, '2025-07-12 14:11:23', '2025-07-12 14:42:43'),
(2, NULL, 'deposit', 2, 0, NULL, NULL, NULL, '2025-07-01', '2025-07-31', 10, 1, '2025-07-12 14:11:23', '2025-07-12 14:43:30'),
(3, NULL, 'check_out', 3, 0, NULL, NULL, NULL, NULL, NULL, 10, 1, '2025-07-12 14:11:23', '2025-07-12 14:11:23'),
(4, NULL, 'cancellation', 10, 0, NULL, NULL, NULL, NULL, NULL, 10, 1, '2025-07-12 09:51:47', '2025-07-12 09:51:47'),
(5, NULL, 'cancellation', 11, 1, NULL, NULL, NULL, NULL, NULL, 20, 1, '2025-07-12 09:51:47', '2025-07-12 09:51:47'),
(6, NULL, 'deposit', 10, 0, NULL, NULL, NULL, NULL, NULL, 10, 1, '2025-07-12 09:51:47', '2025-07-12 09:51:47'),
(7, NULL, 'deposit', 11, 1, NULL, NULL, NULL, NULL, NULL, 20, 1, '2025-07-12 09:51:47', '2025-07-12 09:51:47'),
(8, NULL, 'check_out', 4, NULL, NULL, NULL, NULL, NULL, NULL, 10, 1, '2025-07-12 09:51:47', '2025-07-12 09:51:47');

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
-- Cấu trúc bảng cho bảng `representatives`
--

CREATE TABLE `representatives` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `booking_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_card` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `representatives`
--

INSERT INTO `representatives` (`id`, `booking_id`, `booking_code`, `room_id`, `full_name`, `phone_number`, `email`, `id_card`, `created_at`, `updated_at`, `user_id`) VALUES
(21, 23, 'LAVISHSTAY_509999', 255, 'qeweqw', '0335920306', 'quyenjpn@gmail.com', 'qweqweqweqwe', '2025-07-01 04:08:52', '2025-07-01 04:08:52', NULL),
(22, 23, 'LAVISHSTAY_509999', 256, 'qeweqw', '0335920306', 'quyenjpn@gmail.com', 'qweqweqweqwe', '2025-07-01 04:08:52', '2025-07-01 04:08:52', NULL),
(23, 25, 'LVS20250707030928246', 1, 'qeweqw', '0335920306', 'reception@hotel.com', 'qweqweqweqwe', '2025-07-06 20:09:28', '2025-07-06 20:09:28', NULL),
(24, 26, 'LVS20250707031018433', 15, 'qeweqw', '0335920306', 'quyenjpn@gmail.com', 'qweqweqweqwe', '2025-07-06 20:10:18', '2025-07-06 20:10:18', NULL),
(25, 27, 'LVS20250707031110789', 15, 'qeweqw', '0335920306', 'quyenjpn@gmail.com', 'qweqweqweqwe', '2025-07-06 20:11:10', '2025-07-06 20:11:10', NULL),
(27, 31, 'LVS31050405', 1, 'Nguyen Van Test', '0123456789', 'test@email.com', '123456789', '2025-07-07 22:04:05', '2025-07-07 22:04:05', NULL),
(28, 32, 'LVS32050513', 1, 'Nguyen Van Test', '0123456789', 'test@email.com', '123456789', '2025-07-07 22:05:13', '2025-07-07 22:05:13', NULL),
(29, 33, 'LVS33050538', 1, 'Nguyen Van Test', '0123456789', 'test@email.com', '123456789', '2025-07-07 22:05:38', '2025-07-07 22:05:38', NULL),
(30, 34, 'LVS34050642', 6, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-07 22:06:42', '2025-07-07 22:06:42', NULL),
(31, 35, 'LVS35050702', 6, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-07 22:07:02', '2025-07-07 22:07:02', NULL),
(32, 36, 'LVS36050814', 6, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-07 22:08:14', '2025-07-07 22:08:14', NULL),
(33, 37, 'LVS37051044', 2, 'qeweqw', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-07 22:10:44', '2025-07-07 22:10:44', NULL),
(34, 38, 'LVS38051120', 2, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-07 22:11:20', '2025-07-07 22:11:20', NULL),
(35, 39, 'LVS39063958', 7, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-07 23:39:58', '2025-07-07 23:39:58', NULL),
(36, 40, 'LVS40064052', 7, 'qeweqw', '0987654321', 'reception@hotel.com', '', '2025-07-07 23:40:52', '2025-07-07 23:40:52', NULL),
(37, 41, 'LVS41073901', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:39:01', '2025-07-08 00:39:01', NULL),
(38, 42, 'LVS42074259', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:42:59', '2025-07-08 00:42:59', NULL),
(39, 43, 'LVS43074325', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:43:25', '2025-07-08 00:43:25', NULL),
(40, 44, 'LVS44074700', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:47:00', '2025-07-08 00:47:00', NULL),
(41, 45, 'LVS45075351', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:53:51', '2025-07-08 00:53:51', NULL),
(42, 46, 'LVS46075512', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 00:55:12', '2025-07-08 00:55:12', NULL),
(43, 51, 'LVS51083510', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 01:35:10', '2025-07-08 01:35:10', NULL),
(44, 53, 'LVS53093059', 255, 'Nguyen Van Test', '0987654321', 'test@gmail.com', '', '2025-07-08 02:30:59', '2025-07-08 02:30:59', NULL),
(45, 54, 'LVS54093118', 255, 'Test User', '0123456789', 'test@test.com', '', '2025-07-08 02:31:18', '2025-07-08 02:31:18', NULL),
(46, 56, 'LVS56094825', 255, 'Test User Full', '0123456789', 'test@test.com', '', '2025-07-08 02:48:25', '2025-07-08 02:48:25', NULL),
(47, 60, 'LVS60104819', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 03:48:19', '2025-07-08 03:48:19', NULL),
(48, 61, 'LVS61105534', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 03:55:34', '2025-07-08 03:55:34', NULL),
(49, 62, 'LVS62153758', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 08:37:58', '2025-07-08 08:37:58', NULL),
(50, 63, 'LVS63162115', 1, 'Huỳnh Thị Bích Tuyền', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 09:21:15', '2025-07-08 09:21:15', NULL),
(51, 64, 'LVS64164554', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 09:45:54', '2025-07-08 09:45:54', NULL),
(52, 65, 'LVS65165011', 1, 'qeweqw', '333241324342', 'quyenjpn@gmail.com', '', '2025-07-08 09:50:11', '2025-07-08 09:50:11', NULL),
(53, 66, 'LVS66165335', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 09:53:35', '2025-07-08 09:53:35', NULL),
(54, 67, 'LVS67031840', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 20:18:40', '2025-07-08 20:18:40', NULL),
(55, 68, 'LVS68032045', 1, 'qeweqw', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 20:20:45', '2025-07-08 20:20:45', NULL),
(56, 69, 'LVS69050107', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-08 22:01:07', '2025-07-08 22:01:07', NULL),
(59, 75, 'LVS75070930', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-09 00:09:30', '2025-07-09 00:09:30', NULL),
(60, 76, 'LVS76073559', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-09 00:35:59', '2025-07-09 00:35:59', NULL),
(61, 77, 'LVS77082516', 1, '明心', '12342342341', 'quyenjpn@gmail.com', '', '2025-07-09 01:25:16', '2025-07-09 01:25:16', NULL),
(63, 79, 'LVS79072153', 7, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 00:21:53', '2025-07-14 00:21:53', NULL),
(64, 80, 'LVS80072418', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 00:24:18', '2025-07-14 00:24:18', NULL),
(65, 81, 'LVS81091621', 1, '明心', '123412341234', 'quyenjpn@gmail.com', '', '2025-07-14 02:16:21', '2025-07-14 02:16:21', NULL),
(66, 82, 'LVS82092729', 1, '明心', '123412342', 'quyenjpn@gmail.com', '', '2025-07-14 02:27:29', '2025-07-14 02:27:29', NULL),
(69, 85, 'LVS85093648', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 02:36:48', '2025-07-14 02:36:48', NULL),
(72, 88, 'LVS88094850', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 02:48:50', '2025-07-14 02:48:50', NULL),
(73, 92, 'LVS92105428', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 03:54:28', '2025-07-14 03:54:28', NULL),
(74, 93, 'LVS93105832', 1, '明心', '23413421243', 'quyenjpn@gmail.com', '', '2025-07-14 03:58:32', '2025-07-14 03:58:32', NULL),
(78, 94, 'LVS94111645', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 04:21:04', '2025-07-14 04:21:04', NULL),
(79, 95, 'LVS95112222', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 04:22:29', '2025-07-14 04:22:29', NULL),
(80, 96, 'LVS96112503', 1, '明心', '1234123412431', 'quyenjpn@gmail.com', '', '2025-07-14 04:25:53', '2025-07-14 04:25:53', NULL),
(81, 98, 'LVS98114231', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 04:42:51', '2025-07-14 04:42:51', NULL),
(82, 99, 'LVS99114449', 1, '明心', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-14 04:44:57', '2025-07-14 04:44:57', NULL),
(83, 100, 'LVS100023425', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 19:35:46', '2025-07-14 19:35:46', NULL),
(84, 101, 'LVS101023558', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 19:37:12', '2025-07-14 19:37:12', NULL),
(85, 102, 'LVS102024015', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 19:40:18', '2025-07-14 19:40:18', NULL),
(86, 103, 'LVS103024501', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 19:45:43', '2025-07-14 19:45:43', NULL),
(87, 104, 'LVS104024936', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 19:49:44', '2025-07-14 19:49:44', NULL),
(88, 105, 'LVS105025917', 1, '明心', '1234', 'quyenjpn@gmail.com', '', '2025-07-14 19:59:17', '2025-07-14 19:59:17', NULL),
(89, 106, 'LVS106030509', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-14 20:05:12', '2025-07-14 20:05:12', NULL),
(90, 107, 'LVS107030523', 1, '明心', '2354', 'quyenjpn@gmail.com', '', '2025-07-14 20:08:35', '2025-07-14 20:08:35', NULL),
(98, 113, 'LVS113070418', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-15 00:04:49', '2025-07-15 00:04:49', NULL),
(99, 114, 'LVS114070529', 1, '明心', '1234124312341', 'quyenjpn@gmail.com', '', '2025-07-15 00:07:55', '2025-07-15 00:07:55', NULL),
(100, 121, 'LVS121091522', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-15 02:15:22', '2025-07-15 02:15:22', NULL),
(103, 124, 'LVS124093516', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-15 02:35:16', '2025-07-15 02:35:16', NULL),
(104, 127, 'LVS127025346', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-15 19:54:17', '2025-07-15 19:54:17', NULL),
(105, 129, 'LVS129030846', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-15 20:09:37', '2025-07-15 20:09:37', NULL),
(106, 130, 'LVS130033257', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-15 20:33:11', '2025-07-15 20:33:11', NULL),
(107, 131, 'LVS131033527', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-15 20:36:09', '2025-07-15 20:36:09', NULL),
(108, 132, 'LVS132033857', 1, '明心', '0987654321', 'quyenjpn@gmail.com', '', '2025-07-15 20:39:30', '2025-07-15 20:39:30', NULL),
(109, 133, 'LVS133070932', 1, '明心', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-16 00:10:00', '2025-07-16 00:10:00', NULL),
(111, 136, 'LVS20250717034906701', 4, 'Quyền Nguyễn Văn', '0335920306', 'quyenjpn@gmail.com', '12341234123', '2025-07-16 20:49:06', '2025-07-16 20:49:06', NULL),
(112, 137, 'LVS137075500', 5, '明心', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-17 00:56:06', '2025-07-17 00:56:06', NULL),
(113, 138, 'LVS138081920', 5, '明心', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-17 01:20:32', '2025-07-17 01:20:32', NULL),
(114, 141, 'LVS141104347', NULL, '明têttetetete', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-17 03:44:03', '2025-07-17 03:44:03', NULL),
(115, 142, 'LVS142120903', NULL, 'test', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-17 05:10:26', '2025-07-17 05:10:26', NULL),
(116, 144, 'LVS144031538', NULL, '明心', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-18 20:16:34', '2025-07-18 20:16:34', NULL),
(117, 151, 'LVS151023546', NULL, '明心', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-19 19:36:12', '2025-07-19 19:36:12', NULL),
(118, 152, 'LVS152023648', NULL, '明心', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-19 19:37:20', '2025-07-19 19:37:20', NULL),
(119, 153, 'LVS153025209', NULL, '明心', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-19 19:53:06', '2025-07-19 19:53:06', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reschedule_policies`
--

CREATE TABLE `reschedule_policies` (
  `policy_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `room_type_id` int DEFAULT NULL,
  `min_days_before_checkin` int DEFAULT NULL COMMENT 'Số ngày tối thiểu trước check-in để được phép rời lịch',
  `reschedule_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí rời lịch cố định (nếu có)',
  `reschedule_fee_percentage` decimal(5,2) DEFAULT '0.00' COMMENT 'Phí rời lịch theo phần trăm giá booking',
  `applies_to_holiday` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho ngày lễ',
  `applies_to_weekend` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho cuối tuần',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reschedule_policies`
--

INSERT INTO `reschedule_policies` (`policy_id`, `name`, `description`, `room_type_id`, `min_days_before_checkin`, `reschedule_fee_vnd`, `reschedule_fee_percentage`, `applies_to_holiday`, `applies_to_weekend`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Rời lịch miễn phí 7 ngày', 'Miễn phí nếu yêu cầu rời lịch trước 7 ngày check-in, áp dụng cho tất cả các loại phòng vào ngày thường.', NULL, 7, 0.00, 0.00, 0, 0, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00'),
(2, 'Rời lịch tiêu chuẩn 3 ngày', 'Phí 200,000 VND nếu yêu cầu rời lịch trong vòng 3-7 ngày trước check-in, áp dụng cho tất cả các loại phòng.', NULL, 3, 200000.00, 0.00, 0, 0, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00'),
(3, 'Rời lịch phòng VIP 5 ngày', 'Miễn phí nếu yêu cầu trước 5 ngày cho phòng VIP (room_type_id=2), phí 10% giá trị booking nếu trong vòng 2-5 ngày.', 2, 5, 0.00, 10.00, 0, 0, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00'),
(4, 'Rời lịch ngày lễ phòng Suite', 'Phí 15% giá trị booking nếu yêu cầu rời lịch trong vòng 7 ngày trước check-in cho phòng Suite (room_type_id=3) vào ngày lễ.', 3, 7, 0.00, 15.00, 1, 0, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00'),
(5, 'Rời lịch cuối tuần phòng tiêu chuẩn', 'Phí 300,000 VND nếu yêu cầu rời lịch trong vòng 3 ngày trước check-in cho phòng tiêu chuẩn (room_type_id=1) vào cuối tuần.', 1, 3, 300000.00, 0.00, 0, 1, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00'),
(6, 'Rời lịch khẩn cấp', 'Phí 500,000 VND cho yêu cầu rời lịch trong vòng 24 giờ trước check-in, áp dụng cho tất cả các loại phòng.', NULL, 1, 500000.00, 0.00, 1, 1, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00'),
(7, 'Rời lịch The Level', 'Miễn phí nếu yêu cầu trước 10 ngày cho phòng The Level (room_type_id=4), phí 20% giá trị booking nếu trong vòng 5-10 ngày.', 4, 10, 0.00, 20.00, 1, 1, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int NOT NULL COMMENT 'Mã đánh giá',
  `booking_id` int NOT NULL COMMENT 'Liên kết đến booking',
  `rating` decimal(2,1) NOT NULL COMMENT 'Điểm đánh giá (0.0 – 5.0)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `review_date` date NOT NULL,
  `helpful` int UNSIGNED DEFAULT '0',
  `not_helpful` int UNSIGNED DEFAULT '0',
  `travel_type` enum('business','couple','solo','family_young','group') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `admin_reply_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `admin_reply_date` date DEFAULT NULL,
  `admin_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `score_cleanliness` decimal(2,1) DEFAULT NULL COMMENT 'Độ sạch sẽ',
  `score_location` decimal(2,1) DEFAULT NULL COMMENT 'Vị trí',
  `score_facilities` decimal(2,1) DEFAULT NULL COMMENT 'Cơ sở vật chất',
  `score_service` decimal(2,1) DEFAULT NULL COMMENT 'Dịch vụ',
  `score_value` decimal(2,1) DEFAULT NULL COMMENT 'Đáng giá tiền',
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Đánh giá gắn với booking';

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`review_id`, `booking_id`, `rating`, `title`, `comment`, `review_date`, `helpful`, `not_helpful`, `travel_type`, `admin_reply_content`, `admin_reply_date`, `admin_name`, `created_at`, `updated_at`, `score_cleanliness`, `score_location`, `score_facilities`, `score_service`, `score_value`, `status`) VALUES
(5, 23, 5.0, 'Chất lượng tuyệt vời', 'Chất lượng tuyệt vời', '2025-07-25', 1, 0, 'solo', NULL, NULL, NULL, '2025-07-25 03:20:12', '2025-07-24 20:21:20', NULL, NULL, NULL, NULL, NULL, 'approved');

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
(1, 1),
(7, 2);

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
  `status` enum('available','out_of_service') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chi tiết phòng',
  `last_cleaned` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin phòng';

--
-- Đang đổ dữ liệu cho bảng `room`
--

INSERT INTO `room` (`room_id`, `room_type_id`, `name`, `image`, `floor_id`, `bed_type_fixed`, `status`, `description`, `last_cleaned`, `created_at`, `updated_at`) VALUES
(1, 1, '0201', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-07-07 03:14:47'),
(2, 1, '0202', NULL, 2, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(3, 1, '0203', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(4, 1, '0204', NULL, 2, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(5, 1, '0205', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(6, 1, '0206', NULL, 2, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(7, 1, '0207', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(8, 1, '0208', NULL, 2, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(9, 1, '0209', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(10, 1, '0210', NULL, 2, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(11, 1, '0211', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(12, 1, '0212', NULL, 2, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(13, 1, '0213', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(14, 1, '0214', NULL, 2, 2, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
(15, 1, '0215', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-07-07 03:11:10'),
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
  `urgency_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Thông báo khan hiếm',
  `most_popular` tinyint(1) DEFAULT '0' COMMENT 'Tùy chọn phổ biến nhất',
  `recommended` tinyint(1) DEFAULT '0' COMMENT 'Tùy chọn được đề xuất',
  `meal_type` int DEFAULT NULL COMMENT 'Khóa ngoại, mã bữa ăn',
  `bed_type` int DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn giường',
  `recommendation_score` decimal(5,2) DEFAULT NULL,
  `deposit_policy_id` int DEFAULT NULL,
  `check_out_policy_id` int DEFAULT NULL,
  `policy_applied_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `policy_applied_date` date DEFAULT NULL,
  `policy_snapshot_json` json DEFAULT NULL,
  `cancellation_policy_id` int DEFAULT NULL,
  `package_id` int DEFAULT NULL,
  `adjusted_price` decimal(15,2) DEFAULT NULL COMMENT 'Giá sau khi áp dụng các quy tắc',
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu tùy chọn giá và dịch vụ của phòng';

--
-- Đang đổ dữ liệu cho bảng `room_option`
--

INSERT INTO `room_option` (`option_id`, `room_id`, `name`, `price_per_night_vnd`, `max_guests`, `min_guests`, `urgency_message`, `most_popular`, `recommended`, `meal_type`, `bed_type`, `recommendation_score`, `deposit_policy_id`, `check_out_policy_id`, `policy_applied_reason`, `policy_applied_date`, `policy_snapshot_json`, `cancellation_policy_id`, `package_id`, `adjusted_price`, `created_at`, `updated_at`) VALUES
('BOOK-LVS100023425-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS100023425-R1-2', NULL, 'Standard Package', 11000.00, 3, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS103024501-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS103024501-R1-2', NULL, 'Standard Package', 11000.00, 3, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS104024936-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS105025917-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS106030509-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS107030523-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS113070418-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS114070529-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS121091522-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS124093516-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-14', '[]', NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS127025346-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS129030846-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS130033257-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS131033527-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS132033857-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS133070932-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS137075500-R5-1', NULL, 'Suite Package', 2700000.00, 3, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS137075500-R5-2', NULL, 'Suite Package', 2700000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS138081920-R5-1', NULL, 'Suite Package', 2700000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS138081920-R5-2', NULL, 'Suite Package', 2700000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS141104347-R5-1', NULL, 'Suite Package', 2700000.00, 6, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS141104347-R5-2', NULL, 'Suite Package', 2700000.00, 5, 3, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS141104347-R5-3', NULL, 'Suite Package', 2700000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS142120903-R5-1', NULL, 'Suite Package', 2700000.00, 6, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS142120903-R5-2', NULL, 'Suite Package', 2700000.00, 5, 3, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS142120903-R5-3', NULL, 'Suite Package', 2700000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS144031538-R6-1', NULL, 'Luxury Package', 3200000.00, 6, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 6, 3200000.00, NULL, NULL),
('BOOK-LVS144031538-R6-2', NULL, 'Luxury Package', 3200000.00, 4, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 6, 3200000.00, NULL, NULL),
('BOOK-LVS151023546-R6-1', NULL, 'Luxury Package', 3200000.00, 6, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 6, 3200000.00, NULL, NULL),
('BOOK-LVS151023546-R6-2', NULL, 'Luxury Package', 3200000.00, 4, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 6, 3200000.00, NULL, NULL),
('BOOK-LVS152023648-R1-1', NULL, 'Premium Package', 1006000.00, 6, 2, NULL, 1, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 8, 1006000.00, NULL, NULL),
('BOOK-LVS152023648-R1-2', NULL, 'Premium Package', 1006000.00, 4, 2, NULL, 1, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 8, 1006000.00, NULL, NULL),
('BOOK-LVS153025209-R1-1', NULL, 'Standard Package', 11000.00, 6, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS153025209-R1-2', NULL, 'Standard Package', 11000.00, 4, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS60104819-R1-1', 1, 'Standard Package', 1440000.00, 5, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00, NULL, NULL),
('BOOK-LVS60104819-R1-2', 1, 'Standard Package', 1440000.00, 6, 4, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00, NULL, NULL),
('BOOK-LVS60104819-R1-3', 1, 'Standard Package', 1440000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00, NULL, NULL),
('BOOK-LVS61105534-R1-1', 1, 'Standard Package', 1440000.00, 5, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00, NULL, NULL),
('BOOK-LVS61105534-R1-2', 1, 'Standard Package', 1440000.00, 6, 4, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00, NULL, NULL),
('BOOK-LVS61105534-R1-3', 1, 'Standard Package', 1440000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00, NULL, NULL),
('BOOK-LVS62153758-R1-1', 1, 'Standard Package', 1440000.00, 5, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00, NULL, NULL),
('BOOK-LVS62153758-R1-2', 1, 'Standard Package', 1440000.00, 6, 4, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00, NULL, NULL),
('BOOK-LVS62153758-R1-3', 1, 'Standard Package', 1440000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1440000.00, NULL, NULL),
('BOOK-LVS63162115-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS64164554-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS65165011-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS66165335-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS67031840-R1-1', 1, 'Standard Package', 11000.00, 1, 1, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS68032045-R1-1', 1, 'Standard Package', 11000.00, 3, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS69050107-R1-1', 1, 'Standard Package', 11000.00, 3, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS75070930-R1-1', 1, 'Standard Package', 11000.00, 2, 1, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS76073559-R1-1', 1, 'Standard Package', 11000.00, 2, 1, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS77082516-R1-1', 1, 'Standard Package', 11000.00, 2, 1, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS79072153-R7-1', 7, 'Presidential Package', 6200000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 4, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 7, 6200000.00, NULL, NULL),
('BOOK-LVS80072418-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS88094850-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS92105428-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS93105832-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL);
INSERT INTO `room_option` (`option_id`, `room_id`, `name`, `price_per_night_vnd`, `max_guests`, `min_guests`, `urgency_message`, `most_popular`, `recommended`, `meal_type`, `bed_type`, `recommendation_score`, `deposit_policy_id`, `check_out_policy_id`, `policy_applied_reason`, `policy_applied_date`, `policy_snapshot_json`, `cancellation_policy_id`, `package_id`, `adjusted_price`, `created_at`, `updated_at`) VALUES
('BOOK-LVS94111645-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS95112222-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS96112503-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS98114231-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS98114231-R1-2', 1, 'Standard Package', 11000.00, 3, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS99114449-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS99114449-R1-2', NULL, 'Standard Package', 11000.00, 3, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 4, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('OPT10', 92, 'Premium Corner King', 2000000.00, 2, 1, 'Phòng cao cấp, đặt ngay!', 1, 1, 1, 1, 95.00, 1, NULL, NULL, NULL, NULL, NULL, 2, 2000000.00, NULL, '2025-08-03'),
('OPT11', 258, 'The Level Premium King', 2500000.00, 2, 1, 'Chỉ còn 1 phòng!', 0, 1, 1, 1, 88.00, 1, NULL, NULL, NULL, NULL, NULL, 3, 2500000.00, NULL, NULL),
('OPT12', 259, 'The Level Premium Corner Twin', 2800000.00, 2, 1, 'Phòng cao cấp, đặt ngay!', 1, 1, 1, 2, 92.00, 1, NULL, NULL, NULL, NULL, NULL, 4, 2800000.00, NULL, NULL),
('OPT13', 261, 'Suite King', 4000000.00, 2, 1, 'Phòng cao cấp nhất!', 1, 1, 1, 1, 99.00, 1, NULL, NULL, NULL, NULL, NULL, 6, 4000000.00, NULL, NULL),
('OPT6', 260, 'The Level Suite King', 3500000.00, 2, 1, 'Phòng sang trọng, đặt ngay!', 1, 1, 1, 1, 97.00, 1, NULL, NULL, NULL, NULL, NULL, 5, 3500000.00, NULL, NULL),
('OPT8', 255, 'Deluxe King Room', 1200000.00, 2, 1, 'Chỉ còn 3 phòng!', 1, 1, 1, 1, 90.00, 1, NULL, NULL, NULL, NULL, NULL, 1, 1200000.00, NULL, NULL),
('OPT9', 256, 'Deluxe Twin Room', 1300000.00, 2, 1, 'Chỉ còn 2 phòng!', 0, 1, 1, 2, 85.00, 1, NULL, NULL, NULL, NULL, NULL, 1, 1300000.00, NULL, NULL),
('OPT_PRES_1', 295, 'Presidential Suite King', 10000000.00, 4, 2, 'Phòng Tổng thống, đặt ngay!', 1, 1, 1, 1, 100.00, 1, NULL, NULL, NULL, NULL, NULL, 7, 10000000.00, NULL, NULL);

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
(3, 2, '2025-07-02', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-06-26 21:55:01', '2025-07-31 10:11:54'),
(4, 2, '2025-07-03', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-26 21:55:01', '2025-06-29 02:35:55'),
(5, 2, '2025-07-04', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":3,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":80}}]\"', '2025-06-26 21:55:01', '2025-07-03 03:12:38'),
(6, 2, '2025-07-31', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-06-26 21:59:08', '2025-07-31 14:02:33'),
(7, 2, '2025-08-01', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-06-26 21:59:08', '2025-07-31 13:59:57'),
(8, 1, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 02:47:01', '2025-06-28 02:47:01'),
(9, 1, '2025-06-29', 1200000.00, 1680000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":1,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":83}}]\"', '2025-06-28 02:47:01', '2025-06-29 09:09:46'),
(10, 1, '2025-06-30', 1200000.00, 1368000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:01', '2025-06-29 09:09:46'),
(11, 1, '2025-07-01', 5000.00, 5700.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:01', '2025-07-16 01:14:26'),
(12, 1, '2025-07-02', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:01', '2025-07-31 10:11:54'),
(13, 1, '2025-07-03', 1200000.00, 1440000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:01', '2025-06-29 09:09:46'),
(14, 3, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 02:47:03', '2025-06-28 02:47:03'),
(15, 3, '2025-06-29', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 02:47:03', '2025-06-29 04:30:12'),
(16, 3, '2025-06-30', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:03', '2025-06-29 21:13:26'),
(17, 3, '2025-07-01', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:03', '2025-07-03 01:59:50'),
(18, 3, '2025-07-02', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:03', '2025-07-31 10:11:54'),
(19, 3, '2025-07-03', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:03', '2025-06-29 04:30:12'),
(20, 7, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 02:47:08', '2025-06-28 02:47:08'),
(21, 7, '2025-06-29', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 02:47:08', '2025-06-29 02:30:36'),
(22, 7, '2025-06-30', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:08', '2025-06-29 23:54:20'),
(23, 7, '2025-07-01', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:08', '2025-07-03 01:59:50'),
(24, 7, '2025-07-02', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:08', '2025-07-31 10:11:54'),
(25, 7, '2025-07-03', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 02:47:08', '2025-06-29 02:30:36'),
(26, 6, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:40:23', '2025-06-28 03:40:23'),
(27, 6, '2025-06-29', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:40:23', '2025-06-29 02:33:36'),
(28, 6, '2025-06-30', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:40:23', '2025-07-02 03:28:15'),
(29, 6, '2025-07-01', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:40:23', '2025-07-03 01:59:50'),
(30, 6, '2025-07-02', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:40:23', '2025-07-31 10:11:54'),
(31, 6, '2025-07-03', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:40:23', '2025-06-29 02:33:36'),
(32, 2, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:40:24', '2025-06-28 03:40:24'),
(33, 2, '2025-06-29', 1500000.00, 1950000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:40:24', '2025-06-29 05:15:28'),
(34, 2, '2025-06-30', 1500000.00, 1710000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:40:24', '2025-06-29 20:51:47'),
(35, 4, '2025-06-28', 0.00, 0.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"15.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:42:30', '2025-06-28 03:42:30'),
(36, 4, '2025-06-29', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 03:42:30', '2025-06-29 02:34:36'),
(37, 4, '2025-06-30', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:42:30', '2025-06-29 20:52:43'),
(38, 4, '2025-07-01', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:42:30', '2025-07-03 01:59:50'),
(39, 4, '2025-07-02', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:42:30', '2025-07-31 10:11:54'),
(40, 4, '2025-07-03', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 03:42:30', '2025-07-02 04:22:05'),
(41, 4, '2025-07-04', 1000000.00, 1270000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":74}}]\"', '2025-06-28 23:26:04', '2025-07-03 03:12:38'),
(42, 6, '2025-07-04', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:29:06', '2025-06-29 02:33:36'),
(43, 3, '2025-07-04', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:29:18', '2025-06-29 04:30:12'),
(44, 5, '2025-06-29', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-28 23:31:55', '2025-06-29 02:33:38'),
(45, 5, '2025-06-30', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-30T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-07-01T00:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:31:55', '2025-07-02 03:28:15'),
(46, 5, '2025-07-01', 1000000.00, 1140000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":18,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"event\\\",\\\"price_adjustment\\\":\\\"-6.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"event\\\",\\\"event_name\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n c\\\\u00f3 1 0 2\\\",\\\"event_dates\\\":{\\\"start_date\\\":\\\"2025-06-29T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-06-30T17:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:31:55', '2025-07-30 02:42:35'),
(47, 5, '2025-07-02', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-06-28 23:31:55', '2025-07-31 10:11:54'),
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
(63, 2, '2025-07-30', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-06-29 21:43:29', '2025-07-29 10:37:07'),
(64, 7, '2025-07-06', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-06-29 23:54:20', '2025-06-29 23:54:20'),
(65, 2, '2025-07-07', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-02 04:26:19', '2025-07-02 04:26:19'),
(66, 2, '2025-07-08', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-02 04:26:19', '2025-07-02 04:26:19'),
(67, 5, '2025-07-06', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-04 19:42:25', '2025-07-04 19:42:25'),
(68, 6, '2025-07-06', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-04 19:42:25', '2025-07-04 19:42:25'),
(69, 1, '2025-07-07', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-05 20:44:48', '2025-07-09 00:16:39'),
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
(84, 4, '2025-07-09', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-07 19:14:30', '2025-07-08 20:15:37'),
(85, 5, '2025-07-09', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-07 19:14:30', '2025-07-07 19:14:30'),
(86, 6, '2025-07-09', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-07 19:14:30', '2025-07-07 19:14:30'),
(87, 7, '2025-07-09', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-07 19:14:30', '2025-07-07 19:14:30'),
(88, 1, '2025-07-10', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34'),
(89, 2, '2025-07-10', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34'),
(90, 3, '2025-07-10', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34'),
(91, 4, '2025-07-10', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 20:19:57'),
(92, 5, '2025-07-10', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34'),
(93, 6, '2025-07-10', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34'),
(94, 7, '2025-07-10', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-08 19:31:34', '2025-07-08 19:31:34'),
(95, 1, '2025-07-11', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-09 22:12:13', '2025-07-22 23:59:42'),
(96, 2, '2025-07-11', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":4,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"30.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"90.00\\\",\\\"current_occupancy\\\":92}}]\"', '2025-07-09 22:12:13', '2025-07-23 02:49:07'),
(97, 3, '2025-07-11', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-09 22:12:13', '2025-07-09 22:12:13'),
(98, 4, '2025-07-11', 1000000.00, 1270000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":83}}]\"', '2025-07-09 22:12:13', '2025-07-23 02:49:07'),
(99, 5, '2025-07-11', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-09 22:12:13', '2025-07-09 22:12:13'),
(100, 6, '2025-07-11', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-09 22:12:13', '2025-07-09 22:12:13'),
(101, 7, '2025-07-11', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-09 22:12:13', '2025-07-09 22:12:13'),
(102, 1, '2025-07-12', 5000.00, 7000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":1,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":87}}]\"', '2025-07-10 21:21:00', '2025-07-23 09:09:36'),
(103, 2, '2025-07-12', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":3,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":85}}]\"', '2025-07-10 21:21:00', '2025-07-23 09:09:36'),
(104, 3, '2025-07-12', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-10 21:21:00', '2025-07-10 21:21:00'),
(105, 4, '2025-07-12', 1000000.00, 1370000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":72}}]\"', '2025-07-10 21:21:00', '2025-07-23 09:09:36'),
(106, 5, '2025-07-12', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-10 21:21:00', '2025-07-10 21:21:00'),
(107, 6, '2025-07-12', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-10 21:21:00', '2025-07-10 21:21:00'),
(108, 7, '2025-07-12', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-10 21:21:00', '2025-07-10 21:21:00'),
(109, 1, '2025-08-15', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-12 05:00:40', '2025-08-14 03:53:32'),
(110, 1, '2025-07-14', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-12 07:18:05', '2025-07-12 07:18:05'),
(111, 2, '2025-07-14', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-12 07:18:05', '2025-07-12 07:18:05'),
(112, 3, '2025-07-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-12 07:18:05', '2025-07-12 07:18:05'),
(113, 4, '2025-07-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-12 07:18:05', '2025-07-13 21:35:07'),
(114, 5, '2025-07-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-12 07:18:05', '2025-07-12 07:18:05'),
(115, 6, '2025-07-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-12 07:18:05', '2025-07-12 07:18:05'),
(116, 7, '2025-07-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-12 09:55:42', '2025-07-12 09:55:42'),
(117, 1, '2025-07-13', 5000.00, 7000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":1,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":83}}]\"', '2025-07-12 10:10:29', '2025-07-12 10:10:29'),
(118, 2, '2025-07-13', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":4,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"30.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"90.00\\\",\\\"current_occupancy\\\":94}}]\"', '2025-07-12 10:10:29', '2025-07-12 10:10:29'),
(119, 3, '2025-07-13', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-12 10:10:29', '2025-07-12 10:10:29'),
(120, 4, '2025-07-13', 1000000.00, 1370000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":73}}]\"', '2025-07-12 10:10:29', '2025-07-12 10:10:29'),
(121, 5, '2025-07-13', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-12 10:10:29', '2025-07-12 10:10:29'),
(122, 6, '2025-07-13', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-12 10:10:29', '2025-07-12 10:10:29'),
(123, 7, '2025-07-13', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-12 10:10:29', '2025-07-12 10:10:29'),
(124, 1, '2025-07-15', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 20:33:30', '2025-07-13 20:33:30'),
(125, 2, '2025-07-15', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 20:33:30', '2025-07-13 20:33:30'),
(126, 3, '2025-07-15', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 20:33:30', '2025-07-13 20:33:30'),
(127, 4, '2025-07-15', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 20:33:30', '2025-07-13 20:33:30'),
(128, 5, '2025-07-15', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 20:33:30', '2025-07-13 20:33:30'),
(129, 6, '2025-07-15', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 20:33:30', '2025-07-13 20:33:30'),
(130, 7, '2025-07-15', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 20:33:30', '2025-07-13 20:33:30'),
(131, 1, '2025-07-17', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 22:22:11', '2025-07-13 22:22:11'),
(132, 2, '2025-07-17', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 22:22:11', '2025-07-13 22:22:11'),
(133, 3, '2025-07-17', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 22:22:11', '2025-07-13 22:22:11'),
(134, 4, '2025-07-17', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 22:22:11', '2025-07-16 01:28:35'),
(135, 5, '2025-07-17', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 22:22:11', '2025-07-13 22:22:11'),
(136, 6, '2025-07-17', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 22:22:11', '2025-07-13 22:22:11'),
(137, 7, '2025-07-17', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-13 22:22:11', '2025-07-13 22:22:11'),
(138, 1, '2025-07-16', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-14 19:58:52', '2025-07-14 19:58:52'),
(139, 2, '2025-07-16', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-14 19:58:52', '2025-07-14 19:58:52'),
(140, 3, '2025-07-16', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-14 19:58:52', '2025-07-14 19:58:52'),
(141, 4, '2025-07-16', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-14 19:58:52', '2025-07-15 00:52:17'),
(142, 5, '2025-07-16', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-14 19:58:52', '2025-07-14 19:58:52'),
(143, 6, '2025-07-16', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-14 19:58:53', '2025-07-14 19:58:53'),
(144, 7, '2025-07-16', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-14 19:58:53', '2025-07-14 19:58:53'),
(145, 1, '2025-07-18', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-16 20:16:52', '2025-07-17 08:45:59'),
(146, 2, '2025-07-18', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-16 20:16:52', '2025-07-18 03:52:25'),
(147, 3, '2025-07-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-16 20:16:52', '2025-07-16 20:16:52'),
(148, 4, '2025-07-18', 1000000.00, 1270000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":76}}]\"', '2025-07-16 20:16:52', '2025-07-18 03:52:25'),
(149, 5, '2025-07-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-16 20:16:52', '2025-07-16 20:16:52'),
(150, 6, '2025-07-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-16 20:16:52', '2025-07-16 20:16:52'),
(151, 7, '2025-07-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-16 20:16:52', '2025-07-16 20:16:52'),
(152, 1, '2025-07-19', 5000.00, 7000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":1,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":80}}]\"', '2025-07-18 00:25:35', '2025-07-22 22:12:09'),
(153, 2, '2025-07-19', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":3,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":82}}]\"', '2025-07-18 00:25:36', '2025-07-22 22:12:09'),
(154, 3, '2025-07-19', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-18 00:25:36', '2025-07-18 00:25:36'),
(155, 4, '2025-07-19', 1000000.00, 1370000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":77}}]\"', '2025-07-18 00:25:36', '2025-07-22 22:12:09'),
(156, 5, '2025-07-19', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-18 00:25:36', '2025-07-18 00:25:36'),
(157, 6, '2025-07-19', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-18 00:25:36', '2025-07-18 00:25:36'),
(158, 7, '2025-07-19', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-18 00:25:36', '2025-07-18 00:25:36'),
(159, 1, '2025-08-18', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-07-18 03:53:17'),
(160, 2, '2025-08-18', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-07-18 03:53:17'),
(161, 3, '2025-08-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-07-18 03:53:17'),
(162, 4, '2025-08-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-07-18 20:37:38'),
(163, 5, '2025-08-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-07-18 03:53:17'),
(164, 6, '2025-08-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-07-18 03:53:17'),
(165, 1, '2025-07-20', 5000.00, 6500.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-18 18:46:30', '2025-07-20 19:18:20'),
(166, 2, '2025-07-20', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":3,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":83}}]\"', '2025-07-18 18:46:30', '2025-07-20 19:18:20');
INSERT INTO `room_price_history` (`price_history_id`, `room_type_id`, `date`, `base_price`, `adjusted_price`, `applied_rules`, `created_at`, `updated_at`) VALUES
(167, 3, '2025-07-20', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-18 18:46:30', '2025-07-18 18:46:30'),
(168, 4, '2025-07-20', 1000000.00, 1370000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":75}}]\"', '2025-07-18 18:46:30', '2025-07-20 19:18:20'),
(169, 5, '2025-07-20', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-18 18:46:30', '2025-07-18 18:46:30'),
(170, 6, '2025-07-20', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-18 18:46:31', '2025-07-18 18:46:31'),
(171, 7, '2025-07-20', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-18 18:46:31', '2025-07-18 18:46:31'),
(172, 1, '2025-07-21', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 19:06:00', '2025-07-18 19:06:00'),
(173, 2, '2025-07-21', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 19:06:00', '2025-07-18 19:06:00'),
(174, 3, '2025-07-21', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 19:06:00', '2025-07-18 19:06:00'),
(175, 4, '2025-07-21', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 19:06:00', '2025-07-18 19:06:00'),
(176, 5, '2025-07-21', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 19:06:00', '2025-07-18 19:06:00'),
(177, 6, '2025-07-21', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 19:06:00', '2025-07-18 19:06:00'),
(178, 7, '2025-07-21', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-18 19:06:00', '2025-07-18 19:06:00'),
(179, 1, '2025-07-22', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-20 19:07:02', '2025-07-20 19:07:02'),
(180, 2, '2025-07-22', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-20 19:07:02', '2025-07-20 19:07:02'),
(181, 3, '2025-07-22', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-20 19:07:02', '2025-07-20 19:07:02'),
(182, 4, '2025-07-22', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-20 19:07:02', '2025-07-20 19:07:02'),
(183, 5, '2025-07-22', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-20 19:07:02', '2025-07-20 19:07:02'),
(184, 6, '2025-07-22', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-20 19:07:02', '2025-07-20 19:07:02'),
(185, 7, '2025-07-22', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-20 19:07:02', '2025-07-20 19:07:02'),
(186, 1, '2025-07-23', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-21 23:12:43', '2025-07-21 23:12:43'),
(187, 2, '2025-07-23', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-21 23:12:43', '2025-07-21 23:12:43'),
(188, 3, '2025-07-23', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-21 23:12:43', '2025-07-21 23:12:43'),
(189, 4, '2025-07-23', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-21 23:12:43', '2025-07-21 23:12:43'),
(190, 5, '2025-07-23', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-21 23:12:43', '2025-07-21 23:12:43'),
(191, 6, '2025-07-23', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-21 23:12:43', '2025-07-21 23:12:43'),
(192, 7, '2025-07-23', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-21 23:12:43', '2025-07-21 23:12:43'),
(193, 1, '2025-07-24', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 21:59:54', '2025-07-22 21:59:54'),
(194, 2, '2025-07-24', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 21:59:54', '2025-07-22 21:59:54'),
(195, 3, '2025-07-24', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 21:59:54', '2025-07-22 21:59:54'),
(196, 4, '2025-07-24', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 21:59:54', '2025-07-22 21:59:54'),
(197, 5, '2025-07-24', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 21:59:54', '2025-07-22 21:59:54'),
(198, 6, '2025-07-24', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 21:59:54', '2025-07-22 21:59:54'),
(199, 7, '2025-07-24', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 21:59:54', '2025-07-22 21:59:54'),
(200, 1, '2025-08-03', 5000.00, 7000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":2,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"90.00\\\",\\\"current_occupancy\\\":93}}]\"', '2025-07-22 22:00:07', '2025-08-03 04:16:54'),
(201, 2, '2025-08-03', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":4,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"30.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"90.00\\\",\\\"current_occupancy\\\":93}}]\"', '2025-07-22 22:00:07', '2025-08-03 04:16:54'),
(202, 3, '2025-08-03', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-22 22:00:07', '2025-08-03 04:16:54'),
(203, 4, '2025-08-03', 1000000.00, 1370000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":84}}]\"', '2025-07-22 22:00:07', '2025-08-03 04:16:55'),
(204, 5, '2025-08-03', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-22 22:00:07', '2025-08-03 04:16:55'),
(205, 6, '2025-08-03', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-22 22:00:07', '2025-08-03 04:16:55'),
(206, 7, '2025-08-03', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-22 22:00:07', '2025-07-22 22:00:07'),
(207, 1, '2025-08-11', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 22:11:05', '2025-07-22 22:11:05'),
(208, 2, '2025-08-11', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 22:11:05', '2025-07-22 22:11:05'),
(209, 3, '2025-08-11', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 22:11:05', '2025-07-22 22:11:05'),
(210, 4, '2025-08-11', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 22:11:05', '2025-07-22 22:11:05'),
(211, 5, '2025-08-11', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 22:11:05', '2025-07-22 22:11:05'),
(212, 6, '2025-08-11', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 22:11:05', '2025-07-22 22:11:05'),
(213, 7, '2025-08-11', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-22 22:11:05', '2025-07-22 22:11:05'),
(214, 1, '2025-07-25', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-24 20:01:19', '2025-07-24 20:01:19'),
(215, 1, '2025-07-26', 5000.00, 6500.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-24 20:01:19', '2025-07-24 20:25:01'),
(216, 1, '2025-07-27', 5000.00, 7000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":2,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"90.00\\\",\\\"current_occupancy\\\":94}}]\"', '2025-07-24 20:01:19', '2025-07-24 20:01:19'),
(217, 1, '2025-07-28', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-24 20:01:19', '2025-07-24 20:01:19'),
(218, 1, '2025-07-29', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-24 20:01:19', '2025-07-24 20:01:19'),
(219, 1, '2025-07-30', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-24 20:01:19', '2025-07-29 10:37:07'),
(220, 1, '2025-07-31', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-24 20:01:19', '2025-07-31 14:02:33'),
(221, 2, '2025-07-26', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":3,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":83}}]\"', '2025-07-24 20:25:01', '2025-07-24 20:25:01'),
(222, 3, '2025-07-26', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-24 20:25:01', '2025-07-24 20:25:01'),
(223, 4, '2025-07-26', 1000000.00, 1370000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":72}}]\"', '2025-07-24 20:25:01', '2025-07-24 20:25:01'),
(224, 5, '2025-07-26', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-24 20:25:01', '2025-07-24 20:25:01'),
(225, 6, '2025-07-26', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-24 20:25:01', '2025-07-24 20:25:01'),
(226, 7, '2025-07-26', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-07-24 20:25:02', '2025-07-24 20:25:02'),
(227, 2, '2025-07-29', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-27 19:34:22', '2025-07-27 19:34:22'),
(228, 3, '2025-07-29', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-27 19:34:22', '2025-07-27 19:34:22'),
(229, 4, '2025-07-29', 1000000.00, 1270000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":70}}]\"', '2025-07-27 19:34:22', '2025-07-28 06:25:46'),
(230, 5, '2025-07-29', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-27 19:34:22', '2025-07-27 19:34:22'),
(231, 6, '2025-07-29', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-27 19:34:22', '2025-07-27 19:34:22'),
(232, 7, '2025-07-29', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-27 19:34:22', '2025-07-27 19:34:22'),
(233, 2, '2025-07-28', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-27 19:36:37', '2025-07-27 19:36:37'),
(234, 3, '2025-07-28', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-27 19:36:37', '2025-07-27 19:36:37'),
(235, 4, '2025-07-28', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-27 19:36:37', '2025-07-27 19:36:37'),
(236, 5, '2025-07-28', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-27 19:36:37', '2025-07-27 19:36:37'),
(237, 6, '2025-07-28', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\"', '2025-07-27 19:36:37', '2025-07-27 19:36:37'),
(238, 3, '2025-07-30', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-29 00:44:57', '2025-07-29 10:37:07'),
(239, 4, '2025-07-30', 1000000.00, 1270000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":70}}]\"', '2025-07-29 00:44:57', '2025-07-29 16:42:49'),
(240, 5, '2025-07-30', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-29 00:44:57', '2025-07-29 10:37:07'),
(241, 6, '2025-07-30', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-29 00:44:57', '2025-07-29 10:37:07'),
(242, 7, '2025-07-30', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-29 00:44:57', '2025-07-29 10:37:07'),
(243, 1, '2025-08-01', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-31 07:29:19', '2025-07-31 10:11:05'),
(244, 3, '2025-08-01', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-31 07:29:19', '2025-07-31 07:29:19'),
(245, 4, '2025-08-01', 1000000.00, 1270000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":84}}]\"', '2025-07-31 07:29:19', '2025-07-31 13:59:57'),
(246, 5, '2025-08-01', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-31 07:29:19', '2025-07-31 07:29:19'),
(247, 6, '2025-08-01', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-31 07:29:19', '2025-07-31 07:29:19'),
(248, 7, '2025-08-01', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-31 07:29:19', '2025-07-31 07:29:19'),
(249, 3, '2025-07-31', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-31 14:02:33', '2025-07-31 14:02:33'),
(250, 4, '2025-07-31', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-31 14:02:33', '2025-07-31 14:02:33'),
(251, 5, '2025-07-31', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-31 14:02:33', '2025-07-31 14:02:33'),
(252, 6, '2025-07-31', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-31 14:02:33', '2025-07-31 14:02:33'),
(253, 1, '2025-08-04', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-03 04:16:06', '2025-08-03 04:16:06'),
(254, 2, '2025-08-04', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-03 04:16:06', '2025-08-03 04:16:06'),
(255, 3, '2025-08-04', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-03 04:16:06', '2025-08-03 04:16:06'),
(256, 4, '2025-08-04', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-03 04:16:06', '2025-08-03 07:02:14'),
(257, 5, '2025-08-04', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-03 04:16:06', '2025-08-03 04:16:06');
INSERT INTO `room_price_history` (`price_history_id`, `room_type_id`, `date`, `base_price`, `adjusted_price`, `applied_rules`, `created_at`, `updated_at`) VALUES
(258, 6, '2025-08-04', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-03 04:16:06', '2025-08-03 04:16:06'),
(259, 7, '2025-08-04', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-03 04:16:06', '2025-08-03 04:16:06'),
(260, 1, '2025-08-12', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 09:29:54', '2025-08-11 09:29:54'),
(261, 2, '2025-08-12', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 09:29:54', '2025-08-11 09:29:54'),
(262, 3, '2025-08-12', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 09:29:54', '2025-08-11 09:29:54'),
(263, 4, '2025-08-12', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 09:29:54', '2025-08-11 09:29:54'),
(264, 5, '2025-08-12', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 09:29:54', '2025-08-11 09:29:54'),
(265, 6, '2025-08-12', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 09:29:54', '2025-08-11 09:29:54'),
(266, 7, '2025-08-12', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 09:29:54', '2025-08-11 09:29:54'),
(267, 1, '2025-08-13', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-12 03:00:48', '2025-08-12 03:00:48'),
(268, 2, '2025-08-13', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-12 03:00:48', '2025-08-12 03:00:48'),
(269, 3, '2025-08-13', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-12 03:00:48', '2025-08-12 03:00:48'),
(270, 4, '2025-08-13', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-12 03:00:48', '2025-08-12 03:00:48'),
(271, 5, '2025-08-13', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-12 03:00:48', '2025-08-12 03:00:48'),
(272, 6, '2025-08-13', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-12 03:00:48', '2025-08-12 03:00:48'),
(273, 7, '2025-08-13', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-12 03:00:48', '2025-08-12 03:00:48'),
(274, 1, '2025-08-14', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 03:11:46', '2025-08-13 03:11:46'),
(275, 2, '2025-08-14', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 03:11:46', '2025-08-13 03:11:46'),
(276, 3, '2025-08-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 03:11:46', '2025-08-13 03:11:46'),
(277, 4, '2025-08-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 03:11:46', '2025-08-13 03:11:46'),
(278, 5, '2025-08-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 03:11:46', '2025-08-13 03:11:46'),
(279, 6, '2025-08-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 03:11:46', '2025-08-13 03:11:46'),
(280, 7, '2025-08-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 03:11:46', '2025-08-13 03:11:46'),
(281, 2, '2025-08-15', 1500000.00, 2100000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":3,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":88}}]\"', '2025-08-14 02:20:53', '2025-08-14 04:14:17'),
(282, 3, '2025-08-15', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-14 02:20:53', '2025-08-14 02:20:53'),
(283, 4, '2025-08-15', 1000000.00, 1270000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":70}}]\"', '2025-08-14 02:20:53', '2025-08-14 04:14:17'),
(284, 5, '2025-08-15', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-14 02:20:53', '2025-08-14 02:20:53'),
(285, 6, '2025-08-15', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-14 02:20:53', '2025-08-14 02:20:53'),
(286, 7, '2025-08-15', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-14 02:20:53', '2025-08-14 02:20:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_transfers`
--

CREATE TABLE `room_transfers` (
  `transfer_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `old_room_id` int NOT NULL,
  `new_room_id` int NOT NULL,
  `new_option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mã gói phòng mới',
  `transfer_policy_id` int DEFAULT NULL COMMENT 'Mã chính sách chuyển phòng',
  `status` enum('Pending','Approved','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending' COMMENT 'Trạng thái yêu cầu',
  `price_difference_vnd` decimal(15,2) DEFAULT NULL COMMENT 'Chênh lệch giá (dương: bù tiền, âm: hoàn tiền)',
  `payment_id` int DEFAULT NULL COMMENT 'Mã hóa đơn liên quan (nếu có bù tiền)',
  `processed_by` bigint UNSIGNED DEFAULT NULL COMMENT 'Mã nhân viên xử lý',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Lý do chuyển phòng',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `room_transfers`
--

INSERT INTO `room_transfers` (`transfer_id`, `booking_id`, `old_room_id`, `new_room_id`, `new_option_id`, `transfer_policy_id`, `status`, `price_difference_vnd`, `payment_id`, `processed_by`, `reason`, `created_at`, `updated_at`) VALUES
(3, 24, 20, 20, 'OPT10', 7, 'Approved', -959999.00, 113, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:21:40', '2025-08-02 16:21:40'),
(4, 24, 20, 20, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:33:47', '2025-08-02 16:33:47'),
(5, 24, 20, 20, 'OPT10', 7, 'Approved', 3980000.00, 114, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:35:08', '2025-08-02 16:35:08'),
(6, 24, 20, 20, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:40:34', '2025-08-02 16:40:34'),
(7, 24, 20, 20, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:40:36', '2025-08-02 16:40:36'),
(8, 24, 20, 20, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:40:39', '2025-08-02 16:40:39'),
(9, 24, 20, 20, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-03 04:19:40', '2025-08-03 04:19:40'),
(10, 24, 20, 20, 'OPT10', 7, 'Approved', -3980000.00, 115, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-03 04:19:46', '2025-08-03 04:19:46'),
(11, 24, 92, 92, 'OPT10', 7, 'Approved', 7960000.00, 116, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-03 04:22:14', '2025-08-03 04:22:14'),
(12, 24, 95, 95, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp gói phòng', '2025-08-03 11:18:19', '2025-08-03 11:18:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_transfer_policies`
--

CREATE TABLE `room_transfer_policies` (
  `policy_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên chính sách',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chính sách',
  `transfer_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí chuyển phòng cố định (VND)',
  `transfer_fee_percentage` decimal(5,2) DEFAULT '0.00' COMMENT 'Phí chuyển phòng theo phần trăm giá phòng mới',
  `min_days_before_check_in` int DEFAULT NULL COMMENT 'Số ngày tối thiểu trước check-in để áp dụng chuyển phòng',
  `applies_to_holiday` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho ngày lễ',
  `applies_to_weekend` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho cuối tuần',
  `requires_guest_confirmation` tinyint(1) DEFAULT '1' COMMENT 'Yêu cầu xác nhận từ khách',
  `room_type_id` int DEFAULT NULL COMMENT 'Loại phòng áp dụng (NULL nếu áp dụng tất cả)',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái kích hoạt',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` date DEFAULT NULL,
  `applies_to_package_change` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Chính sách chuyển phòng';

--
-- Đang đổ dữ liệu cho bảng `room_transfer_policies`
--

INSERT INTO `room_transfer_policies` (`policy_id`, `name`, `description`, `transfer_fee_vnd`, `transfer_fee_percentage`, `min_days_before_check_in`, `applies_to_holiday`, `applies_to_weekend`, `requires_guest_confirmation`, `room_type_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `applies_to_package_change`) VALUES
(1, 'Chuyển phòng tiêu chuẩn', 'Chuyển phòng cùng loại hoặc nâng cấp, miễn phí nếu thông báo trước 1 ngày, áp dụng cho mọi loại phòng.', 0.00, 0.00, 1, 0, 0, 0, NULL, 1, '2025-08-02 15:21:00', '2025-08-02 15:21:00', NULL, 0),
(2, 'Chuyển phòng ngày lễ', 'Chuyển phòng trong ngày lễ, phí cố định 500,000 VND, yêu cầu thông báo trước 2 ngày, áp dụng cho mọi loại phòng.', 500000.00, 0.00, 2, 1, 0, 1, NULL, 1, '2025-08-02 15:21:00', '2025-08-02 15:21:00', NULL, 0),
(3, 'Chuyển phòng cuối tuần Deluxe', 'Chuyển phòng loại Deluxe vào cuối tuần, phí 10% giá phòng mới, yêu cầu thông báo trước 1 ngày.', 0.00, 10.00, 1, 0, 1, 1, 2, 1, '2025-08-02 15:21:00', '2025-08-02 15:21:00', NULL, 0),
(4, 'Nâng cấp phòng Suite', 'Nâng cấp lên phòng Suite, phí cố định 1,000,000 VND, áp dụng cho loại phòng Suite.', 1000000.00, 0.00, NULL, 0, 0, 1, 3, 1, '2025-08-02 15:21:00', '2025-08-02 15:21:00', NULL, 0),
(5, 'Chuyển phòng khẩn cấp', 'Chuyển phòng trong ngày, phí 15% giá phòng mới, áp dụng cho mọi loại phòng, không yêu cầu thông báo trước.', 0.00, 15.00, 0, 0, 0, 1, NULL, 1, '2025-08-02 15:21:00', '2025-08-02 15:21:00', NULL, 0),
(6, 'Chuyển phòng Standard ngày lễ', 'Chuyển phòng loại Standard trong ngày lễ, phí cố định 300,000 VND, yêu cầu thông báo trước 2 ngày.', 300000.00, 0.00, 2, 1, 0, 1, 1, 1, '2025-08-02 15:21:00', '2025-08-02 15:21:00', NULL, 0),
(7, 'Chuyển phòng cùng loại miễn phí', 'Chuyển phòng cùng loại phòng, miễn phí, không yêu cầu thông báo trước, áp dụng cho mọi loại phòng.', 0.00, 0.00, NULL, 0, 0, 0, NULL, 1, '2025-08-02 15:21:00', '2025-08-02 15:21:00', NULL, 0),
(8, 'Chuyển phòng hạ cấp', 'Chuyển sang phòng loại thấp hơn, phí cố định 200,000 VND, áp dụng cho mọi loại phòng.', 200000.00, 0.00, 1, 0, 0, 1, NULL, 1, '2025-08-02 15:21:00', '2025-08-02 15:21:00', NULL, 0),
(9, 'Chuyển phòng với gói khác', NULL, 50000.00, 5.00, NULL, 0, 0, 0, NULL, 1, '2025-08-03 04:43:28', '2025-08-03 04:43:28', NULL, 1);

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
(29, 1, 'Phòng Loại Sang (Deluxe Room) - Ảnh 1', '/storage/room-types/1/1.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(30, 1, 'Phòng Loại Sang (Deluxe Room) - Ảnh 2', '/storage/room-types/1/2.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(31, 1, 'Phòng Loại Sang (Deluxe Room) - Ảnh 3', '/storage/room-types/1/3.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(32, 1, 'Phòng Loại Sang (Deluxe Room) - Ảnh 4', '/storage/room-types/1/4.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(33, 2, 'Premium Corner - Ảnh 2', '/storage/room-types/2/1.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(34, 2, 'Premium Corner - Ảnh 3', '/storage/room-types/2/2.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(35, 2, 'Premium Corner - Ảnh 4', '/storage/room-types/2/3.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(36, 2, 'Premium Corner - Ảnh 5', '/storage/room-types/2/4.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(37, 2, 'Premium Corner - Ảnh 6', '/storage/room-types/2/5.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(38, 2, 'Premium Corner - Ảnh 7', '/storage/room-types/2/6.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(39, 3, 'The Level Premium - Ảnh 2', '/storage/room-types/3/1.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(40, 3, 'The Level Premium - Ảnh 3', '/storage/room-types/3/2.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(41, 3, 'The Level Premium - Ảnh 4', '/storage/room-types/3/3.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(42, 3, 'The Level Premium - Ảnh 5', '/storage/room-types/3/4.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(43, 3, 'The Level Premium - Ảnh 6', '/storage/room-types/3/5.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(44, 3, 'The Level Premium - Ảnh 7', '/storage/room-types/3/6.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(45, 3, 'The Level Premium - Ảnh 8', '/storage/room-types/3/7.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(46, 3, 'The Level Premium - Ảnh 9', '/storage/room-types/3/8.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(47, 6, 'Suite - Ảnh 2', '/storage/room-types/6/1.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(48, 6, 'Suite - Ảnh 3', '/storage/room-types/6/2.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(49, 6, 'Suite - Ảnh 4', '/storage/room-types/6/3.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(50, 6, 'Suite - Ảnh 5', '/storage/room-types/6/4.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(51, 6, 'Suite - Ảnh 6', '/storage/room-types/6/5.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(52, 6, 'Suite - Ảnh 7', '/storage/room-types/6/6.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(53, 6, 'Suite - Ảnh 8', '/storage/room-types/6/7.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(54, 4, 'The Level Premium Corner - Ảnh 2', '/storage/room-types/4/1.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(55, 4, 'The Level Premium Corner - Ảnh 3', '/storage/room-types/4/2.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(56, 4, 'The Level Premium Corner - Ảnh 4', '/storage/room-types/4/3.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(57, 4, 'The Level Premium Corner - Ảnh 5', '/storage/room-types/4/4.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(58, 4, 'The Level Premium Corner - Ảnh 6', '/storage/room-types/4/5.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(59, 4, 'The Level Premium Corner - Ảnh 7', '/storage/room-types/4/6.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(60, 5, 'The Level Suite - Ảnh 2', '/storage/room-types/5/1.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(61, 5, 'The Level Suite - Ảnh 3', '/storage/room-types/5/2.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(62, 5, 'The Level Suite - Ảnh 4', '/storage/room-types/5/3.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(63, 5, 'The Level Suite - Ảnh 5', '/storage/room-types/5/4.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(64, 5, 'The Level Suite - Ảnh 6', '/storage/room-types/5/5.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(65, 5, 'The Level Suite - Ảnh 7', '/storage/room-types/5/6.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(66, 5, 'The Level Suite - Ảnh 8', '/storage/room-types/5/7.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(67, 5, 'The Level Suite - Ảnh 9', '/storage/room-types/5/8.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(68, 7, 'Presidential Suite - Ảnh 2', '/storage/room-types/7/1.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(69, 7, 'Presidential Suite - Ảnh 3', '/storage/room-types/7/2.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(70, 7, 'Presidential Suite - Ảnh 4', '/storage/room-types/7/3.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(71, 7, 'Presidential Suite - Ảnh 5', '/storage/room-types/7/4.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(72, 7, 'Presidential Suite - Ảnh 6', '/storage/room-types/7/5.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(73, 7, 'Presidential Suite - Ảnh 7', '/storage/room-types/7/6.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(74, 7, 'Presidential Suite - Ảnh 8', '/storage/room-types/7/7.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(75, 7, 'Presidential Suite - Ảnh 9', '/storage/room-types/7/8.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(76, 7, 'Presidential Suite - Ảnh 10', '/storage/room-types/7/9.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(77, 7, 'Presidential Suite - Ảnh 11', '/storage/room-types/7/10.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(78, 7, 'Presidential Suite - Ảnh 12', '/storage/room-types/7/11.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(79, 7, 'Presidential Suite - Ảnh 13', '/storage/room-types/7/12.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(80, 7, 'Presidential Suite - Ảnh 14', '/storage/room-types/7/13.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(81, 7, 'Presidential Suite - Ảnh 15', '/storage/room-types/7/14.webp', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(82, 7, 'Presidential Suite - Ảnh 16', '/storage/room-types/7/15.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(83, 7, 'Presidential Suite - Ảnh 17', '/storage/room-types/7/16.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(84, 7, 'Presidential Suite - Ảnh 18', '/storage/room-types/7/17.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00'),
(85, 7, 'Presidential Suite - Ảnh 19', '/storage/room-types/7/18.jpg', NULL, 0, '2025-07-10 15:05:00', '2025-07-10 15:05:00');

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

--
-- Đang đổ dữ liệu cho bảng `room_type_package_services`
--

INSERT INTO `room_type_package_services` (`id`, `package_id`, `service_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-07-30 03:52:03', '2025-07-30 03:52:03'),
(2, 8, 1, '2025-07-30 03:52:25', '2025-07-30 03:52:25');

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
('5gOK3ldmiZbmf62iko8uexlRMN9B2mQlShRLekXE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTER5Wmo3N3VpWW9UcVJnbUYwVVJNQk5sbFlmRHNid3JVdEk2MVdtUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODg4OC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1752301336),
('8ZR9jSid1KTtEOuxSVnEyS5HVVA0ss9OYgIet2Tu', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWkhXbVlOVmZGcnJocGF6OXFBNkwzdVM5U3FTQ1UyQlRGWkJLZFI5RyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0ODoiaHR0cDovLzEyNy4wLjAuMTo4ODg4L2FkbWluL3Jvb21zP3Jvb21fdHlwZV9pZD0xIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODg4OC9hZG1pbi9yb29tcz9yb29tX3R5cGVfaWQ9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1752331964),
('DRODFLxFPrKpW7VuSK1CvBaGuOkLJa8vVQJCtJEo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRld6bnRrT3NWbloxSEZwRE5nSlhFOUhONGQxTE13MDlnYVJiTEpuYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODg4OC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1752321197),
('e8KJ8MkgdWDFwY8i82LB8Vv6CA6QL3h8Q3dW8qS0', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibDVCZXdKM0p1QzJmcjhUVjY0SVd2cGdEWWtRUllnMENjQkxZZVBXNyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ4OiJodHRwOi8vMTI3LjAuMC4xOjg4ODgvYWRtaW4vcm9vbXM/cm9vbV90eXBlX2lkPTEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1752320952),
('FOBt9dtCMx2EXJRbwMe2jBjrUm0LqIRGxvAlrH1w', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoieG43cjVnV2VSTkR0SHp5VDRhNWJMQXBDSDBWV05KZEhJWHBiaVQzZyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ5OiJodHRwOi8vMTI3LjAuMC4xOjg4ODgvYWRtaW4vY2FuY2VsbGF0aW9uLXBvbGljaWVzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1752333411),
('GFQ2TBiH8WwYeOwrxjw5wMvsZfAfJuNq6jpROJXQ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaTBYcEc4ZUVxTENHTmJaS0gyWEE1aDVTMzRZTjBEQURNcnFVeXRMViI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vMTI3LjAuMC4xOjg4ODgvYWRtaW4vcm9vbS10eXBlcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1752250620),
('i33TjfDog7jIyXZTAooqYHXBUycsU1QbtnRJfhNC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieVhYbnM5QU1TbXptNXBEU241UHF5OFV1c0pqQUxyMGtFYzRDa0JrRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODg4OC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1752312665),
('oNitxo9SjAS8zo9AyPyT4wG1ysAD78aH5vZ6lJRZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY2h6aUdtZHFmcFF3d2dUd09iWE9iWVBTQ3lNUVBSRFd3ZkZLbkFORCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODg4OC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1752329409),
('ulzMoazgYv2MmzpEkyYisMA5VcZQIexnOjVwFEZC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSXl3bUhDdWFqVGZZT1lBYXJadzgwM0lxMVR2bzZWakRHUkZ3SFNEZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODg4OC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1752340222),
('wi72prq2WsWUE2tEZWZj1rt7cNj5BmPCZ0lOluXU', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUWFWVEtRTlhocXliUnpjNWhwb2xmenhmbFJtUFI1cjBPaW1nS2d4dSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ0OiJodHRwOi8vMTI3LjAuMC4xOjg4ODgvYWRtaW4vZGVwb3NpdC1wb2xpY2llcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1752305193),
('ZxzdyZPTSkAz58POHTttrWwZJTWmdvPSr1BmW9wf', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYWNFTWV2aU9wbHNkYzcwbWFXTm44QnRibkY4RTFTSzBmR2x4bWpWVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODg4OC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1752250643);

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
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
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

INSERT INTO `users` (`id`, `name`, `email`, `google_id`, `avatar`, `email_verified_at`, `password`, `phone`, `address`, `identity_code`, `role`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'NGUYỄN ANH ĐỨC', 'nguyenanhduc2909@gmail.com', NULL, NULL, NULL, '$2y$12$c2dNZ4nJgjNNQzaupkPYP.qIR6Ax7vkA65tXqK/n/uStI/bAr5haa', '0822153447', 'Thanh Hóa', '038205000950', 'admin', NULL, NULL, NULL, NULL, NULL, 'profile-photos/mfqMmmx1jtzkRy9YdNHQRl7xjSLZwxGgqDHJd4JS.png', '2025-05-21 01:07:42', '2025-07-21 02:55:59'),
(2, 'Nguyễn Anh Đức', 'nguyenandhduc2909@gmail.com', NULL, NULL, NULL, '$2y$12$ofny2jH99JRC2egJJaVzLOyRIuw2.5aL93twDg6Zw4hOq0KKWdxAu', '08221534422', 'Thanh Hóa', NULL, 'guest', NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-10 09:47:13', '2025-06-10 09:47:13'),
(3, 'Thu Huyền', 'nguyenanhduc29090@gmail.com', NULL, NULL, NULL, '$2y$12$sIBuDRsM3GZwHvaxR8xNeeF6UIW1YTu5wCghwC.M63T3qWoUC6gna', '03111512022', 'Tuyên Quang', '035656218945', NULL, NULL, NULL, NULL, NULL, NULL, 'profile-photos/sVoWN7luhQjbSUrhKANRv2sJKh7h1hOc0saidVWn.jpg', '2025-06-27 00:17:18', '2025-06-27 00:17:18'),
(5, '明心', 'quyenjpn@gmail.com', '109271388597887089369', 'https://lh3.googleusercontent.com/a/ACg8ocLibsuu8ZHTUKCZ5jMRf4XanikYipmCOnfOQFqEYq_3W7lJkd6YCA=s96-c', NULL, '$2y$12$/AcXTgdK8ApiZERpHkvx3.RE/9rRrtszdM3lV.WFPfqCW3j40v/XG', '0335920306', 'Thanh hoá', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-08 21:51:07', '2025-07-08 21:51:07'),
(6, 'Pro Mark', 'markpro824@gmail.com', '103984459604437565231', 'https://lh3.googleusercontent.com/a/ACg8ocLyS17KMeW7ftc9SYLqQGewq65wYm54Chs2pk1kHjkOBT0SBg=s96-c', NULL, '$2y$12$eeWAk0mGEgXsJItVMonL3eP7bVFMFZXKl25jO8gpZL6pU60lGG82e', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-08 21:54:23', '2025-07-08 21:54:23'),
(7, 'nguyễn văn quyền ADMIN', 'werwerww@gmail.com', NULL, NULL, NULL, '$2y$12$fYWYXd5Bo5JeaCgj/6pgl.f7O4WHg/tZjpwbddGbpASPebWu1u4Em', '0987654321', 'jhvbujh', '324123423', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 00:07:53', '2025-07-09 00:12:49'),
(8, 'Phương nguyễn', 'maiiphuong1981@gmail.com', '107379410125025514536', 'https://lh3.googleusercontent.com/a/ACg8ocIGGrOzbzC7mG4bgj1Wz_l4crSDbQ3SWRkvHbbEus5j4BYCO-PR=s96-c', NULL, '$2y$12$SOT8QcuNDrfkZuExPoOGKu/iI8weNR9.Wsu9QGCrKGkFPMD.Ozw0q', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-20 21:36:42', '2025-07-20 21:36:42');

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
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `new_room_id` (`new_room_id`),
  ADD KEY `new_option_id` (`new_option_id`),
  ADD KEY `reschedule_policy_id` (`reschedule_policy_id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `processed_by` (`processed_by`);

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
-- Chỉ mục cho bảng `booking_room_children`
--
ALTER TABLE `booking_room_children`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cancellation_policies`
--
ALTER TABLE `cancellation_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Chỉ mục cho bảng `cancellation_requests`
--
ALTER TABLE `cancellation_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `cancellation_policy_id` (`cancellation_policy_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Chỉ mục cho bảng `check_in_policies`
--
ALTER TABLE `check_in_policies`
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
-- Chỉ mục cho bảng `children_surcharges`
--
ALTER TABLE `children_surcharges`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversations_client_token_unique` (`client_token`),
  ADD KEY `conversations_user_id_foreign` (`user_id`),
  ADD KEY `conversations_handover_to_user_id_foreign` (`handover_to_user_id`);

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
-- Chỉ mục cho bảng `extension_policies`
--
ALTER TABLE `extension_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Chỉ mục cho bảng `extension_requests`
--
ALTER TABLE `extension_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `extension_policy_id` (`extension_policy_id`),
  ADD KEY `processed_by` (`processed_by`);

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
-- Chỉ mục cho bảng `media_files`
--
ALTER TABLE `media_files`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_conversation_id_foreign` (`conversation_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `thumbnail_id` (`thumbnail_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `news1`
--
ALTER TABLE `news1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_news_thumbnail` (`thumbnail_id`),
  ADD KEY `fk_news_author` (`author_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `news_categories`
--
ALTER TABLE `news_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `news_comments`
--
ALTER TABLE `news_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_comments_user_id_foreign` (`user_id`),
  ADD KEY `news_comments_news_id_created_at_index` (`news_id`,`created_at`),
  ADD KEY `news_comments_parent_id_index` (`parent_id`);

--
-- Chỉ mục cho bảng `news_user_actions`
--
ALTER TABLE `news_user_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_news_action` (`news_id`,`user_id`),
  ADD KEY `news_user_actions_user_id_foreign` (`user_id`),
  ADD KEY `news_user_actions_is_liked_index` (`is_liked`),
  ADD KEY `news_user_actions_is_bookmarked_index` (`is_bookmarked`),
  ADD KEY `news_user_actions_rating_index` (`rating`);

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
-- Chỉ mục cho bảng `policy_applications`
--
ALTER TABLE `policy_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_policy_match` (`room_type_id`,`policy_type`,`applies_to_holiday`);

--
-- Chỉ mục cho bảng `pricing_config`
--
ALTER TABLE `pricing_config`
  ADD PRIMARY KEY (`config_id`);

--
-- Chỉ mục cho bảng `representatives`
--
ALTER TABLE `representatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `fk_representative_user` (`user_id`);

--
-- Chỉ mục cho bảng `reschedule_policies`
--
ALTER TABLE `reschedule_policies`
  ADD PRIMARY KEY (`policy_id`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `booking_id` (`booking_id`);

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
  ADD KEY `package_id` (`package_id`),
  ADD KEY `check_out_policy_id` (`check_out_policy_id`);

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
  ADD KEY `new_room_id` (`new_room_id`),
  ADD KEY `new_option_id` (`new_option_id`),
  ADD KEY `transfer_policy_id` (`transfer_policy_id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Chỉ mục cho bảng `room_transfer_policies`
--
ALTER TABLE `room_transfer_policies`
  ADD PRIMARY KEY (`policy_id`),
  ADD KEY `room_type_id` (`room_type_id`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_google_id_index` (`google_id`);

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
  MODIFY `log_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `bed_types`
--
ALTER TABLE `bed_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã đặt phòng', AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT cho bảng `booking_extensions`
--
ALTER TABLE `booking_extensions`
  MODIFY `extension_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `booking_reschedules`
--
ALTER TABLE `booking_reschedules`
  MODIFY `reschedule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `booking_rooms`
--
ALTER TABLE `booking_rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT cho bảng `booking_room_children`
--
ALTER TABLE `booking_room_children`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT cho bảng `cancellation_policies`
--
ALTER TABLE `cancellation_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `cancellation_requests`
--
ALTER TABLE `cancellation_requests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã yêu cầu hủy';

--
-- AUTO_INCREMENT cho bảng `check_in_policies`
--
ALTER TABLE `check_in_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã chính sách nhận phòng';

--
-- AUTO_INCREMENT cho bảng `check_out_policies`
--
ALTER TABLE `check_out_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `check_out_requests`
--
ALTER TABLE `check_out_requests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `children_surcharges`
--
ALTER TABLE `children_surcharges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `datafeeds`
--
ALTER TABLE `datafeeds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `deposit_policies`
--
ALTER TABLE `deposit_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- AUTO_INCREMENT cho bảng `extension_policies`
--
ALTER TABLE `extension_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã chính sách gia hạn', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `extension_requests`
--
ALTER TABLE `extension_requests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã yêu cầu gia hạn', AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT cho bảng `media_files`
--
ALTER TABLE `media_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính của file ảnh/media', AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã bài viết', AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `news1`
--
ALTER TABLE `news1`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, định danh bài viết', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `news_categories`
--
ALTER TABLE `news_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính chuyên mục', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `news_comments`
--
ALTER TABLE `news_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính', AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT cho bảng `news_user_actions`
--
ALTER TABLE `news_user_actions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính', AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã thanh toán', AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `policy_applications`
--
ALTER TABLE `policy_applications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID auto increment', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `pricing_config`
--
ALTER TABLE `pricing_config`
  MODIFY `config_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `representatives`
--
ALTER TABLE `representatives`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT cho bảng `reschedule_policies`
--
ALTER TABLE `reschedule_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int NOT NULL AUTO_INCREMENT COMMENT 'Mã đánh giá', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã phòng', AUTO_INCREMENT=296;

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
  MODIFY `price_history_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=287;

--
-- AUTO_INCREMENT cho bảng `room_transfers`
--
ALTER TABLE `room_transfers`
  MODIFY `transfer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `room_transfer_policies`
--
ALTER TABLE `room_transfer_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `room_types`
--
ALTER TABLE `room_types`
  MODIFY `room_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `room_type_image`
--
ALTER TABLE `room_type_image`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã ảnh', AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT cho bảng `room_type_package`
--
ALTER TABLE `room_type_package`
  MODIFY `package_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `room_type_package_services`
--
ALTER TABLE `room_type_package_services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  ADD CONSTRAINT `booking_reschedules_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_reschedules_ibfk_2` FOREIGN KEY (`new_room_id`) REFERENCES `room` (`room_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_reschedules_ibfk_3` FOREIGN KEY (`new_option_id`) REFERENCES `room_option` (`option_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_reschedules_ibfk_4` FOREIGN KEY (`reschedule_policy_id`) REFERENCES `reschedule_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_reschedules_ibfk_5` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_reschedules_ibfk_6` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD CONSTRAINT `booking_rooms_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  ADD CONSTRAINT `booking_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`),
  ADD CONSTRAINT `booking_rooms_ibfk_3` FOREIGN KEY (`representative_id`) REFERENCES `representatives` (`id`),
  ADD CONSTRAINT `booking_rooms_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `cancellation_requests`
--
ALTER TABLE `cancellation_requests`
  ADD CONSTRAINT `cancellation_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cancellation_requests_ibfk_2` FOREIGN KEY (`cancellation_policy_id`) REFERENCES `cancellation_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cancellation_requests_ibfk_3` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `check_out_requests`
--
ALTER TABLE `check_out_requests`
  ADD CONSTRAINT `check_out_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_handover_to_user_id_foreign` FOREIGN KEY (`handover_to_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `dynamic_pricing_rules`
--
ALTER TABLE `dynamic_pricing_rules`
  ADD CONSTRAINT `dynamic_pricing_rules_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `extension_requests`
--
ALTER TABLE `extension_requests`
  ADD CONSTRAINT `extension_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `extension_requests_ibfk_2` FOREIGN KEY (`extension_policy_id`) REFERENCES `extension_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `extension_requests_ibfk_3` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
-- Ràng buộc cho bảng `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`thumbnail_id`) REFERENCES `media_files` (`id`),
  ADD CONSTRAINT `news_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `news_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`);

--
-- Ràng buộc cho bảng `news1`
--
ALTER TABLE `news1`
  ADD CONSTRAINT `fk_news_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_news_thumbnail` FOREIGN KEY (`thumbnail_id`) REFERENCES `media_files` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `news1_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ràng buộc cho bảng `news_comments`
--
ALTER TABLE `news_comments`
  ADD CONSTRAINT `news_comments_news_id_foreign` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `news_comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `news_user_actions`
--
ALTER TABLE `news_user_actions`
  ADD CONSTRAINT `news_user_actions_news_id_foreign` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_user_actions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_representative_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `representatives_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  ADD CONSTRAINT `representatives_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`);

--
-- Ràng buộc cho bảng `reschedule_policies`
--
ALTER TABLE `reschedule_policies`
  ADD CONSTRAINT `reschedule_policies_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_booking` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `room_option_ibfk_4` FOREIGN KEY (`package_id`) REFERENCES `room_type_package` (`package_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_option_ibfk_5` FOREIGN KEY (`check_out_policy_id`) REFERENCES `check_out_policies` (`policy_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
  ADD CONSTRAINT `room_transfers_ibfk_3` FOREIGN KEY (`new_room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `room_transfers_ibfk_4` FOREIGN KEY (`new_option_id`) REFERENCES `room_option` (`option_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_transfers_ibfk_5` FOREIGN KEY (`transfer_policy_id`) REFERENCES `room_transfer_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_transfers_ibfk_6` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_transfers_ibfk_7` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `room_transfer_policies`
--
ALTER TABLE `room_transfer_policies`
  ADD CONSTRAINT `room_transfer_policies_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `room_type_amenity`
--
ALTER TABLE `room_type_amenity`
  ADD CONSTRAINT `room_type_amenity_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_type_amenity_ibfk_2` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`amenity_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
