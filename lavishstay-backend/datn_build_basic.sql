-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 26, 2025 at 02:51 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datn_build_basic`
--

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `amenity_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon_lib` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amenities`
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
-- Table structure for table `audit_logs`
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
-- Table structure for table `bed_types`
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
-- Dumping data for table `bed_types`
--

INSERT INTO `bed_types` (`id`, `type_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'King', 'hh', 1, '2025-06-10 00:16:30', '2025-06-26 03:21:52'),
(2, 'Twin', '2 giường đơn', 1, '2025-06-26 03:22:13', '2025-06-26 03:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int NOT NULL COMMENT 'Khóa chính, mã đặt phòng',
  `user_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Khóa ngoại, mã người dùng (nếu có)',
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn phòng',
  `check_in_date` date NOT NULL COMMENT 'Ngày nhận phòng',
  `check_out_date` date NOT NULL COMMENT 'Ngày trả phòng',
  `total_price_vnd` decimal(15,2) NOT NULL COMMENT 'Tổng giá (VND)',
  `guest_count` int NOT NULL COMMENT 'Số khách',
  `status` enum('pending','confirmed','cancelled','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Trạng thái đặt phòng',
  `quantity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  `guest_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tên khách',
  `guest_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Email khách',
  `guest_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số điện thoại khách',
  `room_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin đặt phòng';

-- --------------------------------------------------------

--
-- Table structure for table `booking_extensions`
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
-- Table structure for table `booking_reschedules`
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
-- Table structure for table `cancellation_policies`
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
-- Dumping data for table `cancellation_policies`
--

INSERT INTO `cancellation_policies` (`policy_id`, `name`, `free_cancellation_days`, `penalty_percentage`, `penalty_fixed_amount_vnd`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Hủy miễn phí 7 ngày', 7, 0.00, 200000.00, 'Hủy miễn phí nếu trước 7 ngày', 1, '2025-06-11 02:26:26', '2025-06-11 01:24:04'),
(2, 'Hủy có phí', 2, 50.00, NULL, 'Phạt 50% nếu hủy trong vòng 2 ngày', 0, '2025-06-11 02:26:26', '2025-06-13 00:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `check_out_policies`
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
-- Dumping data for table `check_out_policies`
--

INSERT INTO `check_out_policies` (`policy_id`, `name`, `early_check_out_fee_vnd`, `late_check_out_fee_vnd`, `late_check_out_max_hours`, `early_check_out_max_hours`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Trả phòng muộn sau 4 giờ', 0.00, 200000.00, 4, NULL, 'Phí 200,000 VND nếu trả phòng muộn tối đa 4 giờ', 1, '2025-06-11 02:36:00', '2025-06-16 18:37:57'),
(3, 'e', NULL, 0.00, NULL, 4, 'e', 1, '2025-06-16 18:40:33', '2025-06-16 18:40:56');

-- --------------------------------------------------------

--
-- Table structure for table `check_out_requests`
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
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `currency_code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Khóa chính, mã tiền tệ (VND, USD, v.v.)',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên tiền tệ',
  `exchange_rate` decimal(10,4) DEFAULT NULL COMMENT 'Tỷ giá so với VND',
  `symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Ký hiệu tiền tệ (₫, $, v.v.)',
  `format` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Định dạng (ví dụ: {amount} ₫)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin tiền tệ và tỷ giá';

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`currency_code`, `name`, `exchange_rate`, `symbol`, `format`) VALUES
('USD', 'US Dollar', 0.0000, '$', '${amount}'),
('VND', 'Vietnamese Dong', 1.0000, '₫', '{amount} ₫');

-- --------------------------------------------------------

--
-- Table structure for table `datafeeds`
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
-- Table structure for table `deposit_policies`
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
-- Dumping data for table `deposit_policies`
--

INSERT INTO `deposit_policies` (`policy_id`, `name`, `deposit_percentage`, `deposit_fixed_amount_vnd`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Đặt cọc 50%', 50.00, NULL, 'Yêu cầu đặt cọc 50% tổng giá', 1, '2025-06-11 02:24:24', '2025-06-11 02:24:24');

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_pricing_rules`
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
-- Dumping data for table `dynamic_pricing_rules`
--

INSERT INTO `dynamic_pricing_rules` (`rule_id`, `room_type_id`, `occupancy_threshold`, `price_adjustment`, `is_active`, `created_at`, `updated_at`, `priority`, `is_exclusive`) VALUES
(1, 1, 80.00, 10.00, 1, '2025-06-11 02:44:33', '2025-06-23 06:57:56', 5, 0),
(2, 1, 90.00, 20.00, 1, '2025-06-11 02:44:33', '2025-06-11 02:44:33', 5, 0),
(3, 2, 80.00, 20.00, 1, '2025-06-14 03:41:08', '2025-06-14 03:42:03', 5, 0),
(4, 2, 90.00, 30.00, 1, '2025-06-14 04:17:32', '2025-06-14 04:17:32', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `events`
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
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `name`, `start_date`, `end_date`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Lễ hội pháo hoa Đà Nẵng', '2025-07-01', '2025-07-07', 'Sự kiện pháo hoa quốc tế', 1, '2025-06-11 02:20:27', '2025-06-11 02:20:27');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `faqs`
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
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`faq_id`, `question_en`, `question_vi`, `answer_en`, `answer_vi`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Do you serve breakfast?', 'Họ có phục vụ bữa sáng không?', 'Yes, we offer an excellent buffet breakfast from 6:30 AM to 10:30 AM daily with both international and Vietnamese cuisine.', 'Có, chúng tôi cung cấp bữa sáng buffet tuyệt hảo từ 6:30 đến 10:30 hàng ngày với ẩm thực quốc tế và Việt Nam.', 5, 1, '2025-05-23 02:50:42', '2025-06-02 03:21:17'),
(2, 'Is parking available?', 'Chỗ nghỉ có chỗ đỗ xe không?', 'Yes, we provide complimentary self-parking for hotel guests. Valet parking is also available for an additional charge.', 'Có, chúng tôi cung cấp chỗ đỗ xe tự phục vụ miễn phí cho khách khách sạn. Dịch vụ đỗ xe có người phục vụ cũng có sẵn với phí bổ sung.', 3, 1, '2025-05-23 02:50:42', '2025-06-02 03:33:00'),
(3, 'Do you provide airport shuttle service?', 'Chỗ nghỉ có dịch vụ đưa đón sân bay không?', 'Yes, we offer airport transfer service for $25 per trip. Please contact our concierge to arrange your transfer.', 'Có, chúng tôi cung cấp dịch vụ đưa đón sân bay với giá $25 mỗi chuyến. Vui lòng liên hệ với lễ tân để sắp xếp chuyến đi.', 10, 1, '2025-05-23 02:50:42', '2025-06-02 03:33:16'),
(4, 'What is your WiFi ?', 'Chỗ nghỉ có  Wi-Fi ra sao?', 'High-speed WiFi is complimentary throughout the hotel including all guest rooms and public areas.', 'Wi-Fi tốc độ cao miễn phí trong toàn bộ khách sạn bao gồm tất cả các phòng khách và khu vực công cộng.', 0, 1, '2025-05-23 02:50:42', '2025-06-12 00:57:25'),
(7, 'Am i handsome?', 'Tôi có đẹp trai không?', 'Yes Sirrrrr', 'Chắc chắn  rồi broooo', 2, 1, '2025-06-02 02:14:43', '2025-06-02 03:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `flexible_pricing_rules`
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
-- Dumping data for table `flexible_pricing_rules`
--

INSERT INTO `flexible_pricing_rules` (`rule_id`, `room_type_id`, `rule_type`, `days_of_week`, `event_id`, `holiday_id`, `season_name`, `start_date`, `end_date`, `price_adjustment`, `is_active`, `created_at`, `updated_at`, `priority`, `is_exclusive`) VALUES
(3, NULL, 'event', NULL, 1, NULL, NULL, '2025-06-01', '2025-08-01', 25.00, 1, '2025-06-23 03:03:08', '2025-06-26 14:47:48', 2, 1),
(4, NULL, 'holiday', NULL, NULL, 1, NULL, NULL, NULL, 30.00, 1, '2025-06-23 03:03:08', '2025-06-26 14:47:48', 1, 1),
(5, NULL, 'season', NULL, NULL, NULL, 'Mùa cao điểm', '2025-06-01', '2025-08-31', 20.00, 1, '2025-06-23 03:03:08', '2025-06-26 14:47:48', 3, 0),
(6, 1, 'season', NULL, NULL, NULL, 'Mùa thấp điểm', '2025-11-01', '2026-02-28', -10.00, 1, '2025-06-23 03:03:08', '2025-06-26 14:47:48', 3, 0),
(12, NULL, 'weekend', '\"[\\\"Saturday\\\",\\\"Sunday\\\"]\"', NULL, NULL, NULL, NULL, NULL, 15.00, 1, '2025-06-23 00:45:55', '2025-06-26 14:47:47', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `floors`
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
-- Dumping data for table `floors`
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
-- Table structure for table `guests`
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
-- Table structure for table `holidays`
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
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`holiday_id`, `name`, `start_date`, `end_date`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Tết Nguyên Đán', '2025-01-29', '2025-01-31', 'Tết Âm lịch Việt Nam', 1, '2025-06-11 02:21:39', '2025-06-13 09:05:57'),
(2, 'Quốc khánh', '2025-09-02', NULL, 'Ngày Quốc khánh Việt Nam', 1, '2025-06-11 02:21:39', '2025-06-11 02:21:39');

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `hotel_id` int NOT NULL COMMENT 'Khóa chính, mã khách sạn',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên khách sạn',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Địa chỉ khách sạn',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả khách sạn'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin khách sạn';

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`hotel_id`, `name`, `address`, `description`) VALUES
(1, 'Mường Thanh Thanh Hóa', 'Thanh Hóa', 'Khách sạn Mường Thanh');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_rating`
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
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `language_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Khóa chính, mã ngôn ngữ (vi, en, v.v.)',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên ngôn ngữ (Vietnamese, English, v.v.)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách ngôn ngữ hỗ trợ';

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`language_code`, `name`) VALUES
('en_EN', 'English'),
('vi-VN', 'Vietnamese');

-- --------------------------------------------------------

--
-- Table structure for table `meal_types`
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
-- Dumping data for table `meal_types`
--

INSERT INTO `meal_types` (`id`, `type_name`, `description`, `base_price_vnd`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Không', 'Khôngg', 0.00, 1, '2025-06-09 20:37:04', '2025-06-09 20:37:28');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
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
(9, '2025_06_16_094513_add_early_checkout_fields_to_check_out_policies_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
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
-- Table structure for table `payment`
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

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
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
-- Table structure for table `pricing_config`
--

CREATE TABLE `pricing_config` (
  `config_id` int NOT NULL,
  `max_price_increase_percentage` decimal(5,2) DEFAULT '40.00' COMMENT 'Giới hạn tăng giá tối đa (%)',
  `max_absolute_price_vnd` decimal(15,2) DEFAULT '3000000.00' COMMENT 'Giới hạn giá trần tuyệt đối (VND)',
  `use_exclusive_rule` tinyint(1) DEFAULT '0' COMMENT 'Bật chế độ chỉ lấy quy tắc ưu tiên cao nhất',
  `exclusive_rule_type` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Loại quy tắc độc quyền (event, holiday, season, weekend, occupancy)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pricing_config`
--

INSERT INTO `pricing_config` (`config_id`, `max_price_increase_percentage`, `max_absolute_price_vnd`, `use_exclusive_rule`, `exclusive_rule_type`, `created_at`, `updated_at`) VALUES
(1, 40.00, 3000000.00, 0, NULL, '2025-06-26 14:47:48', '2025-06-26 14:47:48');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int NOT NULL COMMENT 'Khóa chính, mã phòng',
  `room_type_id` int NOT NULL COMMENT 'Khóa ngoại, mã loại phòng',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên phòng',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh chính',
  `floor_id` int DEFAULT NULL COMMENT 'Tầng của phòng',
  `bed_type_fixed` int DEFAULT NULL COMMENT 'Loại giường mặc định',
  `status` enum('available','occupied','maintenance','cleaning') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chi tiết phòng',
  `last_cleaned` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin phòng';

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_type_id`, `name`, `image`, `floor_id`, `bed_type_fixed`, `status`, `description`, `last_cleaned`, `created_at`, `updated_at`) VALUES
(1, 1, '0201', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
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
(15, 1, '0215', NULL, 2, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
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
(45, 1, '0415', NULL, 4, 1, 'available', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
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
(256, 5, '2502', NULL, 25, 1, 'occupied', NULL, NULL, '2025-06-24 12:00:15', '2025-06-24 12:00:15'),
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
-- Table structure for table `room_availability`
--

CREATE TABLE `room_availability` (
  `availability_id` int NOT NULL COMMENT 'Khóa chính, mã lịch',
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn phòng',
  `date` date NOT NULL COMMENT 'Ngày áp dụng',
  `total_rooms` int NOT NULL COMMENT 'Tổng số phòng',
  `available_rooms` int NOT NULL COMMENT 'Số phòng còn trống'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu lịch đặt phòng theo ngày';

-- --------------------------------------------------------

--
-- Table structure for table `room_bed_types`
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
-- Table structure for table `room_meal_types`
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
-- Table structure for table `room_occupancy`
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

-- --------------------------------------------------------

--
-- Table structure for table `room_option`
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

-- --------------------------------------------------------

--
-- Table structure for table `room_option_promotion`
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
-- Table structure for table `room_price_history`
--

CREATE TABLE `room_price_history` (
  `price_history_id` int NOT NULL,
  `room_type_id` int NOT NULL,
  `date` date NOT NULL,
  `base_price` decimal(15,2) NOT NULL COMMENT 'Giá cơ bản',
  `adjusted_price` decimal(15,2) NOT NULL COMMENT 'Giá sau điều chỉnh',
  `applied_rules` json DEFAULT NULL COMMENT 'Danh sách quy tắc áp dụng (ID và loại quy tắc)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_transfers`
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
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `room_type_id` int NOT NULL COMMENT 'Khóa chính, mã loại phòng',
  `room_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Code loại phòng',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên loại phòng',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả loại phòng',
  `total_room` int DEFAULT NULL,
  `base_price` decimal(15,2) NOT NULL COMMENT 'Giá cơ bản',
  `room_area` int NOT NULL COMMENT 'Diện tích phòng',
  `view` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Cảnh, góc nhìn',
  `rating` int NOT NULL COMMENT 'Đánh giá, max là 5',
  `max_guests` int NOT NULL COMMENT 'Số khách tối đa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách loại phòng';

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`room_type_id`, `room_code`, `name`, `description`, `total_room`, `base_price`, `room_area`, `view`, `rating`, `max_guests`) VALUES
(1, 'deluxe', 'Phòng Loại Sang (Deluxe Room)', 'Chưa có mô tả', 90, 0.00, 32, 'ABC', 0, 0),
(2, 'premium_corner', 'Phòng cao cấp trong góc (Premium Corner Room)', 'Chưa có mô tả', 96, 0.00, 64, 'BCD', 0, 0),
(3, 'suite', 'Phòng Suite (Suite Room)', 'Chưa có mô tả', 20, 0.00, 45, 'TDG', 0, 0),
(4, 'the_level_premium', 'Phòng The Level Cao cấp (The Level Premium Room)', 'Chưa luônnn', 30, 0.00, 92, 'BBJ', 0, 0),
(5, 'the_level_suite', 'The Level Suite (Phòng gì đó)', 'Không', 20, 0.00, 48, 'FFS', 0, 0),
(6, 'the_level_premium_corner', 'The Level Premium Corner (Phòng cao cấp trong góc)', 'Không', 32, 0.00, 20, 'WD', 0, 0),
(7, 'presidential_suite', 'Presidential Suite (Phòng Tổng thống)', 'Không', 1, 0.00, 10, 'OKO', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_type_amenity`
--

CREATE TABLE `room_type_amenity` (
  `room_type_id` int NOT NULL,
  `amenity_id` int NOT NULL,
  `is_highlighted` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_type_amenity`
--

INSERT INTO `room_type_amenity` (`room_type_id`, `amenity_id`, `is_highlighted`, `created_at`, `updated_at`) VALUES
(1, 3, 0, '2025-06-25 23:51:40', '2025-06-25 23:51:40'),
(1, 17, 1, '2025-06-25 23:51:40', '2025-06-25 23:51:40'),
(1, 22, 1, '2025-06-25 23:51:40', '2025-06-25 23:51:40'),
(1, 36, 0, '2025-06-25 23:51:40', '2025-06-25 23:51:40'),
(1, 39, 1, '2025-06-25 23:51:40', '2025-06-25 23:51:40'),
(2, 2, 1, '2025-06-25 23:52:59', '2025-06-25 23:52:59'),
(2, 3, 0, '2025-06-25 23:52:59', '2025-06-25 23:52:59'),
(2, 7, 0, '2025-06-25 23:52:59', '2025-06-25 23:52:59'),
(2, 10, 0, '2025-06-25 23:52:59', '2025-06-25 23:52:59'),
(2, 15, 1, '2025-06-25 23:52:59', '2025-06-25 23:52:59'),
(2, 38, 1, '2025-06-25 23:52:59', '2025-06-25 23:52:59'),
(2, 39, 1, '2025-06-25 23:52:59', '2025-06-25 23:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `room_type_image`
--

CREATE TABLE `room_type_image` (
  `image_id` int NOT NULL COMMENT 'Khóa chính, mã ảnh',
  `room_type_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã phòng',
  `alt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Đường dẫn ảnh',
  `is_main` tinyint(1) DEFAULT '0' COMMENT 'Ảnh chính',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách ảnh của phòng';

--
-- Dumping data for table `room_type_image`
--

INSERT INTO `room_type_image` (`image_id`, `room_type_id`, `alt_text`, `image_path`, `is_main`, `created_at`, `updated_at`) VALUES
(1, 1, 'Hahad', 'https://kconceptvn.com/wp-content/uploads/2020/04/hotel-photography-chup-anh-khach-san-khach-san-bamboo-sapa-hotel-18-1024x683.jpg', 1, '2025-06-08 10:21:06', '2025-06-09 07:20:50'),
(2, 1, NULL, 'https://studiochupanhdep.com/Upload/Newsimages/phong-khach-san-tt-studio.jpg', 0, '2025-06-08 10:21:06', '2025-06-09 07:20:50'),
(4, 1, 'hhah', 'https://asiky.com/files/images/Article/tin-tuc/chup-anh-khach-san.jpg', 0, '2025-06-08 10:21:06', '2025-06-09 07:20:50'),
(5, 1, NULL, 'https://kksapahotel.com/uploads/images/VQK_2153%20(1).jpg', 0, '2025-06-08 10:21:06', '2025-06-09 07:20:50'),
(15, 2, 'Phòng cao cấp trong góc (Premium Corner Room) - Ảnh 1', '/storage/room-types/2/1749478606_0_6846ecce37c50.jpg', 1, '2025-06-09 07:16:46', '2025-06-09 08:02:03'),
(20, 2, 'Phòng cao cấp trong góc (Premium Corner Room) - Ảnh 1', '/storage/room-types/2/1749479414_0_6846eff63b9d2.jpg', 0, '2025-06-09 07:30:14', '2025-06-09 08:02:03'),
(22, 1, 'Phòng Loại Sang (Deluxe Room) - Ảnh 1', '/storage/room-types/1/1750863283_0_685c0db332611.png', 0, '2025-06-25 07:54:43', '2025-06-25 07:54:43'),
(23, 3, 'Phòng Suite (Suite Room) - Ảnh 1', '/storage/room-types/3/1750921018_0_685cef3ad7560.jpg', 1, '2025-06-25 23:56:58', '2025-06-25 23:56:58');

-- --------------------------------------------------------

--
-- Table structure for table `room_type_package`
--

CREATE TABLE `room_type_package` (
  `package_id` int NOT NULL,
  `room_type_id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `price_modifier_vnd` decimal(15,2) DEFAULT '0.00',
  `include_all_services` tinyint(1) DEFAULT '0',
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_type_service`
--

CREATE TABLE `room_type_service` (
  `id` int NOT NULL,
  `room_type_id` int NOT NULL,
  `service_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_type_service`
--

INSERT INTO `room_type_service` (`id`, `room_type_id`, `service_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-06-26 02:47:15', '2025-06-26 02:47:15');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price_vnd` decimal(15,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Ví dụ: lần, ngày, giờ, kg',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `name`, `description`, `price_vnd`, `unit`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Ăn uống tại phòng', 'Khách có thể đặt các món ăn, đồ uống từ thực đơn của nhà hàng khách sạn và được nhân viên mang đến tận phòng.', 200000.00, '1', 1, '2025-06-25 16:25:18', '2025-06-26 02:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('LvtAVtAbXlOLD6Yd6vL2ebJIntCExL83TrEGnsoy', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVGJobXBJMTZiTHdldk50ZWc4ZzNjNGF0UEJoY3ZFcnpDS2lzVW16bCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjU2OiJodHRwOi8vMTI3LjAuMC4xOjg4ODgvYWRtaW4vZmxleGlibGUtcHJpY2luZy9kYXRhP3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1750932387),
('Rve3cSZsdhSzgXXmkDNQXB7N2mVFZhOPkkrWX75p', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoienNacTJHdmt3MHZrOHVmdk54VHk4VVZhTmlmVEExSFRTOEt3MVlWdSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ1OiJodHRwOi8vMTI3LjAuMC4xOjg4ODgvYWRtaW4vc2VydmljZXMvc2VydmljZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1750921073);

-- --------------------------------------------------------

--
-- Table structure for table `table_translation`
--

CREATE TABLE `table_translation` (
  `id` bigint UNSIGNED NOT NULL,
  `table_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `table_translation`
--

INSERT INTO `table_translation` (`id`, `table_name`, `display_name`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'amenities', 'Tiện ích', 0, NULL, NULL),
(3, 'currency', 'Tiền tệ', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `translation`
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `role` enum('guest','receptionist','manager','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Vai trò',
  `two_factor_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `address`, `role`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'NGUYỄN ANH ĐỨC', 'nguyenanhduc2909@gmail.com', NULL, '$2y$12$c2dNZ4nJgjNNQzaupkPYP.qIR6Ax7vkA65tXqK/n/uStI/bAr5haa', '0822153447', 'Thanh Hóa', 'admin', NULL, NULL, NULL, NULL, NULL, 'profile-photos/DjTR2i1uqXfUMCJURsg6kxS1HZfuCgqHyy5qERDx.jpg', '2025-05-21 01:07:42', '2025-06-11 07:11:24'),
(2, 'Nguyễn Anh Đức', 'nguyenandhduc2909@gmail.com', NULL, '$2y$12$ofny2jH99JRC2egJJaVzLOyRIuw2.5aL93twDg6Zw4hOq0KKWdxAu', '08221534422', 'Thanh Hóa', 'guest', NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-10 09:47:13', '2025-06-10 09:47:13');

-- --------------------------------------------------------

--
-- Table structure for table `weekend_days`
--

CREATE TABLE `weekend_days` (
  `id` int NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weekend_days`
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
-- Indexes for dumped tables
--

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`amenity_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bed_types`
--
ALTER TABLE `bed_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `idx_check_in_out` (`check_in_date`,`check_out_date`),
  ADD KEY `fk_booking_room` (`room_id`);

--
-- Indexes for table `booking_extensions`
--
ALTER TABLE `booking_extensions`
  ADD PRIMARY KEY (`extension_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `booking_reschedules`
--
ALTER TABLE `booking_reschedules`
  ADD PRIMARY KEY (`reschedule_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `cancellation_policies`
--
ALTER TABLE `cancellation_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `check_out_policies`
--
ALTER TABLE `check_out_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `check_out_requests`
--
ALTER TABLE `check_out_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_code`);

--
-- Indexes for table `datafeeds`
--
ALTER TABLE `datafeeds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit_policies`
--
ALTER TABLE `deposit_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `dynamic_pricing_rules`
--
ALTER TABLE `dynamic_pricing_rules`
  ADD PRIMARY KEY (`rule_id`),
  ADD KEY `room_type_id` (`room_type_id`),
  ADD KEY `idx_dynamic_priority` (`priority`,`is_exclusive`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `flexible_pricing_rules`
--
ALTER TABLE `flexible_pricing_rules`
  ADD PRIMARY KEY (`rule_id`),
  ADD KEY `flexible_pricing_room_type_id_index` (`room_type_id`),
  ADD KEY `flexible_pricing_event_id_index` (`event_id`),
  ADD KEY `flexible_pricing_holiday_id_index` (`holiday_id`),
  ADD KEY `idx_priority` (`priority`,`is_exclusive`);

--
-- Indexes for table `floors`
--
ALTER TABLE `floors`
  ADD PRIMARY KEY (`floor_id`),
  ADD UNIQUE KEY `floor_number` (`floor_number`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guest_id`),
  ADD KEY `fk_guests_user` (`user_id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`holiday_id`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`hotel_id`),
  ADD KEY `idx_hotel_id` (`hotel_id`);

--
-- Indexes for table `hotel_rating`
--
ALTER TABLE `hotel_rating`
  ADD PRIMARY KEY (`hotel_id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`language_code`);

--
-- Indexes for table `meal_types`
--
ALTER TABLE `meal_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `idx_booking_status` (`booking_id`,`status`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pricing_config`
--
ALTER TABLE `pricing_config`
  ADD PRIMARY KEY (`config_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `idx_room_type_id` (`room_type_id`),
  ADD KEY `bed_type_fixed` (`bed_type_fixed`),
  ADD KEY `floor_id` (`floor_id`);

--
-- Indexes for table `room_availability`
--
ALTER TABLE `room_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `idx_option_date` (`option_id`,`date`);

--
-- Indexes for table `room_bed_types`
--
ALTER TABLE `room_bed_types`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_bed_unique` (`bed_type_id`),
  ADD KEY `idx_bed_type_id` (`bed_type_id`);

--
-- Indexes for table `room_meal_types`
--
ALTER TABLE `room_meal_types`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_meal_unique` (`meal_type_id`),
  ADD KEY `idx_meal_type_id` (`meal_type_id`);

--
-- Indexes for table `room_occupancy`
--
ALTER TABLE `room_occupancy`
  ADD PRIMARY KEY (`occupancy_id`),
  ADD UNIQUE KEY `idx_room_type_date` (`room_type_id`,`date`);

--
-- Indexes for table `room_option`
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
-- Indexes for table `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  ADD PRIMARY KEY (`promotion_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `room_price_history`
--
ALTER TABLE `room_price_history`
  ADD PRIMARY KEY (`price_history_id`),
  ADD UNIQUE KEY `idx_room_type_date` (`room_type_id`,`date`);

--
-- Indexes for table `room_transfers`
--
ALTER TABLE `room_transfers`
  ADD PRIMARY KEY (`transfer_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `old_room_id` (`old_room_id`),
  ADD KEY `new_room_id` (`new_room_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`room_type_id`);

--
-- Indexes for table `room_type_amenity`
--
ALTER TABLE `room_type_amenity`
  ADD PRIMARY KEY (`room_type_id`,`amenity_id`),
  ADD KEY `amenity_id` (`amenity_id`);

--
-- Indexes for table `room_type_image`
--
ALTER TABLE `room_type_image`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `room_image_ibfk_1` (`room_type_id`);

--
-- Indexes for table `room_type_package`
--
ALTER TABLE `room_type_package`
  ADD PRIMARY KEY (`package_id`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Indexes for table `room_type_service`
--
ALTER TABLE `room_type_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_type_id` (`room_type_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `table_translation`
--
ALTER TABLE `table_translation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `table_translation_table_name_unique` (`table_name`);

--
-- Indexes for table `translation`
--
ALTER TABLE `translation`
  ADD PRIMARY KEY (`translation_id`),
  ADD KEY `idx_translation` (`table_name`,`column_name`,`record_id`,`language_code`),
  ADD KEY `language_code` (`language_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `weekend_days`
--
ALTER TABLE `weekend_days`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `amenity_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bed_types`
--
ALTER TABLE `bed_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã đặt phòng', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking_extensions`
--
ALTER TABLE `booking_extensions`
  MODIFY `extension_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_reschedules`
--
ALTER TABLE `booking_reschedules`
  MODIFY `reschedule_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cancellation_policies`
--
ALTER TABLE `cancellation_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `check_out_policies`
--
ALTER TABLE `check_out_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `check_out_requests`
--
ALTER TABLE `check_out_requests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `datafeeds`
--
ALTER TABLE `datafeeds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit_policies`
--
ALTER TABLE `deposit_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `dynamic_pricing_rules`
--
ALTER TABLE `dynamic_pricing_rules`
  MODIFY `rule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `faq_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `flexible_pricing_rules`
--
ALTER TABLE `flexible_pricing_rules`
  MODIFY `rule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `floors`
--
ALTER TABLE `floors`
  MODIFY `floor_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã tầng', AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `holiday_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `hotel_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã khách sạn', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `meal_types`
--
ALTER TABLE `meal_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã thanh toán';

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pricing_config`
--
ALTER TABLE `pricing_config`
  MODIFY `config_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã phòng', AUTO_INCREMENT=296;

--
-- AUTO_INCREMENT for table `room_availability`
--
ALTER TABLE `room_availability`
  MODIFY `availability_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã lịch', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `room_occupancy`
--
ALTER TABLE `room_occupancy`
  MODIFY `occupancy_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  MODIFY `promotion_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã khuyến mãi';

--
-- AUTO_INCREMENT for table `room_price_history`
--
ALTER TABLE `room_price_history`
  MODIFY `price_history_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_transfers`
--
ALTER TABLE `room_transfers`
  MODIFY `transfer_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `room_type_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã loại phòng', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `room_type_image`
--
ALTER TABLE `room_type_image`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã ảnh', AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `room_type_package`
--
ALTER TABLE `room_type_package`
  MODIFY `package_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_type_service`
--
ALTER TABLE `room_type_service`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `table_translation`
--
ALTER TABLE `table_translation`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `translation`
--
ALTER TABLE `translation`
  MODIFY `translation_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã bản dịch', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `weekend_days`
--
ALTER TABLE `weekend_days`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_booking_room` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE SET NULL;

--
-- Constraints for table `booking_extensions`
--
ALTER TABLE `booking_extensions`
  ADD CONSTRAINT `booking_extensions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_reschedules`
--
ALTER TABLE `booking_reschedules`
  ADD CONSTRAINT `booking_reschedules_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `check_out_requests`
--
ALTER TABLE `check_out_requests`
  ADD CONSTRAINT `check_out_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `dynamic_pricing_rules`
--
ALTER TABLE `dynamic_pricing_rules`
  ADD CONSTRAINT `dynamic_pricing_rules_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL;

--
-- Constraints for table `flexible_pricing_rules`
--
ALTER TABLE `flexible_pricing_rules`
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_3` FOREIGN KEY (`holiday_id`) REFERENCES `holidays` (`holiday_id`) ON DELETE SET NULL;

--
-- Constraints for table `guests`
--
ALTER TABLE `guests`
  ADD CONSTRAINT `fk_guests_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_rating`
--
ALTER TABLE `hotel_rating`
  ADD CONSTRAINT `hotel_rating_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`hotel_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_ibfk_3` FOREIGN KEY (`bed_type_fixed`) REFERENCES `bed_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_ibfk_4` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`floor_number`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `room_availability`
--
ALTER TABLE `room_availability`
  ADD CONSTRAINT `room_availability_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE CASCADE;

--
-- Constraints for table `room_bed_types`
--
ALTER TABLE `room_bed_types`
  ADD CONSTRAINT `fk_room_bed_type` FOREIGN KEY (`bed_type_id`) REFERENCES `bed_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_bed_types_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `room_meal_types`
--
ALTER TABLE `room_meal_types`
  ADD CONSTRAINT `fk_room_meal_type` FOREIGN KEY (`meal_type_id`) REFERENCES `meal_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_meal_types_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `room_occupancy`
--
ALTER TABLE `room_occupancy`
  ADD CONSTRAINT `room_occupancy_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE;

--
-- Constraints for table `room_option`
--
ALTER TABLE `room_option`
  ADD CONSTRAINT `fk_room_option_cancellation_policy` FOREIGN KEY (`cancellation_policy_id`) REFERENCES `cancellation_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_room_option_deposit_policy` FOREIGN KEY (`deposit_policy_id`) REFERENCES `deposit_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_option_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_option_ibfk_2` FOREIGN KEY (`bed_type`) REFERENCES `bed_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_option_ibfk_3` FOREIGN KEY (`meal_type`) REFERENCES `meal_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_option_ibfk_4` FOREIGN KEY (`package_id`) REFERENCES `room_type_package` (`package_id`) ON DELETE SET NULL;

--
-- Constraints for table `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  ADD CONSTRAINT `room_option_promotion_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE CASCADE;

--
-- Constraints for table `room_price_history`
--
ALTER TABLE `room_price_history`
  ADD CONSTRAINT `room_price_history_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE;

--
-- Constraints for table `room_transfers`
--
ALTER TABLE `room_transfers`
  ADD CONSTRAINT `room_transfers_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_transfers_ibfk_2` FOREIGN KEY (`old_room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `room_transfers_ibfk_3` FOREIGN KEY (`new_room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT;

--
-- Constraints for table `room_type_amenity`
--
ALTER TABLE `room_type_amenity`
  ADD CONSTRAINT `room_type_amenity_ibfk_1` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`amenity_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_type_amenity_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `room_type_image`
--
ALTER TABLE `room_type_image`
  ADD CONSTRAINT `room_type_image_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `room_type_package`
--
ALTER TABLE `room_type_package`
  ADD CONSTRAINT `room_type_package_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE;

--
-- Constraints for table `room_type_service`
--
ALTER TABLE `room_type_service`
  ADD CONSTRAINT `room_type_service_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_type_service_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE;

--
-- Constraints for table `translation`
--
ALTER TABLE `translation`
  ADD CONSTRAINT `translation_ibfk_1` FOREIGN KEY (`language_code`) REFERENCES `language` (`language_code`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
