-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 19, 2025 at 10:43 AM
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
-- Database: `datn_build_basic2`
--

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
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
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`amenity_id`, `name`, `icon`, `icon_lib`, `category`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Điều hòa không khí tân tiến', 'Snowflake', 'lucide', 'basic', 'Hệ thống điều hòa nhiệt độ hiện đại', 1, '2025-06-25 21:06:10', '2025-08-15 04:13:49'),
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
  `audit_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Người thực hiện hành động',
  `session_id` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Session ID để track theo phiên',
  `action` enum('create','update','delete','restore','login','logout','bulk_update','bulk_delete','other') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại hành động',
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên model/bảng tác động',
  `model_id` bigint UNSIGNED NOT NULL COMMENT 'ID bản ghi tác động',
  `old_values` json DEFAULT NULL COMMENT 'Dữ liệu trước khi thay đổi',
  `new_values` json DEFAULT NULL COMMENT 'Dữ liệu sau khi thay đổi',
  `changes_summary` text COLLATE utf8mb4_unicode_ci COMMENT 'Tóm tắt thay đổi (human readable)',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả hành động hoặc lý do',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP address',
  `user_agent` text COLLATE utf8mb4_unicode_ci COMMENT 'User agent string',
  `url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL được truy cập',
  `method` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'HTTP method',
  `metadata` json DEFAULT NULL COMMENT 'Thông tin bổ sung (tags, categories, etc.)',
  `is_sensitive` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Có chứa dữ liệu nhạy cảm không',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu trữ lịch sử thay đổi dữ liệu hệ thống';

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`audit_id`, `user_id`, `session_id`, `action`, `model`, `model_id`, `old_values`, `new_values`, `changes_summary`, `description`, `ip_address`, `user_agent`, `url`, `method`, `metadata`, `is_sensitive`, `created_at`) VALUES
(1, 1, 'XqR8mMQecfzQKvjFUZh2fPsufG5fbA5M5dbxBVtU', 'update', 'CancellationPolicy', 2, '{\"name\": \"Hủy có phí\", \"priority\": 0, \"is_active\": true, \"policy_id\": 2, \"conditions\": null, \"description\": \"Phạt 50% nếu hủy trong vòng 2 ngày\", \"penalty_days\": 3, \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"max_booking_amount\": null, \"min_booking_amount\": null, \"penalty_percentage\": \"50.00\", \"free_cancellation_days\": null, \"penalty_fixed_amount_vnd\": null}', '{\"penalty_percentage\": \"30.00\"}', NULL, 'NGUYỄN ANH ĐỨC đã cập nhật CancellationPolicy #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/cancellation-policies/update/2', 'PUT', '{\"route_name\": \"admin.cancellation-policies.update\", \"model_class\": \"App\\\\Models\\\\CancellationPolicy\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\CancellationPolicyController@update\", \"action_timestamp\": \"2025-08-15T03:03:56.425091Z\"}', 0, '2025-08-15 03:03:56'),
(2, 1, 'XqR8mMQecfzQKvjFUZh2fPsufG5fbA5M5dbxBVtU', 'delete', 'User', 8, '{\"id\": 8, \"name\": \"Phương nguyễn\", \"role\": null, \"email\": \"maiiphuong1981@gmail.com\", \"phone\": null, \"avatar\": \"https://lh3.googleusercontent.com/a/ACg8ocIGGrOzbzC7mG4bgj1Wz_l4crSDbQ3SWRkvHbbEus5j4BYCO-PR=s96-c\", \"address\": null, \"password\": \"eyJpdiI6IkppR2JXZVErMmc3R3MrR21yN3RiNHc9PSIsInZhbHVlIjoiODhUZVM0QTNtbmxvM3JodjgvZVpMYWdZbTJHbzNRNEZ2Y3F6OStqVkZYQTJlQThVUHYxM1BEb1JPbHlxVDZqN3pkbWxPT3ZOQ1RaSStqekpzeEk3bmc9PSIsIm1hYyI6IjUzZmIzZDQ1MWY3ZTNmNjhmMzUxOGMzZDE3MTcyYTMyZDhiZTNjYzExNzEwNmUyNzIxMDE3MjE5YjdlYzY5MTkiLCJ0YWciOiIifQ==\", \"google_id\": \"107379410125025514536\", \"created_at\": \"2025-07-20T21:36:42.000000Z\", \"updated_at\": \"2025-07-20T21:36:42.000000Z\", \"identity_code\": null, \"remember_token\": \"eyJpdiI6Im96Y2ZWOVhyU3ZlRzBSRlhsZktqQ2c9PSIsInZhbHVlIjoiTXZtYWdlVnVoSGg2dVNkRFdVamdsdz09IiwibWFjIjoiNjRlMjk0ZGI4MjIxYjI5NzI3NTJjN2M2YTUyMDcyZjY0Y2JhMzYyNjI5ZWEzZDZmNmM4ODYzZGFhMjc1N2E2OSIsInRhZyI6IiJ9\", \"current_team_id\": null, \"email_verified_at\": null, \"two_factor_secret\": \"eyJpdiI6IlBJRWZGcFl0Yzc3VFhPeW5uMUEwWHc9PSIsInZhbHVlIjoiaVE0S2VUNzBYWGtxZVUxMVNqNis1Zz09IiwibWFjIjoiYTU0OTdkM2Y1ZjljZWE4NTVjYzZhZGRhY2E1NTJmOGIyZjNkNGVjZTUyZmE0OTA3ZDNlZTZjMzlmOTE5ZmUzNCIsInRhZyI6IiJ9\", \"profile_photo_path\": null, \"two_factor_confirmed_at\": null, \"two_factor_recovery_codes\": \"eyJpdiI6Ik5jb0pLMmdUejN2OXozalJQY0ovQ3c9PSIsInZhbHVlIjoiQytNN0hzUm9LTUhtMGtqaHUzcGxtUT09IiwibWFjIjoiMzI4NDMzNGM0Y2Y1NjMwMWZmM2I5OGZhZDMxZGMwYWFiMmNlZjYyMGMxM2Q3ZmFhYjc4ODk5NGY2ZDFhYjJjYiIsInRhZyI6IiJ9\"}', 'null', NULL, 'NGUYỄN ANH ĐỨC đã xóa User #8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/staffs/destroy/8', 'DELETE', '{\"route_name\": \"admin.users.staffs.destroy\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Admin\\\\StaffController@destroy\", \"action_timestamp\": \"2025-08-15T03:24:49.397110Z\"}', 1, '2025-08-15 03:24:49'),
(3, 1, 'XqR8mMQecfzQKvjFUZh2fPsufG5fbA5M5dbxBVtU', 'delete', 'User', 8, '{\"id\": 8, \"name\": \"Phương nguyễn\", \"role\": null, \"email\": \"maiiphuong1981@gmail.com\", \"phone\": null, \"avatar\": \"https://lh3.googleusercontent.com/a/ACg8ocIGGrOzbzC7mG4bgj1Wz_l4crSDbQ3SWRkvHbbEus5j4BYCO-PR=s96-c\", \"address\": null, \"password\": \"eyJpdiI6IlVORkFRc2ZKZTlJSkxhd29NNjlwL3c9PSIsInZhbHVlIjoicFA2QWtsZU9RekRVYVFrdWNTdHI5dHc3V1NrQWJSM3RYbEx1dGkrVDZJOUJieWZiOTJzSFpiMm9pRElFZURJMnplRW1IWVkrN3B1b0NrV3RuOU5Uc3c9PSIsIm1hYyI6IjI1MzcwNmNmNzk0YWQzOWVjZTk0NjY4OTEyNjY0ZDU4MDU5MmNhNTBjZTA4ZDIwOWJjZWQ2MWUwNzIzOTJjYmYiLCJ0YWciOiIifQ==\", \"google_id\": \"107379410125025514536\", \"created_at\": \"2025-07-20T21:36:42.000000Z\", \"updated_at\": \"2025-07-20T21:36:42.000000Z\", \"identity_code\": null, \"remember_token\": \"eyJpdiI6ImN2L1pLV1ZjRWhteFdacEEvR08vcGc9PSIsInZhbHVlIjoiQ3hEV0hmZ3BHZnF3SkowbkRRQTJadz09IiwibWFjIjoiMWIxZmFmZDRhMjNmZTkyMDhiYTZmNmM1ODc0MzlmMzFmNTNlNjljYTc4NDUxNTdhMmIyOTIwMWVlMGMzMGQ1MSIsInRhZyI6IiJ9\", \"current_team_id\": null, \"email_verified_at\": null, \"two_factor_secret\": \"eyJpdiI6IlNLQW5oNUhhZDJCTWJNM3VsakNPU1E9PSIsInZhbHVlIjoiZTJYMFV4TFlIM1lMNU1Eemd0ZHBrQT09IiwibWFjIjoiMzhhOGI1NjkwZGYyNGVlODgzNWFiMmM0Y2I0ZDlhZDliZDQzZmYwOGMyMmE5MTFiNWQ4M2FhN2ZlOWM4ZjgwNCIsInRhZyI6IiJ9\", \"profile_photo_path\": null, \"two_factor_confirmed_at\": null, \"two_factor_recovery_codes\": \"eyJpdiI6Inp2cHBZWGFYcE5wN0Ird0FlTXpVSGc9PSIsInZhbHVlIjoiNlg0TW1aOGtxeTJucVp0djJXaUd2Zz09IiwibWFjIjoiNWMxMWVkNDE0ZTVlMDc2NWVkOTIyZGJlYTkxYTFiYWVmMzQ2ODAwZTQ2MjIzYzNkMTJjMDE0ZWFiYThiY2JiNCIsInRhZyI6IiJ9\"}', 'null', NULL, 'NGUYỄN ANH ĐỨC đã xóa User #8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/staffs/destroy/8', 'DELETE', '{\"route_name\": \"admin.users.staffs.destroy\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Admin\\\\StaffController@destroy\", \"action_timestamp\": \"2025-08-15T03:24:49.417803Z\"}', 1, '2025-08-15 03:24:49'),
(4, 1, 'XqR8mMQecfzQKvjFUZh2fPsufG5fbA5M5dbxBVtU', 'create', 'User', 9, 'null', '{\"id\": 9, \"name\": \"Phương nguyễn\", \"email\": \"maiiphuong1981@gmail.com\", \"phone\": null, \"avatar\": \"https://lh3.googleusercontent.com/a/ACg8ocIGGrOzbzC7mG4bgj1Wz_l4crSDbQ3SWRkvHbbEus5j4BYCO-PR=s96-c\", \"address\": null, \"password\": \"eyJpdiI6IjRRblk0ZU44c1ljUXlIR3RUVnJXbUE9PSIsInZhbHVlIjoiTFdySUVGd1BrUXIyRE96MVA5eENDT2JCRzg2Y1oxRmxibTZ4YzJLZzYvQklvSWl4bXcrSWNBNVpHVnVpQVZTUVpZRUtZcE5xeTk1VmliM0VGSEduZUE9PSIsIm1hYyI6ImQ2ZGJkNGM5N2Q3Y2VjOTk4YzJmN2QxZTY0N2JiMTM0MDQxMzNhZDFiYWMyYzNiMGQ2ZjI3MzkwYmM5MGZjZmEiLCJ0YWciOiIifQ==\", \"google_id\": \"107379410125025514536\", \"created_at\": \"2025-08-15 10:25:38\", \"updated_at\": \"2025-08-15 10:25:38\", \"identity_code\": null, \"current_team_id\": null, \"profile_photo_path\": null}', NULL, 'NGUYỄN ANH ĐỨC đã tạo mới User #9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/audit/2/restore', 'POST', '{\"route_name\": \"admin.audit.restore\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\AuditController@restore\", \"action_timestamp\": \"2025-08-15T03:25:38.774313Z\"}', 1, '2025-08-15 03:25:38'),
(5, 1, 'XqR8mMQecfzQKvjFUZh2fPsufG5fbA5M5dbxBVtU', 'create', 'User', 9, 'null', '{\"id\": 9, \"name\": \"Phương nguyễn\", \"email\": \"maiiphuong1981@gmail.com\", \"phone\": null, \"avatar\": \"https://lh3.googleusercontent.com/a/ACg8ocIGGrOzbzC7mG4bgj1Wz_l4crSDbQ3SWRkvHbbEus5j4BYCO-PR=s96-c\", \"address\": null, \"password\": \"eyJpdiI6IjJqMWRkbjh3T0NBMVk4ajhCS1R2V2c9PSIsInZhbHVlIjoiZFJwN29IV3R4a25Od3d4UDVEY0xNeDFNWUhSL29ZSG1KRm5FanlkLzlrWjVCVTZhZ29XemhWak5pS2crNXhKQnBzUzRoVWpRNmtQNEpNeGNWZzBZY3c9PSIsIm1hYyI6ImM0Mzk2YmIxZDJjNGRiMTk2OTQwNjViOTA0MWUwMzUyZjE4NGU0ZmIzMTViYmU4Mzc3MjQzOTJjNmVjNDEzYWYiLCJ0YWciOiIifQ==\", \"google_id\": \"107379410125025514536\", \"created_at\": \"2025-08-15 10:25:38\", \"updated_at\": \"2025-08-15 10:25:38\", \"identity_code\": null, \"current_team_id\": null, \"profile_photo_path\": null}', NULL, 'NGUYỄN ANH ĐỨC đã tạo mới User #9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/audit/2/restore', 'POST', '{\"route_name\": \"admin.audit.restore\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\AuditController@restore\", \"action_timestamp\": \"2025-08-15T03:25:38.803578Z\"}', 1, '2025-08-15 03:25:38'),
(6, 1, 'XqR8mMQecfzQKvjFUZh2fPsufG5fbA5M5dbxBVtU', 'restore', 'User', 9, 'null', '{\"id\": 8, \"name\": \"Phương nguyễn\", \"role\": null, \"email\": \"maiiphuong1981@gmail.com\", \"phone\": null, \"avatar\": \"https://lh3.googleusercontent.com/a/ACg8ocIGGrOzbzC7mG4bgj1Wz_l4crSDbQ3SWRkvHbbEus5j4BYCO-PR=s96-c\", \"address\": null, \"password\": \"eyJpdiI6InlVL3ZQMXRjV0VvbTlITDRKN3V2ZlE9PSIsInZhbHVlIjoiR2hGcjdNMlh5V2xoWkU1a3BtdjRqN1NEVU5vbjJaZ0tsSDFaUHl3RHRnWUJlK1pJNnliRVBXUERzcVEwOGVaWDh2dWlVZUNoMUUyalY3TS9ZQ1pnVEE9PSIsIm1hYyI6IjM4NmEyMjk0YzJjZDFlYTBmODI3OTE5NmQ1MzAxZTQzOWQ2ZWUyZjM0ZTM0NDU2NmJkNjRiOTA4ZGZlMjU2ZWUiLCJ0YWciOiIifQ==\", \"google_id\": \"107379410125025514536\", \"created_at\": \"2025-07-20T21:36:42.000000Z\", \"updated_at\": \"2025-07-20T21:36:42.000000Z\", \"identity_code\": null, \"remember_token\": \"eyJpdiI6ImdrVmFHYTBKZW5MTEpvbDRNaGIwZFE9PSIsInZhbHVlIjoiekNOdjFPREtXbGU3ZnNXY0l3V1c5QT09IiwibWFjIjoiNGIzYmEzOGVjMzI3MzFjNmM2MDVlMGUwYjc1NDgyMjQ5M2M3NWVmNGQ2N2YzNDhkNzkxZWY1ZjhiZDE1NWZiYSIsInRhZyI6IiJ9\", \"current_team_id\": null, \"email_verified_at\": null, \"two_factor_secret\": \"eyJpdiI6Im5KQ1lEeTZSZ2J4am5jMWFubXBScWc9PSIsInZhbHVlIjoiTHB5REpWajVtOCs0Q1AzeVZRakdXdz09IiwibWFjIjoiYTJlYjkwNzBjM2MxZjQ3MTNkNTQyNWRlMGVmMjE0NTVmMjRiY2FjMTljNTQ4NDg5MTEzM2UxZWIxZmFhODk3MSIsInRhZyI6IiJ9\", \"profile_photo_path\": null, \"two_factor_confirmed_at\": null, \"two_factor_recovery_codes\": \"eyJpdiI6IjlPM29YeEt5ODhkaXpxUnJSUmJLOHc9PSIsInZhbHVlIjoiM0JtTkRTdk5WUWIvaE1HTXJxMDYrQT09IiwibWFjIjoiMjBhYWQzMDI0YmIxYmZhOTk3Njg1YTc0MzA0ZGU5NzAyNDRhYTA0Y2I4ZjRhNjNkMDU0NWVhMzE3N2U1YmM3YSIsInRhZyI6IiJ9\"}', NULL, 'Khôi phục bản ghi từ audit log #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/audit/2/restore', 'POST', '{\"original_deleted_at\": \"2025-08-15T03:24:49.000000Z\", \"restored_from_audit_id\": 2}', 1, '2025-08-15 03:25:38'),
(7, 1, 'XqR8mMQecfzQKvjFUZh2fPsufG5fbA5M5dbxBVtU', 'update', 'CancellationPolicy', 11, '{\"name\": \"Hủy miễn phí 3 ngày - Lễ Tết\", \"priority\": 20, \"is_active\": true, \"policy_id\": 11, \"conditions\": null, \"description\": \"Áp dụng cho ngày lễ tết, hủy trước 3 ngày\", \"penalty_days\": null, \"applies_to_holiday\": 1, \"applies_to_weekend\": 0, \"max_booking_amount\": null, \"min_booking_amount\": null, \"penalty_percentage\": \"50.00\", \"free_cancellation_days\": 3, \"penalty_fixed_amount_vnd\": \"0.00\"}', '{\"free_cancellation_days\": \"2\"}', NULL, 'NGUYỄN ANH ĐỨC đã cập nhật CancellationPolicy #11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/cancellation-policies/update/11', 'PUT', '{\"route_name\": \"admin.cancellation-policies.update\", \"model_class\": \"App\\\\Models\\\\CancellationPolicy\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\CancellationPolicyController@update\", \"action_timestamp\": \"2025-08-15T03:28:49.600160Z\"}', 0, '2025-08-15 03:28:49'),
(8, 1, 'XqR8mMQecfzQKvjFUZh2fPsufG5fbA5M5dbxBVtU', 'update', 'Amenity', 1, '{\"icon\": \"Snowflake\", \"name\": \"Điều hòa không khí\", \"category\": \"basic\", \"icon_lib\": \"lucide\", \"is_active\": true, \"amenity_id\": 1, \"description\": \"Hệ thống điều hòa nhiệt độ hiện đại\"}', '{\"name\": \"Điều hòa không khí tân tiến\"}', NULL, 'NGUYỄN ANH ĐỨC đã cập nhật Amenity #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/services/amenities/update/1', 'PUT', '{\"route_name\": \"admin.services.amenities.update\", \"model_class\": \"App\\\\Models\\\\Amenity\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\ServiceAmenityController@update\", \"action_timestamp\": \"2025-08-15T04:13:49.974875Z\"}', 0, '2025-08-15 04:13:49'),
(9, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'update', 'RoomType', 1, '{\"name\": \"Deluxe Room\", \"view\": \"ABC\", \"rating\": 0, \"is_active\": 1, \"room_area\": 32, \"room_code\": \"deluxe\", \"base_price\": \"5000\", \"max_guests\": 2, \"total_room\": 90, \"description\": \"Phòng giường đôi rộng rãi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng cùng bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, tủ để quần áo cũng như tầm nhìn ra thành phố.\", \"room_type_id\": 1}', '{\"base_price\": \"50000\"}', NULL, 'NGUYỄN ANH ĐỨC đã cập nhật RoomType #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/room-types/update/1', 'PUT', '{\"route_name\": \"admin.room-types.update\", \"model_class\": \"App\\\\Models\\\\RoomType\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\RoomTypeController@update\", \"action_timestamp\": \"2025-08-15T07:00:26.224259Z\"}', 0, '2025-08-15 07:00:26'),
(10, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'update', 'User', 1, '{\"id\": 1, \"name\": \"NGUYỄN ANH ĐỨC\", \"role\": \"admin\", \"email\": \"nguyenanhduc2909@gmail.com\", \"phone\": \"0822153447\", \"avatar\": null, \"address\": \"Thanh Hóa\", \"password\": \"eyJpdiI6InIvd0RBTTlPRXhRMWdQdjh1M054Z0E9PSIsInZhbHVlIjoiQmVEcUFOcXE1enBqOHNiSjBlbENPZ0t6bDA4RVdUQ2IxY2s4cHJLNU1SUk1DZVJVRkQzNmZHVHN0dHdwQVFwc1p3clRJN2E3UUQ0OHZRWjdJNmNqbkE9PSIsIm1hYyI6IjUwYzVmODJjMjU3Y2VhMTE4Nzc1YjBiYjk1OTExZTU3ZDRkNGJjMmQzNzU1Yjk1OTI0NDE5NmI2OWIwYzdjMDAiLCJ0YWciOiIifQ==\", \"google_id\": null, \"identity_code\": \"038205000950\", \"current_team_id\": null, \"two_factor_secret\": \"eyJpdiI6IlgwSnFkbXlCTlRELzQwWEhFcElKMkE9PSIsInZhbHVlIjoidXM2ak9Fc1h6WXErbXRMOWc1dUNqZz09IiwibWFjIjoiMjVhNTdlZTMyMjQ5MTZkYmRlNWFhMWQ2ZWJkMDM4OGI5ODMxZTIxNTAzMTAzYzA2YzI4Zjg3ZTAxMDE0MDMyMyIsInRhZyI6IiJ9\", \"profile_photo_path\": \"profile-photos/mfqMmmx1jtzkRy9YdNHQRl7xjSLZwxGgqDHJd4JS.png\", \"two_factor_confirmed_at\": null, \"two_factor_recovery_codes\": \"eyJpdiI6IlVjMDhLYjBENzNpQlBBNy9lNUJnbkE9PSIsInZhbHVlIjoidGRvSTh3OXRXcDBHZWdwa2NHREpCQT09IiwibWFjIjoiY2E4OWYxODI4ZGRjYTgzOTg0ZjExOWJkZjk0MjNlN2UwYzgyZjc1NTJiYzBkNjFhMzIwNGNhMGVkY2MzNWUyNiIsInRhZyI6IiJ9\"}', '{\"name\": \"Nguyễn Anh Đứccc\"}', NULL, 'NGUYỄN ANH ĐỨC đã cập nhật User #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/staffs/update/1', 'PUT', '{\"route_name\": \"admin.users.staffs.update\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Admin\\\\StaffController@update\", \"action_timestamp\": \"2025-08-15T07:16:23.235854Z\"}', 1, '2025-08-15 07:16:23'),
(11, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'update', 'User', 1, '{\"id\": 1, \"name\": \"NGUYỄN ANH ĐỨC\", \"role\": \"admin\", \"email\": \"nguyenanhduc2909@gmail.com\", \"phone\": \"0822153447\", \"avatar\": null, \"address\": \"Thanh Hóa\", \"password\": \"eyJpdiI6IjJLd2xiL3ozVEExRVFOMkN5NDlCSnc9PSIsInZhbHVlIjoiYXNrcE00NGFINmVpUmxJNFJHQ20wb0pMdCtJb0JxNTZtaGJ5ZXdDSW5XRTlTdC9jdEF4RnNHNVJmd1hRS3pkQnNZUzg5ZTVKaUtHa1ZIZTNaVXZ1amc9PSIsIm1hYyI6ImY2MmQ1MDYxM2ZlZTAzYjgxMDMyNzdlMDlmOGQ5YTFiOGE4OTU0N2Q5MjQ0OTYwMGMyYzk0OGYxZjdkMzYzNGMiLCJ0YWciOiIifQ==\", \"google_id\": null, \"identity_code\": \"038205000950\", \"current_team_id\": null, \"two_factor_secret\": \"eyJpdiI6Ii9MaU9Ia3F3c1ZpZm9BWklMS0NXd0E9PSIsInZhbHVlIjoiRVBYbGV4bjdoWFdRWEhGUjhDU0Nidz09IiwibWFjIjoiNjEwYWIwZjZmOWZhY2VhZDUyMzdhNjZiNTYzOWNhMGJiYzQ0OThiNGRkNzg0NmQ5MTkyNmNmMGFlZDRiYjhhNSIsInRhZyI6IiJ9\", \"profile_photo_path\": \"profile-photos/mfqMmmx1jtzkRy9YdNHQRl7xjSLZwxGgqDHJd4JS.png\", \"two_factor_confirmed_at\": null, \"two_factor_recovery_codes\": \"eyJpdiI6Ik5WNWdLSHR2eXpCZWd2engzOThqbUE9PSIsInZhbHVlIjoicjBPcjBqeHlHbkd6L0ZVaVpGWis3UT09IiwibWFjIjoiOWM0YTVmMjI1YzhiMTcyOWZkYWUwNjY0NWVkZGYzY2NmZTI0Y2U2NDdiMWVlZGFmOTI2MGIzMzcwMWE4YTgxMiIsInRhZyI6IiJ9\"}', '{\"name\": \"Nguyễn Anh Đứccc\"}', NULL, 'NGUYỄN ANH ĐỨC đã cập nhật User #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/staffs/update/1', 'PUT', '{\"route_name\": \"admin.users.staffs.update\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Admin\\\\StaffController@update\", \"action_timestamp\": \"2025-08-15T07:16:23.258562Z\"}', 1, '2025-08-15 07:16:23'),
(12, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'update', 'User', 1, '{\"id\": 1, \"name\": \"Nguyễn Anh Đứccc\", \"role\": \"admin\", \"email\": \"nguyenanhduc2909@gmail.com\", \"phone\": \"0822153447\", \"avatar\": null, \"address\": \"Thanh Hóa\", \"password\": \"eyJpdiI6ImdBdUMrR2VuV3RhUE8yaWxJU2xnREE9PSIsInZhbHVlIjoieEVHajVsMVZwREg5eEF1NDRUQ1UwaGhHbWRUVnNNRkREUXFzbU1BbnMya251K2dOKzJQUW9WaFhSdFlXcW1pWmcrUWxjZFRVc2ZRdG92bk5SM3JRMVE9PSIsIm1hYyI6IjdjYzk3N2ZhNzgyNDY2ZDg1ODgwYjMyMjEzNDJlYjlkZTQ1ZWU2ODkzZTdlODQ0ZDRlNmY1NDFmMTYzMGMyOWEiLCJ0YWciOiIifQ==\", \"google_id\": null, \"identity_code\": \"038205000950\", \"current_team_id\": null, \"two_factor_secret\": \"eyJpdiI6Ii9NMkRLL29VeFlIdEd0T1djTjgwUlE9PSIsInZhbHVlIjoiT3lCNHkxZ2VMTzVkMjRVVnF6TnF4UT09IiwibWFjIjoiMWFhNDQ0ZTZjYTUwNWExZjdkMmM2NGIwNGNkYzQ5ZTQ2MDZmNDFmYWU0M2VjMWJlYTA3ZGMyNmRmYTIwNDI5OSIsInRhZyI6IiJ9\", \"profile_photo_path\": \"profile-photos/mfqMmmx1jtzkRy9YdNHQRl7xjSLZwxGgqDHJd4JS.png\", \"two_factor_confirmed_at\": null, \"two_factor_recovery_codes\": \"eyJpdiI6IlVNL2cwVlRSdmZxNUxRZWtiSk4wVEE9PSIsInZhbHVlIjoiT1BRdnBMUjM4SjJsY0pPVjRqRHB5UT09IiwibWFjIjoiOTQ5MmQyZDg3NjcwNjQ3Y2Q3OGM0NTAxMDQ1NWJkYzM4YjIyYjViYjNlODM2MjI0NzhhODI2ZDAxNmU5ZjdkYiIsInRhZyI6IiJ9\"}', '{\"name\": \"Nguyễn Anh Đức\", \"phone\": \"08221534477\"}', NULL, 'Nguyễn Anh Đứccc đã cập nhật User #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/staffs/update/1', 'PUT', '{\"route_name\": \"admin.users.staffs.update\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Admin\\\\StaffController@update\", \"action_timestamp\": \"2025-08-15T07:16:36.310840Z\"}', 1, '2025-08-15 07:16:36'),
(13, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'update', 'User', 1, '{\"id\": 1, \"name\": \"Nguyễn Anh Đứccc\", \"role\": \"admin\", \"email\": \"nguyenanhduc2909@gmail.com\", \"phone\": \"0822153447\", \"avatar\": null, \"address\": \"Thanh Hóa\", \"password\": \"eyJpdiI6IjJXR0g2MW1UR1FJL0JUZ3lqa3dMZEE9PSIsInZhbHVlIjoickd5VjZXdGhuVW1UZmk2NHdNdVlLd3ZjeGhDL0o1Q2dteXFnOCtzNWhtZjZjQ1JyNEF2ZG1oMHQ4WW04QjJRdVJYM1lKNEVvNTg1cVVvWFFIM1VBNEE9PSIsIm1hYyI6IjE4ZjhhMzU5NTdhNmE0N2ExZDI5NzhhOTFmZTAxOGFlZmQxNGQyZGVmMTJlNzk4MjdkNWYxYWE1NjJlMjVkMjIiLCJ0YWciOiIifQ==\", \"google_id\": null, \"identity_code\": \"038205000950\", \"current_team_id\": null, \"two_factor_secret\": \"eyJpdiI6IjBjMDNTTVJiTUNVeEx4dEpoZ1hjaXc9PSIsInZhbHVlIjoiSEVlQTJrTktuZUhjNGNscUlpUmJTdz09IiwibWFjIjoiMWZiYWMyYzA5NjRjN2JmNTk4ZTczNDMzY2JhMDEyY2NhNTIyOGRjZGUzMzcyMjZlYTVmNWIxNDhiNTRiYjkwYiIsInRhZyI6IiJ9\", \"profile_photo_path\": \"profile-photos/mfqMmmx1jtzkRy9YdNHQRl7xjSLZwxGgqDHJd4JS.png\", \"two_factor_confirmed_at\": null, \"two_factor_recovery_codes\": \"eyJpdiI6Ikxzbk83Uis0TUJaM1ZxQVJnSkQrS2c9PSIsInZhbHVlIjoiRzRkcDlMbWlPU29pS0d2eDh3MmhNQT09IiwibWFjIjoiY2M0MjgyZTk2ZjVjOTM3MDNmYWE4YWRjMzJmYTc3NDA1ZDhjNjM4Yzg2ODFkMWEyZmE3NGQ2NDQyMjE3MjYxNSIsInRhZyI6IiJ9\"}', '{\"name\": \"Nguyễn Anh Đức\", \"phone\": \"08221534477\"}', NULL, 'Nguyễn Anh Đứccc đã cập nhật User #1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/staffs/update/1', 'PUT', '{\"route_name\": \"admin.users.staffs.update\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Admin\\\\StaffController@update\", \"action_timestamp\": \"2025-08-15T07:16:36.328509Z\"}', 1, '2025-08-15 07:16:36'),
(14, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'create', 'User', 10, 'null', '{\"id\": 10, \"name\": \"PHNguyễn Anh Đức\", \"email\": \"nguyenanhduc2909@gmail.comm\", \"phone\": \"0822153447\", \"address\": \"Thanh Hóa\\r\\nThanh Hóa\", \"password\": \"eyJpdiI6Ilh1UlBFYTNFaFBhdmJad29USGJyc2c9PSIsInZhbHVlIjoiRjZwcHV4M1Y4UVBrcFFYN3BRNGxUSGxLaEJqdCt0OWhucVZHcjgxRU90eVBTKzZScDlJK2kxM1JPVXBrNG1Bbys4OVpwVHpZR0FRUzZlc29VYXlLYVE9PSIsIm1hYyI6IjZkMWY1MzJjY2I0OTY0MjAxODk5MTY3ZTYzYjFlMzAxY2VkYWIzMzdkNGM2NWYyN2RjN2M2ZDI1ZTUxYWU1ZWQiLCJ0YWciOiIifQ==\", \"created_at\": \"2025-08-15 14:46:42\", \"updated_at\": \"2025-08-15 14:46:42\", \"identity_code\": \"0356562189457\"}', NULL, 'Nguyễn Anh Đức đã tạo mới User #10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/customers/store', 'POST', '{\"route_name\": \"admin.users.customers.store\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Admin\\\\CustomerController@store\", \"action_timestamp\": \"2025-08-15T07:46:42.241831Z\"}', 1, '2025-08-15 07:46:42'),
(15, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'create', 'User', 10, 'null', '{\"id\": 10, \"name\": \"PHNguyễn Anh Đức\", \"email\": \"nguyenanhduc2909@gmail.comm\", \"phone\": \"0822153447\", \"address\": \"Thanh Hóa\\r\\nThanh Hóa\", \"password\": \"eyJpdiI6InVQdUNxSmlQOFJqeFhkNXcxVU01V0E9PSIsInZhbHVlIjoic1dtT1k0ejFmRkpYbjFQTHJ6WFpnNDhIeTZEMmVXaVNvRlpDa21OYVl3OS9OdFFLY3lHUWN3R3I2SDBvU25OYUNBOVRWemV2UDVmQlh1ZngxUm1ZaUE9PSIsIm1hYyI6ImZkNjM4NTI5MDAwYWIwY2U3YWI2MGU5Y2Y3ZDEwMmQ0ZmI0YjM0OTExMTlkODc1MjQ2OGE0NTVlODhmOWY4NjAiLCJ0YWciOiIifQ==\", \"created_at\": \"2025-08-15 14:46:42\", \"updated_at\": \"2025-08-15 14:46:42\", \"identity_code\": \"0356562189457\"}', NULL, 'Nguyễn Anh Đức đã tạo mới User #10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/customers/store', 'POST', '{\"route_name\": \"admin.users.customers.store\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Admin\\\\CustomerController@store\", \"action_timestamp\": \"2025-08-15T07:46:42.265801Z\"}', 1, '2025-08-15 07:46:42'),
(16, NULL, 'k6IvCRDC6LdVD8mWs8DQy6Uy3XVJpG8KbDnLJvix', 'create', 'RoomPriceHistory', 295, 'null', '{\"date\": \"2025-08-16 00:00:00\", \"base_price\": \"50000\", \"created_at\": \"2025-08-15 15:28:44\", \"updated_at\": \"2025-08-15 15:28:44\", \"room_type_id\": 1, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 65000, \"price_history_id\": 295}', NULL, 'System đã tạo mới RoomPriceHistory #295', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-15T08:28:44.074879Z\"}', 0, '2025-08-15 08:28:44'),
(17, NULL, 'k6IvCRDC6LdVD8mWs8DQy6Uy3XVJpG8KbDnLJvix', 'create', 'RoomPriceHistory', 296, 'null', '{\"date\": \"2025-08-16 00:00:00\", \"base_price\": \"1500000\", \"created_at\": \"2025-08-15 15:28:44\", \"updated_at\": \"2025-08-15 15:28:44\", \"room_type_id\": 2, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1950000, \"price_history_id\": 296}', NULL, 'System đã tạo mới RoomPriceHistory #296', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-15T08:28:44.147415Z\"}', 0, '2025-08-15 08:28:44'),
(18, NULL, 'k6IvCRDC6LdVD8mWs8DQy6Uy3XVJpG8KbDnLJvix', 'create', 'RoomPriceHistory', 297, 'null', '{\"date\": \"2025-08-16 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-15 15:28:44\", \"updated_at\": \"2025-08-15 15:28:44\", \"room_type_id\": 3, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1300000, \"price_history_id\": 297}', NULL, 'System đã tạo mới RoomPriceHistory #297', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-15T08:28:44.166730Z\"}', 0, '2025-08-15 08:28:44'),
(19, NULL, 'k6IvCRDC6LdVD8mWs8DQy6Uy3XVJpG8KbDnLJvix', 'create', 'RoomPriceHistory', 298, 'null', '{\"date\": \"2025-08-16 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-15 15:28:44\", \"updated_at\": \"2025-08-15 15:28:44\", \"room_type_id\": 4, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"7.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"70.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":90}}]\\\"\", \"adjusted_price\": 1370000, \"price_history_id\": 298}', NULL, 'System đã tạo mới RoomPriceHistory #298', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-15T08:28:44.184154Z\"}', 0, '2025-08-15 08:28:44'),
(20, NULL, 'k6IvCRDC6LdVD8mWs8DQy6Uy3XVJpG8KbDnLJvix', 'create', 'RoomPriceHistory', 299, 'null', '{\"date\": \"2025-08-16 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-15 15:28:44\", \"updated_at\": \"2025-08-15 15:28:44\", \"room_type_id\": 5, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1300000, \"price_history_id\": 299}', NULL, 'System đã tạo mới RoomPriceHistory #299', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-15T08:28:44.207918Z\"}', 0, '2025-08-15 08:28:44'),
(21, NULL, 'k6IvCRDC6LdVD8mWs8DQy6Uy3XVJpG8KbDnLJvix', 'create', 'RoomPriceHistory', 300, 'null', '{\"date\": \"2025-08-16 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-15 15:28:44\", \"updated_at\": \"2025-08-15 15:28:44\", \"room_type_id\": 6, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1300000, \"price_history_id\": 300}', NULL, 'System đã tạo mới RoomPriceHistory #300', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-15T08:28:44.228522Z\"}', 0, '2025-08-15 08:28:44'),
(22, NULL, 'k6IvCRDC6LdVD8mWs8DQy6Uy3XVJpG8KbDnLJvix', 'create', 'RoomPriceHistory', 301, 'null', '{\"date\": \"2025-08-16 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-15 15:28:44\", \"updated_at\": \"2025-08-15 15:28:44\", \"room_type_id\": 7, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1300000, \"price_history_id\": 301}', NULL, 'System đã tạo mới RoomPriceHistory #301', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-15T08:28:44.250290Z\"}', 0, '2025-08-15 08:28:44'),
(23, NULL, 'vfVSh8hwF7K7hAj59he5ynGvfzYLMCgRiGj9EmPm', 'update', 'RoomPriceHistory', 295, '{\"date\": \"2025-08-15T17:00:00.000000Z\", \"base_price\": \"50000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\", \"adjusted_price\": \"65000.00\", \"price_history_id\": 295}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":2,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"90.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":93}}]\\\"\", \"adjusted_price\": 70000}', NULL, 'System đã cập nhật RoomPriceHistory #295', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-15T08:47:22.468412Z\"}', 0, '2025-08-15 08:47:22'),
(24, NULL, 'vfVSh8hwF7K7hAj59he5ynGvfzYLMCgRiGj9EmPm', 'update', 'RoomPriceHistory', 298, '{\"date\": \"2025-08-15T17:00:00.000000Z\", \"base_price\": \"1000000.00\", \"room_type_id\": 4, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":90}}]\", \"adjusted_price\": \"1370000.00\", \"price_history_id\": 298}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"7.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"70.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":83}}]\\\"\"}', NULL, 'System đã cập nhật RoomPriceHistory #298', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-15T08:47:22.535425Z\"}', 0, '2025-08-15 08:47:22'),
(25, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'update', 'RoomPriceHistory', 109, '{\"date\": \"2025-08-14T17:00:00.000000Z\", \"base_price\": \"5000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}},{\\\"rule_id\\\":1,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":83}}]\", \"adjusted_price\": \"6500.00\", \"price_history_id\": 109}', '{\"base_price\": 50000, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":1,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"80.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":80}}]\\\"\", \"adjusted_price\": 65000}', NULL, 'Nguyễn Anh Đức đã cập nhật RoomPriceHistory #109', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/pricing/preview?end_date=2025-08-21&room_type_id=1&start_date=2025-08-15', 'GET', '{\"route_name\": \"admin.pricing.preview\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PricingManagementController@getPricingPreview\", \"action_timestamp\": \"2025-08-15T08:55:17.083650Z\"}', 0, '2025-08-15 08:55:17'),
(26, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'update', 'RoomPriceHistory', 295, '{\"date\": \"2025-08-15T17:00:00.000000Z\", \"base_price\": \"50000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":2,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"90.00\\\",\\\"current_occupancy\\\":93}}]\", \"adjusted_price\": \"70000.00\", \"price_history_id\": 295}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":1,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"80.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":87}}]\\\"\"}', NULL, 'Nguyễn Anh Đức đã cập nhật RoomPriceHistory #295', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/pricing/preview?end_date=2025-08-21&room_type_id=1&start_date=2025-08-15', 'GET', '{\"route_name\": \"admin.pricing.preview\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PricingManagementController@getPricingPreview\", \"action_timestamp\": \"2025-08-15T08:55:17.116595Z\"}', 0, '2025-08-15 08:55:17'),
(27, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'create', 'RoomPriceHistory', 302, 'null', '{\"date\": \"2025-08-17 00:00:00\", \"base_price\": 50000, \"created_at\": \"2025-08-15 15:55:17\", \"updated_at\": \"2025-08-15 15:55:17\", \"room_type_id\": \"1\", \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 65000, \"price_history_id\": 302}', NULL, 'Nguyễn Anh Đức đã tạo mới RoomPriceHistory #302', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/pricing/preview?end_date=2025-08-21&room_type_id=1&start_date=2025-08-15', 'GET', '{\"route_name\": \"admin.pricing.preview\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PricingManagementController@getPricingPreview\", \"action_timestamp\": \"2025-08-15T08:55:17.127475Z\"}', 0, '2025-08-15 08:55:17'),
(28, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'update', 'RoomPriceHistory', 159, '{\"date\": \"2025-08-17T17:00:00.000000Z\", \"base_price\": \"5000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\", \"adjusted_price\": \"6000.00\", \"price_history_id\": 159}', '{\"base_price\": 50000, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 60000}', NULL, 'Nguyễn Anh Đức đã cập nhật RoomPriceHistory #159', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/pricing/preview?end_date=2025-08-21&room_type_id=1&start_date=2025-08-15', 'GET', '{\"route_name\": \"admin.pricing.preview\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PricingManagementController@getPricingPreview\", \"action_timestamp\": \"2025-08-15T08:55:17.140265Z\"}', 0, '2025-08-15 08:55:17');
INSERT INTO `audit_logs` (`audit_id`, `user_id`, `session_id`, `action`, `model`, `model_id`, `old_values`, `new_values`, `changes_summary`, `description`, `ip_address`, `user_agent`, `url`, `method`, `metadata`, `is_sensitive`, `created_at`) VALUES
(29, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'create', 'RoomPriceHistory', 303, 'null', '{\"date\": \"2025-08-19 00:00:00\", \"base_price\": 50000, \"created_at\": \"2025-08-15 15:55:17\", \"updated_at\": \"2025-08-15 15:55:17\", \"room_type_id\": \"1\", \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 60000, \"price_history_id\": 303}', NULL, 'Nguyễn Anh Đức đã tạo mới RoomPriceHistory #303', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/pricing/preview?end_date=2025-08-21&room_type_id=1&start_date=2025-08-15', 'GET', '{\"route_name\": \"admin.pricing.preview\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PricingManagementController@getPricingPreview\", \"action_timestamp\": \"2025-08-15T08:55:17.152411Z\"}', 0, '2025-08-15 08:55:17'),
(30, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'create', 'RoomPriceHistory', 304, 'null', '{\"date\": \"2025-08-20 00:00:00\", \"base_price\": 50000, \"created_at\": \"2025-08-15 15:55:17\", \"updated_at\": \"2025-08-15 15:55:17\", \"room_type_id\": \"1\", \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 60000, \"price_history_id\": 304}', NULL, 'Nguyễn Anh Đức đã tạo mới RoomPriceHistory #304', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/pricing/preview?end_date=2025-08-21&room_type_id=1&start_date=2025-08-15', 'GET', '{\"route_name\": \"admin.pricing.preview\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PricingManagementController@getPricingPreview\", \"action_timestamp\": \"2025-08-15T08:55:17.163674Z\"}', 0, '2025-08-15 08:55:17'),
(31, 1, 'lfYI0quVcaG2FEh5Ah5HSzRT53jevLjlC1r6fWnh', 'create', 'RoomPriceHistory', 305, 'null', '{\"date\": \"2025-08-21 00:00:00\", \"base_price\": 50000, \"created_at\": \"2025-08-15 15:55:17\", \"updated_at\": \"2025-08-15 15:55:17\", \"room_type_id\": \"1\", \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 60000, \"price_history_id\": 305}', NULL, 'Nguyễn Anh Đức đã tạo mới RoomPriceHistory #305', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/pricing/preview?end_date=2025-08-21&room_type_id=1&start_date=2025-08-15', 'GET', '{\"route_name\": \"admin.pricing.preview\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PricingManagementController@getPricingPreview\", \"action_timestamp\": \"2025-08-15T08:55:17.175324Z\"}', 0, '2025-08-15 08:55:17'),
(32, NULL, 'NN0rle7b61yfZHhos8VWeZKQifN2ozfI1SBSNkku', 'update', 'RoomPriceHistory', 302, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"50000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\", \"adjusted_price\": \"65000.00\", \"price_history_id\": 302}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":2,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"90.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":95}}]\\\"\", \"adjusted_price\": 70000}', NULL, 'System đã cập nhật RoomPriceHistory #302', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-16T09:09:18.050699Z\"}', 0, '2025-08-16 09:09:18'),
(33, NULL, 'NN0rle7b61yfZHhos8VWeZKQifN2ozfI1SBSNkku', 'create', 'RoomPriceHistory', 306, 'null', '{\"date\": \"2025-08-17 00:00:00\", \"base_price\": \"1500000\", \"created_at\": \"2025-08-16 16:09:18\", \"updated_at\": \"2025-08-16 16:09:18\", \"room_type_id\": 2, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1950000, \"price_history_id\": 306}', NULL, 'System đã tạo mới RoomPriceHistory #306', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-16T09:09:18.163202Z\"}', 0, '2025-08-16 09:09:18'),
(34, NULL, 'NN0rle7b61yfZHhos8VWeZKQifN2ozfI1SBSNkku', 'create', 'RoomPriceHistory', 307, 'null', '{\"date\": \"2025-08-17 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-16 16:09:18\", \"updated_at\": \"2025-08-16 16:09:18\", \"room_type_id\": 3, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1300000, \"price_history_id\": 307}', NULL, 'System đã tạo mới RoomPriceHistory #307', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-16T09:09:18.252010Z\"}', 0, '2025-08-16 09:09:18'),
(35, NULL, 'NN0rle7b61yfZHhos8VWeZKQifN2ozfI1SBSNkku', 'create', 'RoomPriceHistory', 308, 'null', '{\"date\": \"2025-08-17 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-16 16:09:18\", \"updated_at\": \"2025-08-16 16:09:18\", \"room_type_id\": 4, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"7.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"70.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":87}}]\\\"\", \"adjusted_price\": 1370000, \"price_history_id\": 308}', NULL, 'System đã tạo mới RoomPriceHistory #308', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-16T09:09:18.292431Z\"}', 0, '2025-08-16 09:09:18'),
(36, NULL, 'NN0rle7b61yfZHhos8VWeZKQifN2ozfI1SBSNkku', 'create', 'RoomPriceHistory', 309, 'null', '{\"date\": \"2025-08-17 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-16 16:09:18\", \"updated_at\": \"2025-08-16 16:09:18\", \"room_type_id\": 5, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1300000, \"price_history_id\": 309}', NULL, 'System đã tạo mới RoomPriceHistory #309', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-16T09:09:18.325404Z\"}', 0, '2025-08-16 09:09:18'),
(37, NULL, 'NN0rle7b61yfZHhos8VWeZKQifN2ozfI1SBSNkku', 'create', 'RoomPriceHistory', 310, 'null', '{\"date\": \"2025-08-17 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-16 16:09:18\", \"updated_at\": \"2025-08-16 16:09:18\", \"room_type_id\": 6, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1300000, \"price_history_id\": 310}', NULL, 'System đã tạo mới RoomPriceHistory #310', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-16T09:09:18.355471Z\"}', 0, '2025-08-16 09:09:18'),
(38, NULL, 'NN0rle7b61yfZHhos8VWeZKQifN2ozfI1SBSNkku', 'create', 'RoomPriceHistory', 311, 'null', '{\"date\": \"2025-08-17 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-16 16:09:18\", \"updated_at\": \"2025-08-16 16:09:18\", \"room_type_id\": 7, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1300000, \"price_history_id\": 311}', NULL, 'System đã tạo mới RoomPriceHistory #311', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-16T09:09:18.387349Z\"}', 0, '2025-08-16 09:09:18'),
(39, NULL, 'g689UJ834IJkmdXVV8ny35Dc9OScg2WKh4hw2yi7', 'update', 'RoomPriceHistory', 295, '{\"date\": \"2025-08-15T17:00:00.000000Z\", \"base_price\": \"50000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":1,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":87}}]\", \"adjusted_price\": \"70000.00\", \"price_history_id\": 295}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 65000}', NULL, 'System đã cập nhật RoomPriceHistory #295', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-16T09:13:34.450124Z\"}', 0, '2025-08-16 09:13:34'),
(40, NULL, 'g689UJ834IJkmdXVV8ny35Dc9OScg2WKh4hw2yi7', 'update', 'RoomPriceHistory', 298, '{\"date\": \"2025-08-15T17:00:00.000000Z\", \"base_price\": \"1000000.00\", \"room_type_id\": 4, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":83}}]\", \"adjusted_price\": \"1370000.00\", \"price_history_id\": 298}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"7.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"70.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":82}}]\\\"\"}', NULL, 'System đã cập nhật RoomPriceHistory #298', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-16T09:13:34.650947Z\"}', 0, '2025-08-16 09:13:34'),
(41, NULL, '4p468tmZYVnyRXY86st0zCSZmmy5z3AdzpDjloxJ', 'create', 'Booking', 171, 'null', '{\"notes\": \"d\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 171, \"created_at\": \"2025-08-16 16:13:43\", \"guest_name\": \"Nguyễn Anh Đức\", \"updated_at\": \"2025-08-16 16:13:43\", \"guest_count\": 7, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-16 00:00:00\", \"check_out_date\": \"2025-08-17 00:00:00\", \"total_price_vnd\": 250000}', NULL, 'System đã tạo mới Booking #171', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-16T09:13:43.303666Z\"}', 0, '2025-08-16 09:13:43'),
(42, NULL, '4p468tmZYVnyRXY86st0zCSZmmy5z3AdzpDjloxJ', 'update', 'Booking', 171, '{\"notes\": \"d\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 171, \"guest_name\": \"Nguyễn Anh Đức\", \"guest_count\": 7, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-15T17:00:00.000000Z\", \"check_out_date\": \"2025-08-16T17:00:00.000000Z\", \"total_price_vnd\": 250000}', '{\"booking_code\": \"LVS171161343\"}', NULL, 'System đã cập nhật Booking #171', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-16T09:13:43.332547Z\"}', 0, '2025-08-16 09:13:43'),
(43, NULL, '4p468tmZYVnyRXY86st0zCSZmmy5z3AdzpDjloxJ', 'create', 'Payment', 134, 'null', '{\"status\": \"pending\", \"amount_vnd\": 250000, \"booking_id\": 171, \"created_at\": \"2025-08-16 16:13:43\", \"payment_id\": 134, \"updated_at\": \"2025-08-16 16:13:43\", \"payment_type\": \"vietqr\"}', NULL, 'System đã tạo mới Payment #134', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Payment\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-16T09:13:43.338626Z\"}', 0, '2025-08-16 09:13:43'),
(44, 1, 'vwsm2XgnaOtdvcU7RN1WyG9nDke9AwO9qJFlvdeW', 'update', 'PaymentSetting', 2, '{\"id\": 2, \"key\": \"vietqr.account_no\", \"type\": \"string\", \"value\": \"0335920306\", \"is_active\": true, \"group_name\": \"vietqr\", \"description\": \"Số tài khoản ngân hàng\", \"is_encrypted\": false}', '{\"value\": \"19290920058383\"}', NULL, 'Nguyễn Anh Đức đã cập nhật PaymentSetting #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/payment/setting', 'PUT', '{\"route_name\": \"admin.payment.setting.update\", \"model_class\": \"App\\\\Models\\\\PaymentSetting\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@updateSettings\", \"action_timestamp\": \"2025-08-16T09:14:30.382886Z\"}', 0, '2025-08-16 09:14:30'),
(45, 1, 'vwsm2XgnaOtdvcU7RN1WyG9nDke9AwO9qJFlvdeW', 'update', 'PaymentSetting', 3, '{\"id\": 3, \"key\": \"vietqr.account_name\", \"type\": \"string\", \"value\": \"NGUYEN VAN QUYEN\", \"is_active\": true, \"group_name\": \"vietqr\", \"description\": \"Tên chủ tài khoản\", \"is_encrypted\": false}', '{\"value\": \"NGUYEN ANH DUC\"}', NULL, 'Nguyễn Anh Đức đã cập nhật PaymentSetting #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/payment/setting', 'PUT', '{\"route_name\": \"admin.payment.setting.update\", \"model_class\": \"App\\\\Models\\\\PaymentSetting\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@updateSettings\", \"action_timestamp\": \"2025-08-16T09:14:30.408276Z\"}', 0, '2025-08-16 09:14:30'),
(46, NULL, 'Kid4zrYfFr4UxAgzPMuA8TAnSXvQt01nYoVcFfxr', 'create', 'Booking', 172, 'null', '{\"notes\": \"tg\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 172, \"created_at\": \"2025-08-16 16:14:40\", \"guest_name\": \"Nguyễn Anh Đức\", \"updated_at\": \"2025-08-16 16:14:40\", \"guest_count\": 7, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-16 00:00:00\", \"check_out_date\": \"2025-08-17 00:00:00\", \"total_price_vnd\": 250000}', NULL, 'System đã tạo mới Booking #172', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-16T09:14:40.183274Z\"}', 0, '2025-08-16 09:14:40'),
(47, NULL, 'Kid4zrYfFr4UxAgzPMuA8TAnSXvQt01nYoVcFfxr', 'update', 'Booking', 172, '{\"notes\": \"tg\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 172, \"guest_name\": \"Nguyễn Anh Đức\", \"guest_count\": 7, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-15T17:00:00.000000Z\", \"check_out_date\": \"2025-08-16T17:00:00.000000Z\", \"total_price_vnd\": 250000}', '{\"booking_code\": \"LVS172161440\"}', NULL, 'System đã cập nhật Booking #172', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-16T09:14:40.219208Z\"}', 0, '2025-08-16 09:14:40'),
(48, NULL, 'Kid4zrYfFr4UxAgzPMuA8TAnSXvQt01nYoVcFfxr', 'create', 'Payment', 135, 'null', '{\"status\": \"pending\", \"amount_vnd\": 250000, \"booking_id\": 172, \"created_at\": \"2025-08-16 16:14:40\", \"payment_id\": 135, \"updated_at\": \"2025-08-16 16:14:40\", \"payment_type\": \"vietqr\"}', NULL, 'System đã tạo mới Payment #135', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Payment\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-16T09:14:40.223808Z\"}', 0, '2025-08-16 09:14:40'),
(49, 1, 'vwsm2XgnaOtdvcU7RN1WyG9nDke9AwO9qJFlvdeW', 'update', 'PaymentSetting', 2, '{\"id\": 2, \"key\": \"vietqr.account_no\", \"type\": \"string\", \"value\": \"19290920058383\", \"is_active\": true, \"group_name\": \"vietqr\", \"description\": \"Số tài khoản ngân hàng\", \"is_encrypted\": false}', '{\"value\": \"0335920306\"}', NULL, 'Nguyễn Anh Đức đã cập nhật PaymentSetting #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/payment/setting/reset', 'GET', '{\"route_name\": \"admin.payment.setting.reset\", \"model_class\": \"App\\\\Models\\\\PaymentSetting\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@resetToDefaultsFromAdmin\", \"action_timestamp\": \"2025-08-16T09:14:52.530220Z\"}', 0, '2025-08-16 09:14:52'),
(50, 1, 'vwsm2XgnaOtdvcU7RN1WyG9nDke9AwO9qJFlvdeW', 'update', 'PaymentSetting', 3, '{\"id\": 3, \"key\": \"vietqr.account_name\", \"type\": \"string\", \"value\": \"NGUYEN ANH DUC\", \"is_active\": true, \"group_name\": \"vietqr\", \"description\": \"Tên chủ tài khoản\", \"is_encrypted\": false}', '{\"value\": \"NGUYEN VAN QUYEN\"}', NULL, 'Nguyễn Anh Đức đã cập nhật PaymentSetting #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/payment/setting/reset', 'GET', '{\"route_name\": \"admin.payment.setting.reset\", \"model_class\": \"App\\\\Models\\\\PaymentSetting\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@resetToDefaultsFromAdmin\", \"action_timestamp\": \"2025-08-16T09:14:52.553530Z\"}', 0, '2025-08-16 09:14:52'),
(51, NULL, 'd5GHbSKXqstpwv4eshpWCLLkuelbL5Rde9aC1HZP', 'create', 'Booking', 173, 'null', '{\"notes\": \"d\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 173, \"created_at\": \"2025-08-16 16:15:04\", \"guest_name\": \"Nguyễn Anh Đức\", \"updated_at\": \"2025-08-16 16:15:04\", \"guest_count\": 7, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-16 00:00:00\", \"check_out_date\": \"2025-08-17 00:00:00\", \"total_price_vnd\": 250000}', NULL, 'System đã tạo mới Booking #173', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-16T09:15:04.180355Z\"}', 0, '2025-08-16 09:15:04'),
(52, NULL, 'd5GHbSKXqstpwv4eshpWCLLkuelbL5Rde9aC1HZP', 'update', 'Booking', 173, '{\"notes\": \"d\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 173, \"guest_name\": \"Nguyễn Anh Đức\", \"guest_count\": 7, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-15T17:00:00.000000Z\", \"check_out_date\": \"2025-08-16T17:00:00.000000Z\", \"total_price_vnd\": 250000}', '{\"booking_code\": \"LVS173161504\"}', NULL, 'System đã cập nhật Booking #173', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-16T09:15:04.207676Z\"}', 0, '2025-08-16 09:15:04'),
(53, NULL, 'd5GHbSKXqstpwv4eshpWCLLkuelbL5Rde9aC1HZP', 'create', 'Payment', 136, 'null', '{\"status\": \"pending\", \"amount_vnd\": 250000, \"booking_id\": 173, \"created_at\": \"2025-08-16 16:15:04\", \"payment_id\": 136, \"updated_at\": \"2025-08-16 16:15:04\", \"payment_type\": \"vietqr\"}', NULL, 'System đã tạo mới Payment #136', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Payment\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-16T09:15:04.221366Z\"}', 0, '2025-08-16 09:15:04'),
(54, NULL, 'LkTEiWo9NYGCBmmMYicWEVOR26tlH3bUdFuAG06f', 'update', 'RoomPriceHistory', 302, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"50000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":2,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"90.00\\\",\\\"current_occupancy\\\":95}}]\", \"adjusted_price\": \"70000.00\", \"price_history_id\": 302}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":1,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"80.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":86}}]\\\"\"}', NULL, 'System đã cập nhật RoomPriceHistory #302', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-16T10:02:32.322063Z\"}', 0, '2025-08-16 10:02:32'),
(55, NULL, 'LkTEiWo9NYGCBmmMYicWEVOR26tlH3bUdFuAG06f', 'update', 'RoomPriceHistory', 306, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"1500000.00\", \"room_type_id\": 2, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\", \"adjusted_price\": \"1950000.00\", \"price_history_id\": 306}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":4,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"30.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"90.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":95}}]\\\"\", \"adjusted_price\": 2100000}', NULL, 'System đã cập nhật RoomPriceHistory #306', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-16T10:02:32.512231Z\"}', 0, '2025-08-16 10:02:32'),
(56, NULL, 'LkTEiWo9NYGCBmmMYicWEVOR26tlH3bUdFuAG06f', 'update', 'RoomPriceHistory', 308, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"1000000.00\", \"room_type_id\": 4, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":87}}]\", \"adjusted_price\": \"1370000.00\", \"price_history_id\": 308}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"7.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"70.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":83}}]\\\"\"}', NULL, 'System đã cập nhật RoomPriceHistory #308', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-16T10:02:32.593655Z\"}', 0, '2025-08-16 10:02:32'),
(57, NULL, 'rsgiHckFkSEr1utsSujOyGiIaGXcWxRj95pCGk3B', 'update', 'Booking', 24, '{\"notes\": \"Check-in completed at 2025-08-05 19:39:26\", \"status\": \"Operational\", \"room_id\": 2, \"user_id\": 2, \"children\": 2, \"quantity\": 3, \"option_id\": \"OPT10\", \"booking_id\": 24, \"guest_name\": \"húhu\", \"guest_count\": 2, \"guest_email\": \"quyen@gmai.comđ\", \"guest_phone\": \"231443342423\", \"booking_code\": \"LAVISHYSTAY_931923\", \"children_age\": \"3\", \"room_type_id\": 2, \"check_in_date\": \"2025-08-03T17:00:00.000000Z\", \"booking_source\": null, \"check_out_date\": \"2025-08-14T17:00:00.000000Z\", \"payment_policy\": null, \"total_price_vnd\": 500000, \"is_document_verified\": 0}', '{\"notes\": \"Check-in completed at 2025-08-05 19:39:26\\nCheck-out completed at 2025-08-16 17:05:05\", \"status\": \"Cleaning\", \"total_price_vnd\": 2900000}', NULL, 'System đã cập nhật Booking #24', '127.0.0.1', 'PostmanRuntime/7.45.0', 'http://127.0.0.1:8888/api/bookings/24/checkout', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\BookingCheckoutController@processCheckout\", \"action_timestamp\": \"2025-08-16T10:05:05.720325Z\"}', 0, '2025-08-16 10:05:05'),
(58, NULL, 'rsgiHckFkSEr1utsSujOyGiIaGXcWxRj95pCGk3B', 'create', 'Invoice', 1, 'null', '{\"status\": \"Draft\", \"issued_at\": \"2025-08-16 17:05:05\", \"booking_id\": 24, \"invoice_id\": 1, \"total_amount_vnd\": 2900000}', NULL, 'System đã tạo mới Invoice #1', '127.0.0.1', 'PostmanRuntime/7.45.0', 'http://127.0.0.1:8888/api/bookings/24/checkout', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Invoice\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\BookingCheckoutController@processCheckout\", \"action_timestamp\": \"2025-08-16T10:05:05.755007Z\"}', 0, '2025-08-16 10:05:05'),
(59, NULL, 'UqRZyvSmD6MCluI21plkg1COVYc0phS9bIvEHswQ', 'update', 'RoomPriceHistory', 302, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"50000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":1,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":86}}]\", \"adjusted_price\": \"70000.00\", \"price_history_id\": 302}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 65000}', NULL, 'System đã cập nhật RoomPriceHistory #302', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/rooms?include=room_type', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\ReceptionController@getRooms\", \"action_timestamp\": \"2025-08-16T10:05:26.955643Z\"}', 0, '2025-08-16 10:05:26');
INSERT INTO `audit_logs` (`audit_id`, `user_id`, `session_id`, `action`, `model`, `model_id`, `old_values`, `new_values`, `changes_summary`, `description`, `ip_address`, `user_agent`, `url`, `method`, `metadata`, `is_sensitive`, `created_at`) VALUES
(60, NULL, 'UqRZyvSmD6MCluI21plkg1COVYc0phS9bIvEHswQ', 'update', 'RoomPriceHistory', 306, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"1500000.00\", \"room_type_id\": 2, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":4,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"30.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"90.00\\\",\\\"current_occupancy\\\":95}}]\", \"adjusted_price\": \"2100000.00\", \"price_history_id\": 306}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1950000}', NULL, 'System đã cập nhật RoomPriceHistory #306', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/rooms?include=room_type', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\ReceptionController@getRooms\", \"action_timestamp\": \"2025-08-16T10:05:27.107236Z\"}', 0, '2025-08-16 10:05:27'),
(61, NULL, 'UqRZyvSmD6MCluI21plkg1COVYc0phS9bIvEHswQ', 'update', 'RoomPriceHistory', 308, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"1000000.00\", \"room_type_id\": 4, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":83}}]\", \"adjusted_price\": \"1370000.00\", \"price_history_id\": 308}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"7.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"70.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":84}}]\\\"\"}', NULL, 'System đã cập nhật RoomPriceHistory #308', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/rooms?include=room_type', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\ReceptionController@getRooms\", \"action_timestamp\": \"2025-08-16T10:05:27.286342Z\"}', 0, '2025-08-16 10:05:27'),
(62, NULL, 'edaxKSDLZVjZHUkPSt6GYFPuQDy4r6hV3I4gjcoB', 'update', 'RoomPriceHistory', 260, '{\"date\": \"2025-08-05T17:00:00.000000Z\", \"base_price\": \"5000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\", \"adjusted_price\": \"6000.00\", \"price_history_id\": 260}', '{\"base_price\": \"50000\", \"adjusted_price\": 60000}', NULL, 'System đã cập nhật RoomPriceHistory #260', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/rooms/available?check_in_date=2025-08-06&check_out_date=2025-08-08', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailableRooms\", \"action_timestamp\": \"2025-08-16T10:07:05.076640Z\"}', 0, '2025-08-16 10:07:05'),
(63, NULL, 'GaKmLAEzjQRy0ReRW2mE6IuQfTocWe698hans9FF', 'update', 'RoomPriceHistory', 160, '{\"date\": \"2025-08-17T17:00:00.000000Z\", \"base_price\": \"1500000.00\", \"room_type_id\": 2, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\", \"adjusted_price\": \"1800000.00\", \"price_history_id\": 160}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\"}', NULL, 'System đã cập nhật RoomPriceHistory #160', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-17T07:44:22.515755Z\"}', 0, '2025-08-17 07:44:22'),
(64, NULL, 'GaKmLAEzjQRy0ReRW2mE6IuQfTocWe698hans9FF', 'update', 'RoomPriceHistory', 161, '{\"date\": \"2025-08-17T17:00:00.000000Z\", \"base_price\": \"1000000.00\", \"room_type_id\": 3, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\", \"adjusted_price\": \"1200000.00\", \"price_history_id\": 161}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\"}', NULL, 'System đã cập nhật RoomPriceHistory #161', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-17T07:44:22.570857Z\"}', 0, '2025-08-17 07:44:22'),
(65, NULL, 'GaKmLAEzjQRy0ReRW2mE6IuQfTocWe698hans9FF', 'update', 'RoomPriceHistory', 162, '{\"date\": \"2025-08-17T17:00:00.000000Z\", \"base_price\": \"1000000.00\", \"room_type_id\": 4, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\", \"adjusted_price\": \"1200000.00\", \"price_history_id\": 162}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\"}', NULL, 'System đã cập nhật RoomPriceHistory #162', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-17T07:44:22.593871Z\"}', 0, '2025-08-17 07:44:22'),
(66, NULL, 'GaKmLAEzjQRy0ReRW2mE6IuQfTocWe698hans9FF', 'update', 'RoomPriceHistory', 163, '{\"date\": \"2025-08-17T17:00:00.000000Z\", \"base_price\": \"1000000.00\", \"room_type_id\": 5, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\", \"adjusted_price\": \"1200000.00\", \"price_history_id\": 163}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\"}', NULL, 'System đã cập nhật RoomPriceHistory #163', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-17T07:44:22.616149Z\"}', 0, '2025-08-17 07:44:22'),
(67, NULL, 'GaKmLAEzjQRy0ReRW2mE6IuQfTocWe698hans9FF', 'update', 'RoomPriceHistory', 164, '{\"date\": \"2025-08-17T17:00:00.000000Z\", \"base_price\": \"1000000.00\", \"room_type_id\": 6, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-06-01T00:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-31T00:00:00.000000Z\\\"}}}]\", \"adjusted_price\": \"1200000.00\", \"price_history_id\": 164}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\"}', NULL, 'System đã cập nhật RoomPriceHistory #164', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-17T07:44:22.639206Z\"}', 0, '2025-08-17 07:44:22'),
(68, NULL, 'GaKmLAEzjQRy0ReRW2mE6IuQfTocWe698hans9FF', 'create', 'RoomPriceHistory', 312, 'null', '{\"date\": \"2025-08-18 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-17 14:44:22\", \"updated_at\": \"2025-08-17 14:44:22\", \"room_type_id\": 7, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1200000, \"price_history_id\": 312}', NULL, 'System đã tạo mới RoomPriceHistory #312', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-types', 'GET', '{\"route_name\": \"room-types.index\", \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomTypeController@index\", \"action_timestamp\": \"2025-08-17T07:44:22.659188Z\"}', 0, '2025-08-17 07:44:22'),
(69, NULL, 'ylLjEmD7q3YxGATJKzKwmOflDDljiadsorkTyCRJ', 'update', 'RoomPriceHistory', 302, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"50000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\", \"adjusted_price\": \"65000.00\", \"price_history_id\": 302}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":1,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"80.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":84}}]\\\"\", \"adjusted_price\": 70000}', NULL, 'System đã cập nhật RoomPriceHistory #302', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-17T07:44:24.533728Z\"}', 0, '2025-08-17 07:44:24'),
(70, NULL, 'ylLjEmD7q3YxGATJKzKwmOflDDljiadsorkTyCRJ', 'update', 'RoomPriceHistory', 306, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"1500000.00\", \"room_type_id\": 2, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\", \"adjusted_price\": \"1950000.00\", \"price_history_id\": 306}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":4,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"30.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"90.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":91}}]\\\"\", \"adjusted_price\": 2100000}', NULL, 'System đã cập nhật RoomPriceHistory #306', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-17T07:44:24.582287Z\"}', 0, '2025-08-17 07:44:24'),
(71, NULL, 'ylLjEmD7q3YxGATJKzKwmOflDDljiadsorkTyCRJ', 'update', 'RoomPriceHistory', 308, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"1000000.00\", \"room_type_id\": 4, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":84}}]\", \"adjusted_price\": \"1370000.00\", \"price_history_id\": 308}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}},{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"dynamic\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"occupancy\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"7.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"occupancy_threshold\\\\\\\":\\\\\\\"70.00\\\\\\\",\\\\\\\"current_occupancy\\\\\\\":75}}]\\\"\"}', NULL, 'System đã cập nhật RoomPriceHistory #308', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-17T07:44:24.634298Z\"}', 0, '2025-08-17 07:44:24'),
(74, 1, 'FUjVmA6wSemLNmAzgDK3DMxH12D5zQZIlsWGF1WK', 'update', 'User', 2, '{\"id\": 2, \"name\": \"Nguyễn Anh Đức\", \"role\": \"guest\", \"email\": \"nguyenandhduc2909@gmail.com\", \"phone\": \"08221534422\", \"avatar\": null, \"address\": \"Thanh Hóa\", \"password\": \"eyJpdiI6IkNqbmw5QkNzL21hWFlUUFlwcHFYbkE9PSIsInZhbHVlIjoiV2lYbnZGWWtiTnhQQitULzFlTEhiYVk3SXJFQmNqbDZiZDA0UUpLOTQrMFZjdnBXVTl2WXhtSFdPNi9TOUVKcWNOdndvTUprVndXT1RVYXNNanJTdGc9PSIsIm1hYyI6IjgxYjUyMTEwNWIyZTBkZGUxNjk5YzdjMTMyYzY1YWIxOWRlYWEzMmRiZGZjMjQ0NDQzYzM2NmM2MzA4NGIzZmMiLCJ0YWciOiIifQ==\", \"google_id\": null, \"identity_code\": null, \"current_team_id\": null, \"two_factor_secret\": \"eyJpdiI6ImhvQXhHb3lCbEpxQmF0eHdudERidVE9PSIsInZhbHVlIjoibGdlWlU1MDAwcDNZVHBzQ2pTTGoyUT09IiwibWFjIjoiMGJlYmI3ZGFhZTZkYmVlM2JkYTdlYzM4ODE1ZDUwMDk4OGNiNGE3ZGUxNmM0M2I4MWMyY2JmZGMyMjE3OGYyZCIsInRhZyI6IiJ9\", \"profile_photo_path\": null, \"two_factor_confirmed_at\": null, \"two_factor_recovery_codes\": \"eyJpdiI6IkRHZkFoYjlYTjdQTDhSb1FkVWZxRHc9PSIsInZhbHVlIjoiYisxbEhJU25nNU54ZVJ0dmFuS09oUT09IiwibWFjIjoiNjI3ODBjNmU0YTI2MzQ1OTUyZjAyMDhjMWE3NTk1YWE3NjQ4NDJhYWFkMGQyYjU0YzY0MzMyOWExZDIyNDU1YiIsInRhZyI6IiJ9\"}', '{\"identity_code\": \"038205000957\"}', NULL, 'Nguyễn Anh Đức đã cập nhật User #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/staffs/update/2', 'PUT', '{\"route_name\": \"admin.users.staffs.update\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Admin\\\\StaffController@update\", \"action_timestamp\": \"2025-08-19T08:37:52.849215Z\"}', 1, '2025-08-19 08:37:52'),
(75, 1, 'FUjVmA6wSemLNmAzgDK3DMxH12D5zQZIlsWGF1WK', 'update', 'User', 2, '{\"id\": 2, \"name\": \"Nguyễn Anh Đức\", \"role\": \"guest\", \"email\": \"nguyenandhduc2909@gmail.com\", \"phone\": \"08221534422\", \"avatar\": null, \"address\": \"Thanh Hóa\", \"password\": \"eyJpdiI6ImF3T2REd215aENpZUw4VG9VUUJhU2c9PSIsInZhbHVlIjoiYjhnNnkvVXRHSUtWWFpLU3RaWkN0VUtpN2FNMWtCd1kyZm1BSEo5SEhMMWhEeFdlTnd4RUQwN3dJSEMxcm9FOHRTQlJsSFZMT0crK3BGL3JBS1JVSnc9PSIsIm1hYyI6ImU3ZmYyNDY4ZDdjYWIxYzJkMjcxNjFlOGVkNmNlZTk1OGUyZmI2MGRkYzcxNThhMzZjMmJmNGZlMTNhMjk1MTkiLCJ0YWciOiIifQ==\", \"google_id\": null, \"identity_code\": null, \"current_team_id\": null, \"two_factor_secret\": \"eyJpdiI6IjhDc29EZVBSSkR0MnlFV2RITTN5TFE9PSIsInZhbHVlIjoia3lqaXdpbUlnMXoyUm5lSHZ1bkhrZz09IiwibWFjIjoiY2Q4NzE0MGU0ZDYwODgzMzM3ZDFhOTBmZTcxZDExYzU5N2ZmZDJjZGQzZTAyODA2MThhN2IzNThmODA0YWExZSIsInRhZyI6IiJ9\", \"profile_photo_path\": null, \"two_factor_confirmed_at\": null, \"two_factor_recovery_codes\": \"eyJpdiI6IklrUDdNYmFiWEs4SmxmVXpHa2RIalE9PSIsInZhbHVlIjoiOWlQbzdoOEhGQTI4RGJJaFBsUTI1UT09IiwibWFjIjoiYTA0Y2RiOTIwMjVkMWY3Yjg4YWVkOTJkOWFmYjVhNGQzOWQ3YmNhZTFiYWI2ZjVmOWNiMmI1MGM2ZmEzNTVlMCIsInRhZyI6IiJ9\"}', '{\"identity_code\": \"038205000957\"}', NULL, 'Nguyễn Anh Đức đã cập nhật User #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/staffs/update/2', 'PUT', '{\"route_name\": \"admin.users.staffs.update\", \"model_class\": \"App\\\\Models\\\\User\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Admin\\\\StaffController@update\", \"action_timestamp\": \"2025-08-19T08:37:52.882650Z\"}', 1, '2025-08-19 08:37:52'),
(76, NULL, 'OnSJlhnLgJVUCljM5DhfvxPXgyN4Ph0QF8dHHuaC', 'create', 'RoomPriceHistory', 313, 'null', '{\"date\": \"2025-08-20 00:00:00\", \"base_price\": \"1500000.00\", \"created_at\": \"2025-08-19 17:01:09\", \"updated_at\": \"2025-08-19 17:01:09\", \"room_type_id\": 2, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1800000, \"price_history_id\": 313}', NULL, 'System đã tạo mới RoomPriceHistory #313', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/rooms?include=room_type', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\ReceptionController@getRooms\", \"action_timestamp\": \"2025-08-19T10:01:09.058620Z\"}', 0, '2025-08-19 10:01:09'),
(77, NULL, 'OnSJlhnLgJVUCljM5DhfvxPXgyN4Ph0QF8dHHuaC', 'create', 'RoomPriceHistory', 314, 'null', '{\"date\": \"2025-08-20 00:00:00\", \"base_price\": \"1000000.00\", \"created_at\": \"2025-08-19 17:01:09\", \"updated_at\": \"2025-08-19 17:01:09\", \"room_type_id\": 3, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1200000, \"price_history_id\": 314}', NULL, 'System đã tạo mới RoomPriceHistory #314', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/rooms?include=room_type', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\ReceptionController@getRooms\", \"action_timestamp\": \"2025-08-19T10:01:09.173160Z\"}', 0, '2025-08-19 10:01:09'),
(78, NULL, 'OnSJlhnLgJVUCljM5DhfvxPXgyN4Ph0QF8dHHuaC', 'create', 'RoomPriceHistory', 315, 'null', '{\"date\": \"2025-08-20 00:00:00\", \"base_price\": \"1000000.00\", \"created_at\": \"2025-08-19 17:01:09\", \"updated_at\": \"2025-08-19 17:01:09\", \"room_type_id\": 4, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1200000, \"price_history_id\": 315}', NULL, 'System đã tạo mới RoomPriceHistory #315', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/rooms?include=room_type', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\ReceptionController@getRooms\", \"action_timestamp\": \"2025-08-19T10:01:09.213603Z\"}', 0, '2025-08-19 10:01:09'),
(79, NULL, 'OnSJlhnLgJVUCljM5DhfvxPXgyN4Ph0QF8dHHuaC', 'create', 'RoomPriceHistory', 316, 'null', '{\"date\": \"2025-08-20 00:00:00\", \"base_price\": \"1000000.00\", \"created_at\": \"2025-08-19 17:01:09\", \"updated_at\": \"2025-08-19 17:01:09\", \"room_type_id\": 5, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1200000, \"price_history_id\": 316}', NULL, 'System đã tạo mới RoomPriceHistory #316', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/rooms?include=room_type', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\ReceptionController@getRooms\", \"action_timestamp\": \"2025-08-19T10:01:09.251229Z\"}', 0, '2025-08-19 10:01:09'),
(80, NULL, 'OnSJlhnLgJVUCljM5DhfvxPXgyN4Ph0QF8dHHuaC', 'create', 'RoomPriceHistory', 317, 'null', '{\"date\": \"2025-08-20 00:00:00\", \"base_price\": \"1000000.00\", \"created_at\": \"2025-08-19 17:01:09\", \"updated_at\": \"2025-08-19 17:01:09\", \"room_type_id\": 6, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1200000, \"price_history_id\": 317}', NULL, 'System đã tạo mới RoomPriceHistory #317', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/rooms?include=room_type', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\ReceptionController@getRooms\", \"action_timestamp\": \"2025-08-19T10:01:09.283514Z\"}', 0, '2025-08-19 10:01:09'),
(81, NULL, 'OnSJlhnLgJVUCljM5DhfvxPXgyN4Ph0QF8dHHuaC', 'create', 'RoomPriceHistory', 318, 'null', '{\"date\": \"2025-08-20 00:00:00\", \"base_price\": \"1000000.00\", \"created_at\": \"2025-08-19 17:01:09\", \"updated_at\": \"2025-08-19 17:01:09\", \"room_type_id\": 7, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1200000, \"price_history_id\": 318}', NULL, 'System đã tạo mới RoomPriceHistory #318', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/rooms?include=room_type', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\ReceptionController@getRooms\", \"action_timestamp\": \"2025-08-19T10:01:09.316641Z\"}', 0, '2025-08-19 10:01:09'),
(82, NULL, 'BuBP96iWaeT7pDGcMYWAwakFPcfocNb4XHoWPjae', 'update', 'Booking', 142, '{\"notes\": \"bich tuyen cute\", \"status\": \"Confirmed\", \"room_id\": null, \"user_id\": null, \"children\": null, \"quantity\": null, \"option_id\": null, \"booking_id\": 142, \"guest_name\": \"test\", \"guest_count\": 13, \"guest_email\": \"quyenjpn@gmail.com\", \"guest_phone\": \"0335920306\", \"booking_code\": \"LVS142120903\", \"children_age\": null, \"room_type_id\": 6, \"check_in_date\": \"2025-07-16T17:00:00.000000Z\", \"booking_source\": null, \"check_out_date\": \"2025-07-17T17:00:00.000000Z\", \"payment_policy\": null, \"total_price_vnd\": 8650000, \"is_document_verified\": 0}', '{\"notes\": \"bich tuyen cute\\nCheck-in completed at 2025-08-19 17:03:17\", \"status\": \"Operational\"}', NULL, 'System đã cập nhật Booking #142', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/checkin/booking/142/process', 'POST', '{\"route_name\": \"api.checkin.process\", \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\BookingCheckinController@processCheckin\", \"action_timestamp\": \"2025-08-19T10:03:17.810329Z\"}', 0, '2025-08-19 10:03:17'),
(83, NULL, 'yNZzIBhu5P1oc614gFDsyVZbvcZ3vgZEwojY5tVp', 'create', 'BookingService', 3, 'null', '{\"id\": 3, \"quantity\": 1, \"price_vnd\": \"2000000.00\", \"booking_id\": \"142\", \"service_id\": 8}', NULL, 'System đã tạo mới BookingService #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/bookings/142/services', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\BookingService\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\BookingCheckoutController@addBookingService\", \"action_timestamp\": \"2025-08-19T10:03:33.474850Z\"}', 0, '2025-08-19 10:03:33'),
(84, NULL, 'ZwSYrM6u4JvMk7KNlhRJTMhvmGRJeMOggUkIc6Si', 'update', 'BookingService', 3, '{\"id\": 3, \"quantity\": 1, \"price_vnd\": \"2000000.00\", \"booking_id\": 142, \"service_id\": 8}', '{\"quantity\": 2}', NULL, 'System đã cập nhật BookingService #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/bookings/142/services/8', 'PUT', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\BookingService\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\BookingCheckoutController@updateBookingService\", \"action_timestamp\": \"2025-08-19T10:03:42.283071Z\"}', 0, '2025-08-19 10:03:42'),
(85, NULL, '6bU2rq0CZO8BnbVKVDTqbmqEBXLWioT4VC9FqWI8', 'update', 'BookingService', 3, '{\"id\": 3, \"quantity\": 2, \"price_vnd\": \"2000000.00\", \"booking_id\": 142, \"service_id\": 8}', '{\"quantity\": 3}', NULL, 'System đã cập nhật BookingService #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/bookings/142/services', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\BookingService\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\BookingCheckoutController@addBookingService\", \"action_timestamp\": \"2025-08-19T10:04:41.310321Z\"}', 0, '2025-08-19 10:04:41'),
(86, NULL, 'bC0e5RBnDSemsg234r3LHk0ou0R68i9RumNBtqOM', 'update', 'Booking', 142, '{\"notes\": \"bich tuyen cute\\nCheck-in completed at 2025-08-19 17:03:17\", \"status\": \"Operational\", \"room_id\": null, \"user_id\": null, \"children\": null, \"quantity\": null, \"option_id\": null, \"booking_id\": 142, \"guest_name\": \"test\", \"guest_count\": 13, \"guest_email\": \"quyenjpn@gmail.com\", \"guest_phone\": \"0335920306\", \"booking_code\": \"LVS142120903\", \"children_age\": null, \"room_type_id\": 6, \"check_in_date\": \"2025-07-16T17:00:00.000000Z\", \"booking_source\": null, \"check_out_date\": \"2025-07-17T17:00:00.000000Z\", \"payment_policy\": null, \"total_price_vnd\": 8650000, \"is_document_verified\": 0}', '{\"notes\": \"bich tuyen cute\\nCheck-in completed at 2025-08-19 17:03:17\\nCheck-out completed at 2025-08-19 17:08:27\", \"status\": \"Cleaning\", \"total_price_vnd\": 14650000}', NULL, 'System đã cập nhật Booking #142', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/bookings/142/checkout', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\BookingCheckoutController@processCheckout\", \"action_timestamp\": \"2025-08-19T10:08:27.934324Z\"}', 0, '2025-08-19 10:08:27'),
(87, NULL, 'bC0e5RBnDSemsg234r3LHk0ou0R68i9RumNBtqOM', 'create', 'Invoice', 2, 'null', '{\"status\": \"Draft\", \"issued_at\": \"2025-08-19 17:08:27\", \"booking_id\": 142, \"invoice_id\": 2, \"total_amount_vnd\": 14650000}', NULL, 'System đã tạo mới Invoice #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/reception/bookings/142/checkout', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Invoice\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\BookingCheckoutController@processCheckout\", \"action_timestamp\": \"2025-08-19T10:08:27.955881Z\"}', 0, '2025-08-19 10:08:27'),
(88, NULL, 'MF8uPEXWD8Y9i7HA8ElDarkxylrFD46RIGPdomoj', 'update', 'RoomPriceHistory', 302, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"50000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":1,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":84}}]\", \"adjusted_price\": \"70000.00\", \"price_history_id\": 302}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 65000}', NULL, 'System đã cập nhật RoomPriceHistory #302', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/rooms/available?check_in_date=2025-08-17&check_out_date=2025-08-18', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailableRooms\", \"action_timestamp\": \"2025-08-19T10:09:37.488115Z\"}', 0, '2025-08-19 10:09:37'),
(89, NULL, 'MF8uPEXWD8Y9i7HA8ElDarkxylrFD46RIGPdomoj', 'update', 'RoomPriceHistory', 306, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"1500000.00\", \"room_type_id\": 2, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":4,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"30.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"90.00\\\",\\\"current_occupancy\\\":91}}]\", \"adjusted_price\": \"2100000.00\", \"price_history_id\": 306}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1950000}', NULL, 'System đã cập nhật RoomPriceHistory #306', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/rooms/available?check_in_date=2025-08-17&check_out_date=2025-08-18', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailableRooms\", \"action_timestamp\": \"2025-08-19T10:09:37.552247Z\"}', 0, '2025-08-19 10:09:37'),
(90, NULL, 'MF8uPEXWD8Y9i7HA8ElDarkxylrFD46RIGPdomoj', 'update', 'RoomPriceHistory', 308, '{\"date\": \"2025-08-16T17:00:00.000000Z\", \"base_price\": \"1000000.00\", \"room_type_id\": 4, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":75}}]\", \"adjusted_price\": \"1370000.00\", \"price_history_id\": 308}', '{\"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}},{\\\\\\\"rule_id\\\\\\\":12,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"10.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"weekend\\\\\\\",\\\\\\\"days_of_week\\\\\\\":[\\\\\\\"Saturday\\\\\\\",\\\\\\\"Sunday\\\\\\\"]}}]\\\"\", \"adjusted_price\": 1300000}', NULL, 'System đã cập nhật RoomPriceHistory #308', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/rooms/available?check_in_date=2025-08-17&check_out_date=2025-08-18', 'GET', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailableRooms\", \"action_timestamp\": \"2025-08-19T10:09:37.619552Z\"}', 0, '2025-08-19 10:09:37');
INSERT INTO `audit_logs` (`audit_id`, `user_id`, `session_id`, `action`, `model`, `model_id`, `old_values`, `new_values`, `changes_summary`, `description`, `ip_address`, `user_agent`, `url`, `method`, `metadata`, `is_sensitive`, `created_at`) VALUES
(91, NULL, 'UogoWeG1FG6ceZdHs47xR9geUeAe23T9sr95ufAd', 'create', 'RoomPriceHistory', 319, 'null', '{\"date\": \"2025-08-19 00:00:00\", \"base_price\": \"1500000\", \"created_at\": \"2025-08-19 17:10:01\", \"updated_at\": \"2025-08-19 17:10:01\", \"room_type_id\": 2, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1800000, \"price_history_id\": 319}', NULL, 'System đã tạo mới RoomPriceHistory #319', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-19T10:10:01.899642Z\"}', 0, '2025-08-19 10:10:01'),
(92, NULL, 'UogoWeG1FG6ceZdHs47xR9geUeAe23T9sr95ufAd', 'create', 'RoomPriceHistory', 320, 'null', '{\"date\": \"2025-08-19 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-19 17:10:01\", \"updated_at\": \"2025-08-19 17:10:01\", \"room_type_id\": 3, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1200000, \"price_history_id\": 320}', NULL, 'System đã tạo mới RoomPriceHistory #320', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-19T10:10:01.944763Z\"}', 0, '2025-08-19 10:10:01'),
(93, NULL, 'UogoWeG1FG6ceZdHs47xR9geUeAe23T9sr95ufAd', 'create', 'RoomPriceHistory', 321, 'null', '{\"date\": \"2025-08-19 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-19 17:10:01\", \"updated_at\": \"2025-08-19 17:10:01\", \"room_type_id\": 4, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1200000, \"price_history_id\": 321}', NULL, 'System đã tạo mới RoomPriceHistory #321', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-19T10:10:01.970657Z\"}', 0, '2025-08-19 10:10:01'),
(94, NULL, 'UogoWeG1FG6ceZdHs47xR9geUeAe23T9sr95ufAd', 'create', 'RoomPriceHistory', 322, 'null', '{\"date\": \"2025-08-19 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-19 17:10:01\", \"updated_at\": \"2025-08-19 17:10:01\", \"room_type_id\": 5, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1200000, \"price_history_id\": 322}', NULL, 'System đã tạo mới RoomPriceHistory #322', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-19T10:10:01.997098Z\"}', 0, '2025-08-19 10:10:01'),
(95, NULL, 'UogoWeG1FG6ceZdHs47xR9geUeAe23T9sr95ufAd', 'create', 'RoomPriceHistory', 323, 'null', '{\"date\": \"2025-08-19 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-19 17:10:02\", \"updated_at\": \"2025-08-19 17:10:02\", \"room_type_id\": 6, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1200000, \"price_history_id\": 323}', NULL, 'System đã tạo mới RoomPriceHistory #323', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-19T10:10:02.022270Z\"}', 0, '2025-08-19 10:10:02'),
(96, NULL, 'UogoWeG1FG6ceZdHs47xR9geUeAe23T9sr95ufAd', 'create', 'RoomPriceHistory', 324, 'null', '{\"date\": \"2025-08-19 00:00:00\", \"base_price\": \"1000000\", \"created_at\": \"2025-08-19 17:10:02\", \"updated_at\": \"2025-08-19 17:10:02\", \"room_type_id\": 7, \"applied_rules\": \"\\\"[{\\\\\\\"rule_id\\\\\\\":5,\\\\\\\"type\\\\\\\":\\\\\\\"flexible\\\\\\\",\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"price_adjustment\\\\\\\":\\\\\\\"20.00\\\\\\\",\\\\\\\"details\\\\\\\":{\\\\\\\"rule_type\\\\\\\":\\\\\\\"season\\\\\\\",\\\\\\\"season_name\\\\\\\":\\\\\\\"M\\\\\\\\u00f9a cao \\\\\\\\u0111i\\\\\\\\u1ec3m\\\\\\\",\\\\\\\"season_dates\\\\\\\":{\\\\\\\"start_date\\\\\\\":\\\\\\\"2025-05-31T17:00:00.000000Z\\\\\\\",\\\\\\\"end_date\\\\\\\":\\\\\\\"2025-08-30T17:00:00.000000Z\\\\\\\"}}}]\\\"\", \"adjusted_price\": 1200000, \"price_history_id\": 324}', NULL, 'System đã tạo mới RoomPriceHistory #324', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-19T10:10:02.046961Z\"}', 0, '2025-08-19 10:10:02'),
(97, NULL, 'e6sTyWICvW6ZEAhF4NhNd54ZYgYPtRfv4qr2C0rd', 'update', 'RoomPriceHistory', 303, '{\"date\": \"2025-08-18T17:00:00.000000Z\", \"base_price\": \"50000.00\", \"room_type_id\": 1, \"applied_rules\": \"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\", \"adjusted_price\": \"60000.00\", \"price_history_id\": 303}', '{\"base_price\": \"1000\", \"adjusted_price\": 1200}', NULL, 'System đã cập nhật RoomPriceHistory #303', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/room-packages/search', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomPriceHistory\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\Api\\\\RoomAvailabilityController@getAvailablePackages\", \"action_timestamp\": \"2025-08-19T10:10:42.187794Z\"}', 0, '2025-08-19 10:10:42'),
(98, NULL, 'fdQ0eBLKyoZf3rXCOqWjEqdWZAGHRyPiguukde0f', 'create', 'Booking', 174, 'null', '{\"notes\": \"Có sân pickerball\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 174, \"created_at\": \"2025-08-19 17:12:49\", \"guest_name\": \"Nguyễn Anh Đức\", \"updated_at\": \"2025-08-19 17:12:49\", \"guest_count\": 2, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-19 00:00:00\", \"check_out_date\": \"2025-08-20 00:00:00\", \"total_price_vnd\": 6200}', NULL, 'System đã tạo mới Booking #174', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-19T10:12:49.766163Z\"}', 0, '2025-08-19 10:12:49'),
(99, NULL, 'fdQ0eBLKyoZf3rXCOqWjEqdWZAGHRyPiguukde0f', 'update', 'Booking', 174, '{\"notes\": \"Có sân pickerball\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 174, \"guest_name\": \"Nguyễn Anh Đức\", \"guest_count\": 2, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-18T17:00:00.000000Z\", \"check_out_date\": \"2025-08-19T17:00:00.000000Z\", \"total_price_vnd\": 6200}', '{\"booking_code\": \"LVS174171249\"}', NULL, 'System đã cập nhật Booking #174', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-19T10:12:49.790279Z\"}', 0, '2025-08-19 10:12:49'),
(100, NULL, 'fdQ0eBLKyoZf3rXCOqWjEqdWZAGHRyPiguukde0f', 'create', 'Payment', 137, 'null', '{\"status\": \"pending\", \"amount_vnd\": 6200, \"booking_id\": 174, \"created_at\": \"2025-08-19 17:12:49\", \"payment_id\": 137, \"updated_at\": \"2025-08-19 17:12:49\", \"payment_type\": \"vietqr\"}', NULL, 'System đã tạo mới Payment #137', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Payment\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-19T10:12:49.797087Z\"}', 0, '2025-08-19 10:12:49'),
(101, 1, 'FUjVmA6wSemLNmAzgDK3DMxH12D5zQZIlsWGF1WK', 'update', 'PaymentSetting', 2, '{\"id\": 2, \"key\": \"vietqr.account_no\", \"type\": \"string\", \"value\": \"0335920306\", \"is_active\": true, \"group_name\": \"vietqr\", \"description\": \"Số tài khoản ngân hàng\", \"is_encrypted\": false}', '{\"value\": \"19290920058383\"}', NULL, 'Nguyễn Anh Đức đã cập nhật PaymentSetting #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/payment/setting', 'PUT', '{\"route_name\": \"admin.payment.setting.update\", \"model_class\": \"App\\\\Models\\\\PaymentSetting\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@updateSettings\", \"action_timestamp\": \"2025-08-19T10:13:56.239361Z\"}', 0, '2025-08-19 10:13:56'),
(102, 1, 'FUjVmA6wSemLNmAzgDK3DMxH12D5zQZIlsWGF1WK', 'update', 'PaymentSetting', 3, '{\"id\": 3, \"key\": \"vietqr.account_name\", \"type\": \"string\", \"value\": \"NGUYEN VAN QUYEN\", \"is_active\": true, \"group_name\": \"vietqr\", \"description\": \"Tên chủ tài khoản\", \"is_encrypted\": false}', '{\"value\": \"NGUYEN ANH DUC\"}', NULL, 'Nguyễn Anh Đức đã cập nhật PaymentSetting #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/payment/setting', 'PUT', '{\"route_name\": \"admin.payment.setting.update\", \"model_class\": \"App\\\\Models\\\\PaymentSetting\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@updateSettings\", \"action_timestamp\": \"2025-08-19T10:13:56.270714Z\"}', 0, '2025-08-19 10:13:56'),
(103, NULL, '28vULZpWqAXIESG7UHQ55vOCfohZYWNYXtc7xknl', 'create', 'Booking', 175, 'null', '{\"notes\": \"d\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 175, \"created_at\": \"2025-08-19 17:14:04\", \"guest_name\": \"Nguyễn Anh Đức\", \"updated_at\": \"2025-08-19 17:14:04\", \"guest_count\": 2, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-19 00:00:00\", \"check_out_date\": \"2025-08-20 00:00:00\", \"total_price_vnd\": 6200}', NULL, 'System đã tạo mới Booking #175', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-19T10:14:04.914666Z\"}', 0, '2025-08-19 10:14:04'),
(104, NULL, '28vULZpWqAXIESG7UHQ55vOCfohZYWNYXtc7xknl', 'update', 'Booking', 175, '{\"notes\": \"d\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 175, \"guest_name\": \"Nguyễn Anh Đức\", \"guest_count\": 2, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-18T17:00:00.000000Z\", \"check_out_date\": \"2025-08-19T17:00:00.000000Z\", \"total_price_vnd\": 6200}', '{\"booking_code\": \"LVS175171404\"}', NULL, 'System đã cập nhật Booking #175', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-19T10:14:04.941910Z\"}', 0, '2025-08-19 10:14:04'),
(105, NULL, '28vULZpWqAXIESG7UHQ55vOCfohZYWNYXtc7xknl', 'create', 'Payment', 138, 'null', '{\"status\": \"pending\", \"amount_vnd\": 6200, \"booking_id\": 175, \"created_at\": \"2025-08-19 17:14:04\", \"payment_id\": 138, \"updated_at\": \"2025-08-19 17:14:04\", \"payment_type\": \"vietqr\"}', NULL, 'System đã tạo mới Payment #138', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Payment\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-19T10:14:04.947527Z\"}', 0, '2025-08-19 10:14:04'),
(106, 1, 'FUjVmA6wSemLNmAzgDK3DMxH12D5zQZIlsWGF1WK', 'update', 'PaymentSetting', 2, '{\"id\": 2, \"key\": \"vietqr.account_no\", \"type\": \"string\", \"value\": \"19290920058383\", \"is_active\": true, \"group_name\": \"vietqr\", \"description\": \"Số tài khoản ngân hàng\", \"is_encrypted\": false}', '{\"value\": \"0335920306\"}', NULL, 'Nguyễn Anh Đức đã cập nhật PaymentSetting #2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/payment/setting/reset', 'GET', '{\"route_name\": \"admin.payment.setting.reset\", \"model_class\": \"App\\\\Models\\\\PaymentSetting\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@resetToDefaultsFromAdmin\", \"action_timestamp\": \"2025-08-19T10:14:18.108237Z\"}', 0, '2025-08-19 10:14:18'),
(107, 1, 'FUjVmA6wSemLNmAzgDK3DMxH12D5zQZIlsWGF1WK', 'update', 'PaymentSetting', 3, '{\"id\": 3, \"key\": \"vietqr.account_name\", \"type\": \"string\", \"value\": \"NGUYEN ANH DUC\", \"is_active\": true, \"group_name\": \"vietqr\", \"description\": \"Tên chủ tài khoản\", \"is_encrypted\": false}', '{\"value\": \"NGUYEN VAN QUYEN\"}', NULL, 'Nguyễn Anh Đức đã cập nhật PaymentSetting #3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/admin/payment/setting/reset', 'GET', '{\"route_name\": \"admin.payment.setting.reset\", \"model_class\": \"App\\\\Models\\\\PaymentSetting\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@resetToDefaultsFromAdmin\", \"action_timestamp\": \"2025-08-19T10:14:18.143251Z\"}', 0, '2025-08-19 10:14:18'),
(108, NULL, 'vj0JiqgY7LAdvuyHU35sCgBKnmRt0QgvqSSVtWoU', 'create', 'Booking', 176, 'null', '{\"notes\": \"Pickerball\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 176, \"created_at\": \"2025-08-19 17:14:50\", \"guest_name\": \"Nguyễn Anh Đức\", \"updated_at\": \"2025-08-19 17:14:50\", \"guest_count\": 2, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-19 00:00:00\", \"check_out_date\": \"2025-08-20 00:00:00\", \"total_price_vnd\": 6200}', NULL, 'System đã tạo mới Booking #176', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-19T10:14:50.912892Z\"}', 0, '2025-08-19 10:14:50'),
(109, NULL, 'vj0JiqgY7LAdvuyHU35sCgBKnmRt0QgvqSSVtWoU', 'update', 'Booking', 176, '{\"notes\": \"Pickerball\", \"status\": \"pending\", \"room_id\": null, \"user_id\": 1, \"option_id\": null, \"booking_id\": 176, \"guest_name\": \"Nguyễn Anh Đức\", \"guest_count\": 2, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"\", \"room_type_id\": 1, \"check_in_date\": \"2025-08-18T17:00:00.000000Z\", \"check_out_date\": \"2025-08-19T17:00:00.000000Z\", \"total_price_vnd\": 6200}', '{\"booking_code\": \"LVS176171450\"}', NULL, 'System đã cập nhật Booking #176', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-19T10:14:50.940621Z\"}', 0, '2025-08-19 10:14:50'),
(110, NULL, 'vj0JiqgY7LAdvuyHU35sCgBKnmRt0QgvqSSVtWoU', 'create', 'Payment', 139, 'null', '{\"status\": \"pending\", \"amount_vnd\": 6200, \"booking_id\": 176, \"created_at\": \"2025-08-19 17:14:50\", \"payment_id\": 139, \"updated_at\": \"2025-08-19 17:14:50\", \"payment_type\": \"vietqr\"}', NULL, 'System đã tạo mới Payment #139', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/create-booking', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Payment\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@createBooking\", \"action_timestamp\": \"2025-08-19T10:14:50.945148Z\"}', 0, '2025-08-19 10:14:50'),
(111, NULL, 'MRU9HrLGpIW8U1MeFhfveNfMR7Af3HkWv33wEawj', 'update', 'Booking', 176, '{\"notes\": \"Pickerball\", \"status\": \"Pending\", \"room_id\": null, \"user_id\": 1, \"children\": null, \"quantity\": null, \"option_id\": null, \"booking_id\": 176, \"guest_name\": \"Nguyễn Anh Đức\", \"guest_count\": 2, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"LVS176171450\", \"children_age\": null, \"room_type_id\": 1, \"check_in_date\": \"2025-08-18T17:00:00.000000Z\", \"booking_source\": null, \"check_out_date\": \"2025-08-19T17:00:00.000000Z\", \"payment_policy\": null, \"total_price_vnd\": 6200, \"is_document_verified\": 0}', '{\"option_id\": \"BOOK-LVS176171450\"}', NULL, 'System đã cập nhật Booking #176', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/check-cpay', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@checkCPayPayment\", \"action_timestamp\": \"2025-08-19T10:17:23.444328Z\"}', 0, '2025-08-19 10:17:23'),
(112, NULL, 'MRU9HrLGpIW8U1MeFhfveNfMR7Af3HkWv33wEawj', 'create', 'RoomOccupancy', 25, 'null', '{\"date\": \"2025-08-19 00:00:00\", \"created_at\": \"2025-08-19 17:17:23\", \"updated_at\": \"2025-08-19 17:17:23\", \"total_rooms\": 90, \"booked_rooms\": 0, \"occupancy_id\": 25, \"room_type_id\": 1}', NULL, 'System đã tạo mới RoomOccupancy #25', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/check-cpay', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\RoomOccupancy\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@checkCPayPayment\", \"action_timestamp\": \"2025-08-19T10:17:23.476760Z\"}', 0, '2025-08-19 10:17:23'),
(113, NULL, 'MRU9HrLGpIW8U1MeFhfveNfMR7Af3HkWv33wEawj', 'update', 'Booking', 176, '{\"notes\": \"Pickerball\", \"status\": \"Pending\", \"room_id\": null, \"user_id\": 1, \"children\": null, \"quantity\": null, \"option_id\": null, \"booking_id\": 176, \"guest_name\": \"Nguyễn Anh Đức\", \"guest_count\": 2, \"guest_email\": \"nguyenanhduc2909@gmail.com\", \"guest_phone\": \"08221534477\", \"booking_code\": \"LVS176171450\", \"children_age\": null, \"room_type_id\": 1, \"check_in_date\": \"2025-08-18T17:00:00.000000Z\", \"booking_source\": null, \"check_out_date\": \"2025-08-19T17:00:00.000000Z\", \"payment_policy\": null, \"total_price_vnd\": 6200, \"is_document_verified\": 0}', '{\"status\": \"confirmed\"}', NULL, 'System đã cập nhật Booking #176', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/check-cpay', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Booking\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@checkCPayPayment\", \"action_timestamp\": \"2025-08-19T10:17:27.593919Z\"}', 0, '2025-08-19 10:17:27'),
(114, NULL, 'MRU9HrLGpIW8U1MeFhfveNfMR7Af3HkWv33wEawj', 'update', 'Payment', 139, '{\"status\": \"pending\", \"amount_vnd\": \"6200.00\", \"booking_id\": 176, \"payment_id\": 139, \"payment_type\": \"vietqr\", \"transaction_id\": null}', '{\"status\": \"completed\", \"transaction_id\": \"CPAY_LVS176171450_1755598643\"}', NULL, 'System đã cập nhật Payment #139', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'http://localhost:8888/api/payment/check-cpay', 'POST', '{\"route_name\": null, \"model_class\": \"App\\\\Models\\\\Payment\", \"route_action\": \"App\\\\Http\\\\Controllers\\\\PaymentController@checkCPayPayment\", \"action_timestamp\": \"2025-08-19T10:17:27.598084Z\"}', 0, '2025-08-19 10:17:27');

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
(1, 'King', '1 giường cực lớn', 1, '2025-06-10 00:16:30', '2025-08-06 07:19:38'),
(2, 'Twin', '2 giường đơn', 1, '2025-06-26 03:22:13', '2025-06-26 03:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
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
  `status` enum('Pending','Confirmed','Operational','Completed','Cancelled','Cancelled With Penalty','Unsuccessful','Cleaning') COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Trạng thái đặt phòng, bao gồm Cleaning để biểu thị phòng đang dọn dẹp',
  `booking_source` enum('website','phone','walk_in','agent','online_travel_agency') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Nguồn đặt',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `quantity` int DEFAULT NULL,
  `payment_policy` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_type_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  `guest_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tên khách',
  `guest_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Email khách',
  `guest_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số điện thoại khách',
  `room_id` int DEFAULT NULL,
  `children` int DEFAULT NULL,
  `children_age` json DEFAULT NULL,
  `is_document_verified` tinyint DEFAULT '0' COMMENT 'Xác nhận giấy tờ của khách (0: chưa xác nhận, 1: đã xác nhận)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin đặt phòng';

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `booking_code`, `user_id`, `option_id`, `check_in_date`, `check_out_date`, `total_price_vnd`, `guest_count`, `status`, `booking_source`, `notes`, `quantity`, `payment_policy`, `room_type_id`, `created_at`, `updated_at`, `guest_name`, `guest_email`, `guest_phone`, `room_id`, `children`, `children_age`, `is_document_verified`) VALUES
(23, 'LAVISHSTAY_509999', NULL, NULL, '2025-07-01', '2025-07-02', 2400000.00, 2, 'Cancelled', NULL, '', 2, NULL, NULL, '2025-07-01 04:08:52', '2025-07-04 02:26:20', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', 255, NULL, NULL, 0),
(24, 'LAVISHYSTAY_931923', 2, 'OPT10', '2025-08-04', '2025-08-15', 2900000.00, 2, 'Cleaning', NULL, 'Check-in completed at 2025-08-05 19:39:26\nCheck-out completed at 2025-08-16 17:05:05', 3, NULL, 2, '2025-07-04 03:41:38', '2025-08-16 10:05:05', 'húhu', 'quyen@gmai.comđ', '231443342423', 2, 2, '3', 0),
(25, 'LVS20250707030928246', NULL, NULL, '2025-07-07', '2025-07-08', 2880000.00, 2, 'Confirmed', NULL, '', 1, NULL, NULL, '2025-07-06 20:09:28', '2025-07-06 20:09:28', 'qeweqw', 'reception@hotel.com', '0335920306', NULL, 1, '4', 0),
(26, 'LVS20250707031018433', NULL, NULL, '2025-07-07', '2025-07-11', 5760000.00, 2, 'Confirmed', NULL, '', 1, NULL, NULL, '2025-07-06 20:10:18', '2025-07-06 20:10:18', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', NULL, 1, '4', 0),
(27, 'LVS20250707031110789', NULL, NULL, '2025-07-07', '2025-07-11', 5760000.00, 2, 'Confirmed', NULL, '', 1, NULL, NULL, '2025-07-06 20:11:10', '2025-07-06 20:11:10', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', NULL, 1, '10', 0),
(31, 'LVS31050405', NULL, NULL, '2025-07-09', '2025-07-10', 2000000.00, 2, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:04:05', '2025-07-07 22:04:05', 'Nguyen Van Test', 'test@email.com', '0123456789', NULL, 0, NULL, 0),
(32, 'LVS32050513', NULL, NULL, '2025-07-09', '2025-07-10', 2000000.00, 2, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:05:13', '2025-07-07 22:05:13', 'Nguyen Van Test', 'test@email.com', '0123456789', NULL, 0, NULL, 0),
(33, 'LVS33050538', NULL, NULL, '2025-07-09', '2025-07-10', 2000000.00, 2, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:05:38', '2025-07-07 22:05:38', 'Nguyen Van Test', 'test@email.com', '0123456789', NULL, 0, NULL, 0),
(34, 'LVS34050642', NULL, NULL, '2025-07-07', '2025-07-08', 3200000.00, 3, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:06:42', '2025-07-07 22:06:42', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(35, 'LVS35050702', NULL, NULL, '2025-07-07', '2025-07-08', 3200000.00, 3, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:07:02', '2025-07-07 22:07:02', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(36, 'LVS36050814', NULL, NULL, '2025-07-07', '2025-07-08', 3200000.00, 3, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-07 22:08:14', '2025-07-07 22:08:14', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(37, 'LVS37051044', NULL, NULL, '2025-07-07', '2025-07-08', 2300000.00, 3, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-07 22:10:44', '2025-07-07 22:10:44', 'qeweqw', 'quyenjpn@gmail.com', '0335920306', NULL, 0, NULL, 0),
(38, 'LVS38051120', NULL, NULL, '2025-07-07', '2025-07-08', 2300000.00, 3, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-07 22:11:20', '2025-07-07 22:11:20', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(39, 'LVS39063958', NULL, NULL, '2025-07-07', '2025-07-08', 6200000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-07 23:39:57', '2025-07-07 23:39:58', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(40, 'LVS40064052', NULL, NULL, '2025-07-07', '2025-07-08', 6200000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-07 23:40:52', '2025-07-07 23:40:52', 'qeweqw', 'reception@hotel.com', '0987654321', NULL, 0, NULL, 0),
(41, 'LVS41073901', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:39:01', '2025-07-08 00:39:01', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(42, 'LVS42074259', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:42:59', '2025-07-08 00:42:59', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(43, 'LVS43074325', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:43:25', '2025-07-08 00:43:25', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(44, 'LVS44074700', NULL, NULL, '2025-07-07', '2025-07-08', 7320000.00, 7, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:47:00', '2025-07-08 00:47:00', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(45, 'LVS45075351', NULL, NULL, '2025-07-07', '2025-07-08', 8640000.00, 13, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:53:51', '2025-07-08 00:53:51', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(46, 'LVS46075512', NULL, NULL, '2025-07-07', '2025-07-08', 1440000.00, 6, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-08 00:55:12', '2025-07-08 00:55:12', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(51, 'LVS51083510', NULL, NULL, '2025-07-07', '2025-07-08', 2880000.00, 11, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 01:35:10', '2025-07-08 01:35:10', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[]', 0),
(53, 'LVS53093059', NULL, NULL, '2025-07-09', '2025-07-10', 4320000.00, 13, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 02:30:59', '2025-07-08 02:30:59', 'Nguyen Van Test', 'test@gmail.com', '0987654321', NULL, 5, '[]', 0),
(54, 'LVS54093118', NULL, NULL, '2025-07-09', '2025-07-10', 1200000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 02:31:18', '2025-07-08 02:31:18', 'Test User', 'test@test.com', '0123456789', NULL, 0, '[]', 0),
(56, 'LVS56094825', NULL, NULL, '2025-07-09', '2025-07-10', 1200000.00, 5, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 02:48:25', '2025-07-08 02:48:25', 'Test User Full', 'test@test.com', '0123456789', NULL, 3, '[[{\"age\": 8}, {\"age\": 10}, {\"age\": 5}]]', 0),
(60, 'LVS60104819', NULL, NULL, '2025-07-07', '2025-07-08', 4320000.00, 13, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 03:48:19', '2025-07-08 03:48:19', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[[{\"id\": \"room_0_child_1\", \"age\": 8}, {\"id\": \"room_0_child_2\", \"age\": 8}, {\"id\": \"room_0_child_3\", \"age\": 8}], [{\"id\": \"room_1_child_1\", \"age\": 8}, {\"id\": \"room_1_child_2\", \"age\": 8}], []]', 0),
(61, 'LVS61105534', NULL, NULL, '2025-07-07', '2025-07-08', 4320000.00, 13, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 03:55:34', '2025-07-08 03:55:34', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[[{\"id\": \"room_0_child_1\", \"age\": 8}, {\"id\": \"room_0_child_2\", \"age\": 8}, {\"id\": \"room_0_child_3\", \"age\": 8}], [{\"id\": \"room_1_child_1\", \"age\": 8}, {\"id\": \"room_1_child_2\", \"age\": 8}], []]', 0),
(62, 'LVS62153758', NULL, NULL, '2025-07-07', '2025-07-08', 4320000.00, 13, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 08:37:58', '2025-07-08 08:37:58', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 5, '[[{\"id\": \"room_0_child_1\", \"age\": 8}, {\"id\": \"room_0_child_2\", \"age\": 8}, {\"id\": \"room_0_child_3\", \"age\": 8}], [{\"id\": \"room_1_child_1\", \"age\": 8}, {\"id\": \"room_1_child_2\", \"age\": 8}], []]', 0),
(63, 'LVS63162115', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 09:21:15', '2025-07-08 09:45:21', 'Huỳnh Thị Bích Tuyền', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '[[]]', 0),
(64, 'LVS64164554', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 09:45:54', '2025-07-08 09:46:51', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '[[]]', 0),
(65, 'LVS65165011', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 09:50:11', '2025-07-08 09:50:54', 'qeweqw', 'quyenjpn@gmail.com', '333241324342', NULL, 0, '[[]]', 0),
(66, 'LVS66165335', NULL, NULL, '2025-07-07', '2025-07-08', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 09:53:35', '2025-07-08 09:54:06', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '[[]]', 0),
(67, 'LVS67031840', NULL, NULL, '2025-07-08', '2025-07-09', 11000.00, 1, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 20:18:40', '2025-07-08 20:19:17', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '[[]]', 0),
(68, 'LVS68032045', NULL, NULL, '2025-07-08', '2025-07-10', 22000.00, 3, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-08 20:20:45', '2025-07-08 20:22:08', 'qeweqw', 'quyenjpn@gmail.com', '0987654321', NULL, 1, '[[{\"id\": \"room_0_child_1\", \"age\": 8}]]', 0),
(69, 'LVS69050107', NULL, NULL, '2025-07-08', '2025-07-11', 33000.00, 3, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-08 22:01:07', '2025-07-08 22:01:07', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 1, '[[{\"id\": \"room_0_child_1\", \"age\": 8}]]', 0),
(75, 'LVS75070930', NULL, NULL, '2025-07-08', '2025-07-09', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-09 00:09:30', '2025-07-09 00:09:30', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 1, '[[{\"id\": \"room_0_child_1\", \"age\": 8}]]', 0),
(76, 'LVS76073559', NULL, NULL, '2025-07-08', '2025-07-09', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-09 00:35:59', '2025-07-09 00:35:59', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 1, '[[{\"id\": \"room_0_child_1\", \"age\": 8}]]', 0),
(77, 'LVS77082516', NULL, NULL, '2025-07-08', '2025-07-09', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-09 01:25:16', '2025-07-09 01:25:16', '明心', 'quyenjpn@gmail.com', '12342342341', NULL, 1, '[[{\"id\": \"room_0_child_1\", \"age\": 8}]]', 0),
(79, 'LVS79072153', NULL, NULL, '2025-07-13', '2025-07-14', 6200000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 00:21:53', '2025-07-14 00:21:53', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(80, 'LVS80072418', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-14 00:24:18', '2025-07-14 00:26:13', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(81, 'LVS81091621', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:16:21', '2025-07-14 02:17:03', '明心', 'quyenjpn@gmail.com', '123412341234', NULL, 0, '{\"totals\": {\"nights\": 1, \"taxAmount\": 0, \"finalTotal\": 11000, \"roomsTotal\": 11000, \"serviceFee\": 0, \"breakfastTotal\": 0, \"discountAmount\": 0}, \"rooms_data\": [{\"adults\": 2, \"room_id\": \"1\", \"bed_type\": null, \"children\": 0, \"policies\": {\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}, \"meal_type\": null, \"option_id\": \"pkg-1\", \"guest_name\": \"明心\", \"package_id\": \"1\", \"room_price\": 11000, \"guest_email\": \"quyenjpn@gmail.com\", \"guest_phone\": \"123412341234\", \"option_name\": \"Standard Package\", \"recommended\": 1, \"children_age\": [], \"most_popular\": 0, \"option_price\": 11000, \"payment_policy\": \"Đặt cọc 30% giá trị booking\", \"urgency_message\": null, \"check_out_policy\": \"Check-out tiêu chuẩn 12:00\", \"deposit_percentage\": \"30.00\", \"penalty_percentage\": \"0.00\", \"cancellation_policy\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"deposit_fixed_amount\": \"0.00\", \"penalty_fixed_amount\": \"200000.00\", \"recommendation_score\": null, \"free_cancellation_days\": 7, \"standard_check_out_time\": \"12:00:00\"}], \"payment_method\": \"vietqr\"}', 0),
(82, 'LVS82092729', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:27:29', '2025-07-14 02:27:29', '明心', 'quyenjpn@gmail.com', '123412342', NULL, 0, '{\"totals\": {\"nights\": 1, \"taxAmount\": 0, \"finalTotal\": 11000, \"roomsTotal\": 11000, \"serviceFee\": 0, \"breakfastTotal\": 0, \"discountAmount\": 0}, \"rooms_data\": [{\"adults\": 2, \"room_id\": \"1\", \"bed_type\": null, \"children\": 0, \"policies\": {\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}, \"meal_type\": null, \"option_id\": \"pkg-1\", \"guest_name\": \"明心\", \"package_id\": \"1\", \"room_price\": 11000, \"guest_email\": \"quyenjpn@gmail.com\", \"guest_phone\": \"123412342\", \"option_name\": \"Standard Package\", \"recommended\": 1, \"children_age\": [], \"most_popular\": 0, \"option_price\": 11000, \"payment_policy\": \"Đặt cọc 30% giá trị booking\", \"urgency_message\": null, \"check_out_policy\": \"Check-out tiêu chuẩn 12:00\", \"deposit_percentage\": \"30.00\", \"penalty_percentage\": \"0.00\", \"cancellation_policy\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"deposit_fixed_amount\": \"0.00\", \"penalty_fixed_amount\": \"200000.00\", \"recommendation_score\": null, \"free_cancellation_days\": 7, \"standard_check_out_time\": \"12:00:00\"}], \"payment_method\": \"vietqr\"}', 0),
(85, 'LVS85093648', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:36:48', '2025-07-14 02:36:48', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, '{\"totals\": {\"nights\": 1, \"taxAmount\": 0, \"finalTotal\": 11000, \"roomsTotal\": 11000, \"serviceFee\": 0, \"breakfastTotal\": 0, \"discountAmount\": 0}, \"rooms_data\": [{\"adults\": 2, \"room_id\": \"1\", \"bed_type\": null, \"children\": 0, \"policies\": {\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}, \"meal_type\": null, \"option_id\": \"pkg-1\", \"guest_name\": \"明心\", \"package_id\": \"1\", \"room_price\": 11000, \"guest_email\": \"quyenjpn@gmail.com\", \"guest_phone\": \"0987654321\", \"option_name\": \"Standard Package\", \"recommended\": 1, \"children_age\": [], \"most_popular\": 0, \"option_price\": 11000, \"payment_policy\": \"Đặt cọc 30% giá trị booking\", \"urgency_message\": null, \"check_out_policy\": \"Check-out tiêu chuẩn 12:00\", \"deposit_percentage\": \"30.00\", \"penalty_percentage\": \"0.00\", \"cancellation_policy\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"deposit_fixed_amount\": \"0.00\", \"penalty_fixed_amount\": \"200000.00\", \"recommendation_score\": null, \"free_cancellation_days\": 7, \"standard_check_out_time\": \"12:00:00\"}], \"payment_method\": \"vietqr\"}', 0),
(88, 'LVS88094850', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:48:50', '2025-07-14 02:49:53', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(89, 'LVS89103511', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 03:35:11', '2025-07-14 03:35:11', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(90, 'LVS90104127', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 03:41:27', '2025-07-14 03:41:27', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(91, 'LVS91104507', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 03:45:07', '2025-07-14 03:45:07', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(92, 'LVS92105428', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, '', NULL, NULL, NULL, '2025-07-14 03:54:28', '2025-07-14 11:05:54', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(93, 'LVS93105832', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 03:58:32', '2025-07-14 03:58:32', '明心', 'quyenjpn@gmail.com', '23413421243', NULL, 0, NULL, 0),
(94, 'LVS94111645', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, '12341234', NULL, NULL, NULL, '2025-07-14 04:16:45', '2025-07-14 04:21:04', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(95, 'LVS95112222', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, 'hú hsu loo lô', NULL, NULL, NULL, '2025-07-14 04:22:22', '2025-07-14 04:22:29', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(96, 'LVS96112503', NULL, NULL, '2025-07-13', '2025-07-14', 11000.00, 2, 'Confirmed', NULL, 'sdffsdfdfgdfgsd', NULL, NULL, NULL, '2025-07-14 04:25:03', '2025-07-14 04:25:53', '明心', 'quyenjpn@gmail.com', '1234123412431', NULL, 0, NULL, 0),
(97, 'LVS97113050', NULL, NULL, '2025-07-13', '2025-07-14', 132000.00, 5, 'Pending', NULL, '412324311234', NULL, NULL, NULL, '2025-07-14 04:30:50', '2025-07-14 04:30:50', '明心', 'quyenjpn@gmail.com', '124314232134', NULL, 0, NULL, 0),
(98, 'LVS98114231', NULL, NULL, '2025-07-13', '2025-07-14', 132000.00, 5, 'Confirmed', NULL, '11234234123', NULL, NULL, NULL, '2025-07-14 04:42:31', '2025-07-14 04:42:51', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(99, 'LVS99114449', NULL, NULL, '2025-07-13', '2025-07-14', 132000.00, 5, 'Confirmed', NULL, '12341234', NULL, NULL, NULL, '2025-07-14 04:44:49', '2025-07-14 04:44:57', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, 0, NULL, 0),
(100, 'LVS100023425', NULL, NULL, '2025-07-14', '2025-07-15', 132000.00, 5, 'Confirmed', NULL, '123r1243', NULL, NULL, NULL, '2025-07-14 19:34:25', '2025-07-14 19:35:46', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(101, 'LVS101023558', NULL, NULL, '2025-07-14', '2025-07-15', 132000.00, 5, 'Confirmed', NULL, '123412342314', NULL, NULL, NULL, '2025-07-14 19:35:58', '2025-07-14 19:37:12', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(102, 'LVS102024015', NULL, NULL, '2025-07-14', '2025-07-15', 132000.00, 5, 'Confirmed', NULL, '1234123421', NULL, NULL, NULL, '2025-07-14 19:40:15', '2025-07-14 19:40:18', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(103, 'LVS103024501', NULL, NULL, '2025-07-14', '2025-07-15', 132000.00, 5, 'Confirmed', NULL, '1341234123', NULL, NULL, NULL, '2025-07-14 19:45:01', '2025-07-14 19:45:43', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(104, 'LVS104024936', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '1234', NULL, NULL, NULL, '2025-07-14 19:49:36', '2025-07-14 19:49:44', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(105, 'LVS105025917', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '1234', NULL, NULL, NULL, '2025-07-14 19:59:17', '2025-07-14 20:00:09', '明心', 'quyenjpn@gmail.com', '1234', NULL, 0, NULL, 0),
(106, 'LVS106030509', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '23452', NULL, NULL, NULL, '2025-07-14 20:05:09', '2025-07-14 20:05:12', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(107, 'LVS107030523', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '253425432543', NULL, NULL, NULL, '2025-07-14 20:05:23', '2025-07-14 20:08:36', '明心', 'quyenjpn@gmail.com', '2354', NULL, 0, NULL, 0),
(108, 'LVS108031734', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, '1234234', NULL, NULL, NULL, '2025-07-14 20:17:34', '2025-07-14 20:17:34', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(109, 'LVS109033233', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, '123414231423', NULL, NULL, NULL, '2025-07-14 20:32:33', '2025-07-14 20:32:33', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(110, 'LVS110041104', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, '31241234', NULL, NULL, NULL, '2025-07-14 21:11:04', '2025-07-14 21:11:04', '明心', 'quyenjpn@gmail.com', '12341234', NULL, 0, NULL, 0),
(111, 'LVS111042232', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-14 21:22:32', '2025-07-14 21:22:32', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, 0, NULL, 0),
(112, 'LVS112044511', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '2341', NULL, NULL, NULL, '2025-07-14 21:45:11', '2025-07-14 22:05:39', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(113, 'LVS113070418', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '324234', NULL, NULL, NULL, '2025-07-15 00:04:18', '2025-07-15 00:04:49', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(114, 'LVS114070529', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:05:29', '2025-07-15 00:07:55', '明心', 'quyenjpn@gmail.com', '1234124312341', NULL, NULL, NULL, 0),
(115, 'LVS115071036', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:10:36', '2025-07-15 00:10:36', '明心', 'quyenjpn@gmail.com', '2134', NULL, NULL, NULL, 0),
(116, 'LVS116072010', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:20:10', '2025-07-15 00:20:10', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(117, 'LVS117072552', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:25:52', '2025-07-15 00:25:52', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(118, 'LVS118072800', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:27:59', '2025-07-15 00:28:00', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(119, 'LVS119075227', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 00:52:27', '2025-07-15 00:52:27', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(120, 'LVS120085204', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 01:52:04', '2025-07-15 01:52:04', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(121, 'LVS121091522', NULL, NULL, '2025-07-14', '2025-07-15', 11000.00, 2, 'Confirmed', NULL, '', NULL, NULL, NULL, '2025-07-15 02:15:22', '2025-07-15 02:30:14', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(124, 'LVS124093516', NULL, NULL, '2025-07-14', '2025-07-16', 22000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 02:35:16', '2025-07-15 02:35:16', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(125, 'LVS125105711', NULL, NULL, '2025-07-15', '2025-07-16', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 03:57:11', '2025-07-15 03:57:11', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(126, 'LVS126023058', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-15 19:30:58', '2025-07-15 19:30:58', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(127, 'LVS127025346', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-15 19:53:46', '2025-07-15 19:54:17', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(128, 'LVS128025435', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Completed', NULL, NULL, NULL, NULL, NULL, '2025-07-15 19:54:35', '2025-08-16 10:10:30', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(129, 'LVS129030846', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Completed', NULL, 'hihihihi', NULL, NULL, NULL, '2025-07-15 20:08:46', '2025-08-16 10:10:57', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(130, 'LVS130033257', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Operational', NULL, NULL, NULL, NULL, NULL, '2025-07-15 20:32:57', '2025-07-18 12:57:45', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(131, 'LVS131033527', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-15 20:35:27', '2025-07-15 20:36:09', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(132, 'LVS132033857', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Confirmed', NULL, '1234', NULL, NULL, NULL, '2025-07-15 20:38:57', '2025-07-15 20:39:30', '明心', 'quyenjpn@gmail.com', '0987654321', NULL, NULL, NULL, 0),
(133, 'LVS133070932', NULL, NULL, '2025-07-16', '2025-07-17', 11000.00, 2, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-16 00:09:32', '2025-07-16 00:10:00', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(134, 'LVS134032119', NULL, NULL, '2025-07-17', '2025-07-18', 11000.00, 1, 'Pending', NULL, 'qeqweqwqwe', NULL, NULL, NULL, '2025-07-16 20:21:19', '2025-07-16 20:21:19', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(136, 'LVS20250717034906701', NULL, NULL, '2025-07-17', '2025-07-24', 42000.00, 1, 'Confirmed', NULL, NULL, 1, NULL, NULL, '2025-07-16 20:49:06', '2025-07-16 20:49:06', 'Quyền Nguyễn Văn', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(137, 'LVS137075500', NULL, NULL, '2025-07-17', '2025-07-18', 5510000.00, 5, 'Confirmed', NULL, 'bich tuyen', NULL, NULL, 6, '2025-07-17 00:55:00', '2025-07-18 09:29:12', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(138, 'LVS138081920', NULL, NULL, '2025-07-17', '2025-07-18', 5400000.00, 4, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-17 01:19:20', '2025-07-17 01:20:38', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(139, 'LVS139082730', NULL, NULL, '2025-07-17', '2025-07-18', 5400000.00, 4, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-17 01:27:30', '2025-07-17 01:28:01', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(140, 'LVS140103856', NULL, NULL, '2025-07-17', '2025-07-18', 8650000.00, 13, 'Confirmed', NULL, 'test', NULL, NULL, NULL, '2025-07-17 03:38:56', '2025-07-17 03:39:28', 'Quyền', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(141, 'LVS141104347', NULL, NULL, '2025-07-17', '2025-07-18', 8650000.00, 13, 'Confirmed', NULL, 'trtyrtrytyre', NULL, NULL, NULL, '2025-07-17 03:43:47', '2025-07-17 03:44:07', '明têttetetete', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(142, 'LVS142120903', NULL, NULL, '2025-07-17', '2025-07-18', 14650000.00, 13, 'Cleaning', NULL, 'bich tuyen cute\nCheck-in completed at 2025-08-19 17:03:17\nCheck-out completed at 2025-08-19 17:08:27', NULL, NULL, 6, '2025-07-17 05:09:03', '2025-08-19 10:08:27', 'test', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(143, 'LVS143125143', NULL, NULL, '2025-08-18', '2025-08-21', 15450000.00, 10, 'Cancelled', NULL, NULL, NULL, NULL, NULL, '2025-07-18 05:51:43', '2025-07-18 19:10:32', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(144, 'LVS144031538', NULL, NULL, '2025-08-18', '2025-08-21', 20850000.00, 10, 'Confirmed', NULL, NULL, NULL, NULL, NULL, '2025-07-18 20:15:38', '2025-07-18 20:16:40', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(145, 'LVS145032559', NULL, NULL, '2025-08-18', '2025-08-21', 20850000.00, 10, 'Pending', NULL, 'kokoko', NULL, NULL, NULL, '2025-07-18 20:25:59', '2025-07-18 20:25:59', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(146, 'LVS146033153', NULL, NULL, '2025-08-18', '2025-08-21', 20850000.00, 10, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-18 20:31:53', '2025-07-18 20:31:53', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(147, 'LVS147033726', NULL, NULL, '2025-08-18', '2025-08-21', 1716000.00, 10, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-18 20:37:26', '2025-07-18 20:37:26', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(148, 'LVS148033746', NULL, NULL, '2025-08-18', '2025-08-21', 20850000.00, 10, 'Pending', NULL, NULL, NULL, NULL, NULL, '2025-07-18 20:37:46', '2025-07-18 20:37:46', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(149, 'LVS149034419', NULL, NULL, '2025-08-14', '2025-08-16', 20850000.00, 10, 'Cancelled', NULL, 'thgisch', NULL, NULL, 6, '2025-07-18 20:44:19', '2025-07-31 13:56:40', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(150, 'LVS150034633', NULL, NULL, '2025-08-18', '2025-08-21', 20850000.00, 10, 'Cancelled', NULL, NULL, NULL, NULL, 6, '2025-07-18 20:46:33', '2025-07-31 13:57:03', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(151, 'LVS151023546', NULL, NULL, '2025-08-18', '2025-08-22', 27800000.00, 10, 'Cancelled', NULL, NULL, NULL, NULL, 6, '2025-07-19 19:35:46', '2025-07-31 13:57:29', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(152, 'LVS152023648', NULL, NULL, '2025-08-18', '2025-08-22', 10248000.00, 10, 'Confirmed', NULL, NULL, NULL, NULL, 1, '2025-07-19 19:36:48', '2025-07-19 19:37:24', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(153, 'LVS153025209', NULL, NULL, '2025-08-18', '2025-08-22', 2288000.00, 10, 'Cancelled', NULL, NULL, NULL, NULL, 1, '2025-07-19 19:52:09', '2025-07-31 13:37:24', '明心', 'quyenjpn@gmail.com', '0335920306', NULL, NULL, NULL, 0),
(154, 'LVS154095008', NULL, NULL, '2025-07-22', '2025-07-23', 11000.00, 2, 'Pending', NULL, 'v', NULL, NULL, 1, '2025-07-21 02:50:08', '2025-07-21 02:50:08', 'Đào Tùng Dưn', 'dun@gmail.com', '02151651121', NULL, NULL, NULL, 0),
(155, 'LVS155092059', NULL, NULL, '2025-07-28', '2025-07-29', 22000.00, 6, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-07-28 02:20:59', '2025-07-28 02:20:59', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(156, 'LVS156093654', NULL, NULL, '2025-07-28', '2025-07-29', 22000.00, 6, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-07-28 02:36:54', '2025-07-28 02:36:54', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(157, 'LVS157124518', NULL, NULL, '2025-07-28', '2025-07-29', 22000.00, 6, 'Pending', NULL, 'q', NULL, NULL, 1, '2025-07-28 05:45:18', '2025-07-28 05:45:18', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(158, 'LVS158124550', NULL, NULL, '2025-07-28', '2025-07-29', 22000.00, 6, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-07-28 05:45:50', '2025-07-28 05:45:50', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(159, 'LVS159132746', NULL, NULL, '2025-07-28', '2025-07-29', 22000.00, 6, 'Pending', NULL, NULL, NULL, NULL, 1, '2025-07-28 06:27:46', '2025-07-28 06:27:46', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(160, 'LVS160021712', NULL, NULL, '2025-08-01', '2025-08-05', 49414.40, 6, 'Operational', NULL, 'za', NULL, NULL, 1, '2025-07-28 19:17:12', '2025-08-02 15:11:50', 'PH Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(161, 'LVS161110042', NULL, NULL, '2025-08-13', '2025-08-14', 22000.00, 6, 'Pending', NULL, 'ge', NULL, NULL, 1, '2025-08-13 04:00:42', '2025-08-13 04:00:42', 'PHNguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(162, 'LVS162221924', NULL, NULL, '2025-08-13', '2025-08-14', 132000.00, 7, 'Pending', NULL, 'x', NULL, NULL, 1, '2025-08-13 15:19:24', '2025-08-13 15:19:24', 'Đức Đẹp Trai', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(163, 'LVS163223826', NULL, NULL, '2025-08-13', '2025-08-14', 132000.00, 7, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-08-13 15:38:26', '2025-08-13 15:38:26', 'Đức Đẹp Trai', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(164, 'LVS164224016', NULL, NULL, '2025-08-13', '2025-08-14', 132000.00, 7, 'Pending', NULL, 'đ', NULL, NULL, 1, '2025-08-13 15:40:16', '2025-08-13 15:40:16', 'Đức Đẹp Trai', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(165, 'LVS165224128', NULL, NULL, '2025-08-13', '2025-08-14', 132000.00, 7, 'Pending', NULL, 'đ', NULL, NULL, 1, '2025-08-13 15:41:28', '2025-08-13 15:41:28', 'Đức Đẹp Trai', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(166, 'LVS166224716', NULL, NULL, '2025-08-13', '2025-08-14', 132000.00, 7, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-08-13 15:47:16', '2025-08-13 15:47:16', 'Đức Đẹp Trai', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(167, 'LVS167225123', NULL, NULL, '2025-08-13', '2025-08-14', 132000.00, 7, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-08-13 15:51:23', '2025-08-13 15:51:23', 'Đức Đẹp Trai', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(168, 'LVS168225441', NULL, NULL, '2025-08-13', '2025-08-14', 132000.00, 7, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-08-13 15:54:41', '2025-08-13 15:54:41', 'Đức Đẹp Trai', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(169, 'LVS169225915', NULL, NULL, '2025-08-13', '2025-08-14', 132000.00, 7, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-08-13 15:59:15', '2025-08-13 15:59:15', 'Đức Đẹp Trai', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(170, 'LVS170230519', NULL, NULL, '2025-08-13', '2025-08-14', 132000.00, 7, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-08-13 16:05:19', '2025-08-13 16:05:19', 'Đức Đẹp Trai', 'nguyenanhduc2909@gmail.com', '0822153447', NULL, NULL, NULL, 0),
(171, 'LVS171161343', 1, NULL, '2025-08-16', '2025-08-17', 250000.00, 7, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-08-16 09:13:43', '2025-08-16 09:13:43', 'Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '08221534477', NULL, NULL, NULL, 0),
(172, 'LVS172161440', 1, NULL, '2025-08-16', '2025-08-17', 250000.00, 7, 'Pending', NULL, 'tg', NULL, NULL, 1, '2025-08-16 09:14:40', '2025-08-16 09:14:40', 'Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '08221534477', NULL, NULL, NULL, 0),
(173, 'LVS173161504', 1, NULL, '2025-08-16', '2025-08-17', 250000.00, 7, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-08-16 09:15:04', '2025-08-16 09:15:04', 'Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '08221534477', NULL, NULL, NULL, 0),
(174, 'LVS174171249', 1, NULL, '2025-08-19', '2025-08-20', 6200.00, 2, 'Pending', NULL, 'Có sân pickerball', NULL, NULL, 1, '2025-08-19 10:12:49', '2025-08-19 10:12:49', 'Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '08221534477', NULL, NULL, NULL, 0),
(175, 'LVS175171404', 1, NULL, '2025-08-19', '2025-08-20', 6200.00, 2, 'Pending', NULL, 'd', NULL, NULL, 1, '2025-08-19 10:14:04', '2025-08-19 10:14:04', 'Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '08221534477', NULL, NULL, NULL, 0),
(176, 'LVS176171450', 1, 'BOOK-LVS176171450', '2025-08-19', '2025-08-20', 6200.00, 2, 'Confirmed', NULL, 'Pickerball', NULL, NULL, 1, '2025-08-19 10:14:50', '2025-08-19 10:17:27', 'Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', '08221534477', NULL, NULL, NULL, 0);

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
-- Dumping data for table `booking_reschedules`
--

INSERT INTO `booking_reschedules` (`reschedule_id`, `booking_id`, `new_check_in_date`, `new_check_out_date`, `new_room_id`, `new_option_id`, `reschedule_policy_id`, `price_difference_vnd`, `payment_id`, `status`, `reason`, `suggested_rooms`, `created_at`, `updated_at`, `processed_by`) VALUES
(2, 24, '2025-08-10', '2025-08-15', 2, 'OPT10', 5, -5175000.00, NULL, 'Approved', 'Thay đổi kế hoạch cá nhân', '\"[]\"', '2025-08-03 16:23:41', '2025-08-03 16:23:41', NULL),
(3, 24, '2025-08-10', '2025-08-15', 4, 'OPT10', 5, 0.00, NULL, 'Approved', 'Thay đổi kế hoạch cá nhân', '\"[]\"', '2025-08-03 16:24:41', '2025-08-03 16:24:41', NULL),
(4, 24, '2025-08-10', '2025-08-15', 2, 'OPT10', 5, 0.00, NULL, 'Approved', 'Thay đổi kế hoạch cá nhân', '\"[]\"', '2025-08-03 16:25:27', '2025-08-03 16:25:27', NULL),
(6, 24, '2025-08-10', '2025-08-15', 95, 'OPT10', 3, 8175000.00, NULL, 'Approved', 'Thay đổi kế hoạch cá nhân', '\"[]\"', '2025-08-04 04:57:59', '2025-08-04 04:57:59', NULL),
(7, 24, '2025-08-10', '2025-08-15', 91, 'OPT10', 3, 0.00, NULL, 'Approved', 'Thay đổi kế hoạch cá nhân', '\"[]\"', '2025-08-04 04:58:52', '2025-08-04 04:58:52', NULL),
(8, 24, '2025-08-10', '2025-08-15', 92, 'OPT10', 3, 0.00, NULL, 'Approved', 'Thay đổi kế hoạch cá nhân', '\"[]\"', '2025-08-04 04:59:01', '2025-08-04 04:59:01', NULL),
(9, 24, '2025-08-10', '2025-08-15', 91, 'OPT10', 3, 0.00, NULL, 'Approved', 'Thay đổi kế hoạch cá nhân', '\"[]\"', '2025-08-05 09:07:51', '2025-08-05 09:07:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_rooms`
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
-- Dumping data for table `booking_rooms`
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
(149, 24, NULL, 91, 'OPT10', NULL, NULL, NULL, NULL, NULL, NULL, 2000000, 5, 10000000, '2025-08-10', '2025-08-15', '2025-08-05 09:07:51', '2025-08-05 09:07:51'),
(150, 176, 'LVS176171450', NULL, 'BOOK-LVS176171450', 'Standard Package', 6200.00, 120, 2, 0, NULL, 6200, 1, 6200, '2025-08-19', '2025-08-20', '2025-08-19 10:17:23', '2025-08-19 10:17:23');

-- --------------------------------------------------------

--
-- Table structure for table `booking_room_children`
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
-- Dumping data for table `booking_room_children`
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
-- Table structure for table `booking_services`
--

CREATE TABLE `booking_services` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `service_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price_vnd` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin dịch vụ phát sinh cho mỗi booking';

--
-- Dumping data for table `booking_services`
--

INSERT INTO `booking_services` (`id`, `booking_id`, `service_id`, `quantity`, `price_vnd`, `created_at`, `updated_at`) VALUES
(1, 24, 10, 2, 200000.00, '2025-08-11 10:47:20', '2025-08-11 10:47:20'),
(2, 24, 8, 1, 2000000.00, '2025-08-11 10:47:20', '2025-08-11 10:47:20'),
(3, 142, 8, 3, 2000000.00, '2025-08-19 10:03:33', '2025-08-19 10:04:41');

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_policies`
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
-- Dumping data for table `cancellation_policies`
--

INSERT INTO `cancellation_policies` (`policy_id`, `name`, `free_cancellation_days`, `penalty_days`, `penalty_percentage`, `penalty_fixed_amount_vnd`, `description`, `priority`, `conditions`, `applies_to_weekend`, `applies_to_holiday`, `min_booking_amount`, `max_booking_amount`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Hủy có phí', NULL, 3, 30.00, NULL, 'Phạt 50% nếu hủy trong vòng 2 ngày', 0, NULL, 0, 0, NULL, NULL, 1, '2025-06-11 02:26:26', '2025-08-15 03:03:56'),
(10, 'Hủy miễn phí 7 ngày', 7, NULL, 0.00, 200000.00, 'Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k', 10, NULL, 0, 0, NULL, NULL, 1, '2025-07-12 09:51:47', '2025-07-12 09:51:47'),
(11, 'Hủy miễn phí 3 ngày - Lễ Tết', 2, NULL, 50.00, 0.00, 'Áp dụng cho ngày lễ tết, hủy trước 3 ngày', 20, NULL, 0, 1, NULL, NULL, 1, '2025-07-12 09:51:47', '2025-08-15 03:28:49');

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_requests`
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
-- Table structure for table `check_in_policies`
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  `conditions` text COLLATE utf8mb4_general_ci COMMENT 'Điều kiện áp dụng chính sách (JSON hoặc text)',
  `action` text COLLATE utf8mb4_general_ci COMMENT 'Hành động khi chính sách được áp dụng',
  `priority` int DEFAULT '0' COMMENT 'Mức độ ưu tiên (cao hơn được áp dụng trước)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Danh sách chính sách nhận phòng';

--
-- Dumping data for table `check_in_policies`
--

INSERT INTO `check_in_policies` (`policy_id`, `name`, `description`, `standard_check_in_time`, `early_check_in_fee_vnd`, `early_check_in_max_hours`, `late_check_in_fee_vnd`, `late_check_in_max_hours`, `applies_to_holiday`, `applies_to_weekend`, `is_active`, `created_at`, `updated_at`, `conditions`, `action`, `priority`) VALUES
(5, 'Standard Check-in', 'Check-in từ 14:00–23:59, yêu cầu giấy tờ và thanh toán 100%.', '14:00:00', 0.00, NULL, 0.00, NULL, 0, 0, 1, '2025-08-05 03:35:35', '2025-08-05 03:35:35', '{\"time\": \"14:00-23:59\", \"payment_status\": \">=100%\", \"room_assigned\": true, \"document_verified\": true}', 'Allow check-in, assign room, provide welcome drink', 10),
(6, 'Early Check-in', 'Check-in từ 8:00–13:59, phụ phí 50% nếu trước 12:00, miễn phí nếu phòng trống.', '08:00:00', 1000000.00, NULL, 0.00, NULL, 0, 0, 1, '2025-08-05 03:35:35', '2025-08-05 04:02:39', '{\"time\": \"08:00-13:59\", \"payment_status\": \">=100%\", \"room_assigned\": true, \"document_verified\": true, \"room_availability\": \"optional\"}', 'Allow check-in, charge 50% if before 12:00, assign room', 20),
(7, 'Late Check-in', 'Check-in sau 23:00, cần đảm bảo booking bằng thẻ tín dụng.', '23:00:00', 0.00, NULL, 0.00, NULL, 0, 0, 1, '2025-08-05 03:35:35', '2025-08-05 03:35:35', '{\"time\": \"23:00-07:59\", \"payment_status\": \">=100%\", \"room_assigned\": true, \"document_verified\": true, \"guaranteed_booking\": true}', 'Allow check-in, assign room, concierge support', 15),
(8, 'Walk-in Check-in', 'Check-in không đặt trước, cần phòng trống và thanh toán 100%.', '14:00:00', 0.00, NULL, 0.00, NULL, 0, 0, 1, '2025-08-05 03:35:35', '2025-08-05 03:35:35', '{\"no_booking\": true, \"payment_status\": \"100%\", \"room_assigned\": false, \"document_verified\": true, \"room_availability\": true}', 'Assign available room, process payment, allow check-in', 5),
(9, 'Group Check-in', 'Check-in cho đoàn (>10 người), thanh toán trước 50%.', '14:00:00', 0.00, NULL, 0.00, NULL, 0, 0, 1, '2025-08-05 03:35:35', '2025-08-05 03:35:35', '{\"guest_count\": \">10\", \"payment_status\": \">=50%\", \"room_assigned\": true, \"document_verified\": true}', 'Assign multiple rooms, process group check-in', 8),
(10, 'Special Request Check-in', 'Check-in với yêu cầu đặc biệt (tầng cao, view đẹp, giường phụ).', '14:00:00', 0.00, NULL, 0.00, NULL, 0, 0, 1, '2025-08-05 03:35:35', '2025-08-05 03:35:35', '{\"special_requests\": true, \"payment_status\": \">=100%\", \"room_assigned\": true, \"document_verified\": true}', 'Assign room matching requests, allow check-in', 12),
(11, 'No-show Policy', 'Hủy booking nếu không đến trước 23:59, phạt 100%.', '23:59:00', 0.00, NULL, 0.00, NULL, 0, 0, 1, '2025-08-05 03:35:35', '2025-08-05 03:35:35', '{\"time\": \">23:59\", \"no_show\": true, \"payment_status\": \"any\"}', 'Cancel booking, charge 100% penalty', 1),
(12, 'Invalid Payment Check-in', 'Thẻ tín dụng không hợp lệ, yêu cầu phương thức thanh toán khác.', '14:00:00', 0.00, NULL, 0.00, NULL, 0, 0, 1, '2025-08-05 03:35:35', '2025-08-05 03:35:35', '{\"payment_status\": \"invalid\", \"room_assigned\": true, \"document_verified\": true}', 'Reject check-in, request alternative payment', 2),
(13, 'Missing Document Check-in', 'Thiếu giấy tờ, yêu cầu xác minh trước khi check-in.', '14:00:00', 0.00, NULL, 0.00, NULL, 0, 0, 1, '2025-08-05 03:35:35', '2025-08-05 03:35:35', '{\"document_verified\": false, \"payment_status\": \">=100%\", \"room_assigned\": true}', 'Reject check-in, request document verification', 3),
(14, 'Room Unavailable Check-in', 'Phòng chưa sẵn sàng hoặc hỏng, chuyển sang phòng khác.', '14:00:00', 0.00, NULL, 0.00, NULL, 0, 0, 1, '2025-08-05 03:35:35', '2025-08-05 03:35:35', '{\"room_assigned\": true, \"room_availability\": false, \"document_verified\": true, \"payment_status\": \">=100%\"}', 'Assign alternative room or upgrade, allow check-in', 4),
(15, 'Booking Cancellation', 'Hủy booking trước check-in, miễn phí nếu trước 48 giờ, phạt 50% trong 48 giờ, 100% trong 24 giờ.', '00:00:00', 0.00, NULL, 0.00, NULL, 0, 0, 1, '2025-08-05 03:35:35', '2025-08-05 03:35:35', '{\"cancellation\": true, \"time_before_check_in\": \"<=48h\", \"payment_status\": \"any\"}', 'Apply cancellation fee (0%, 50%, or 100% based on time)', 6);

-- --------------------------------------------------------

--
-- Table structure for table `check_in_requests`
--

CREATE TABLE `check_in_requests` (
  `request_id` int NOT NULL COMMENT 'Khóa chính, tự động tăng',
  `booking_id` int NOT NULL COMMENT 'ID của booking liên quan',
  `policy_id` int DEFAULT NULL COMMENT 'Chính sách check-in được áp dụng',
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Loại yêu cầu check-in (VD: Standard, Early, Walk-in)',
  `requested_check_in_time` datetime NOT NULL COMMENT 'Thời gian yêu cầu check-in',
  `fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí bổ sung (VD: phí check-in sớm)',
  `special_requests` text COLLATE utf8mb4_general_ci COMMENT 'Yêu cầu đặc biệt (JSON hoặc text, VD: tầng cao, giường phụ)',
  `total_amount_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Tổng chi phí cần thanh toán (bao gồm phí sớm nếu có)',
  `status` enum('Pending','Approved','Rejected','Awaiting Payment','Missing Document','Room Unavailable') COLLATE utf8mb4_general_ci DEFAULT 'Pending' COMMENT 'Trạng thái yêu cầu check-in',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Yêu cầu check-in từ khách';

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
  `priority` int NOT NULL DEFAULT '0',
  `conditions` json DEFAULT NULL,
  `applies_to_weekend` tinyint(1) NOT NULL DEFAULT '0',
  `applies_to_holiday` tinyint(1) NOT NULL DEFAULT '0',
  `standard_check_out_time` time NOT NULL DEFAULT '12:00:00',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action` text COLLATE utf8mb4_general_ci COMMENT 'Hành động khi chính sách được áp dụng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `check_out_policies`
--

INSERT INTO `check_out_policies` (`policy_id`, `name`, `early_check_out_fee_vnd`, `late_check_out_fee_vnd`, `late_check_out_max_hours`, `early_check_out_max_hours`, `description`, `priority`, `conditions`, `applies_to_weekend`, `applies_to_holiday`, `standard_check_out_time`, `is_active`, `created_at`, `updated_at`, `action`) VALUES
(5, 'Standard Check-out', NULL, 0.00, NULL, NULL, 'Check-out trước 12:00, thanh toán đầy đủ, hóa đơn gửi qua email nếu yêu cầu.', 10, '{\"time\": \"<=12:00\", \"payment_status\": \"100%\", \"services_settled\": true}', 0, 0, '12:00:00', 1, '2025-08-05 03:37:17', '2025-08-05 03:37:17', 'Allow check-out, generate invoice, mark room as Cleaning'),
(6, 'Late Check-out', NULL, 1000000.00, NULL, NULL, 'Check-out từ 12:01–18:00, phụ phí 50% nếu trước 15:00, 100% nếu sau 15:00, miễn phí nếu phòng trống.', 20, '{\"time\": \"12:01-18:00\", \"payment_status\": \"100%\", \"services_settled\": true, \"room_availability\": \"optional\"}', 0, 0, '18:00:00', 1, '2025-08-05 03:37:17', '2025-08-05 03:37:17', 'Allow check-out, charge 50% or 100% based on time, mark room as Cleaning'),
(7, 'Early Check-out', NULL, 0.00, NULL, NULL, 'Check-out trước ngày dự kiến, phạt 50% nếu không báo trước 24 giờ.', 15, '{\"check_out_date\": \"<expected_date\", \"payment_status\": \"100%\", \"services_settled\": true}', 0, 0, '00:00:00', 1, '2025-08-05 03:37:17', '2025-08-05 03:37:17', 'Allow check-out, charge 50% if no 24h notice, mark room as Cleaning'),
(8, 'Express Check-out', NULL, 0.00, NULL, NULL, 'Check-out nhanh qua ứng dụng/thẻ, hóa đơn gửi qua email.', 12, '{\"payment_status\": \"100%\", \"services_settled\": true, \"express_check_out\": true}', 0, 0, '12:00:00', 1, '2025-08-05 03:37:17', '2025-08-05 03:37:17', 'Allow check-out, send invoice via email, mark room as Cleaning'),
(9, 'Service Dispute Check-out', NULL, 0.00, NULL, NULL, 'Khách không đồng ý với hóa đơn dịch vụ, cần kiểm tra.', 5, '{\"payment_status\": \"<100%\", \"service_dispute\": true, \"services_settled\": false}', 0, 0, '12:00:00', 1, '2025-08-05 03:37:17', '2025-08-05 03:37:17', 'Hold check-out, verify services, update invoice'),
(10, 'Incomplete Check-out', NULL, 0.00, NULL, NULL, 'Khách rời đi không thông báo, cần xử lý thanh toán.', 3, '{\"payment_status\": \"<100%\", \"services_settled\": \"any\", \"no_check_out_confirmation\": true}', 0, 0, '12:00:00', 1, '2025-08-05 03:37:17', '2025-08-05 03:37:17', 'Process payment, mark room as Cleaning, notify customer'),
(11, 'Special Request Check-out', NULL, 0.00, NULL, NULL, 'Check-out với yêu cầu đặc biệt (lưu trữ hành lý, hóa đơn đa ngôn ngữ).', 8, '{\"payment_status\": \"100%\", \"services_settled\": true, \"special_requests\": true}', 0, 0, '12:00:00', 1, '2025-08-05 03:37:17', '2025-08-05 03:37:17', 'Allow check-out, handle special requests, mark room as Cleaning'),
(12, 'Invalid Payment Check-out', NULL, 0.00, NULL, NULL, 'Thẻ tín dụng không hợp lệ, yêu cầu phương thức thanh toán khác.', 4, '{\"payment_status\": \"invalid\", \"services_settled\": true}', 0, 0, '12:00:00', 1, '2025-08-05 03:37:17', '2025-08-05 03:37:17', 'Hold check-out, request alternative payment');

-- --------------------------------------------------------

--
-- Table structure for table `check_out_requests`
--

CREATE TABLE `check_out_requests` (
  `request_id` int NOT NULL COMMENT 'Khóa chính, tự động tăng',
  `booking_id` int NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `requested_check_out_time` datetime NOT NULL,
  `fee_vnd` decimal(15,2) DEFAULT '0.00',
  `status` enum('Pending','Approved','Rejected','Awaiting Payment','Disputed','Incomplete') COLLATE utf8mb4_general_ci DEFAULT 'Pending' COMMENT 'Trạng thái yêu cầu check-out',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `policy_id` int DEFAULT NULL COMMENT 'Chính sách check-out được áp dụng',
  `special_requests` text COLLATE utf8mb4_general_ci COMMENT 'Yêu cầu đặc biệt (JSON hoặc text, ví dụ: lưu trữ hành lý, hóa đơn đa ngôn ngữ)',
  `total_amount_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Tổng chi phí cần thanh toán (phòng + dịch vụ + phí bổ sung)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `check_out_requests`
--

INSERT INTO `check_out_requests` (`request_id`, `booking_id`, `type`, `requested_check_out_time`, `fee_vnd`, `status`, `created_at`, `updated_at`, `policy_id`, `special_requests`, `total_amount_vnd`) VALUES
(1, 26, 'early', '2025-07-11 03:00:00', 0.00, 'Approved', '2025-07-09 00:29:58', '2025-07-09 00:29:58', NULL, NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `children_surcharges`
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
-- Dumping data for table `children_surcharges`
--

INSERT INTO `children_surcharges` (`id`, `min_age`, `max_age`, `is_free`, `count_as_adult`, `requires_extra_bed`, `surcharge_amount_vnd`, `created_at`, `updated_at`, `booking_id`) VALUES
(1, 0, 6, 1, 0, 0, NULL, '2025-07-09 20:09:55', '2025-07-09 23:13:23', NULL),
(2, 7, 12, 0, 0, 0, 110000, '2025-07-09 20:09:55', '2025-07-11 01:24:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `compensation_policies`
--

CREATE TABLE `compensation_policies` (
  `compensation_policy_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên chính sách bồi thường',
  `description` text COLLATE utf8mb4_general_ci COMMENT 'Mô tả chi tiết',
  `applies_to_room_type_id` int DEFAULT NULL COMMENT 'Loại phòng áp dụng, NULL nếu áp dụng cho tất cả',
  `condition_type` enum('room_damage','service_failure','overbooking','other') COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại sự cố áp dụng',
  `discount_type` enum('percentage','fixed_amount') COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại giảm giá: % hoặc số tiền cố định',
  `discount_value` decimal(15,2) NOT NULL COMMENT 'Giá trị giảm',
  `max_compensation_amount` decimal(15,2) DEFAULT NULL COMMENT 'Mức bồi thường tối đa (nếu có)',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `compensation_policies`
--

INSERT INTO `compensation_policies` (`compensation_policy_id`, `name`, `description`, `applies_to_room_type_id`, `condition_type`, `discount_type`, `discount_value`, `max_compensation_amount`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Hoàn tiền toàn bộ - phòng không thể sử dụng', 'Áp dụng khi phòng hỏng/không thể sử dụng và không có phòng thay thế. Hoàn tiền 100% cho các đêm chưa sử dụng; nhân viên cần ghi rõ số đêm và nightly_rate trong booking_compensations.', NULL, 'room_damage', 'percentage', 100.00, NULL, 1, '2025-08-12 02:31:06', '2025-08-12 02:31:06'),
(2, 'Giảm 50% - đêm bị ảnh hưởng', 'Áp dụng khi dịch vụ bị gián đoạn (ví dụ mất điện, điều hòa hỏng) cho đêm hiện tại; giảm 50% trên giá đêm bị ảnh hưởng.', NULL, 'service_failure', 'percentage', 50.00, NULL, 1, '2025-08-12 02:31:06', '2025-08-12 02:31:06'),
(3, 'Chi trả chỗ nghỉ tạm & di chuyển (Overbook/Out-of-service)', 'Khi khách phải chuyển sang khách sạn khác: khách sạn chi trả chi phí chỗ nghỉ thay thế và chi phí di chuyển hợp lý. Ghi rõ hóa đơn/phiếu chi vào booking_compensations để hoàn trả.', NULL, 'overbooking', 'fixed_amount', 2000000.00, 2000000.00, 1, '2025-08-12 02:31:06', '2025-08-12 02:31:06'),
(4, 'Credit dịch vụ tại chỗ', 'Cấp credit dùng cho F&B hoặc spa trong thời gian lưu trú. Thường dùng cho trường hợp service failure nhỏ.', NULL, 'service_failure', 'fixed_amount', 300000.00, 300000.00, 1, '2025-08-12 02:31:06', '2025-08-12 02:31:06'),
(5, 'Voucher 1 đêm cho lần ở sau', 'Voucher trị giá 1 đêm (giá tham chiếu: 1,000,000 VND) dùng cho lần đặt tiếp theo. Xử lý voucher ngoài luồng thanh toán.', NULL, 'other', 'fixed_amount', 1000000.00, 1000000.00, 1, '2025-08-12 02:31:06', '2025-08-12 02:31:06'),
(6, 'Nâng hạng phòng miễn phí', 'Nâng hạng phòng (non-monetary). Ghi voucher/note trong booking; xử lý áp dụng thủ công tại check-in/out.', NULL, 'service_failure', 'fixed_amount', 0.00, 0.00, 1, '2025-08-12 02:31:06', '2025-08-12 02:31:06'),
(7, 'Giảm 30% trên hóa đơn hiện tại', 'Giảm 30% trên tổng booking hoặc trên đêm bị ảnh hưởng (theo cấu hình).', NULL, 'service_failure', 'percentage', 30.00, NULL, 1, '2025-08-12 02:31:06', '2025-08-12 02:31:06');

-- --------------------------------------------------------

--
-- Table structure for table `compensation_requests`
--

CREATE TABLE `compensation_requests` (
  `request_id` int NOT NULL COMMENT 'ID yêu cầu bồi thường',
  `booking_id` int NOT NULL COMMENT 'ID booking liên quan',
  `requested_by` bigint UNSIGNED NOT NULL COMMENT 'ID lễ tân gửi yêu cầu (users.id)',
  `policy_id` int DEFAULT NULL COMMENT 'ID chính sách trong compensation_policies, NULL nếu là yêu cầu khác',
  `custom_reason` text COLLATE utf8mb4_general_ci COMMENT 'Lý do nhập tay nếu không chọn policy có sẵn',
  `status` enum('pending','approved','rejected','applied') COLLATE utf8mb4_general_ci DEFAULT 'pending' COMMENT 'Trạng thái xử lý',
  `requested_amount` decimal(15,2) DEFAULT NULL COMMENT 'Số tiền lễ tân đề xuất bồi thường (nếu có)',
  `approved_amount` decimal(15,2) DEFAULT NULL COMMENT 'Số tiền quản lý duyệt cuối cùng',
  `approved_by` bigint UNSIGNED NOT NULL COMMENT 'ID người duyệt (users.id)',
  `approved_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian duyệt',
  `attachments` json DEFAULT NULL COMMENT 'Danh sách file đính kèm (ảnh/video)',
  `admin_note` text COLLATE utf8mb4_general_ci COMMENT 'Ghi chú của quản lý khi duyệt hoặc từ chối',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `compensation_requests`
--

INSERT INTO `compensation_requests` (`request_id`, `booking_id`, `requested_by`, `policy_id`, `custom_reason`, `status`, `requested_amount`, `approved_amount`, `approved_by`, `approved_at`, `attachments`, `admin_note`, `created_at`, `updated_at`) VALUES
(1, 24, 6, NULL, 'dưdwd', 'approved', 100000.00, 200000.00, 1, '2025-08-13 02:24:13', NULL, 'sqsqs', '2025-08-12 07:49:11', '2025-08-13 02:24:13'),
(4, 24, 6, NULL, 'fefef', 'pending', 2000000.00, NULL, 1, NULL, NULL, NULL, '2025-08-19 07:23:13', '2025-08-19 07:23:13');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
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
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `user_id`, `client_token`, `is_bot_only`, `handover_to_user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 0, NULL, 'active', '2025-07-21 10:31:45', '2025-07-21 10:31:45'),
(2, NULL, 'a18c23b1-cc1e-4d3f-bc9a-bc01f153d88f', 0, NULL, 'active', '2025-07-21 10:31:45', '2025-07-21 10:31:45'),
(3, NULL, '87182c0f-5517-4644-b130-a85c2ce9eaef', 0, NULL, 'pending', '2025-07-21 23:13:03', '2025-07-21 23:13:03'),
(4, NULL, 'e6d96243-6af4-4673-bece-4624324838d0', 1, NULL, 'active', '2025-07-23 00:15:47', '2025-07-28 04:41:33');

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

--
-- Dumping data for table `datafeeds`
--

INSERT INTO `datafeeds` (`id`, `label`, `data`, `dataset_name`, `data_type`, `created_at`, `updated_at`) VALUES
(1, '12-01-2020', 732, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(2, '01-01-2021', 610, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(3, '02-01-2021', 610, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(4, '03-01-2021', 504, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(5, '04-01-2021', 504, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(6, '05-01-2021', 504, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(7, '06-01-2021', 349, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(8, '07-01-2021', 349, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(9, '08-01-2021', 504, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(10, '09-01-2021', 342, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(11, '10-01-2021', 504, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(12, '11-01-2021', 610, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(13, '12-01-2021', 391, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(14, '01-01-2022', 192, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(15, '02-01-2022', 154, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(16, '03-01-2022', 273, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(17, '04-01-2022', 191, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(18, '05-01-2022', 191, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(19, '06-01-2022', 126, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(20, '07-01-2022', 263, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(21, '08-01-2022', 349, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(22, '09-01-2022', 252, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(23, '10-01-2022', 423, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(24, '11-01-2022', 622, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(25, '12-01-2022', 470, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(26, '01-01-2023', 532, 1, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(27, NULL, 532, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(28, NULL, 532, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(29, NULL, 532, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(30, NULL, 404, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(31, NULL, 404, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(32, NULL, 314, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(33, NULL, 314, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(34, NULL, 314, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(35, NULL, 314, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(36, NULL, 314, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(37, NULL, 234, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(38, NULL, 314, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(39, NULL, 234, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(40, NULL, 234, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(41, NULL, 314, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(42, NULL, 314, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(43, NULL, 314, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(44, NULL, 388, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(45, NULL, 314, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(46, NULL, 202, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(47, NULL, 202, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(48, NULL, 202, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(49, NULL, 202, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(50, NULL, 314, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(51, NULL, 720, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(52, NULL, 642, 2, 1, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(53, '12-01-2020', 622, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(54, '01-01-2021', 622, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(55, '02-01-2021', 426, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(56, '03-01-2021', 471, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(57, '04-01-2021', 365, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(58, '05-01-2021', 365, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(59, '06-01-2021', 238, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(60, '07-01-2021', 324, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(61, '08-01-2021', 288, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(62, '09-01-2021', 206, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(63, '10-01-2021', 324, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(64, '11-01-2021', 324, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(65, '12-01-2021', 500, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(66, '01-01-2022', 409, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(67, '02-01-2022', 409, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(68, '03-01-2022', 273, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(69, '04-01-2022', 232, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(70, '05-01-2022', 273, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(71, '06-01-2022', 500, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(72, '07-01-2022', 570, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(73, '08-01-2022', 767, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(74, '09-01-2022', 808, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(75, '10-01-2022', 685, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(76, '11-01-2022', 767, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(77, '12-01-2022', 685, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(78, '01-01-2023', 685, 1, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(79, NULL, 732, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(80, NULL, 610, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(81, NULL, 610, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(82, NULL, 504, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(83, NULL, 504, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(84, NULL, 504, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(85, NULL, 349, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(86, NULL, 349, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(87, NULL, 504, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(88, NULL, 342, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(89, NULL, 504, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(90, NULL, 610, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(91, NULL, 391, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(92, NULL, 192, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(93, NULL, 154, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(94, NULL, 273, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(95, NULL, 191, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(96, NULL, 191, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(97, NULL, 126, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(98, NULL, 263, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(99, NULL, 349, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(100, NULL, 252, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(101, NULL, 423, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(102, NULL, 622, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(103, NULL, 470, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(104, NULL, 532, 2, 2, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(105, '12-01-2020', 540, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(106, '01-01-2021', 466, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(107, '02-01-2021', 540, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(108, '03-01-2021', 466, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(109, '04-01-2021', 385, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(110, '05-01-2021', 432, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(111, '06-01-2021', 334, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(112, '07-01-2021', 334, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(113, '08-01-2021', 289, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(114, '09-01-2021', 289, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(115, '10-01-2021', 200, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(116, '11-01-2021', 289, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(117, '12-01-2021', 222, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(118, '01-01-2022', 289, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(119, '02-01-2022', 289, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(120, '03-01-2022', 403, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(121, '04-01-2022', 554, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(122, '05-01-2022', 304, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(123, '06-01-2022', 289, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(124, '07-01-2022', 270, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(125, '08-01-2022', 134, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(126, '09-01-2022', 270, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(127, '10-01-2022', 829, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(128, '11-01-2022', 344, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(129, '12-01-2022', 388, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(130, '01-01-2023', 364, 1, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(131, NULL, 689, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(132, NULL, 562, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(133, NULL, 477, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(134, NULL, 477, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(135, NULL, 477, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(136, NULL, 477, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(137, NULL, 458, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(138, NULL, 314, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(139, NULL, 430, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(140, NULL, 378, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(141, NULL, 430, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(142, NULL, 498, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(143, NULL, 642, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(144, NULL, 350, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(145, NULL, 145, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(146, NULL, 145, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(147, NULL, 354, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(148, NULL, 260, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(149, NULL, 188, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(150, NULL, 188, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(151, NULL, 300, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(152, NULL, 300, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(153, NULL, 282, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(154, NULL, 364, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(155, NULL, 660, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(156, NULL, 554, 2, 3, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(157, '12-01-2020', 800, 1, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(158, '01-01-2021', 1600, 1, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(159, '02-01-2021', 900, 1, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(160, '03-01-2021', 1300, 1, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(161, '04-01-2021', 1950, 1, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(162, '05-01-2021', 1700, 1, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(163, NULL, 4900, 2, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(164, NULL, 2600, 2, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(165, NULL, 5350, 2, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(166, NULL, 4800, 2, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(167, NULL, 5200, 2, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(168, NULL, 4800, 2, 4, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(169, '2022-05-18 11:30:00', 57.81, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(170, '2022-05-18 12:00:00', 57.75, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(171, '2022-05-18 12:30:00', 55.48, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(172, '2022-05-18 13:00:00', 54.28, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(173, '2022-05-18 13:30:00', 53.14, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(174, '2022-05-18 14:00:00', 52.25, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(175, '2022-05-18 14:30:00', 51.04, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(176, '2022-05-18 15:00:00', 52.49, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(177, '2022-05-18 15:30:00', 55.49, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(178, '2022-05-18 16:00:00', 56.87, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(179, '2022-05-18 16:30:00', 53.73, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(180, '2022-05-18 17:00:00', 56.42, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(181, '2022-05-18 17:30:00', 58.06, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(182, '2022-05-18 18:00:00', 55.62, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(183, '2022-05-18 18:30:00', 58.16, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(184, '2022-05-18 19:00:00', 55.22, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(185, '2022-05-18 19:30:00', 58.67, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(186, '2022-05-18 20:00:00', 60.18, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(187, '2022-05-18 20:30:00', 61.31, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(188, '2022-05-18 21:00:00', 63.25, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(189, '2022-05-18 21:30:00', 65.91, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(190, '2022-05-18 22:00:00', 64.44, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(191, '2022-05-18 22:30:00', 65.97, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(192, '2022-05-18 23:00:00', 62.27, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(193, '2022-05-18 23:30:00', 60.96, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(194, '2022-05-19 00:00:00', 59.34, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(195, '2022-05-19 00:30:00', 55.07, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(196, '2022-05-19 01:00:00', 59.85, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(197, '2022-05-19 01:30:00', 53.79, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(198, '2022-05-19 02:00:00', 51.92, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(199, '2022-05-19 02:30:00', 50.95, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(200, '2022-05-19 03:00:00', 49.65, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(201, '2022-05-19 03:30:00', 48.09, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(202, '2022-05-19 04:00:00', 49.81, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(203, '2022-05-19 04:30:00', 47.85, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(204, '2022-05-19 05:00:00', 49.52, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(205, '2022-05-19 05:30:00', 50.21, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(206, '2022-05-19 06:00:00', 52.22, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(207, '2022-05-19 06:30:00', 54.42, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(208, '2022-05-19 07:00:00', 53.42, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(209, '2022-05-19 07:30:00', 50.91, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(210, '2022-05-19 08:00:00', 58.52, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(211, '2022-05-19 08:30:00', 53.37, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(212, '2022-05-19 09:00:00', 57.58, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(213, '2022-05-19 09:30:00', 59.09, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(214, '2022-05-19 10:00:00', 59.36, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(215, '2022-05-19 10:30:00', 58.71, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(216, '2022-05-19 11:00:00', 59.42, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(217, '2022-05-19 11:30:00', 55.93, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(218, '2022-05-19 12:00:00', 57.71, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(219, '2022-05-19 12:30:00', 50.62, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(220, '2022-05-19 13:00:00', 56.28, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(221, '2022-05-19 13:30:00', 57.37, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(222, '2022-05-19 14:00:00', 53.08, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(223, '2022-05-19 14:30:00', 55.94, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(224, '2022-05-19 15:00:00', 55.82, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(225, '2022-05-19 15:30:00', 53.94, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(226, '2022-05-19 16:00:00', 52.65, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(227, '2022-05-19 16:30:00', 50.25, 1, 5, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(228, 'United States', 35, NULL, 6, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(229, 'Italy', 30, NULL, 6, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(230, 'Other', 35, NULL, 6, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(231, '12-01-2020', 73, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(232, '01-01-2021', 64, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(233, '02-01-2021', 73, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(234, '03-01-2021', 69, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(235, '04-01-2021', 104, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(236, '05-01-2021', 104, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(237, '06-01-2021', 164, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(238, '07-01-2021', 164, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(239, '08-01-2021', 120, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(240, '09-01-2021', 120, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(241, '10-01-2021', 120, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(242, '11-01-2021', 148, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(243, '12-01-2021', 142, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(244, '01-01-2022', 104, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(245, '02-01-2022', 122, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(246, '03-01-2022', 110, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(247, '04-01-2022', 104, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(248, '05-01-2022', 152, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(249, '06-01-2022', 166, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(250, '07-01-2022', 233, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(251, '08-01-2022', 268, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(252, '09-01-2022', 252, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(253, '10-01-2022', 284, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(254, '11-01-2022', 284, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(255, '12-01-2022', 333, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(256, '01-01-2023', 323, 1, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(257, NULL, 184, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(258, NULL, 86, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(259, NULL, 42, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(260, NULL, 378, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(261, NULL, 42, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(262, NULL, 243, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(263, NULL, 38, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(264, NULL, 120, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(265, NULL, 0, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(266, NULL, 0, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(267, NULL, 42, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(268, NULL, 0, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(269, NULL, 84, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(270, NULL, 0, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(271, NULL, 276, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(272, NULL, 0, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(273, NULL, 124, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(274, NULL, 42, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(275, NULL, 124, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(276, NULL, 88, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(277, NULL, 88, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(278, NULL, 215, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(279, NULL, 156, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(280, NULL, 88, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(281, NULL, 124, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(282, NULL, 64, 2, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(283, NULL, 122, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(284, NULL, 170, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(285, NULL, 192, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(286, NULL, 86, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(287, NULL, 102, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(288, NULL, 124, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(289, NULL, 115, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(290, NULL, 115, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(291, NULL, 56, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(292, NULL, 104, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(293, NULL, 0, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(294, NULL, 72, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(295, NULL, 208, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(296, NULL, 186, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(297, NULL, 223, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(298, NULL, 188, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(299, NULL, 114, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(300, NULL, 162, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(301, NULL, 200, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(302, NULL, 150, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(303, NULL, 118, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(304, NULL, 118, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(305, NULL, 76, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(306, NULL, 122, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(307, NULL, 230, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(308, NULL, 268, 3, 8, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(309, '12-01-2020', 6200, 1, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(310, '01-01-2021', 9200, 1, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(311, '02-01-2021', 6600, 1, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(312, '03-01-2021', 8800, 1, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(313, '04-01-2021', 5200, 1, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(314, '05-01-2021', 9200, 1, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(315, NULL, -4000, 2, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(316, NULL, -2600, 2, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(317, NULL, -5350, 2, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(318, NULL, -4000, 2, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(319, NULL, -7500, 2, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(320, NULL, -2000, 2, 9, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(321, 'Reasons', 131, 1, 10, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(322, NULL, 100, 2, 10, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(323, NULL, 91, 3, 10, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(324, NULL, 65, 4, 10, '2025-08-19 07:29:12', '2025-08-19 07:29:12'),
(325, NULL, 72, 5, 10, '2025-08-19 07:29:12', '2025-08-19 07:29:12');

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
-- Dumping data for table `deposit_policies`
--

INSERT INTO `deposit_policies` (`policy_id`, `name`, `deposit_percentage`, `deposit_fixed_amount_vnd`, `description`, `priority`, `conditions`, `applies_to_weekend`, `applies_to_holiday`, `min_days_before_checkin`, `min_booking_amount`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Đặt cọc 50%', 50.00, NULL, 'Yêu cầu đặt cọc 50% tổng giá haha', 0, NULL, 0, 0, NULL, NULL, 1, '2025-06-11 02:24:24', '2025-07-10 03:55:39'),
(10, 'Đặt cọc 20%', 20.00, 0.00, 'Đặt cọc 20% giá trị booking', 0, NULL, 0, 0, NULL, NULL, 1, '2025-07-12 09:51:47', '2025-07-21 03:41:18'),
(11, 'Đặt cọc 50% - Lễ tết', 50.00, 0.00, 'Đặt cọc 50% cho ngày lễ tết', 0, NULL, 0, 0, NULL, NULL, 1, '2025-07-12 09:51:47', '2025-07-12 09:51:47');

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
(1, 1, 80.00, 10.00, 1, '2025-06-11 02:44:33', '2025-06-29 19:50:57', 5, 0),
(2, 1, 90.00, 20.00, 1, '2025-06-11 02:44:33', '2025-06-11 02:44:33', 5, 0),
(3, 2, 80.00, 20.00, 1, '2025-06-14 03:41:08', '2025-06-14 03:42:03', 5, 0),
(4, 2, 90.00, 30.00, 1, '2025-06-14 04:17:32', '2025-06-14 04:17:32', 5, 0),
(5, 4, 70.00, 7.00, 1, '2025-06-29 21:11:10', '2025-06-29 21:11:21', 5, 0);

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
(1, 'Lễ hội pháo hoa Đà Nẵng', '2025-06-28', '2025-07-07', 'Sự kiện pháo hoa quốc tế', 1, '2025-06-11 02:20:27', '2025-06-29 07:11:13'),
(3, 'Sự kiện có 1 0 2', '2025-06-30', '2025-07-01', '102', 1, '2025-06-29 11:36:55', '2025-06-29 11:36:55'),
(4, 'Nguyễn Anh Đức', '2025-07-02', '2025-07-03', 't', 1, '2025-06-29 09:45:40', '2025-06-29 09:45:40');

-- --------------------------------------------------------

--
-- Table structure for table `extension_policies`
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
-- Dumping data for table `extension_policies`
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
-- Table structure for table `extension_requests`
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
-- Dumping data for table `extension_requests`
--

INSERT INTO `extension_requests` (`request_id`, `booking_id`, `extension_policy_id`, `new_check_out_date`, `extension_days`, `extension_fee_vnd`, `status`, `processed_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, 160, 12, '2025-08-10', -8, 5866.67, 'Approved', NULL, NULL, '2025-08-01 12:59:25', '2025-08-01 12:59:25'),
(2, 160, 12, '2025-08-10', -8, 7430.93, 'Approved', NULL, NULL, '2025-08-01 13:03:34', '2025-08-01 13:03:34'),
(3, 160, 6, '2025-08-10', 8, 14118.40, 'Approved', NULL, NULL, '2025-08-01 13:08:43', '2025-08-01 13:08:43');

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
  `priority` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo câu hỏi',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật câu hỏi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu trữ câu hỏi thường gặp và câu trả lời';

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`faq_id`, `question_en`, `question_vi`, `answer_en`, `answer_vi`, `sort_order`, `is_active`, `priority`, `created_at`, `updated_at`) VALUES
(1, 'Do you serve breakfast?', 'Họ có phục vụ bữa sáng không?', 'Yes, we offer an excellent buffet breakfast from 6:30 AM to 10:30 AM daily with both international and Vietnamese cuisine.', 'Có, chúng tôi cung cấp bữa sáng buffet tuyệt hảo từ 6:30 đến 10:30 hàng ngày với ẩm thực quốc tế và Việt Nam.', 5, 1, NULL, '2025-05-23 02:50:42', '2025-06-02 03:21:17'),
(2, 'Is parking available?', 'Chỗ nghỉ có chỗ đỗ xe không?', 'Yes, we provide complimentary self-parking for hotel guests. Valet parking is also available for an additional charge.', 'Có, chúng tôi cung cấp chỗ đỗ xe tự phục vụ miễn phí cho khách khách sạn. Dịch vụ đỗ xe có người phục vụ cũng có sẵn với phí bổ sung.', 3, 1, NULL, '2025-05-23 02:50:42', '2025-06-02 03:33:00'),
(3, 'Do you provide airport shuttle service?', 'Chỗ nghỉ có dịch vụ đưa đón sân bay không?', 'Yes, we offer airport transfer service for $25 per trip. Please contact our concierge to arrange your transfer.', 'Có, chúng tôi cung cấp dịch vụ đưa đón sân bay với giá $25 mỗi chuyến. Vui lòng liên hệ với lễ tân để sắp xếp chuyến đi.', 10, 1, NULL, '2025-05-23 02:50:42', '2025-06-02 03:33:16'),
(4, 'What is your WiFi ?', 'Chỗ nghỉ có  Wi-Fi ra sao?', 'High-speed WiFi is complimentary throughout the hotel including all guest rooms and public areas.', 'Wi-Fi tốc độ cao miễn phí trong toàn bộ khách sạn bao gồm tất cả các phòng khách và khu vực công cộng.', 0, 1, NULL, '2025-05-23 02:50:42', '2025-06-12 00:57:25'),
(7, 'Am i handsome?', 'Tôi có đẹp trai không?', 'Yes Sirrrrr', 'Chắc chắn  rồi broooo', 2, 1, NULL, '2025-06-02 02:14:43', '2025-06-02 03:01:20');

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
(4, NULL, 'holiday', NULL, NULL, 1, NULL, NULL, NULL, 30.00, 0, '2025-06-23 03:03:08', '2025-06-29 02:34:28', 1, 1),
(5, NULL, 'season', NULL, NULL, NULL, 'Mùa cao điểm', '2025-06-01', '2025-08-31', 20.00, 1, '2025-06-23 03:03:08', '2025-06-26 14:47:48', 3, 0),
(12, NULL, 'weekend', '\"[\\\"Saturday\\\",\\\"Sunday\\\"]\"', NULL, NULL, NULL, NULL, NULL, 10.00, 1, '2025-06-23 00:45:55', '2025-06-29 07:29:36', 4, 0),
(18, NULL, 'event', NULL, 3, NULL, NULL, '2025-06-30', '2025-07-01', -6.00, 1, '2025-06-29 05:02:45', '2025-06-29 09:09:13', 5, 0);

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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `total_amount_vnd` decimal(15,2) NOT NULL,
  `issued_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Draft','Sent','Paid') COLLATE utf8mb4_general_ci DEFAULT 'Draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin hóa đơn';

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `booking_id`, `total_amount_vnd`, `issued_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 24, 2900000.00, '2025-08-16 10:05:05', 'Draft', '2025-08-16 10:05:05', '2025-08-16 10:05:05'),
(2, 142, 14650000.00, '2025-08-19 10:08:27', 'Draft', '2025-08-19 10:08:27', '2025-08-19 10:08:27');

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
-- Table structure for table `media_files`
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
-- Dumping data for table `media_files`
--

INSERT INTO `media_files` (`id`, `filename`, `filepath`, `alt_text`, `title`, `type`, `size`, `used_in`, `created_at`, `updated_at`) VALUES
(1, '1753807880_screenshot-1png.png', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Hình ảnh bài viết', 'Hình ảnh bài viết', 'image/png', 60413, 'news', '2025-07-29 09:51:20', '2025-08-11 21:28:03'),
(2, '1753807896_logopng.png', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Hình ảnh bài viết', 'Hình ảnh bài viết', 'image/png', 1818956, 'news', '2025-07-29 09:51:36', '2025-08-11 21:28:06'),
(3, '1753852598_logopng.png', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Hình ảnh bài viết', 'Hình ảnh bài viết', 'image/png', 1818956, 'news', '2025-07-29 22:16:38', '2025-08-11 21:28:08'),
(4, '1754039682_screenshot-1png.png', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Hình ảnh bài viết', 'Hình ảnh bài viết', 'image/png', 60413, 'news', '2025-08-01 02:14:42', '2025-08-11 21:28:10'),
(5, 'hotel-lobby.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Sảnh khách sạn sang trọng với thiết kế hiện đại', 'Sảnh Khách Sạn LavishStay', 'image/jpeg', 2048576, 'news', '2025-08-11 02:37:45', '2025-08-11 21:28:11'),
(6, 'deluxe-room.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Phòng deluxe với view biển tuyệt đẹp', 'Phòng Deluxe Sea View', 'image/jpeg', 1876543, 'news', '2025-08-11 02:37:45', '2025-08-11 21:28:13'),
(7, 'restaurant-dining.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Nhà hàng với không gian ấm cúng và món ăn tinh tế', 'Nhà Hàng LavishStay', 'image/jpeg', 1654321, 'news', '2025-08-11 02:37:45', '2025-08-11 21:28:15'),
(8, 'swimming-pool.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Hồ bơi infinity với view toàn cảnh thành phố', 'Hồ Bơi Infinity', 'image/jpeg', 2234567, 'news', '2025-08-11 02:37:45', '2025-08-11 21:28:16'),
(9, 'spa-treatment.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Phòng spa với không gian thư giãn và massage', 'Spa & Massage', 'image/jpeg', 1987654, 'news', '2025-08-11 02:37:45', '2025-08-11 21:28:19'),
(10, 'beach-view.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Bãi biển tuyệt đẹp với cát trắng và nước trong xanh', 'Bãi Biển Paradise', 'image/jpeg', 2345678, 'news', '2025-08-11 02:37:45', '2025-08-11 21:28:21'),
(11, 'conference-room.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Phòng hội nghị hiện đại với thiết bị công nghệ cao', 'Phòng Hội Nghị', 'image/jpeg', 1765432, 'news', '2025-08-11 02:37:45', '2025-08-11 21:28:24'),
(12, 'fitness-center.jpg', 'http://localhost:8888/storage/room-types/1/1.jpg', 'Phòng gym với thiết bị tập luyện hiện đại', 'Trung Tâm Thể Dục', 'image/jpeg', 1456789, 'news', '2025-08-11 02:37:45', '2025-08-11 21:28:27'),
(13, '1755597527_dat-phong-2jpg.jpg', 'http://localhost/storage/media/1755597527_dat-phong-2jpg.jpg', 'Hình ảnh bài viết', 'Hình ảnh bài viết', 'image/jpeg', 1019302, 'news', '2025-08-19 09:58:47', '2025-08-19 09:58:47');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
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
  `message_type` enum('text','image','file','system') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
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
(21, 3, 'guest', NULL, 'Hii', 0, '2025-07-23 00:24:33', '2025-08-15 08:00:25', 1, NULL, NULL),
(22, 3, 'staff', NULL, 'Câu hỏi của bạn đã được chuyển đến nhân viên hỗ trợ. Chúng tôi sẽ trả lời sớm nhất có thể.', 1, '2025-07-23 00:24:33', '2025-07-23 00:24:33', 0, 'system', NULL),
(23, 1, 'staff', 1, 'Có máy bay không', 0, '2025-07-23 00:29:05', '2025-07-23 00:29:05', 0, NULL, NULL),
(24, 4, 'staff', 1, 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ xử lý yêu cầu của bạn sớm nhất có thể.', 0, '2025-07-28 04:41:33', '2025-07-28 04:41:33', 0, NULL, NULL);

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
(21, '2025_08_13_160235_create_payment_settings_table', 13),
(22, '2025_08_14_104639_create_audit_logs_table', 14),
(23, '2025_08_18_233915_create_notification_types_table', 15),
(24, '2025_08_18_234028_create_notifications_table', 16),
(25, '2025_08_18_234051_create_user_notification_settings_table', 16),
(26, '2025_08_19_154429_fix_notification_type_id_nullable', 17);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'Khóa chính, mã bài viết',
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Đường dẫn không dấu, duy nhất cho mỗi bài viết (SEO)',
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tiêu đề bài viết',
  `summary` text COLLATE utf8mb4_general_ci COMMENT 'Tóm tắt ngắn nội dung bài viết',
  `content` longtext COLLATE utf8mb4_general_ci COMMENT 'Nội dung chi tiết bài viết (HTML)',
  `tags` json DEFAULT NULL COMMENT 'Danh sách tag (mảng string, phục vụ tìm kiếm, phân loại)',
  `thumbnail_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID ảnh đại diện (liên kết media_files)',
  `author_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID tác giả (liên kết users)',
  `category_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID chuyên mục/danh mục (liên kết news_categories)',
  `meta_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tiêu đề SEO (meta title)',
  `meta_description` text COLLATE utf8mb4_general_ci COMMENT 'Mô tả SEO (meta description)',
  `meta_keywords` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Từ khóa SEO (meta keywords)',
  `canonical_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'URL chuẩn SEO (canonical)',
  `schema_json` json DEFAULT NULL COMMENT 'Dữ liệu cấu trúc SEO (schema.org, dạng JSON)',
  `views` int DEFAULT '0' COMMENT 'Số lượt xem bài viết',
  `status` tinyint DEFAULT '1' COMMENT 'Trạng thái bài viết (1: hiển thị, 0: ẩn, nháp...)',
  `is_featured` tinyint(1) DEFAULT '1',
  `published_at` datetime DEFAULT NULL COMMENT 'Thời điểm xuất bản',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `slug`, `title`, `summary`, `content`, `tags`, `thumbnail_id`, `author_id`, `category_id`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `schema_json`, `views`, `status`, `is_featured`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'kham-pha-khong-gian-sang-trong-tai-lavishstay-resort', 'Khám Phá Không Gian Sang Trọng Tại LavishStay Resort', 'Trải nghiệm không gian nghỉ dưỡng đẳng cấp với thiết kế hiện đại và dịch vụ 5 sao tại LavishStay Resort.', '<p>LavishStay Resort mang đến cho du khách một trải nghiệm nghỉ dưỡng đẳng cấp với không gian sang trọng và dịch vụ tận tâm. Tọa lạc tại vị trí đắc địa, resort sở hữu kiến trúc hiện đại hòa quyện với thiên nhiên.</p><p>Các phòng nghỉ được thiết kế tinh tế với đầy đủ tiện nghi cao cấp, mang đến sự thoải mái tối đa cho khách hàng. Từ phòng Deluxe đến Suite Presidential, mỗi không gian đều được chăm chút kỹ lưỡng về từng chi tiết.</p><p>Resort còn sở hữu hệ thống tiện ích đa dạng bao gồm nhà hàng fine dining, spa cao cấp, hồ bơi infinity và trung tâm thể dục hiện đại.</p>', '[\"resort\", \"luxury\", \"accommodation\", \"travel\"]', 6, 3, 2, 'LavishStay Resort - Không Gian Nghỉ Dưỡng Đẳng Cấp 5 Sao', 'Khám phá LavishStay Resort với không gian sang trọng, dịch vụ 5 sao và trải nghiệm nghỉ dưỡng đẳng cấp. Đặt phòng ngay để nhận ưu đãi đặc biệt.', 'lavishstay, resort, luxury hotel, 5 star, nghỉ dưỡng, khách sạn cao cấp', '/news/kham-pha-khong-gian-sang-trong-tai-lavishstay-resort', '{\"@type\": \"Article\", \"author\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"@context\": \"https://schema.org\", \"headline\": \"Khám Phá Không Gian Sang Trọng Tại LavishStay Resort\", \"publisher\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"description\": \"Trải nghiệm không gian nghỉ dưỡng đẳng cấp với thiết kế hiện đại và dịch vụ 5 sao tại LavishStay Resort.\"}', 2806, 1, 1, '2025-07-29 16:37:45', '2025-08-11 02:37:45', '2025-08-13 20:41:46'),
(2, 'uu-dai-mua-he-2024-giam-gia-len-den-40-phan-tram', 'Ưu Đãi Mùa Hè 2024 - Giảm Giá Lên Đến 40%', 'Chương trình ưu đãi mùa hè đặc biệt với mức giảm giá lên đến 40% cho tất cả các hạng phòng tại LavishStay.', '<p>Mùa hè đã đến và LavishStay mang đến chương trình ưu đãi đặc biệt dành cho tất cả du khách. Với mức giảm giá lên đến 40%, đây là cơ hội tuyệt vời để bạn trải nghiệm kỳ nghỉ trong mơ.</p><h3>Ưu đãi bao gồm:</h3><ul><li>Giảm 40% cho phòng Suite và Presidential</li><li>Giảm 30% cho phòng Deluxe và Superior</li><li>Giảm 20% cho tất cả dịch vụ spa</li><li>Buffet sáng miễn phí cho trẻ em dưới 12 tuổi</li><li>Late check-out đến 14:00 miễn phí</li></ul><p>Chương trình có hiệu lực từ ngày 1/6 đến 31/8/2024. Áp dụng cho các đêm nghỉ từ Chủ Nhật đến Thứ Năm.</p>', '[\"promotion\", \"summer\", \"discount\", \"offer\"]', 7, 3, 3, 'Ưu Đãi Mùa Hè 2024 - Giảm Đến 40% Tại LavishStay Resort', 'Đừng bỏ lỡ chương trình ưu đãi mùa hè với giảm giá lên đến 40% tất cả hạng phòng. Đặt ngay để nhận ưu đãi tốt nhất!', 'ưu đãi, khuyến mãi, giảm giá, mùa hè, summer promotion', '/news/uu-dai-mua-he-2024-giam-gia-len-den-40-phan-tram', '{\"@type\": \"Article\", \"author\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"@context\": \"https://schema.org\", \"headline\": \"Ưu Đãi Mùa Hè 2024 - Giảm Giá Lên Đến 40%\", \"publisher\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"description\": \"Chương trình ưu đãi mùa hè đặc biệt với mức giảm giá lên đến 40% cho tất cả các hạng phòng tại LavishStay.\"}', 4327, 1, 0, '2025-08-03 16:37:45', '2025-08-11 02:37:45', '2025-08-11 02:37:45'),
(3, 'top-10-dia-diem-du-lich-khong-the-bo-qua-gan-lavishstay', 'Top 10 Địa Điểm Du Lịch Không Thể Bỏ Qua Gần LavishStay', 'Khám phá những địa điểm du lịch hấp dẫn xung quanh khu vực LavishStay Resort với hướng dẫn chi tiết từ A đến Z.', '<p>Khi lưu trú tại LavishStay Resort, bạn sẽ có cơ hội khám phá nhiều địa điểm du lịch tuyệt vời xung quanh. Dưới đây là danh sách 10 địa điểm không thể bỏ qua:</p><h3>1. Bãi Biển Paradise</h3><p>Chỉ cách resort 5 phút đi bộ, bãi biển Paradise với làn nước trong xanh và bãi cát trắng mịn là nơi lý tưởng để thư giãn và tắm nắng.</p><h3>2. Chợ Đêm Địa Phương</h3><p>Trải nghiệm văn hóa địa phương qua những món ăn đường phố đặc sắc và các sản phẩm thủ công truyền thống.</p><h3>3. Đảo San Hô</h3><p>Tour lặn ngắm san hô với nhiều loài cá nhiệt đới đầy màu sắc, phù hợp cho cả người mới bắt đầu và chuyên nghiệp.</p><p>... và còn 7 địa điểm thú vị khác đang chờ bạn khám phá!</p>', '[\"travel guide\", \"attractions\", \"tourism\", \"local\"]', 2, 7, 7, 'Top 10 Địa Điểm Du Lịch Gần LavishStay Resort - Hướng Dẫn Chi Tiết', 'Khám phá 10 địa điểm du lịch tuyệt vời xung quanh LavishStay Resort. Hướng dẫn đầy đủ về các hoạt động và điểm tham quan không thể bỏ qua.', 'du lịch, điểm tham quan, hướng dẫn, tourism, attractions, travel guide', '/news/top-10-dia-diem-du-lich-khong-the-bo-qua-gan-lavishstay', '{\"@type\": \"Article\", \"author\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"@context\": \"https://schema.org\", \"headline\": \"Top 10 Địa Điểm Du Lịch Không Thể Bỏ Qua Gần LavishStay\", \"publisher\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"description\": \"Khám phá những địa điểm du lịch hấp dẫn xung quanh khu vực LavishStay Resort với hướng dẫn chi tiết từ A đến Z.\"}', 3663, 1, 0, '2025-08-04 16:37:45', '2025-08-11 02:37:45', '2025-08-19 09:56:14'),
(4, 'grand-opening-le-khai-truong-nha-hang-rooftop-moi', 'Grand Opening - Lễ Khai Trương Nhà Hàng Rooftop Mới', 'Tham gia lễ khai trương nhà hàng rooftop mới với không gian 360 độ và thực đơn fine dining độc đáo.', '<p>LavishStay Resort hân hạnh giới thiệu nhà hàng rooftop mới - Sky Lounge với tầm nhìn 360 độ tuyệt đẹp ra toàn thành phố và biển cả.</p><h3>Điểm đặc biệt của Sky Lounge:</h3><ul><li>Không gian mở với tầm nhìn panoramic</li><li>Thực đơn fusion cuisine do chef Michelin star thiết kế</li><li>Bar cocktail với hơn 200 loại đồ uống cao cấp</li><li>Live music mỗi tối từ 19:00-22:00</li><li>Không gian riêng tư cho các sự kiện đặc biệt</li></ul><p>Lễ khai trương sẽ diễn ra vào 20:00 ngày 15/12/2024 với sự tham gia của các celebrity và food blogger nổi tiếng. Khách mời sẽ được thưởng thức cocktail welcome drink và canapé miễn phí.</p>', '[\"event\", \"restaurant\", \"opening\", \"rooftop\"]', 7, 2, 7, 'Khai Trương Sky Lounge - Nhà Hàng Rooftop Đẳng Cấp Tại LavishStay', 'Tham gia lễ khai trương Sky Lounge - nhà hàng rooftop với tầm nhìn 360 độ và thực đơn fine dining độc đáo tại LavishStay Resort.', 'nhà hàng rooftop, khai trương, sky lounge, fine dining, event', '/news/grand-opening-le-khai-truong-nha-hang-rooftop-moi', '{\"@type\": \"Article\", \"author\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"@context\": \"https://schema.org\", \"headline\": \"Grand Opening - Lễ Khai Trương Nhà Hàng Rooftop Mới\", \"publisher\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"description\": \"Tham gia lễ khai trương nhà hàng rooftop mới với không gian 360 độ và thực đơn fine dining độc đáo.\"}', 6308, 1, 0, '2025-08-01 16:37:45', '2025-08-11 02:37:45', '2025-08-11 23:26:14'),
(5, 'thuc-don-mua-dong-dac-biet-huong-vi-am-thuc-chau-a', 'Thực Đơn Mùa Đông Đặc Biệt - Hương Vị Âm Thực Châu Á', 'Khám phá thực đơn mùa đông với những món ăn truyền thống châu Á được chế biến bởi đội ngũ chef chuyên nghiệp.', '<p>Mùa đông đã đến và LavishStay Restaurant mang đến thực đơn đặc biệt với hương vị ấm áp của ẩm thực châu Á truyền thống.</p><h3>Món khai vị:</h3><ul><li>Dumpling tôm hấp với sốt gừng</li><li>Salad đu đủ Thái cay nhẹ</li><li>Chả cá Lã Vọng truyền thống</li></ul><h3>Món chính:</h3><ul><li>Lẩu Thái tôm hùm chua cay</li><li>Bún bò Huế chính hiệu</li><li>Cơm niêu Singapore với tôm rang</li><li>Mì Udon Nhật Bản nước dashi đậm đà</li></ul><h3>Tráng miệng:</h3><ul><li>Chè đậu xanh nước cốt dừa</li><li>Mochi ice cream vị matcha</li><li>Bánh flan caramen</li></ul><p>Thực đơn có hiệu lực từ 1/12/2024 đến 28/2/2025. Đặt bàn trước để được ưu tiên phục vụ.</p>', '[\"cuisine\", \"asian food\", \"winter menu\", \"restaurant\"]', 11, 6, 4, 'Thực Đơn Mùa Đông Châu Á - Ẩm Thực Đặc Sắc Tại LavishStay', 'Thưởng thức thực đơn mùa đông đặc biệt với hương vị ẩm thực châu Á truyền thống tại nhà hàng LavishStay Resort.', 'ẩm thực châu á, thực đơn mùa đông, nhà hàng, món ăn đặc sắc', '/news/thuc-don-mua-dong-dac-biet-huong-vi-am-thuc-chau-a', '{\"@type\": \"Article\", \"author\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"@context\": \"https://schema.org\", \"headline\": \"Thực Đơn Mùa Đông Đặc Biệt - Hương Vị Âm Thực Châu Á\", \"publisher\": {\"name\": \"LavishStay Resort\", \"@type\": \"Organization\"}, \"description\": \"Khám phá thực đơn mùa đông với những món ăn truyền thống châu Á được chế biến bởi đội ngũ chef chuyên nghiệp.\"}', 1294, 1, 1, '2025-07-17 16:37:45', '2025-08-11 02:37:45', '2025-08-12 20:56:26'),
(6, 'bai-viet-mau-so-6', 'Bài viết mẫu số 6', 'Đây là bài viết mẫu số 6 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 6.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 1, 3, 7, 'Bài viết mẫu số 6', 'Mô tả bài viết mẫu số 6', 'sample, test, demo', '/news/bai-viet-mau-so-6', '[]', 122, 1, 0, '2025-07-28 16:37:45', '2025-08-11 02:37:45', '2025-08-11 02:37:45'),
(7, 'bai-viet-mau-so-7', 'Bài viết mẫu số 7', 'Đây là bài viết mẫu số 7 để test dữ liệu.', '<p>Nội dung chi tiết của bài viết mẫu số 7.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '[\"sample\", \"test\", \"demo\"]', 11, 6, 5, 'Bài viết mẫu số 7', 'Mô tả bài viết mẫu số 7', 'sample, test, demo', '/news/bai-viet-mau-so-7', '[]', 168, 1, 0, '2025-06-25 16:37:45', '2025-08-11 02:37:45', '2025-08-11 02:37:45');

-- --------------------------------------------------------

--
-- Table structure for table `news_categories`
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
-- Dumping data for table `news_categories`
--

INSERT INTO `news_categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(2, 'Tin Tức Khách Sạn', 'tin-tuc-khach-san', 'Các tin tức mới nhất về khách sạn và dịch vụ', '2025-08-11 02:37:45', '2025-08-11 02:37:45'),
(3, 'Ưu Đãi & Khuyến Mãi', 'uu-dai-khuyen-mai', 'Thông tin về các chương trình ưu đãi, khuyến mãi đặc biệt', '2025-08-11 02:37:45', '2025-08-11 02:37:45'),
(4, 'Hướng Dẫn Du Lịch', 'huong-dan-du-lich', 'Các bài viết hướng dẫn du lịch, địa điểm tham quan', '2025-08-11 02:37:45', '2025-08-11 02:37:45'),
(5, 'Sự Kiện', 'su-kien', 'Thông tin về các sự kiện, lễ hội, hoạt động tại khách sạn', '2025-08-11 02:37:45', '2025-08-11 02:37:45'),
(6, 'Ẩm Thực', 'am-thuc', 'Giới thiệu về ẩm thực, nhà hàng và các món ăn đặc sắc', '2025-08-11 02:37:45', '2025-08-11 02:37:45'),
(7, 'Tips & Tricks', 'tips-tricks', 'Các mẹo và kinh nghiệm hữu ích cho khách du lịch', '2025-08-11 02:37:45', '2025-08-11 02:37:45');

-- --------------------------------------------------------

--
-- Table structure for table `news_comments`
--

CREATE TABLE `news_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `news_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `likes` int DEFAULT '0',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_comments`
--

INSERT INTO `news_comments` (`id`, `news_id`, `user_id`, `content`, `created_at`, `likes`, `parent_id`, `updated_at`) VALUES
(1, 3, 1, 'dfdfdf', '2025-08-16 10:01:40', 1, NULL, '2025-08-16 10:01:50');

-- --------------------------------------------------------

--
-- Table structure for table `news_user_actions`
--

CREATE TABLE `news_user_actions` (
  `id` bigint UNSIGNED NOT NULL,
  `news_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `is_liked` tinyint DEFAULT '0',
  `is_bookmarked` tinyint DEFAULT '0',
  `rating` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_user_actions`
--

INSERT INTO `news_user_actions` (`id`, `news_id`, `user_id`, `is_liked`, `is_bookmarked`, `rating`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 0, 0, 5, '2025-08-16 09:57:08', '2025-08-16 10:01:18');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_type_id` bigint UNSIGNED DEFAULT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `priority` enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '?',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3B82F6',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `read_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','sent','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `notification_type_id`, `notifiable_type`, `notifiable_id`, `title`, `message`, `data`, `priority`, `icon`, `color`, `url`, `read_at`, `status`, `created_at`, `updated_at`) VALUES
('5ed39a69-6a48-4a17-9797-b5e64b83e2a4', NULL, 'App\\Models\\User', 1, 'Service Test Notification', 'This is a test notification sent via NotificationService at 15:45:47', '{\"test\": true, \"debug\": true}', 'normal', '📣', '#3B82F6', '#service-test', '2025-08-19 08:46:46', 'sent', '2025-08-19 08:45:47', '2025-08-19 08:46:46'),
('63529586-0969-4cc1-b62f-47d9bb56abe7', NULL, 'App\\Models\\User', 1, 'Test Notification #3', 'This is test notification number 3 sent at 15:46:03', '{\"test\": true, \"sequence\": 3, \"timestamp\": \"2025-08-19 15:46:03\"}', 'normal', '📣', '#3B82F6', '#test', '2025-08-19 08:46:14', 'sent', '2025-08-19 08:46:03', '2025-08-19 08:46:14'),
('9a319956-967f-4cfb-8d3d-c931ab9290f8', NULL, 'App\\Models\\User', 1, 'Test Notification #2', 'This is test notification number 2 sent at 15:48:40', '{\"test\": true, \"sequence\": 2, \"timestamp\": \"2025-08-19 15:48:40\"}', 'normal', '📣', '#3B82F6', '#test', '2025-08-19 08:49:15', 'sent', '2025-08-19 08:48:40', '2025-08-19 08:49:15'),
('d18c0e37-a93c-4d01-8866-4bb9b51f3ed9', NULL, 'App\\Models\\User', 1, 'Test Notification #1', 'This is test notification number 1 sent at 15:46:01', '{\"test\": true, \"sequence\": 1, \"timestamp\": \"2025-08-19 15:46:01\"}', 'normal', '📣', '#3B82F6', '#test', '2025-08-19 08:46:36', 'sent', '2025-08-19 08:46:01', '2025-08-19 08:46:36'),
('e4cb3ce0-f1e1-486e-911a-9323a3664b71', NULL, 'App\\Models\\User', 1, 'Test Notification #3', 'This is test notification number 3 sent at 15:48:42', '{\"test\": true, \"sequence\": 3, \"timestamp\": \"2025-08-19 15:48:42\"}', 'normal', '📣', '#3B82F6', '#test', '2025-08-19 08:57:26', 'sent', '2025-08-19 08:48:42', '2025-08-19 08:57:26'),
('e8628fd1-8677-4903-a5c4-5bb905c7acce', NULL, 'App\\Models\\User', 1, 'Test Notification #1', 'This is test notification number 1 sent at 15:48:39', '{\"test\": true, \"sequence\": 1, \"timestamp\": \"2025-08-19 15:48:39\"}', 'normal', '📣', '#3B82F6', '#test', NULL, 'sent', '2025-08-19 08:48:39', '2025-08-19 08:48:39'),
('fb3239ac-138a-4b06-af71-168fa860433f', NULL, 'App\\Models\\User', 1, 'Test Notification #2', 'This is test notification number 2 sent at 15:46:02', '{\"test\": true, \"sequence\": 2, \"timestamp\": \"2025-08-19 15:46:02\"}', 'normal', '📣', '#3B82F6', '#test', '2025-08-19 08:46:31', 'sent', '2025-08-19 08:46:02', '2025-08-19 08:46:31');

-- --------------------------------------------------------

--
-- Table structure for table `notification_types`
--

CREATE TABLE `notification_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_template` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '?',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3B82F6',
  `target_roles` json NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_types`
--

INSERT INTO `notification_types` (`id`, `name`, `title`, `message_template`, `priority`, `icon`, `color`, `target_roles`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'booking_new', 'Đặt phòng mới', 'Có đặt phòng mới #{booking_id} từ khách hàng {customer_name}. Phòng: {room_number}, Check-in: {checkin_date}', 'high', '🏨', '#10B981', '[\"admin\", \"hotel_manager\", \"receptionist\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(2, 'booking_cancelled', 'Hủy đặt phòng', 'Đặt phòng #{booking_id} đã bị hủy bởi {customer_name}. Lý do: {reason}', 'normal', '❌', '#EF4444', '[\"admin\", \"hotel_manager\", \"receptionist\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(3, 'booking_modified', 'Thay đổi đặt phòng', 'Đặt phòng #{booking_id} đã được thay đổi. Khách hàng: {customer_name}', 'normal', '✏️', '#F59E0B', '[\"admin\", \"hotel_manager\", \"receptionist\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(4, 'checkin_reminder', 'Nhắc nhở check-in', 'Khách hàng {customer_name} sẽ check-in hôm nay. Phòng: {room_number}', 'normal', '🔔', '#3B82F6', '[\"receptionist\", \"housekeeping\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(5, 'checkout_completed', 'Hoàn tất check-out', 'Khách hàng {customer_name} đã check-out khỏi phòng {room_number}', 'normal', '🚪', '#6B7280', '[\"receptionist\", \"housekeeping\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(6, 'payment_success', 'Thanh toán thành công', 'Thanh toán thành công {amount} VND cho đặt phòng #{booking_id}', 'normal', '💰', '#10B981', '[\"admin\", \"hotel_manager\", \"finance\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(7, 'payment_failed', 'Thanh toán thất bại', 'Thanh toán thất bại cho đặt phòng #{booking_id}. Số tiền: {amount} VND. Lý do: {reason}', 'high', '❗', '#EF4444', '[\"admin\", \"finance\", \"receptionist\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(8, 'refund_requested', 'Yêu cầu hoàn tiền', 'Khách hàng {customer_name} yêu cầu hoàn tiền {amount} VND cho đặt phòng #{booking_id}', 'high', '💸', '#F59E0B', '[\"admin\", \"hotel_manager\", \"finance\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(9, 'room_maintenance', 'Bảo trì phòng', 'Phòng {room_number} cần bảo trì. Vấn đề: {issue}', 'high', '🔧', '#F59E0B', '[\"admin\", \"hotel_manager\", \"housekeeping\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(10, 'room_cleaning_urgent', 'Dọn phòng khẩn cấp', 'Phòng {room_number} cần dọn dẹp khẩn cấp trước {time}', 'urgent', '🧹', '#EF4444', '[\"housekeeping\", \"receptionist\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(11, 'review_new', 'Đánh giá mới', 'Có đánh giá mới từ khách hàng {customer_name}. Rating: {rating}/5', 'normal', '⭐', '#F59E0B', '[\"marketing\", \"hotel_manager\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(12, 'review_negative', 'Đánh giá tiêu cực', 'Đánh giá tiêu cực ({rating}/5) từ khách hàng {customer_name}. Cần xử lý ngay!', 'urgent', '😞', '#EF4444', '[\"admin\", \"hotel_manager\", \"marketing\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(13, 'system_error', 'Lỗi hệ thống', 'Phát hiện lỗi hệ thống: {error_message}', 'urgent', '🚨', '#EF4444', '[\"admin\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(14, 'system_maintenance', 'Bảo trì hệ thống', 'Hệ thống sẽ bảo trì từ {start_time} đến {end_time}', 'high', '⚙️', '#6B7280', '[\"admin\", \"hotel_manager\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19'),
(15, 'staff_shift_reminder', 'Nhắc nhở ca làm việc', 'Ca làm việc của bạn sẽ bắt đầu trong {minutes} phút', 'normal', '⏰', '#3B82F6', '[\"receptionist\", \"housekeeping\"]', 1, '2025-08-19 07:30:19', '2025-08-19 07:30:19');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
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
-- Dumping data for table `payment`
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
(89, 142, 14650000.00, 'full', 'completed', 'CPAY_LVS142120903_1752754226', '2025-07-17 05:09:03', '2025-08-19 10:07:59'),
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
(123, 24, 11000000.00, 'vietqr', 'completed', '1', '2025-08-05 07:02:22', '2025-08-05 07:02:22'),
(124, 161, 22000.00, 'vietqr', 'pending', NULL, '2025-08-13 04:00:42', '2025-08-13 04:00:42'),
(125, 162, 132000.00, 'vietqr', 'pending', NULL, '2025-08-13 15:19:24', '2025-08-13 15:19:24'),
(126, 163, 132000.00, 'vietqr', 'pending', NULL, '2025-08-13 15:38:26', '2025-08-13 15:38:26'),
(127, 164, 132000.00, 'vietqr', 'pending', NULL, '2025-08-13 15:40:16', '2025-08-13 15:40:16'),
(128, 165, 132000.00, 'vietqr', 'pending', NULL, '2025-08-13 15:41:28', '2025-08-13 15:41:28'),
(129, 166, 132000.00, 'vietqr', 'pending', NULL, '2025-08-13 15:47:16', '2025-08-13 15:47:16'),
(130, 167, 132000.00, 'vietqr', 'pending', NULL, '2025-08-13 15:51:23', '2025-08-13 15:51:23'),
(131, 168, 132000.00, 'vietqr', 'pending', NULL, '2025-08-13 15:54:41', '2025-08-13 15:54:41'),
(132, 169, 132000.00, 'vietqr', 'pending', NULL, '2025-08-13 15:59:15', '2025-08-13 15:59:15'),
(133, 170, 132000.00, 'vietqr', 'pending', NULL, '2025-08-13 16:05:19', '2025-08-13 16:05:19'),
(134, 171, 250000.00, 'vietqr', 'pending', NULL, '2025-08-16 09:13:43', '2025-08-16 09:13:43'),
(135, 172, 250000.00, 'vietqr', 'pending', NULL, '2025-08-16 09:14:40', '2025-08-16 09:14:40'),
(136, 173, 250000.00, 'vietqr', 'pending', NULL, '2025-08-16 09:15:04', '2025-08-16 09:15:04'),
(137, 174, 6200.00, 'vietqr', 'pending', NULL, '2025-08-19 10:12:49', '2025-08-19 10:12:49'),
(138, 175, 6200.00, 'vietqr', 'pending', NULL, '2025-08-19 10:14:04', '2025-08-19 10:14:04'),
(139, 176, 6200.00, 'vietqr', 'completed', 'CPAY_LVS176171450_1755598643', '2025-08-19 10:14:50', '2025-08-19 10:17:27');

-- --------------------------------------------------------

--
-- Table structure for table `payment_settings`
--

CREATE TABLE `payment_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` enum('string','number','boolean','json') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_encrypted` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_settings`
--

INSERT INTO `payment_settings` (`id`, `key`, `value`, `type`, `group_name`, `description`, `is_encrypted`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'vietqr.bank_id', 'MBBank', 'string', 'vietqr', 'Mã ngân hàng cho VietQR', 0, 1, '2025-08-13 09:07:02', '2025-08-13 17:09:23'),
(2, 'vietqr.account_no', '0335920306', 'string', 'vietqr', 'Số tài khoản ngân hàng', 0, 1, '2025-08-13 09:07:02', '2025-08-19 10:14:18'),
(3, 'vietqr.account_name', 'NGUYEN VAN QUYEN', 'string', 'vietqr', 'Tên chủ tài khoản', 0, 1, '2025-08-13 09:07:02', '2025-08-19 10:14:18'),
(4, 'vietqr.template', 'print', 'string', 'vietqr', 'Template QR code', 0, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02'),
(5, 'vietqr.enabled', '1', 'boolean', 'vietqr', 'Bật/tắt thanh toán VietQR', 0, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02'),
(6, 'cpay.google_script_url', 'https://script.google.com/macros/s/AKfycbx4F-yvXHfFifvP4JkunVHRiTwgL9cZNg7yE6CgcXZs3hmAjVtr6-1qKIa7ZEk52d00/exec', 'string', 'cpay', 'URL Google Apps Script cho CPay', 1, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02'),
(7, 'cpay.timeout', '30', 'number', 'cpay', 'Timeout cho API CPay (giây)', 0, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02'),
(8, 'cpay.enabled', '1', 'boolean', 'cpay', 'Bật/tắt kiểm tra thanh toán CPay', 0, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02'),
(9, 'vnpay.enabled', '0', 'boolean', 'vnpay', 'Bật/tắt thanh toán VNPay', 0, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02'),
(10, 'vnpay.merchant_id', '', 'string', 'vnpay', 'Mã merchant VNPay', 1, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02'),
(11, 'vnpay.hash_secret', '', 'string', 'vnpay', 'Hash secret VNPay', 1, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02'),
(12, 'pay_at_hotel.enabled', '1', 'boolean', 'pay_at_hotel', 'Bật/tắt thanh toán tại khách sạn', 0, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02'),
(13, 'general.default_payment_method', 'vietqr', 'string', 'general', 'Phương thức thanh toán mặc định', 0, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02'),
(14, 'general.api_base_url', 'http://localhost:8888/api', 'string', 'general', 'Base URL cho API', 0, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02'),
(15, 'general.payment_timeout', '900', 'number', 'general', 'Thời gian timeout thanh toán (giây)', 0, 1, '2025-08-13 09:07:02', '2025-08-13 09:07:02');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
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
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`, `updated_at`, `parent_id`) VALUES
(1, 'quan_ly_user', 'Quản lý user', '2025-06-24 20:58:44', '2025-06-24 20:58:44', NULL),
(2, 'quan_ly_nhan_vien', 'Quản lý nhân viên', '2025-06-24 20:58:44', '2025-06-24 20:58:52', 1),
(3, 'quan_ly_khach_hang', 'Quản lý khách hàng', '2025-06-24 20:58:44', '2025-06-24 20:58:55', 1),
(4, 'vai_tro_&&_quyen', 'Quản lý vai trò và phân quyền', '2025-06-24 21:03:48', '2025-06-24 21:03:48', NULL),
(20, 'bang_dieu_khien', 'Bảng điều khiển', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(21, 'cai dat', 'Cài đặt', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(22, 'gia_phong', 'Giá phòng', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(23, 'xac_thuc', 'Xác thực', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(24, 'ho_tro_khach_hang', 'Hỗ trợ khách hàng', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(25, 'danh_gia', 'Đánh giá', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(26, 'cau_hoi_thuong_gap', 'Câu hỏi thường gặp', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(27, 'bookings', 'Bookings', '2025-08-06 00:36:37', '2025-08-13 03:09:11', NULL),
(28, 'sua_doi_phong', 'Sửa đổi phòng', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(29, 'quan_ly_phong', 'Quản lý phòng', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(30, 'chinh_sach', 'Chính sách', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(31, 'dich_vu', 'Dịch vụ', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(32, 'quan_ly_tin_tuc', 'Quản lý tin tức', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(33, 'da_quoc_gia', 'Đa quốc gia', '2025-08-06 00:36:37', '2025-08-06 00:36:37', NULL),
(34, 'thanh_toan', 'Thanh toán', '2025-08-13 03:09:49', '2025-08-13 03:09:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 2),
(27, 2),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 2),
(34, 2);

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

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(3, 'App\\Models\\User', 5, 'auth_token', 'cd118426368c486572eb14b9ca92e9453134a0df511a9b1de5a85a82e602d3a3', '[\"*\"]', NULL, NULL, '2025-07-08 21:54:40', '2025-07-08 21:54:40'),
(6, 'App\\Models\\User', 5, 'auth_token', 'b286cf3d87c084d1e0d292089de64ac1004cfed69b04f508a4b5a478b580ce84', '[\"*\"]', NULL, NULL, '2025-07-09 00:05:13', '2025-07-09 00:05:13'),
(14, 'App\\Models\\User', 5, 'auth_token', 'a7dce83486592a96856608a098572e0311c9ba83ed782dbb426e4f692e924e29', '[\"*\"]', NULL, NULL, '2025-07-20 22:09:02', '2025-07-20 22:09:02'),
(15, 'App\\Models\\User', 1, 'auth_token', 'bd90ae824dd8bb49e942ac7c7ea67cf04cbe98b88cfdce57585ac1c8334aec20', '[\"*\"]', '2025-08-19 10:42:47', NULL, '2025-08-15 08:29:48', '2025-08-19 10:42:47');

-- --------------------------------------------------------

--
-- Table structure for table `policy_applications`
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
-- Dumping data for table `policy_applications`
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
-- Table structure for table `pricing_config`
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
-- Dumping data for table `pricing_config`
--

INSERT INTO `pricing_config` (`config_id`, `max_price_increase_percentage`, `max_absolute_price_vnd`, `use_exclusive_rule`, `exclusive_rule_type`, `created_at`, `updated_at`) VALUES
(1, 40.00, 3000000.00, 0, NULL, '2025-06-26 14:47:48', '2025-06-26 14:47:48');

-- --------------------------------------------------------

--
-- Table structure for table `representatives`
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
-- Dumping data for table `representatives`
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
(119, 153, 'LVS153025209', NULL, '明心', '0335920306', 'quyenjpn@gmail.com', '', '2025-07-19 19:53:06', '2025-07-19 19:53:06', NULL),
(120, 176, 'LVS176171450', NULL, 'Nguyễn Anh Đức', '08221534477', 'nguyenanhduc2909@gmail.com', '', '2025-08-19 10:17:23', '2025-08-19 10:17:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reschedule_policies`
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
-- Dumping data for table `reschedule_policies`
--

INSERT INTO `reschedule_policies` (`policy_id`, `name`, `description`, `room_type_id`, `min_days_before_checkin`, `reschedule_fee_vnd`, `reschedule_fee_percentage`, `applies_to_holiday`, `applies_to_weekend`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Rời lịch miễn phí 7 ngày', 'Miễn phí nếu yêu cầu rời lịch trước 7 ngày check-in, áp dụng cho tất cả các loại phòng vào ngày thường.', NULL, 7, 0.00, 0.00, 0, 0, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00'),
(2, 'Rời lịch tiêu chuẩn 3 ngày', 'Phí 200,000 VND nếu yêu cầu rời lịch trong vòng 3-7 ngày trước check-in, áp dụng cho tất cả các loại phòng.', NULL, 3, 200000.00, 0.00, 0, 0, 1, '2025-08-03 13:54:00', '2025-08-05 01:36:57'),
(3, 'Rời lịch phòng VIP 5 ngày', 'Miễn phí nếu yêu cầu trước 5 ngày cho phòng VIP (room_type_id=2), phí 10% giá trị booking nếu trong vòng 2-5 ngày.', 2, 5, 0.00, 10.00, 0, 0, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00'),
(4, 'Rời lịch ngày lễ phòng Suite', 'Phí 15% giá trị booking nếu yêu cầu rời lịch trong vòng 7 ngày trước check-in cho phòng Suite (room_type_id=3) vào ngày lễ.', 3, 7, 0.00, 15.00, 1, 0, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00'),
(5, 'Rời lịch cuối tuần phòng tiêu chuẩn', 'Phí 300,000 VND nếu yêu cầu rời lịch trong vòng 3 ngày trước check-in cho phòng tiêu chuẩn (room_type_id=1) vào cuối tuần.', 1, 3, 300000.00, 0.00, 0, 1, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00'),
(6, 'Rời lịch khẩn cấp', 'Phí 500,000 VND cho yêu cầu rời lịch trong vòng 24 giờ trước check-in, áp dụng cho tất cả các loại phòng.', NULL, 1, 500000.00, 0.00, 1, 1, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00'),
(7, 'Rời lịch The Level', 'Miễn phí nếu yêu cầu trước 10 ngày cho phòng The Level (room_type_id=4), phí 20% giá trị booking nếu trong vòng 5-10 ngày.', 4, 10, 0.00, 20.00, 1, 1, 1, '2025-08-03 13:54:00', '2025-08-03 13:54:00');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int UNSIGNED NOT NULL,
  `booking_id` int UNSIGNED DEFAULT NULL,
  `rating` decimal(2,1) NOT NULL DEFAULT '0.0',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `detailed_scores` json DEFAULT NULL COMMENT 'JSON store per-criteria scores',
  `pros` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cons` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `travel_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `helpful_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `booking_id`, `rating`, `title`, `comment`, `detailed_scores`, `pros`, `cons`, `travel_type`, `review_date`, `status`, `admin_note`, `helpful_count`, `created_at`, `updated_at`) VALUES
(1, 174, 4.5, 'Kỳ nghỉ rất ổn', 'Phòng sạch, view đẹp, phục vụ nhiệt tình. Nhân viên rất thân thiện và chu đáo.', '{\"room_comfort\": 5, \"food_breakfast\": 4, \"room_amenities\": 4, \"facilities_pool\": 3, \"value_for_money\": 4, \"room_cleanliness\": 4, \"service_reception\": 5, \"service_housekeeping\": 4}', 'Hồ bơi đẹp, nhân viên thân thiện, view tuyệt vời', 'Bữa sáng có thể đa dạng hơn, wifi hơi chậm', 'couple', '2025-08-16', 'approved', NULL, 0, '2025-08-18 19:40:07', '2025-08-18 19:40:07'),
(2, 175, 5.0, 'dsadsdasadsads', 'ádadsadasd', '{\"room_comfort\": 4, \"food_breakfast\": 4, \"room_amenities\": 5, \"facilities_pool\": 5, \"value_for_money\": 4, \"room_cleanliness\": 4, \"service_reception\": 4, \"service_housekeeping\": 4}', 'dhhdashads', 'iádasdas', 'family_young', '2025-08-19', 'pending', NULL, 0, '2025-08-18 21:28:11', '2025-08-18 21:28:11');

-- --------------------------------------------------------

--
-- Table structure for table `review_media`
--

CREATE TABLE `review_media` (
  `id` int UNSIGNED NOT NULL,
  `review_id` int UNSIGNED NOT NULL,
  `file_url` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` enum('image','video') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review_media`
--

INSERT INTO `review_media` (`id`, `review_id`, `file_url`, `file_type`, `meta`, `created_at`) VALUES
(1, 1, '/storage/review-media/sample_beach.jpg', 'image', '{\"size\": 234321, \"uploaded_at\": \"2025-08-19T02:40:07.246002Z\", \"original_name\": \"beach_view.jpg\"}', '2025-08-18 19:40:07'),
(2, 1, '/storage/review-media/sample_pool.mp4', 'video', '{\"size\": 3045321, \"uploaded_at\": \"2025-08-19T02:40:07.246388Z\", \"original_name\": \"pool_video.mp4\"}', '2025-08-18 19:40:07'),
(3, 2, 'http://localhost:8888/storage/review-media/uBwfn0bDfuIUkrKnQ3GBs0jLc2d7hN4YizZuLRaQ.jpg', 'image', '\"{\\\"original\\\":\\\"\\\\/storage\\\\/review-media\\\\/uBwfn0bDfuIUkrKnQ3GBs0jLc2d7hN4YizZuLRaQ.jpg\\\",\\\"uploaded_at\\\":\\\"2025-08-19T04:28:11.484453Z\\\"}\"', '2025-08-18 21:28:11');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Quản trị hệ thống', '2025-06-15 19:52:24', '2025-06-15 19:52:24'),
(2, 'manager', 'Quản lý điều hành', '2025-06-15 19:52:24', '2025-06-15 19:52:24'),
(3, 'receptionist', 'Lễ tân', '2025-06-15 19:52:24', '2025-06-15 19:52:24'),
(4, 'guest', 'Khách hàng thông thường', '2025-06-15 19:52:24', '2025-06-15 19:52:24'),
(9, 'Bảo vệ', 'Bảo vệ an ninh khách sạnn', '2025-06-23 23:19:47', '2025-06-27 07:38:03');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1),
(7, 2),
(10, 4),
(2, 9);

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
  `status` enum('available','out_of_service') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chi tiết phòng',
  `last_cleaned` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin phòng';

--
-- Dumping data for table `room`
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

--
-- Dumping data for table `room_occupancy`
--

INSERT INTO `room_occupancy` (`occupancy_id`, `room_type_id`, `date`, `total_rooms`, `booked_rooms`, `created_at`, `updated_at`) VALUES
(11, 1, '2025-07-01', 90, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(12, 2, '2025-07-01', 96, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(13, 3, '2025-07-01', 20, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(14, 4, '2025-07-01', 36, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(15, 5, '2025-07-01', 20, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(16, 6, '2025-07-01', 32, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(17, 7, '2025-07-01', 1, 0, '2025-07-01 11:07:43', '2025-07-01 11:07:43'),
(18, 1, '2025-08-17', 90, 0, '2025-08-17 10:13:27', '2025-08-17 10:13:27'),
(19, 2, '2025-08-17', 96, 0, '2025-08-17 10:13:27', '2025-08-17 10:13:27'),
(20, 3, '2025-08-17', 36, 0, '2025-08-17 10:13:27', '2025-08-17 10:13:27'),
(21, 4, '2025-08-17', 32, 0, '2025-08-17 10:13:27', '2025-08-17 10:13:27'),
(22, 5, '2025-08-17', 20, 0, '2025-08-17 10:13:27', '2025-08-17 10:13:27'),
(23, 6, '2025-08-17', 20, 0, '2025-08-17 10:13:27', '2025-08-17 10:13:27'),
(24, 7, '2025-08-17', 1, 0, '2025-08-17 10:13:27', '2025-08-17 10:15:36'),
(25, 1, '2025-08-19', 90, 0, '2025-08-19 10:17:23', '2025-08-19 10:17:23');

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
-- Dumping data for table `room_option`
--

INSERT INTO `room_option` (`option_id`, `room_id`, `name`, `price_per_night_vnd`, `max_guests`, `min_guests`, `urgency_message`, `most_popular`, `recommended`, `meal_type`, `bed_type`, `recommendation_score`, `deposit_policy_id`, `check_out_policy_id`, `policy_applied_reason`, `policy_applied_date`, `policy_snapshot_json`, `cancellation_policy_id`, `package_id`, `adjusted_price`, `created_at`, `updated_at`) VALUES
('BOOK-LVS100023425-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS100023425-R1-2', NULL, 'Standard Package', 11000.00, 3, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS103024501-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS103024501-R1-2', NULL, 'Standard Package', 11000.00, 3, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS104024936-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS105025917-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS106030509-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS107030523-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS113070418-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS114070529-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS121091522-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-14', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS124093516-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-14', '[]', NULL, NULL, 11000.00, NULL, NULL),
('BOOK-LVS127025346-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS129030846-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS130033257-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS131033527-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS132033857-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS133070932-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-16', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS137075500-R5-1', NULL, 'Suite Package', 2700000.00, 3, 2, NULL, 0, 0, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS137075500-R5-2', NULL, 'Suite Package', 2700000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS138081920-R5-1', NULL, 'Suite Package', 2700000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS138081920-R5-2', NULL, 'Suite Package', 2700000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS141104347-R5-1', NULL, 'Suite Package', 2700000.00, 6, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS141104347-R5-2', NULL, 'Suite Package', 2700000.00, 5, 3, NULL, 0, 0, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS141104347-R5-3', NULL, 'Suite Package', 2700000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS142120903-R5-1', NULL, 'Suite Package', 2700000.00, 6, 2, NULL, 0, 0, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS142120903-R5-2', NULL, 'Suite Package', 2700000.00, 5, 3, NULL, 0, 0, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS142120903-R5-3', NULL, 'Suite Package', 2700000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-07-17', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 5, 2700000.00, NULL, NULL),
('BOOK-LVS144031538-R6-1', NULL, 'Luxury Package', 3200000.00, 6, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 6, 3200000.00, NULL, NULL),
('BOOK-LVS144031538-R6-2', NULL, 'Luxury Package', 3200000.00, 4, 2, NULL, 0, 0, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 6, 3200000.00, NULL, NULL),
('BOOK-LVS151023546-R6-1', NULL, 'Luxury Package', 3200000.00, 6, 2, NULL, 0, 0, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 6, 3200000.00, NULL, NULL),
('BOOK-LVS151023546-R6-2', NULL, 'Luxury Package', 3200000.00, 4, 2, NULL, 0, 0, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 6, 3200000.00, NULL, NULL),
('BOOK-LVS152023648-R1-1', NULL, 'Premium Package', 1006000.00, 6, 2, NULL, 1, 0, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 8, 1006000.00, NULL, NULL),
('BOOK-LVS152023648-R1-2', NULL, 'Premium Package', 1006000.00, 4, 2, NULL, 1, 0, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 8, 1006000.00, NULL, NULL),
('BOOK-LVS153025209-R1-1', NULL, 'Standard Package', 11000.00, 6, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS153025209-R1-2', NULL, 'Standard Package', 11000.00, 4, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng sau khi thanh toán thành công', '2025-08-18', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS176171450', NULL, 'Standard Package', 6200.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-08-19', '{\"deposit\": {\"name\": \"Đặt cọc 20%\", \"policy_id\": 10, \"description\": \"Đặt cọc 20% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"20.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": null, \"policy_id\": 4, \"description\": null, \"applies_to_holiday\": null, \"applies_to_weekend\": null, \"late_check_out_fee_vnd\": null, \"early_check_out_fee_vnd\": null, \"standard_check_out_time\": null, \"late_check_out_max_hours\": null, \"early_check_out_max_hours\": null}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 6200.00, '2025-08-19', '2025-08-19'),
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
('BOOK-LVS79072153-R7-1', 7, 'Presidential Package', 6200000.00, 2, 2, NULL, 0, 0, NULL, NULL, NULL, 10, NULL, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 7, 6200000.00, NULL, NULL),
('BOOK-LVS80072418-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS88094850-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, 5, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS92105428-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL);
INSERT INTO `room_option` (`option_id`, `room_id`, `name`, `price_per_night_vnd`, `max_guests`, `min_guests`, `urgency_message`, `most_popular`, `recommended`, `meal_type`, `bed_type`, `recommendation_score`, `deposit_policy_id`, `check_out_policy_id`, `policy_applied_reason`, `policy_applied_date`, `policy_snapshot_json`, `cancellation_policy_id`, `package_id`, `adjusted_price`, `created_at`, `updated_at`) VALUES
('BOOK-LVS93105832-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng từ API tìm kiếm phòng với PolicySelectorService', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS94111645-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS95112222-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS96112503-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS98114231-R1-1', 1, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS98114231-R1-2', 1, 'Standard Package', 11000.00, 3, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS99114449-R1-1', NULL, 'Standard Package', 11000.00, 2, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
('BOOK-LVS99114449-R1-2', NULL, 'Standard Package', 11000.00, 3, 2, NULL, 0, 1, NULL, NULL, NULL, 10, NULL, 'Áp dụng sau khi thanh toán thành công', '2025-07-13', '{\"deposit\": {\"name\": \"Đặt cọc 30%\", \"policy_id\": 10, \"description\": \"Đặt cọc 30% giá trị booking\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"deposit_percentage\": \"30.00\", \"min_days_before_checkin\": null, \"deposit_fixed_amount_vnd\": \"0.00\"}, \"check_out\": {\"name\": \"Check-out tiêu chuẩn\", \"policy_id\": 4, \"description\": \"Check-out tiêu chuẩn 12:00\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"late_check_out_fee_vnd\": \"500000.00\", \"early_check_out_fee_vnd\": \"0.00\", \"standard_check_out_time\": \"12:00:00\", \"late_check_out_max_hours\": 2, \"early_check_out_max_hours\": 4}, \"cancellation\": {\"name\": \"Hủy miễn phí 7 ngày\", \"policy_id\": 10, \"description\": \"Hủy miễn phí nếu trước 7 ngày, sau đó phạt 200k\", \"applies_to_holiday\": 0, \"applies_to_weekend\": 0, \"penalty_percentage\": \"0.00\", \"free_cancellation_days\": 7, \"penalty_fixed_amount_vnd\": \"200000.00\"}}', 10, 1, 11000.00, NULL, NULL),
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_price_history`
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
(109, 1, '2025-08-15', 50000.00, 65000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":1,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"80.00\\\",\\\"current_occupancy\\\":80}}]\"', '2025-07-12 05:00:40', '2025-08-15 08:55:17'),
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
(159, 1, '2025-08-18', 50000.00, 60000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-08-15 08:55:17'),
(160, 2, '2025-08-18', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-08-17 07:44:22'),
(161, 3, '2025-08-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-08-17 07:44:22'),
(162, 4, '2025-08-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-08-17 07:44:22'),
(163, 5, '2025-08-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-08-17 07:44:22'),
(164, 6, '2025-08-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-07-18 03:53:17', '2025-08-17 07:44:22'),
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
(260, 1, '2025-08-06', 50000.00, 60000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-05 09:14:08', '2025-08-16 10:07:05'),
(261, 2, '2025-08-06', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-05 09:14:08', '2025-08-05 09:14:08'),
(262, 3, '2025-08-06', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-05 09:14:08', '2025-08-05 09:14:08'),
(263, 4, '2025-08-06', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-05 09:14:08', '2025-08-05 09:14:08'),
(264, 5, '2025-08-06', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-05 09:14:08', '2025-08-05 09:14:08'),
(265, 6, '2025-08-06', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-05 09:14:08', '2025-08-05 09:14:08'),
(266, 7, '2025-08-06', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-05 09:14:08', '2025-08-05 09:14:08'),
(267, 1, '2025-08-07', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-06 06:44:47', '2025-08-06 06:44:47'),
(268, 2, '2025-08-07', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-06 06:44:47', '2025-08-06 06:44:47'),
(269, 3, '2025-08-07', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-06 06:44:47', '2025-08-06 06:44:47'),
(270, 4, '2025-08-07', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-06 06:44:47', '2025-08-06 06:44:47'),
(271, 5, '2025-08-07', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-06 06:44:47', '2025-08-06 06:44:47'),
(272, 6, '2025-08-07', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-06 06:44:47', '2025-08-06 06:44:47'),
(273, 7, '2025-08-07', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-06 06:44:47', '2025-08-06 06:44:47'),
(274, 1, '2025-08-12', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 11:12:18', '2025-08-11 11:12:18'),
(275, 2, '2025-08-12', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 11:12:18', '2025-08-11 11:12:18'),
(276, 3, '2025-08-12', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 11:12:18', '2025-08-11 11:12:18'),
(277, 4, '2025-08-12', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 11:12:18', '2025-08-11 11:12:18'),
(278, 5, '2025-08-12', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 11:12:18', '2025-08-11 11:12:18'),
(279, 6, '2025-08-12', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 11:12:18', '2025-08-11 11:12:18'),
(280, 7, '2025-08-12', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 11:12:18', '2025-08-11 11:12:18'),
(281, 1, '2025-08-13', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 17:15:52', '2025-08-11 17:15:52'),
(282, 2, '2025-08-13', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 17:15:52', '2025-08-11 17:15:52'),
(283, 3, '2025-08-13', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 17:15:52', '2025-08-11 17:15:52'),
(284, 4, '2025-08-13', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 17:15:52', '2025-08-11 17:15:52'),
(285, 5, '2025-08-13', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 17:15:52', '2025-08-11 17:15:52'),
(286, 6, '2025-08-13', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 17:15:52', '2025-08-11 17:15:52'),
(287, 7, '2025-08-13', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-11 17:15:52', '2025-08-11 17:15:52'),
(288, 1, '2025-08-14', 5000.00, 6000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 04:00:30', '2025-08-13 04:00:30'),
(289, 2, '2025-08-14', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 04:00:30', '2025-08-13 04:00:30'),
(290, 3, '2025-08-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 04:00:30', '2025-08-13 04:00:30'),
(291, 4, '2025-08-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 04:00:30', '2025-08-13 04:00:30'),
(292, 5, '2025-08-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 04:00:30', '2025-08-13 04:00:30'),
(293, 6, '2025-08-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 04:00:30', '2025-08-13 04:00:30'),
(294, 7, '2025-08-14', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-13 04:00:30', '2025-08-13 04:00:30'),
(295, 1, '2025-08-16', 50000.00, 65000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-15 08:28:44', '2025-08-16 09:13:34'),
(296, 2, '2025-08-16', 1500000.00, 1950000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-15 08:28:44', '2025-08-15 08:28:44'),
(297, 3, '2025-08-16', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-15 08:28:44', '2025-08-15 08:28:44'),
(298, 4, '2025-08-16', 1000000.00, 1370000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}},{\\\"rule_id\\\":5,\\\"type\\\":\\\"dynamic\\\",\\\"rule_type\\\":\\\"occupancy\\\",\\\"price_adjustment\\\":\\\"7.00\\\",\\\"details\\\":{\\\"occupancy_threshold\\\":\\\"70.00\\\",\\\"current_occupancy\\\":82}}]\"', '2025-08-15 08:28:44', '2025-08-16 09:13:34'),
(299, 5, '2025-08-16', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-15 08:28:44', '2025-08-15 08:28:44'),
(300, 6, '2025-08-16', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-15 08:28:44', '2025-08-15 08:28:44'),
(301, 7, '2025-08-16', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-15 08:28:44', '2025-08-15 08:28:44'),
(302, 1, '2025-08-17', 50000.00, 65000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-15 08:55:17', '2025-08-19 10:09:37'),
(303, 1, '2025-08-19', 1000.00, 1200.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-15 08:55:17', '2025-08-19 10:10:42'),
(304, 1, '2025-08-20', 50000.00, 60000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-15 08:55:17', '2025-08-15 08:55:17'),
(305, 1, '2025-08-21', 50000.00, 60000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-15 08:55:17', '2025-08-15 08:55:17'),
(306, 2, '2025-08-17', 1500000.00, 1950000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-16 09:09:18', '2025-08-19 10:09:37'),
(307, 3, '2025-08-17', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-16 09:09:18', '2025-08-16 09:09:18'),
(308, 4, '2025-08-17', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-16 09:09:18', '2025-08-19 10:09:37'),
(309, 5, '2025-08-17', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-16 09:09:18', '2025-08-16 09:09:18'),
(310, 6, '2025-08-17', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-16 09:09:18', '2025-08-16 09:09:18'),
(311, 7, '2025-08-17', 1000000.00, 1300000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}},{\\\"rule_id\\\":12,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"weekend\\\",\\\"price_adjustment\\\":\\\"10.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"weekend\\\",\\\"days_of_week\\\":[\\\"Saturday\\\",\\\"Sunday\\\"]}}]\"', '2025-08-16 09:09:18', '2025-08-16 09:09:18'),
(312, 7, '2025-08-18', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-17 07:44:22', '2025-08-17 07:44:22'),
(313, 2, '2025-08-20', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:01:09', '2025-08-19 10:01:09'),
(314, 3, '2025-08-20', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:01:09', '2025-08-19 10:01:09'),
(315, 4, '2025-08-20', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:01:09', '2025-08-19 10:01:09'),
(316, 5, '2025-08-20', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:01:09', '2025-08-19 10:01:09'),
(317, 6, '2025-08-20', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:01:09', '2025-08-19 10:01:09'),
(318, 7, '2025-08-20', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:01:09', '2025-08-19 10:01:09'),
(319, 2, '2025-08-19', 1500000.00, 1800000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:10:01', '2025-08-19 10:10:01'),
(320, 3, '2025-08-19', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:10:01', '2025-08-19 10:10:01'),
(321, 4, '2025-08-19', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:10:01', '2025-08-19 10:10:01'),
(322, 5, '2025-08-19', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:10:01', '2025-08-19 10:10:01'),
(323, 6, '2025-08-19', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:10:02', '2025-08-19 10:10:02'),
(324, 7, '2025-08-19', 1000000.00, 1200000.00, '\"[{\\\"rule_id\\\":5,\\\"type\\\":\\\"flexible\\\",\\\"rule_type\\\":\\\"season\\\",\\\"price_adjustment\\\":\\\"20.00\\\",\\\"details\\\":{\\\"rule_type\\\":\\\"season\\\",\\\"season_name\\\":\\\"M\\\\u00f9a cao \\\\u0111i\\\\u1ec3m\\\",\\\"season_dates\\\":{\\\"start_date\\\":\\\"2025-05-31T17:00:00.000000Z\\\",\\\"end_date\\\":\\\"2025-08-30T17:00:00.000000Z\\\"}}}]\"', '2025-08-19 10:10:02', '2025-08-19 10:10:02');

-- --------------------------------------------------------

--
-- Table structure for table `room_transfers`
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
-- Dumping data for table `room_transfers`
--

INSERT INTO `room_transfers` (`transfer_id`, `booking_id`, `old_room_id`, `new_room_id`, `new_option_id`, `transfer_policy_id`, `status`, `price_difference_vnd`, `payment_id`, `processed_by`, `reason`, `created_at`, `updated_at`) VALUES
(3, 24, 20, 20, 'OPT10', 7, 'Approved', -959999.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:21:40', '2025-08-02 16:21:40'),
(4, 24, 20, 20, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:33:47', '2025-08-02 16:33:47'),
(5, 24, 20, 20, 'OPT10', 7, 'Approved', 3980000.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:35:08', '2025-08-02 16:35:08'),
(6, 24, 20, 20, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:40:34', '2025-08-02 16:40:34'),
(7, 24, 20, 20, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:40:36', '2025-08-02 16:40:36'),
(8, 24, 20, 20, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-02 16:40:39', '2025-08-02 16:40:39'),
(9, 24, 20, 20, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-03 04:19:40', '2025-08-03 04:19:40'),
(10, 24, 20, 20, 'OPT10', 7, 'Approved', -3980000.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-03 04:19:46', '2025-08-03 04:19:46'),
(11, 24, 92, 92, 'OPT10', 7, 'Approved', 7960000.00, NULL, NULL, 'Khách yêu cầu nâng cấp phòng', '2025-08-03 04:22:14', '2025-08-03 04:22:14'),
(12, 24, 95, 95, 'OPT10', 7, 'Approved', 0.00, NULL, NULL, 'Khách yêu cầu nâng cấp gói phòng', '2025-08-03 11:18:19', '2025-08-03 11:18:19');

-- --------------------------------------------------------

--
-- Table structure for table `room_transfer_policies`
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
-- Dumping data for table `room_transfer_policies`
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
-- Table structure for table `room_types`
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
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`room_type_id`, `room_code`, `name`, `description`, `total_room`, `base_price`, `room_area`, `view`, `rating`, `max_guests`, `is_active`) VALUES
(1, 'deluxe', 'Deluxe Room', 'Phòng giường đôi rộng rãi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng cùng bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, tủ để quần áo cũng như tầm nhìn ra thành phố.', 90, 1000.00, 32, 'ABC', 0, 2, 1),
(2, 'premium_corner', 'Premium Corner', 'Phòng giường đôi rộng rãi này có máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm.', 96, 1500000.00, 64, 'BCD', 0, 2, 1),
(3, 'the_level_premium', 'The Level Premium', 'Phòng giường đôi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, máy pha cà phê cũng như tầm nhìn ra thành phố.', 36, 1000000.00, 92, 'BBJ', 0, 2, 1),
(4, 'the_level_premium_corner', 'The Level Premium Corner', 'Phòng giường đôi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, máy pha cà phê cũng như tầm nhìn ra thành phố.\n\n', 32, 1000000.00, 20, 'WD', 0, 2, 1),
(5, 'the_level_suite', 'The Level Suite', 'Phòng giường đôi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, máy pha cà phê cũng như tầm nhìn ra thành phố. Căn này được trang bị 1 giường.\n\n', 20, 1000000.00, 48, 'FFS', 0, 2, 1),
(6, 'suite', 'Suite', 'Suite rộng rãi này được bố trí 1 phòng ngủ, khu vực ghế ngồi và 1 phòng tắm với buồng tắm đứng cùng bồn tắm. Suite này có máy điều hòa, TV màn hình phẳng, tường cách âm, minibar, khu vực ăn uống cũng như tầm nhìn ra thành phố. Căn này được trang bị 1 giường.', 20, 1000000.00, 45, 'TDG', 0, 2, 1),
(7, 'presidential_suite', 'Presidential Suite', 'Suite rộng rãi này được bố trí 1 phòng khách, 2 phòng ngủ riêng biệt và 2 phòng tắm với buồng tắm đứng cùng đồ vệ sinh cá nhân miễn phí. Suite này có máy điều hòa, khu vực ghế ngồi với TV màn hình phẳng, tường cách âm, minibar, máy pha cà phê cũng như khu vực ăn uống. Căn này được trang bị 2 giường.', 1, 1000000.00, 10, 'OKO', 0, 4, 1);

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
-- Table structure for table `room_type_image`
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
-- Dumping data for table `room_type_image`
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
-- Table structure for table `room_type_package`
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
-- Dumping data for table `room_type_package`
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
-- Table structure for table `room_type_package_services`
--

CREATE TABLE `room_type_package_services` (
  `id` int NOT NULL,
  `package_id` int NOT NULL,
  `service_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_type_package_services`
--

INSERT INTO `room_type_package_services` (`id`, `package_id`, `service_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-07-30 03:52:03', '2025-07-30 03:52:03'),
(2, 8, 1, '2025-07-30 03:52:25', '2025-07-30 03:52:25');

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
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `price_vnd` decimal(15,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Ví dụ: lần, ngày, giờ, kg',
  `included_services` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `name`, `description`, `price_vnd`, `unit`, `included_services`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Ăn uống tại phòng', 'Khách có thể đặt các món ăn, đồ uống từ thực đơn của nhà hàng khách sạn và được nhân viên mang đến tận phòng.', 50000.00, '1', 0, 1, '2025-06-25 16:25:18', '2025-08-16 10:20:46'),
(2, 'Dịch vụ spa và massage', 'Các liệu pháp spa thư giãn, massage toàn thân hoặc chuyên sâu, sử dụng tinh dầu và kỹ thuật chuyên nghiệp.', 500000.00, 'lần', 0, 1, '2025-08-11 10:34:37', '2025-08-11 10:34:37'),
(3, 'Dịch vụ giặt ủi', 'Giặt khô, giặt ướt và ủi quần áo, hoàn thành trong 24 giờ.', 50000.00, 'kg', 0, 1, '2025-08-11 10:34:37', '2025-08-11 10:34:37'),
(4, 'Mini bar', 'Sử dụng đồ uống, snack và rượu từ mini bar trong phòng, tính phí theo sản phẩm tiêu thụ.', 100000.00, 'lần', 1, 1, '2025-08-11 10:34:37', '2025-08-11 10:35:27'),
(5, 'Xe đưa đón sân bay', 'Dịch vụ đưa đón hai chiều từ sân bay đến khách sạn bằng xe riêng.', 300000.00, 'chuyến', 0, 1, '2025-08-11 10:34:37', '2025-08-11 10:35:16'),
(6, 'Tour du lịch địa phương', 'Tổ chức tour tham quan các địa danh nổi tiếng, bao gồm hướng dẫn viên và phương tiện di chuyển.', 800000.00, 'người', 1, 1, '2025-08-11 10:34:37', '2025-08-11 10:35:32'),
(7, 'Thuê xe hơi hoặc xe máy', 'Thuê xe hơi tự lái hoặc có tài xế, hoặc xe máy để khám phá khu vực lân cận.', 500000.00, 'ngày', 1, 1, '2025-08-11 10:34:37', '2025-08-11 10:35:04'),
(8, 'Dịch vụ hội nghị và sự kiện', 'Thuê phòng họp hoặc hội trường cho hội nghị, tiệc cưới, với thiết bị âm thanh và ánh sáng đầy đủ.', 2000000.00, 'giờ', 1, 1, '2025-08-11 10:34:37', '2025-08-11 10:35:46'),
(9, 'Bữa tối đặc biệt', 'Bữa tối lãng mạn hoặc theo chủ đề tại nhà hàng, với menu tùy chỉnh và rượu vang.', 1000000.00, 'người', 0, 1, '2025-08-11 10:34:37', '2025-08-11 10:34:37'),
(10, 'Dịch vụ trông trẻ', 'Dịch vụ giữ trẻ chuyên nghiệp tại phòng hoặc khu vui chơi, với nhân viên được đào tạo.', 200000.00, 'giờ', 1, 1, '2025-08-11 10:34:37', '2025-08-11 10:34:59'),
(11, 'Dịch vụ phòng 24/7', 'Dịch vụ phục vụ đồ ăn, thức uống tận phòng bất kỳ lúc nào trong ngày.', 50000.00, 'lần', 0, 1, '2025-08-11 10:34:37', '2025-08-11 10:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Dumping data for table `sessions`
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
-- Table structure for table `table_translation`
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `google_id`, `avatar`, `email_verified_at`, `password`, `phone`, `address`, `identity_code`, `role`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'Nguyễn Anh Đức', 'nguyenanhduc2909@gmail.com', NULL, NULL, NULL, '$2y$12$c2dNZ4nJgjNNQzaupkPYP.qIR6Ax7vkA65tXqK/n/uStI/bAr5haa', '08221534477', 'Thanh Hóa', '038205000950', 'admin', NULL, NULL, NULL, NULL, NULL, 'profile-photos/mfqMmmx1jtzkRy9YdNHQRl7xjSLZwxGgqDHJd4JS.png', '2025-05-21 01:07:42', '2025-08-15 07:16:36'),
(2, 'Nguyễn Anh Đức', 'nguyenandhduc2909@gmail.com', NULL, NULL, NULL, '$2y$12$ofny2jH99JRC2egJJaVzLOyRIuw2.5aL93twDg6Zw4hOq0KKWdxAu', '08221534422', 'Thanh Hóa', '038205000957', 'guest', NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-10 09:47:13', '2025-08-19 08:37:52'),
(3, 'Thu Huyền', 'nguyenanhduc29090@gmail.com', NULL, NULL, NULL, '$2y$12$sIBuDRsM3GZwHvaxR8xNeeF6UIW1YTu5wCghwC.M63T3qWoUC6gna', '03111512022', 'Tuyên Quang', '035656218945', NULL, NULL, NULL, NULL, NULL, NULL, 'profile-photos/sVoWN7luhQjbSUrhKANRv2sJKh7h1hOc0saidVWn.jpg', '2025-06-27 00:17:18', '2025-06-27 00:17:18'),
(5, '明心', 'quyenjpn@gmail.com', '109271388597887089369', 'https://lh3.googleusercontent.com/a/ACg8ocLibsuu8ZHTUKCZ5jMRf4XanikYipmCOnfOQFqEYq_3W7lJkd6YCA=s96-c', NULL, '$2y$12$/AcXTgdK8ApiZERpHkvx3.RE/9rRrtszdM3lV.WFPfqCW3j40v/XG', '0335920306', 'Thanh hoá', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-08 21:51:07', '2025-07-08 21:51:07'),
(6, 'Pro Mark', 'markpro824@gmail.com', '103984459604437565231', 'https://lh3.googleusercontent.com/a/ACg8ocLyS17KMeW7ftc9SYLqQGewq65wYm54Chs2pk1kHjkOBT0SBg=s96-c', NULL, '$2y$12$eeWAk0mGEgXsJItVMonL3eP7bVFMFZXKl25jO8gpZL6pU60lGG82e', NULL, NULL, NULL, 'receptionist', NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-08 21:54:23', '2025-07-08 21:54:23'),
(7, 'nguyễn văn quyền ADMIN', 'werwerww@gmail.com', NULL, NULL, NULL, '$2y$12$fYWYXd5Bo5JeaCgj/6pgl.f7O4WHg/tZjpwbddGbpASPebWu1u4Em', '0987654321', 'jhvbujh', '324123423', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 00:07:53', '2025-07-09 00:12:49'),
(9, 'Phương nguyễn', 'maiiphuong1981@gmail.com', '107379410125025514536', 'https://lh3.googleusercontent.com/a/ACg8ocIGGrOzbzC7mG4bgj1Wz_l4crSDbQ3SWRkvHbbEus5j4BYCO-PR=s96-c', NULL, '$2y$12$SOT8QcuNDrfkZuExPoOGKu/iI8weNR9.Wsu9QGCrKGkFPMD.Ozw0q', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-15 03:25:38', '2025-08-15 03:25:38'),
(10, 'PHNguyễn Anh Đức', 'nguyenanhduc2909@gmail.comm', NULL, NULL, NULL, '$2y$12$C/HiE4KzEReWluPgA3JT2unQ1pdde/OR/dUENi944FM1c/n43taPq', '0822153447', 'Thanh Hóa\r\nThanh Hóa', '0356562189457', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-15 07:46:42', '2025-08-15 07:46:42');

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` json NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_notifications`
--

INSERT INTO `user_notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('11111111-1111-1111-1111-111111111111', 'App\\Notifications\\CheckoutCompletedNotification', 'App\\Models\\User', 123, '{\"url\": \"/review-booking?booking=121\", \"message\": \"Cảm ơn bạn đã lưu trú — vui lòng đánh giá trải nghiệm\", \"booking_id\": 121}', NULL, '2025-08-17 18:00:00', '2025-08-17 18:00:00'),
('22222222-2222-2222-2222-222222222222', 'App\\Notifications\\CheckoutCompletedNotification', 'App\\Models\\User', 123, '{\"url\": \"/review-booking?booking=160\", \"message\": \"Bạn đã checkout thành công — cho chúng tôi biết cảm nhận nhé\", \"booking_id\": 160}', '2025-08-17 19:00:00', '2025-08-17 19:00:00', '2025-08-17 19:00:00'),
('33333333-3333-3333-3333-333333333333', 'App\\Notifications\\CheckoutCompletedNotification', 'App\\Models\\User', 1, '{\"url\": \"/review-booking?booking=175\", \"message\": \"Bạn đã checkout thành công — cho chúng tôi biết cảm nhận nhé\", \"booking_id\": 160}', '2025-08-18 03:39:38', '2025-08-18 03:30:33', '2025-08-18 03:39:38'),
('5a754ec5-411f-493c-86c2-53062829f0af', 'App\\Notifications\\CheckoutCompletedNotification', 'App\\Models\\User', 1, '\"{\\\"booking_id\\\":888,\\\"message\\\":\\\"Manual test notification\\\",\\\"url\\\":\\\"\\\\/review-booking?booking=888\\\",\\\"booking_code\\\":\\\"MANUAL123\\\"}\"', '2025-08-19 09:56:22', '2025-08-17 22:44:50', '2025-08-19 09:56:22'),
('79e13f74-1ce4-41e6-879c-b7ee21ce4fc6', 'App\\Notifications\\CheckoutCompletedNotification', 'App\\Models\\User', 1, '{\"url\": \"/review-booking?booking=999\", \"message\": \"Test notification - Cảm ơn bạn đã lưu trú\", \"booking_id\": 999, \"booking_code\": \"TEST123\"}', NULL, '2025-08-17 22:19:25', '2025-08-17 22:19:25'),
('d941e608-aabf-4e72-b917-9c1809de2f7a', 'App\\Notifications\\CheckoutCompletedNotification', 'App\\Models\\User', 1, '{\"url\": \"/review-booking?booking=999\", \"message\": \"Cảm ơn bạn đã lưu trú — vui lòng đánh giá trải nghiệm\", \"booking_id\": 999, \"booking_code\": \"TEST123\"}', NULL, '2025-08-17 22:22:00', '2025-08-17 22:22:00'),
('f8dc1f0f-0fb5-47e5-bc50-0dacdd57ecc8', 'App\\Notifications\\CheckoutCompletedNotification', 'App\\Models\\User', 1, '{\"url\": \"/review-booking?booking=999\", \"message\": \"Cảm ơn bạn đã lưu trú — vui lòng đánh giá trải nghiệm\", \"booking_id\": 999, \"booking_code\": \"TEST123\"}', '2025-08-19 09:56:32', '2025-08-17 22:44:36', '2025-08-19 09:56:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_notification_settings`
--

CREATE TABLE `user_notification_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `notification_type_id` bigint UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `email_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `push_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_notification_settings`
--

INSERT INTO `user_notification_settings` (`id`, `user_id`, `notification_type_id`, `is_enabled`, `email_enabled`, `push_enabled`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, 1, '2025-08-19 07:31:31', '2025-08-19 07:31:31'),
(2, 1, 2, 1, 0, 1, '2025-08-19 07:31:31', '2025-08-19 07:31:31'),
(3, 1, 3, 1, 0, 1, '2025-08-19 07:31:31', '2025-08-19 07:31:31'),
(4, 1, 6, 1, 0, 1, '2025-08-19 07:31:31', '2025-08-19 07:31:31'),
(5, 1, 7, 1, 1, 1, '2025-08-19 07:31:31', '2025-08-19 07:31:31'),
(6, 1, 8, 1, 1, 1, '2025-08-19 07:31:31', '2025-08-19 07:31:31'),
(7, 1, 9, 1, 0, 1, '2025-08-19 07:31:31', '2025-08-19 07:31:31'),
(8, 1, 12, 1, 1, 1, '2025-08-19 07:31:31', '2025-08-19 07:31:31'),
(9, 1, 13, 1, 1, 1, '2025-08-19 07:31:31', '2025-08-19 07:31:31'),
(10, 1, 14, 1, 1, 1, '2025-08-19 07:31:31', '2025-08-19 07:31:31');

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
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `idx_user_time` (`user_id`,`created_at`),
  ADD KEY `idx_model_record` (`model`,`model_id`),
  ADD KEY `idx_action_time` (`action`,`created_at`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_ip` (`ip_address`);

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
  ADD KEY `fk_booking_room` (`room_id`),
  ADD KEY `room_type_id` (`room_type_id`);

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
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `new_room_id` (`new_room_id`),
  ADD KEY `new_option_id` (`new_option_id`),
  ADD KEY `reschedule_policy_id` (`reschedule_policy_id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `representative_id` (`representative_id`),
  ADD KEY `booking_rooms_option_id_foreign` (`option_id`);

--
-- Indexes for table `booking_room_children`
--
ALTER TABLE `booking_room_children`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `cancellation_policies`
--
ALTER TABLE `cancellation_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `cancellation_requests`
--
ALTER TABLE `cancellation_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `cancellation_policy_id` (`cancellation_policy_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `check_in_policies`
--
ALTER TABLE `check_in_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `check_in_requests`
--
ALTER TABLE `check_in_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `policy_id` (`policy_id`);

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
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `policy_id` (`policy_id`);

--
-- Indexes for table `children_surcharges`
--
ALTER TABLE `children_surcharges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `compensation_policies`
--
ALTER TABLE `compensation_policies`
  ADD PRIMARY KEY (`compensation_policy_id`),
  ADD KEY `applies_to_room_type_id` (`applies_to_room_type_id`);

--
-- Indexes for table `compensation_requests`
--
ALTER TABLE `compensation_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `policy_id` (`policy_id`),
  ADD KEY `requested_by` (`requested_by`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversations_client_token_unique` (`client_token`),
  ADD KEY `conversations_user_id_foreign` (`user_id`),
  ADD KEY `conversations_handover_to_user_id_foreign` (`handover_to_user_id`);

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
-- Indexes for table `extension_policies`
--
ALTER TABLE `extension_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `extension_requests`
--
ALTER TABLE `extension_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `extension_policy_id` (`extension_policy_id`),
  ADD KEY `processed_by` (`processed_by`);

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
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `booking_id` (`booking_id`);

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
-- Indexes for table `media_files`
--
ALTER TABLE `media_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_conversation_id_foreign` (`conversation_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `thumbnail_id` (`thumbnail_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `news_categories`
--
ALTER TABLE `news_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_id` (`news_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `news_user_actions`
--
ALTER TABLE `news_user_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_id` (`news_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  ADD KEY `notifications_read_at_index` (`read_at`),
  ADD KEY `notifications_created_at_index` (`created_at`),
  ADD KEY `notifications_notification_type_id_foreign` (`notification_type_id`);

--
-- Indexes for table `notification_types`
--
ALTER TABLE `notification_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_types_name_unique` (`name`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `idx_booking_status` (`booking_id`,`status`);

--
-- Indexes for table `payment_settings`
--
ALTER TABLE `payment_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_settings_key_unique` (`key`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_permissions_parent_id` (`parent_id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `policy_applications`
--
ALTER TABLE `policy_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_policy_match` (`room_type_id`,`policy_type`,`applies_to_holiday`);

--
-- Indexes for table `pricing_config`
--
ALTER TABLE `pricing_config`
  ADD PRIMARY KEY (`config_id`);

--
-- Indexes for table `representatives`
--
ALTER TABLE `representatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `fk_representative_user` (`user_id`);

--
-- Indexes for table `reschedule_policies`
--
ALTER TABLE `reschedule_policies`
  ADD PRIMARY KEY (`policy_id`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `reviews_booking_id_index` (`booking_id`);

--
-- Indexes for table `review_media`
--
ALTER TABLE `review_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_media_review_id_index` (`review_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD UNIQUE KEY `unique_user_role` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `idx_room_type_id` (`room_type_id`),
  ADD KEY `bed_type_fixed` (`bed_type_fixed`),
  ADD KEY `floor_id` (`floor_id`);

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
  ADD KEY `package_id` (`package_id`),
  ADD KEY `check_out_policy_id` (`check_out_policy_id`);

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
  ADD KEY `new_room_id` (`new_room_id`),
  ADD KEY `new_option_id` (`new_option_id`),
  ADD KEY `transfer_policy_id` (`transfer_policy_id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `room_transfer_policies`
--
ALTER TABLE `room_transfer_policies`
  ADD PRIMARY KEY (`policy_id`),
  ADD KEY `room_type_id` (`room_type_id`);

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
-- Indexes for table `room_type_package_services`
--
ALTER TABLE `room_type_package_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `service_id` (`service_id`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_google_id_index` (`google_id`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  ADD KEY `user_notifications_read_at_index` (`read_at`);

--
-- Indexes for table `user_notification_settings`
--
ALTER TABLE `user_notification_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_notification_settings_user_id_notification_type_id_unique` (`user_id`,`notification_type_id`),
  ADD KEY `user_notification_settings_notification_type_id_foreign` (`notification_type_id`);

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
  MODIFY `audit_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `bed_types`
--
ALTER TABLE `bed_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã đặt phòng', AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `booking_extensions`
--
ALTER TABLE `booking_extensions`
  MODIFY `extension_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_reschedules`
--
ALTER TABLE `booking_reschedules`
  MODIFY `reschedule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `booking_room_children`
--
ALTER TABLE `booking_room_children`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `booking_services`
--
ALTER TABLE `booking_services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cancellation_policies`
--
ALTER TABLE `cancellation_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cancellation_requests`
--
ALTER TABLE `cancellation_requests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã yêu cầu hủy';

--
-- AUTO_INCREMENT for table `check_in_policies`
--
ALTER TABLE `check_in_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã chính sách nhận phòng', AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `check_in_requests`
--
ALTER TABLE `check_in_requests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, tự động tăng';

--
-- AUTO_INCREMENT for table `check_out_policies`
--
ALTER TABLE `check_out_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `check_out_requests`
--
ALTER TABLE `check_out_requests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, tự động tăng', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `children_surcharges`
--
ALTER TABLE `children_surcharges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `compensation_policies`
--
ALTER TABLE `compensation_policies`
  MODIFY `compensation_policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `compensation_requests`
--
ALTER TABLE `compensation_requests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'ID yêu cầu bồi thường', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `datafeeds`
--
ALTER TABLE `datafeeds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=326;

--
-- AUTO_INCREMENT for table `deposit_policies`
--
ALTER TABLE `deposit_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `dynamic_pricing_rules`
--
ALTER TABLE `dynamic_pricing_rules`
  MODIFY `rule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `extension_policies`
--
ALTER TABLE `extension_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã chính sách gia hạn', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `extension_requests`
--
ALTER TABLE `extension_requests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã yêu cầu gia hạn', AUTO_INCREMENT=4;

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
  MODIFY `rule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `floors`
--
ALTER TABLE `floors`
  MODIFY `floor_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã tầng', AUTO_INCREMENT=35;

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
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `meal_types`
--
ALTER TABLE `meal_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `media_files`
--
ALTER TABLE `media_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính của file ảnh/media', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã bài viết', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `news_categories`
--
ALTER TABLE `news_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính chuyên mục', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `news_comments`
--
ALTER TABLE `news_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `news_user_actions`
--
ALTER TABLE `news_user_actions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notification_types`
--
ALTER TABLE `notification_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã thanh toán', AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `payment_settings`
--
ALTER TABLE `payment_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `policy_applications`
--
ALTER TABLE `policy_applications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID auto increment', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pricing_config`
--
ALTER TABLE `pricing_config`
  MODIFY `config_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `representatives`
--
ALTER TABLE `representatives`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `reschedule_policies`
--
ALTER TABLE `reschedule_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `review_media`
--
ALTER TABLE `review_media`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã phòng', AUTO_INCREMENT=296;

--
-- AUTO_INCREMENT for table `room_occupancy`
--
ALTER TABLE `room_occupancy`
  MODIFY `occupancy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  MODIFY `promotion_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã khuyến mãi';

--
-- AUTO_INCREMENT for table `room_price_history`
--
ALTER TABLE `room_price_history`
  MODIFY `price_history_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=325;

--
-- AUTO_INCREMENT for table `room_transfers`
--
ALTER TABLE `room_transfers`
  MODIFY `transfer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `room_transfer_policies`
--
ALTER TABLE `room_transfer_policies`
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `room_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `room_type_image`
--
ALTER TABLE `room_type_image`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã ảnh', AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `room_type_package`
--
ALTER TABLE `room_type_package`
  MODIFY `package_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `room_type_package_services`
--
ALTER TABLE `room_type_package_services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `room_type_service`
--
ALTER TABLE `room_type_service`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_notification_settings`
--
ALTER TABLE `user_notification_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
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
  ADD CONSTRAINT `booking_reschedules_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_reschedules_ibfk_2` FOREIGN KEY (`new_room_id`) REFERENCES `room` (`room_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_reschedules_ibfk_3` FOREIGN KEY (`new_option_id`) REFERENCES `room_option` (`option_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_reschedules_ibfk_4` FOREIGN KEY (`reschedule_policy_id`) REFERENCES `reschedule_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_reschedules_ibfk_5` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_reschedules_ibfk_6` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD CONSTRAINT `booking_rooms_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  ADD CONSTRAINT `booking_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`),
  ADD CONSTRAINT `booking_rooms_ibfk_3` FOREIGN KEY (`representative_id`) REFERENCES `representatives` (`id`),
  ADD CONSTRAINT `booking_rooms_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE SET NULL;

--
-- Constraints for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD CONSTRAINT `booking_services_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE RESTRICT;

--
-- Constraints for table `cancellation_requests`
--
ALTER TABLE `cancellation_requests`
  ADD CONSTRAINT `cancellation_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cancellation_requests_ibfk_2` FOREIGN KEY (`cancellation_policy_id`) REFERENCES `cancellation_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cancellation_requests_ibfk_3` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `check_in_requests`
--
ALTER TABLE `check_in_requests`
  ADD CONSTRAINT `check_in_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `check_in_requests_ibfk_2` FOREIGN KEY (`policy_id`) REFERENCES `check_in_policies` (`policy_id`) ON DELETE SET NULL;

--
-- Constraints for table `check_out_requests`
--
ALTER TABLE `check_out_requests`
  ADD CONSTRAINT `check_out_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `check_out_requests_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `check_out_requests_ibfk_3` FOREIGN KEY (`policy_id`) REFERENCES `check_out_policies` (`policy_id`) ON DELETE SET NULL;

--
-- Constraints for table `compensation_policies`
--
ALTER TABLE `compensation_policies`
  ADD CONSTRAINT `compensation_policies_ibfk_1` FOREIGN KEY (`applies_to_room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL;

--
-- Constraints for table `compensation_requests`
--
ALTER TABLE `compensation_requests`
  ADD CONSTRAINT `compensation_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compensation_requests_ibfk_2` FOREIGN KEY (`policy_id`) REFERENCES `compensation_policies` (`compensation_policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `compensation_requests_ibfk_3` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `compensation_requests_ibfk_4` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_handover_to_user_id_foreign` FOREIGN KEY (`handover_to_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dynamic_pricing_rules`
--
ALTER TABLE `dynamic_pricing_rules`
  ADD CONSTRAINT `dynamic_pricing_rules_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL;

--
-- Constraints for table `extension_requests`
--
ALTER TABLE `extension_requests`
  ADD CONSTRAINT `extension_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `extension_requests_ibfk_2` FOREIGN KEY (`extension_policy_id`) REFERENCES `extension_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `extension_requests_ibfk_3` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `flexible_pricing_rules`
--
ALTER TABLE `flexible_pricing_rules`
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_3` FOREIGN KEY (`holiday_id`) REFERENCES `holidays` (`holiday_id`) ON DELETE SET NULL;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`thumbnail_id`) REFERENCES `media_files` (`id`),
  ADD CONSTRAINT `news_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `news_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`);

--
-- Constraints for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD CONSTRAINT `news_comments_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`),
  ADD CONSTRAINT `news_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `news_comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `news_comments` (`id`);

--
-- Constraints for table `news_user_actions`
--
ALTER TABLE `news_user_actions`
  ADD CONSTRAINT `news_user_actions_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`),
  ADD CONSTRAINT `news_user_actions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_notification_type_id_foreign` FOREIGN KEY (`notification_type_id`) REFERENCES `notification_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `representatives`
--
ALTER TABLE `representatives`
  ADD CONSTRAINT `fk_representative_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `representatives_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  ADD CONSTRAINT `representatives_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`);

--
-- Constraints for table `reschedule_policies`
--
ALTER TABLE `reschedule_policies`
  ADD CONSTRAINT `reschedule_policies_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL;

--
-- Constraints for table `review_media`
--
ALTER TABLE `review_media`
  ADD CONSTRAINT `review_media_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`review_id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_ibfk_3` FOREIGN KEY (`bed_type_fixed`) REFERENCES `bed_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_ibfk_4` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`floor_number`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
  ADD CONSTRAINT `room_option_ibfk_4` FOREIGN KEY (`package_id`) REFERENCES `room_type_package` (`package_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_option_ibfk_5` FOREIGN KEY (`check_out_policy_id`) REFERENCES `check_out_policies` (`policy_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
  ADD CONSTRAINT `room_transfers_ibfk_3` FOREIGN KEY (`new_room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `room_transfers_ibfk_4` FOREIGN KEY (`new_option_id`) REFERENCES `room_option` (`option_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_transfers_ibfk_5` FOREIGN KEY (`transfer_policy_id`) REFERENCES `room_transfer_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_transfers_ibfk_6` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_transfers_ibfk_7` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `room_transfer_policies`
--
ALTER TABLE `room_transfer_policies`
  ADD CONSTRAINT `room_transfer_policies_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL;

--
-- Constraints for table `room_type_amenity`
--
ALTER TABLE `room_type_amenity`
  ADD CONSTRAINT `room_type_amenity_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_type_amenity_ibfk_2` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`amenity_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `room_type_package`
--
ALTER TABLE `room_type_package`
  ADD CONSTRAINT `room_type_package_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE;

--
-- Constraints for table `room_type_package_services`
--
ALTER TABLE `room_type_package_services`
  ADD CONSTRAINT `room_type_package_services_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `room_type_package` (`package_id`),
  ADD CONSTRAINT `room_type_package_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);

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

--
-- Constraints for table `user_notification_settings`
--
ALTER TABLE `user_notification_settings`
  ADD CONSTRAINT `user_notification_settings_notification_type_id_foreign` FOREIGN KEY (`notification_type_id`) REFERENCES `notification_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_notification_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
