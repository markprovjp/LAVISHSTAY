-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2025 at 05:46 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.12

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
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`amenity_id`, `name`, `icon`, `category`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '\r\nĐồ vệ sinh cá nhân miễn phí ', 'i', 'Cá nhân', 'None', 1, '2025-06-09 02:35:50', '2025-06-09 02:35:50'),
(2, 'Khăn tắm cá nhân', 'i', 'Cá nhân', 'None', 1, '2025-06-09 02:36:25', '2025-06-09 03:49:18'),
(3, 'Wifi free', 'i', 'Chung', 'None', 1, '2025-06-09 03:49:09', '2025-06-09 03:49:09'),
(4, 'WiFi miễn phí', '📶', 'Tiện nghi cơ bản', 'Kết nối internet tốc độ cao miễn phí', 1, '2025-06-09 03:52:17', '2025-06-09 03:52:17'),
(5, 'Điều hòa không khí', '❄️', 'Tiện nghi cơ bản', 'Hệ thống điều hòa hiện đại', 1, '2025-06-09 03:52:17', '2025-06-09 03:52:17'),
(6, 'TV màn hình phẳng', '📺', 'Tiện nghi cơ bản', 'TV LCD/LED với nhiều kênh', 1, '2025-06-09 03:52:17', '2025-06-09 03:52:17'),
(7, 'Tủ lạnh mini', '🧊', 'Tiện nghi cơ bản', 'Tủ lạnh nhỏ trong phòng', 1, '2025-06-09 03:52:17', '2025-06-09 03:52:17');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `table_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `record_id` int NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
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
(1, 'dht', 'hh', 1, '2025-06-10 00:16:30', '2025-06-10 00:16:42');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int NOT NULL COMMENT 'Khóa chính, mã đặt phòng',
  `user_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Khóa ngoại, mã người dùng (nếu có)',
  `option_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn phòng',
  `check_in_date` date NOT NULL COMMENT 'Ngày nhận phòng',
  `check_out_date` date NOT NULL COMMENT 'Ngày trả phòng',
  `total_price_vnd` decimal(15,2) NOT NULL COMMENT 'Tổng giá (VND)',
  `guest_count` int NOT NULL COMMENT 'Số khách',
  `status` enum('pending','confirmed','cancelled','completed') COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Trạng thái đặt phòng',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  `guest_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tên khách',
  `guest_email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Email khách',
  `guest_phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số điện thoại khách',
  `room_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin đặt phòng';

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `option_id`, `check_in_date`, `check_out_date`, `total_price_vnd`, `guest_count`, `status`, `created_at`, `updated_at`, `guest_name`, `guest_email`, `guest_phone`, `room_id`) VALUES
(1, 1, 'OPT_R1_STANDARD', '2025-06-07', '2025-06-11', 2000000.00, 3, 'pending', '2025-06-06 03:50:52', '2025-06-06 03:50:52', 'Duc', 'nguyenanhduc2909@gmail.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_extensions`
--

CREATE TABLE `booking_extensions` (
  `extension_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `new_check_out_date` date NOT NULL,
  `additional_fee_vnd` decimal(15,2) DEFAULT '0.00',
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `reason` text COLLATE utf8mb4_general_ci,
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
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `reason` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_policies`
--

CREATE TABLE `cancellation_policies` (
  `policy_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `free_cancellation_days` int DEFAULT NULL COMMENT 'Số ngày trước check-in được hủy miễn phí',
  `penalty_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Phạt hủy (%)',
  `penalty_fixed_amount_vnd` decimal(15,2) DEFAULT NULL COMMENT 'Phạt hủy cố định (VND)',
  `description` text COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancellation_policies`
--

INSERT INTO `cancellation_policies` (`policy_id`, `name`, `free_cancellation_days`, `penalty_percentage`, `penalty_fixed_amount_vnd`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Hủy miễn phí 7 ngày', 7, 0.00, 200000.00, 'Hủy miễn phí nếu trước 7 ngày', 1, '2025-06-11 02:26:26', '2025-06-11 01:24:04'),
(2, 'Hủy có phí', 2, 50.00, NULL, 'Phạt 50% nếu hủy trong vòng 2 ngày', 0, '2025-06-11 02:26:26', '2025-06-13 00:23:30'),
(9, 'Nguyễn Anh Đức', 3, 33.00, NULL, '33', 0, '2025-06-11 00:38:13', '2025-06-11 01:14:25');

-- --------------------------------------------------------

--
-- Table structure for table `check_out_policies`
--

CREATE TABLE `check_out_policies` (
  `policy_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `early_check_out_fee_vnd` decimal(15,2) DEFAULT NULL,
  `late_check_out_fee_vnd` decimal(15,2) DEFAULT NULL,
  `late_check_out_max_hours` int DEFAULT NULL COMMENT 'Số giờ tối đa trả phòng muộn',
  `description` text COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `check_out_policies`
--

INSERT INTO `check_out_policies` (`policy_id`, `name`, `early_check_out_fee_vnd`, `late_check_out_fee_vnd`, `late_check_out_max_hours`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Trả phòng muộn', 0.00, 200.00, 4, 'Phí 200,000 VND nếu trả phòng muộn tối đa 4 giờ', 0, '2025-06-11 02:36:00', '2025-06-12 10:39:53');

-- --------------------------------------------------------

--
-- Table structure for table `check_out_requests`
--

CREATE TABLE `check_out_requests` (
  `request_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `type` enum('early','late') COLLATE utf8mb4_general_ci NOT NULL,
  `requested_check_out_time` datetime NOT NULL,
  `fee_vnd` decimal(15,2) DEFAULT '0.00',
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `currency_code` varchar(3) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Khóa chính, mã tiền tệ (VND, USD, v.v.)',
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên tiền tệ',
  `exchange_rate` decimal(10,4) DEFAULT NULL COMMENT 'Tỷ giá so với VND',
  `symbol` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Ký hiệu tiền tệ (₫, $, v.v.)',
  `format` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Định dạng (ví dụ: {amount} ₫)'
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
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deposit_percentage` decimal(5,2) DEFAULT NULL,
  `deposit_fixed_amount_vnd` decimal(15,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deposit_policies`
--

INSERT INTO `deposit_policies` (`policy_id`, `name`, `deposit_percentage`, `deposit_fixed_amount_vnd`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Đặt cọc 50%', 50.00, NULL, 'Yêu cầu đặt cọc 50% tổng giá', 1, '2025-06-11 02:24:24', '2025-06-11 02:24:24'),
(5, 'Nguyễn Anh Đức', 31.97, 2000.00, 'ulidd', 0, '2025-06-11 07:49:09', '2025-06-13 00:23:25');

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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dynamic_pricing_rules`
--

INSERT INTO `dynamic_pricing_rules` (`rule_id`, `room_type_id`, `occupancy_threshold`, `price_adjustment`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 80.00, 10.00, 1, '2025-06-11 02:44:33', '2025-06-11 02:44:33'),
(2, 1, 90.00, 20.00, 1, '2025-06-11 02:44:33', '2025-06-11 02:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
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
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `holiday_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
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
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên khách sạn',
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Địa chỉ khách sạn',
  `description` text COLLATE utf8mb4_general_ci COMMENT 'Mô tả khách sạn'
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
  `rating_text` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mô tả đánh giá',
  `location` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Vị trí khách sạn',
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
  `language_code` varchar(10) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Khóa chính, mã ngôn ngữ (vi, en, v.v.)',
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên ngôn ngữ (Vietnamese, English, v.v.)'
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
(7, '2025_06_12_074026_create_table_translation_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('cancellation','extension','reschedule','transfer','check_out') COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','sent','failed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
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
  `payment_type` enum('deposit','full','qr_code','at_hotel') COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại thanh toán',
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Trạng thái thanh toán',
  `transaction_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mã giao dịch (từ cổng thanh toán)',
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
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int NOT NULL COMMENT 'Khóa chính, mã phòng',
  `hotel_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã khách sạn',
  `room_type_id` int NOT NULL COMMENT 'Khóa ngoại, mã loại phòng',
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên phòng',
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh chính',
  `floor` int DEFAULT NULL,
  `status` enum('available','occupied','maintenance','cleaning') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `base_price_vnd` decimal(15,2) NOT NULL COMMENT 'Giá cơ bản (VND)',
  `size` int NOT NULL COMMENT 'Diện tích phòng (m²)',
  `view` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tầm nhìn phòng',
  `rating` decimal(3,1) DEFAULT NULL COMMENT 'Điểm đánh giá (0-10)',
  `lavish_plus_discount` decimal(5,2) DEFAULT NULL COMMENT 'Giảm giá LavishPlus (%)',
  `max_guests` int NOT NULL COMMENT 'Số khách tối đa',
  `description` text COLLATE utf8mb4_general_ci COMMENT 'Mô tả chi tiết phòng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin phòng';

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `hotel_id`, `room_type_id`, `name`, `image`, `floor`, `status`, `base_price_vnd`, `size`, `view`, `rating`, `lavish_plus_discount`, `max_guests`, `description`) VALUES
(2, NULL, 1, 'Deluxe 001', 'https://images.squarespace-cdn.com/content/v1/5aadf482aa49a1d810879b88/1626698419120-J7CH9BPMB2YI728SLFPN/1.jpg', 2, NULL, 200000.00, 3000, 'Biển', 5.0, NULL, 4, NULL),
(3, NULL, 1, 'Deluxe 002', 'https://lh3.googleusercontent.com/proxy/WR_6J0_1BhUfSZCkm_zaJ8EjSxxv57ME8-qIofxe0VIrgZ9RpP1jSBLj8feXZ2xx2OcV39348VjCHHL1c0vLzK3Pld_xckbn-1veyPld2VrvdutW0sBbnG6RWTJrwJtgmrpso-ZyuX3wN5YSZiiWc40iRDgXPtMVi_qCrTGcXxY3URg', 2, NULL, 200000.00, 3000, 'Bờ hồ', NULL, NULL, 3, 'Không');

-- --------------------------------------------------------

--
-- Table structure for table `room_availability`
--

CREATE TABLE `room_availability` (
  `availability_id` int NOT NULL COMMENT 'Khóa chính, mã lịch',
  `option_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn phòng',
  `date` date NOT NULL COMMENT 'Ngày áp dụng',
  `total_rooms` int NOT NULL COMMENT 'Tổng số phòng',
  `available_rooms` int NOT NULL COMMENT 'Số phòng còn trống'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu lịch đặt phòng theo ngày';

--
-- Dumping data for table `room_availability`
--

INSERT INTO `room_availability` (`availability_id`, `option_id`, `date`, `total_rooms`, `available_rooms`) VALUES
(1, 'OPT_R1_STANDARD', '2025-06-06', 10, 8),
(2, 'OPT_R1_STANDARD', '2025-06-07', 10, 6),
(3, 'OPT_R1_STANDARD', '2025-06-08', 10, 9),
(4, 'OPT_R1_STANDARD', '2025-06-09', 10, 4),
(5, 'OPT_R1_STANDARD', '2025-06-10', 10, 2),
(6, 'OPT_R1_STANDARD', '2025-06-11', 10, 0),
(7, 'OPT_R1_STANDARD', '2025-06-12', 10, 3),
(8, 'OPT_R1_STANDARD', '2025-06-13', 10, 7);

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
-- Table structure for table `room_option`
--

CREATE TABLE `room_option` (
  `option_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Khóa chính, mã tùy chọn',
  `room_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã phòng',
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên tùy chọn',
  `price_per_night_vnd` decimal(15,2) NOT NULL COMMENT 'Giá mỗi đêm (VND)',
  `max_guests` int NOT NULL COMMENT 'Số khách tối đa',
  `min_guests` int NOT NULL COMMENT 'Số khách tối thiểu',
  `cancellation_policy_type` enum('free','non_refundable','partial_refunded') COLLATE utf8mb4_general_ci NOT NULL,
  `cancellation_penalty` decimal(5,2) DEFAULT NULL COMMENT 'Phạt hủy (%)',
  `cancellation_description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mô tả chính sách hủy',
  `free_until` datetime DEFAULT NULL COMMENT 'Hủy miễn phí đến thời điểm',
  `payment_policy_type` enum('pay_now','pay_at_hotel','pay_partial') COLLATE utf8mb4_general_ci NOT NULL,
  `payment_description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mô tả thanh toán',
  `urgency_message` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Thông báo khan hiếm',
  `most_popular` tinyint(1) DEFAULT '0' COMMENT 'Tùy chọn phổ biến nhất',
  `recommended` tinyint(1) DEFAULT '0' COMMENT 'Tùy chọn được đề xuất',
  `meal_type` int DEFAULT NULL COMMENT 'Khóa ngoại, mã bữa ăn',
  `bed_type` int DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn giường',
  `deposit_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Phần trăm đặt cọc (%)',
  `deposit_fixed_amount_vnd` decimal(15,2) DEFAULT NULL COMMENT 'Số tiền đặt cọc cố định (VND)',
  `deposit_policy_id` int DEFAULT NULL,
  `cancellation_policy_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu tùy chọn giá và dịch vụ của phòng';

--
-- Dumping data for table `room_option`
--

INSERT INTO `room_option` (`option_id`, `room_id`, `name`, `price_per_night_vnd`, `max_guests`, `min_guests`, `cancellation_policy_type`, `cancellation_penalty`, `cancellation_description`, `free_until`, `payment_policy_type`, `payment_description`, `urgency_message`, `most_popular`, `recommended`, `meal_type`, `bed_type`, `deposit_percentage`, `deposit_fixed_amount_vnd`, `deposit_policy_id`, `cancellation_policy_id`) VALUES
('OPT_R1_STANDARD', 2, 'OPT 01', 2000000.00, 3, 1, 'non_refundable', NULL, NULL, NULL, 'pay_partial', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_option_promotion`
--

CREATE TABLE `room_option_promotion` (
  `promotion_id` int NOT NULL COMMENT 'Khóa chính, mã khuyến mãi',
  `option_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn',
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại khuyến mãi (hot, limited)',
  `message` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Thông điệp khuyến mãi',
  `discount` decimal(5,2) DEFAULT NULL COMMENT 'Mức giảm giá (%)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu khuyến mãi của tùy chọn phòng';

-- --------------------------------------------------------

--
-- Table structure for table `room_pricing`
--

CREATE TABLE `room_pricing` (
  `pricing_id` int NOT NULL COMMENT 'Khóa chính, mã giá',
  `room_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã phòng',
  `start_date` date NOT NULL COMMENT 'Ngày bắt đầu áp dụng giá',
  `end_date` date NOT NULL COMMENT 'Ngày kết thúc áp dụng giá',
  `price_vnd` decimal(15,2) NOT NULL COMMENT 'Giá phòng (VND)',
  `reason` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Lý do (mùa cao điểm, lễ hội, v.v.)',
  `option_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn phòng',
  `is_weekend` tinyint(1) DEFAULT '0' COMMENT 'Giá áp dụng cho cuối tuần',
  `event_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã sự kiện',
  `holiday_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã ngày lễ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu giá phòng theo thời gian';

--
-- Dumping data for table `room_pricing`
--

INSERT INTO `room_pricing` (`pricing_id`, `room_id`, `start_date`, `end_date`, `price_vnd`, `reason`, `option_id`, `is_weekend`, `event_id`, `holiday_id`) VALUES
(1, 2, '2025-06-13', '2025-06-15', 2600000.00, 'Không biết', NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_transfers`
--

CREATE TABLE `room_transfers` (
  `transfer_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `old_room_id` int NOT NULL,
  `new_room_id` int NOT NULL,
  `reason` text COLLATE utf8mb4_general_ci,
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
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên loại phòng',
  `description` text COLLATE utf8mb4_general_ci COMMENT 'Mô tả loại phòng',
  `total_room` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách loại phòng';

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`room_type_id`, `room_code`, `name`, `description`, `total_room`) VALUES
(1, 'deluxe', 'Phòng Loại Sang (Deluxe Room)', 'Chưa có mô tả', 20),
(2, 'premium', 'Phòng cao cấp trong góc (Premium Corner Room)', 'Chưa có mô tả', 20),
(3, 'suite', 'Phòng Suite (Suite Room)', 'Chưa có mô tả', 100),
(4, 'the_level_1', 'Phòng The Level Cao cấp (The Level Premium Room)', 'Chưa luônnn', NULL);

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
(1, 2, 0, '2025-06-08 23:56:30', '2025-06-08 23:56:30'),
(1, 4, 1, '2025-06-09 02:44:59', '2025-06-09 02:44:59'),
(1, 5, 1, '2025-06-09 00:07:12', '2025-06-09 00:07:12'),
(1, 6, 0, '2025-06-08 23:56:30', '2025-06-08 23:56:30'),
(2, 1, 0, '2025-06-09 02:55:28', '2025-06-09 02:55:28'),
(2, 2, 0, '2025-06-09 02:55:28', '2025-06-09 02:55:28'),
(2, 7, 0, '2025-06-09 03:04:52', '2025-06-09 03:04:52');

-- --------------------------------------------------------

--
-- Table structure for table `room_type_image`
--

CREATE TABLE `room_type_image` (
  `image_id` int NOT NULL COMMENT 'Khóa chính, mã ảnh',
  `room_type_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã phòng',
  `alt_text` text COLLATE utf8mb4_general_ci,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Đường dẫn ảnh',
  `is_main` tinyint(1) DEFAULT '0' COMMENT 'Ảnh chính',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách ảnh của phòng';

--
-- Dumping data for table `room_type_image`
--

INSERT INTO `room_type_image` (`image_id`, `room_type_id`, `alt_text`, `image_url`, `is_main`, `created_at`, `updated_at`) VALUES
(1, 1, 'Hahad', 'https://kconceptvn.com/wp-content/uploads/2020/04/hotel-photography-chup-anh-khach-san-khach-san-bamboo-sapa-hotel-18-1024x683.jpg', 1, '2025-06-08 10:21:06', '2025-06-09 07:20:50'),
(2, 1, NULL, 'https://studiochupanhdep.com/Upload/Newsimages/phong-khach-san-tt-studio.jpg', 0, '2025-06-08 10:21:06', '2025-06-09 07:20:50'),
(4, 1, 'hhah', 'https://asiky.com/files/images/Article/tin-tuc/chup-anh-khach-san.jpg', 0, '2025-06-08 10:21:06', '2025-06-09 07:20:50'),
(5, 1, NULL, 'https://kksapahotel.com/uploads/images/VQK_2153%20(1).jpg', 0, '2025-06-08 10:21:06', '2025-06-09 07:20:50'),
(15, 2, 'Phòng cao cấp trong góc (Premium Corner Room) - Ảnh 1', '/storage/room-types/2/1749478606_0_6846ecce37c50.jpg', 1, '2025-06-09 07:16:46', '2025-06-09 08:02:03'),
(19, 1, 'Phòng Loại Sang (Deluxe Room) - Ảnh 1', '/storage/room-types/1/1749479159_0_6846eef76733f.jpg', 0, '2025-06-09 07:25:59', '2025-06-09 07:25:59'),
(20, 2, 'Phòng cao cấp trong góc (Premium Corner Room) - Ảnh 1', '/storage/room-types/2/1749479414_0_6846eff63b9d2.jpg', 0, '2025-06-09 07:30:14', '2025-06-09 08:02:03');

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
('mYCn2CBARbCW883d8Njmg9nafKUo48nCdOxB8IxO', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVThQbEJ3OTlQU1MxT0ZjMlpENnFkNHFqUjdLRzRsd2lEMTdWaVBKMCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjY1OiJodHRwOi8vMTI3LjAuMC4xOjg4ODgvYWRtaW4vZXZlbnQtZmVzdGl2YWwtbWFuYWdlbWVudC9kYXRhP3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1749810348),
('ZrnMGV0Tu0bIqKca9bp4xWEAuEH0UPwoCakKKnZe', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidFJZeFVpRXdKck1iV1I5eUxwMGwySldCUGJ5MFJrTEM2djR1aTd0QiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjc0OiJodHRwOi8vMTI3LjAuMC4xOjg4ODgvYWRtaW4vcm9vbXMvdHlwZS8xP3NvcnRfYnk9bWF4X2d1ZXN0cyZzb3J0X29yZGVyPWFzYyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1749833495);

-- --------------------------------------------------------

--
-- Table structure for table `table_translation`
--

CREATE TABLE `table_translation` (
  `id` bigint UNSIGNED NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `table_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên bảng (room, hotel, v.v.)',
  `column_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên cột (name, description, v.v.)',
  `record_id` int NOT NULL COMMENT 'Mã bản ghi',
  `language_code` varchar(10) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Mã ngôn ngữ (vi, en, v.v.)',
  `value` text COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Giá trị bản dịch'
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
  `phone` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `role` enum('guest','receptionist','manager','admin') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Vai trò',
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
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `room_type_id` (`room_type_id`);

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
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `idx_hotel_id` (`hotel_id`),
  ADD KEY `idx_room_type_id` (`room_type_id`);

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
-- Indexes for table `room_option`
--
ALTER TABLE `room_option`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `idx_room_id` (`room_id`),
  ADD KEY `bed_type` (`bed_type`),
  ADD KEY `meal_type` (`meal_type`),
  ADD KEY `fk_room_option_deposit_policy` (`deposit_policy_id`),
  ADD KEY `fk_room_option_cancellation_policy` (`cancellation_policy_id`);

--
-- Indexes for table `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  ADD PRIMARY KEY (`promotion_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `room_pricing`
--
ALTER TABLE `room_pricing`
  ADD PRIMARY KEY (`pricing_id`),
  ADD KEY `idx_room_date` (`room_id`,`start_date`,`end_date`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `fk_room_pricing_event` (`event_id`),
  ADD KEY `fk_room_pricing_holiday` (`holiday_id`);

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
  MODIFY `amenity_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bed_types`
--
ALTER TABLE `bed_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính', AUTO_INCREMENT=2;

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
  MODIFY `policy_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `rule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã phòng', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `room_availability`
--
ALTER TABLE `room_availability`
  MODIFY `availability_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã lịch', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  MODIFY `promotion_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã khuyến mãi';

--
-- AUTO_INCREMENT for table `room_pricing`
--
ALTER TABLE `room_pricing`
  MODIFY `pricing_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã giá', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `room_transfers`
--
ALTER TABLE `room_transfers`
  MODIFY `transfer_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `room_type_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã loại phòng', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `room_type_image`
--
ALTER TABLE `room_type_image`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã ảnh', AUTO_INCREMENT=22;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`hotel_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
-- Constraints for table `room_option`
--
ALTER TABLE `room_option`
  ADD CONSTRAINT `fk_room_option_cancellation_policy` FOREIGN KEY (`cancellation_policy_id`) REFERENCES `cancellation_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_room_option_deposit_policy` FOREIGN KEY (`deposit_policy_id`) REFERENCES `deposit_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_option_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_option_ibfk_2` FOREIGN KEY (`bed_type`) REFERENCES `bed_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_option_ibfk_3` FOREIGN KEY (`meal_type`) REFERENCES `meal_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  ADD CONSTRAINT `room_option_promotion_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE CASCADE;

--
-- Constraints for table `room_pricing`
--
ALTER TABLE `room_pricing`
  ADD CONSTRAINT `fk_room_pricing_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_room_pricing_holiday` FOREIGN KEY (`holiday_id`) REFERENCES `holidays` (`holiday_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_pricing_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_pricing_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE CASCADE;

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
-- Constraints for table `translation`
--
ALTER TABLE `translation`
  ADD CONSTRAINT `translation_ibfk_1` FOREIGN KEY (`language_code`) REFERENCES `language` (`language_code`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
