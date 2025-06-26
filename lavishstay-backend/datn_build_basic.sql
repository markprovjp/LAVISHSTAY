-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
<<<<<<< HEAD
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th6 19, 2025 lúc 07:32 AM
-- Phiên bản máy phục vụ: 8.0.30
-- Phiên bản PHP: 8.1.10
=======
-- Host: localhost:3306
-- Generation Time: Jun 23, 2025 at 03:07 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `datn_build_basic_3`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `amenities`
--

CREATE TABLE `amenities` (
  `amenity_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `amenities`
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
(1, 'dht', 'hh', 1, '2025-06-10 00:16:30', '2025-06-10 00:16:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking`
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  `guest_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tên khách',
  `guest_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Email khách',
  `guest_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số điện thoại khách',
  `room_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin đặt phòng';

<<<<<<< HEAD
--
-- Đang đổ dữ liệu cho bảng `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `option_id`, `check_in_date`, `check_out_date`, `total_price_vnd`, `guest_count`, `status`, `created_at`, `updated_at`, `guest_name`, `guest_email`, `guest_phone`, `room_id`) VALUES
(8, NULL, NULL, '2025-06-17', '2025-06-18', 999999.00, 1, 'pending', '2025-06-17 09:25:16', '2025-06-17 09:25:16', NULL, NULL, NULL, 1706);

=======
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rooms_data` json NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `payment_method` enum('vietqr','pay_at_hotel') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('pending','confirmed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_confirmed_at` timestamp NULL DEFAULT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `special_requests` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `booking_code`, `customer_name`, `customer_email`, `customer_phone`, `rooms_data`, `total_amount`, `payment_method`, `payment_status`, `payment_confirmed_at`, `check_in`, `check_out`, `special_requests`, `created_at`, `updated_at`) VALUES
(1, 'LAVISH11903178', 'nguyễn quyền', 'quyenjpn@gmail.com', '0335920306', '[{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 80}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 90}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 83}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 85}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 77}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 70}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"], \"availableRooms\": 18}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 80}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 1, \"totalPrice\": 5000, \"pricePerNight\": 5000}]', 5500.00, 'vietqr', 'confirmed', '2025-06-10 20:24:40', '2025-06-11', '2025-06-12', NULL, '2025-06-10 20:18:33', '2025-06-10 20:24:40'),
(2, 'LAVISH12368779', 'nguyễn quyền', 'quyenjpn@gmail.com', '0335920306', '[{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 80}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 90}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 83}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 85}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 77}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 70}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"], \"availableRooms\": 18}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 80}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 1, \"totalPrice\": 5000, \"pricePerNight\": 5000}]', 5500.00, 'vietqr', 'confirmed', '2025-06-10 20:27:20', '2025-06-11', '2025-06-12', NULL, '2025-06-10 20:26:18', '2025-06-10 20:27:20'),
(3, 'LAVISH12575067', 'nguyễn quyền', 'quyenjpn@gmail.com', '0335920306', '[{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 80}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 90}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 83}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 85}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 77}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 70}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"], \"availableRooms\": 18}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 80}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 1, \"totalPrice\": 5000, \"pricePerNight\": 5000}]', 5500.00, 'vietqr', 'confirmed', '2025-06-10 20:30:59', '2025-06-11', '2025-06-12', NULL, '2025-06-10 20:29:45', '2025-06-10 20:30:59'),
(4, 'LAVISH14392556', 'nguyễn quyền', 'quyenjpn@gmail.com', '0335920306', '[{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 80}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 90}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 83}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 85}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 77}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 70}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"], \"availableRooms\": 18}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 80}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 1, \"totalPrice\": 5000, \"pricePerNight\": 5000}]', 5500.00, 'vietqr', 'confirmed', '2025-06-12 00:39:06', '2025-06-11', '2025-06-12', NULL, '2025-06-10 21:00:02', '2025-06-12 00:39:06'),
(5, 'LAVISH14461619', 'nguyễn quyền', 'quyenjpn@gmail.com', '0335920306', '[{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 80}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 90}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 83}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 85}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 77}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 70}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"], \"availableRooms\": 18}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 80}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 1, \"totalPrice\": 5000, \"pricePerNight\": 5000}]', 5500.00, 'vietqr', 'confirmed', '2025-06-10 21:02:25', '2025-06-11', '2025-06-12', NULL, '2025-06-10 21:01:08', '2025-06-10 21:02:25'),
(6, 'TEST123', 'Test User', 'test@test.com', '0123456789', '{\"rooms\": []}', 1000000.00, 'vietqr', 'confirmed', '2025-06-12 00:39:05', '2025-06-15', '2025-06-16', NULL, '2025-06-11 21:14:09', '2025-06-12 00:39:05'),
(7, 'TEST124', 'Test User', 'test@test.com', '0123456789', '{\"rooms\": []}', 1000000.00, 'vietqr', 'confirmed', '2025-06-12 00:07:29', '2025-06-15', '2025-06-16', NULL, '2025-06-11 21:14:39', '2025-06-12 00:07:29'),
(8, 'LAVISH02182072', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": [{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 115}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.600.000đ cho 2 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 108}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (3.000.000đ cho 2 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 110}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (3.600.000đ cho 2 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 102}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (4.000.000đ cho 2 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 95}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000.000đ cho 2 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"]}, \"option\": {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 115}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.600.000đ cho 2 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_double_basic\", \"quantity\": 1, \"totalPrice\": 2600000, \"pricePerNight\": 1300000}], \"preferences\": {\"bedPreference\": \"double\", \"breakfastOption\": \"none\", \"specialRequests\": \"\"}}', 2600000.00, 'vietqr', 'confirmed', '2025-06-12 00:07:20', '2025-06-13', '2025-06-15', NULL, '2025-06-11 21:23:18', '2025-06-12 00:07:20');
INSERT INTO `bookings` (`id`, `booking_code`, `customer_name`, `customer_email`, `customer_phone`, `rooms_data`, `total_amount`, `payment_method`, `payment_status`, `payment_confirmed_at`, `check_in`, `check_out`, `special_requests`, `created_at`, `updated_at`) VALUES
(9, 'LAVISH02389746', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": [{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 115}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 108}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 110}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 102}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 95}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"]}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 1, \"totalPrice\": 5000, \"pricePerNight\": 5000}], \"preferences\": {\"bedPreference\": \"double\", \"breakfastOption\": \"none\", \"specialRequests\": \"\"}}', 5000.00, 'vietqr', 'confirmed', '2025-06-12 00:39:05', '2025-06-13', '2025-06-14', NULL, '2025-06-11 21:26:32', '2025-06-12 00:39:05'),
(10, 'LAVISH11494622', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": [{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 115}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 108}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 110}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 102}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 95}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"]}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 1, \"totalPrice\": 5000, \"pricePerNight\": 5000}], \"preferences\": {\"bedPreference\": \"double\", \"breakfastOption\": \"none\", \"specialRequests\": \"\"}}', 5000.00, 'vietqr', 'confirmed', '2025-06-12 00:39:04', '2025-06-13', '2025-06-14', NULL, '2025-06-11 23:58:41', '2025-06-12 00:39:04'),
(11, 'LAVISH11581549', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": [{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 115}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 108}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 110}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 102}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 95}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"]}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 1, \"totalPrice\": 5000, \"pricePerNight\": 5000}], \"preferences\": {\"bedPreference\": \"double\", \"breakfastOption\": \"none\", \"specialRequests\": \"\"}}', 5000.00, 'vietqr', 'confirmed', '2025-06-12 00:39:01', '2025-06-13', '2025-06-14', NULL, '2025-06-11 23:59:43', '2025-06-12 00:39:01'),
(12, 'LAVISH11739472', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": [{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 115}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 108}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 110}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 102}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 95}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"]}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 2, \"totalPrice\": 10000, \"pricePerNight\": 5000}], \"preferences\": {\"bedPreference\": \"double\", \"breakfastOption\": \"none\", \"specialRequests\": \"\"}}', 10000.00, 'vietqr', 'confirmed', '2025-06-12 00:39:03', '2025-06-13', '2025-06-14', NULL, '2025-06-12 00:02:28', '2025-06-12 00:39:03'),
(13, 'LAVISH12079238', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": [{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 115}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 108}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 110}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 102}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 95}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"]}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 2, \"totalPrice\": 10000, \"pricePerNight\": 5000}], \"preferences\": {\"bedPreference\": \"double\", \"breakfastOption\": \"none\", \"specialRequests\": \"\"}}', 10000.00, 'vietqr', 'confirmed', '2025-06-12 00:39:00', '2025-06-13', '2025-06-14', NULL, '2025-06-12 00:08:02', '2025-06-12 00:39:00'),
(14, 'LAVISH12085848', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": [{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 115}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 108}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 110}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 102}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 95}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"]}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 2, \"totalPrice\": 10000, \"pricePerNight\": 5000}], \"preferences\": {\"bedPreference\": \"double\", \"breakfastOption\": \"none\", \"specialRequests\": \"\"}}', 10000.00, 'vietqr', 'confirmed', '2025-06-12 00:11:24', '2025-06-13', '2025-06-14', NULL, '2025-06-12 00:08:07', '2025-06-12 00:11:24');
INSERT INTO `bookings` (`id`, `booking_code`, `customer_name`, `customer_email`, `customer_phone`, `rooms_data`, `total_amount`, `payment_method`, `payment_status`, `payment_confirmed_at`, `check_in`, `check_out`, `special_requests`, `created_at`, `updated_at`) VALUES
(15, 'LAVISH12477144', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": [{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 115}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 108}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 110}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 102}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 95}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"]}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 2, \"totalPrice\": 10000, \"pricePerNight\": 5000}], \"preferences\": {\"bedPreference\": \"double\", \"breakfastOption\": \"none\", \"specialRequests\": \"\"}}', 10000.00, 'vietqr', 'confirmed', '2025-06-12 00:15:32', '2025-06-13', '2025-06-14', NULL, '2025-06-12 00:15:00', '2025-06-12 00:15:32'),
(16, 'LAVISH12621570', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": [{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 115}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 108}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 110}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 102}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 95}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"]}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 2, \"totalPrice\": 10000, \"pricePerNight\": 5000}], \"preferences\": {\"bedPreference\": \"double\", \"breakfastOption\": \"none\", \"specialRequests\": \"\"}}', 10000.00, 'vietqr', 'confirmed', '2025-06-12 00:18:10', '2025-06-13', '2025-06-14', NULL, '2025-06-12 00:17:04', '2025-06-12 00:18:10'),
(17, 'LAVISH13065925', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": [{\"room\": {\"id\": 1, \"name\": \"Phòng Loại Sang (Deluxe Room)\", \"size\": 32, \"view\": \"Nhìn ra thành phố\", \"image\": \"/images/room/Deluxe_Room/1.jpg\", \"images\": [\"/images/room/Deluxe_Room/1.jpg\", \"/images/room/Deluxe_Room/2.jpg\", \"/images/room/Deluxe_Room/3.webp\", \"/images/room/Deluxe_Room/4.webp\"], \"rating\": 9.5, \"bedType\": \"1 giường đôi cực lớn  hoặc 2 giường đơn \", \"options\": [{\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_basic\", \"name\": \"Deluxe Basic - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"hot\", \"message\": \"Giá tốt nhất!\", \"discount\": 15}, \"availability\": {\"total\": 30, \"remaining\": 25}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay - Giá tốt nhất\"}, \"pricePerNight\": {\"vnd\": 1300000}, \"dynamicPricing\": {\"savings\": 195000, \"basePrice\": 1495000, \"finalPrice\": 1300000, \"adjustments\": [{\"type\": \"decrease\", \"factor\": 0.87, \"reason\": \"Ưu đãi đặc biệt\"}], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 115}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.300.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_standard\", \"name\": \"Deluxe Standard - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"mostPopular\": true, \"availability\": {\"total\": 20, \"remaining\": 15}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 1500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1500000, \"finalPrice\": 1500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 108}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.500.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_single_premium\", \"name\": \"Deluxe Premium - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"recommended\": true, \"availability\": {\"total\": 15, \"remaining\": 12}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 1800000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 1800000, \"finalPrice\": 1800000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 110}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Late check-out\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (1.800.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_premium\", \"name\": \"Deluxe Premium - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 10, \"remaining\": 6}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2000000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2000000, \"finalPrice\": 2000000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 102}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi cao cấp\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Welcome drink\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Minibar\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.000.000đ cho 1 đêm)\"}}, {\"id\": \"deluxe_double_luxury\", \"name\": \"Deluxe Luxury - 2 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"promotion\": {\"type\": \"member\", \"message\": \"Gói Luxury - Đẳng cấp\"}, \"availability\": {\"total\": 10, \"remaining\": 2}, \"paymentPolicy\": {\"type\": \"pay_at_hotel\", \"description\": \"Thanh toán tại khách sạn\"}, \"pricePerNight\": {\"vnd\": 2500000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 2500000, \"finalPrice\": 2500000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 95}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi VIP\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe VIP\", \"included\": true}, {\"icon\": \"UserOutlined\", \"name\": \"Butler service\", \"included\": true}, {\"icon\": \"GiftOutlined\", \"name\": \"Premium minibar\", \"included\": true}, {\"icon\": \"StarOutlined\", \"name\": \"Room upgrade\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (2.500.000đ cho 1 đêm)\"}}], \"roomType\": \"deluxe\", \"amenities\": [\"Đồ vệ sinh cá nhân miễn phí\", \"Áo choàng tắm\", \"Két an toàn\", \"Nhà vệ sinh\", \"Bồn tắm hoặc Vòi sen\", \"Khăn tắm\", \"Ổ điện gần giường\", \"Bàn làm việc\", \"Khu vực tiếp khách\", \"TV\", \"Dép\", \"Tủ lạnh\", \"Điện thoại\", \"Máy sấy tóc\", \"Sàn trải thảm\", \"Ấm đun nước điện\", \"Tủ hoặc phòng để quần áo\", \"Giấy vệ sinh\"], \"maxGuests\": 2, \"mainAmenities\": [\"Điều hòa không khí\", \"Phòng tắm riêng \", \"TV màn hình phẳng\", \"Hệ thống cách âm\", \"Minibar\", \"WiFi miễn phí\", \"Có cung cấp nôi/cũi \"]}, \"option\": {\"id\": \"deluxe_single_basic\", \"name\": \"Deluxe Basic - 1 khách\", \"roomType\": \"deluxe\", \"maxGuests\": 2, \"minGuests\": 1, \"availability\": {\"total\": 25, \"remaining\": 18}, \"paymentPolicy\": {\"type\": \"pay_now_with_vietQR\", \"description\": \"Thanh toán ngay\"}, \"pricePerNight\": {\"vnd\": 5000}, \"dynamicPricing\": {\"savings\": 0, \"basePrice\": 5000, \"finalPrice\": 5000, \"adjustments\": [], \"urgencyLevel\": \"urgent\", \"recommendationScore\": 105}, \"additionalServices\": [{\"icon\": \"WifiOutlined\", \"name\": \"Wi-Fi miễn phí\", \"included\": true}, {\"icon\": \"CarOutlined\", \"name\": \"Đỗ xe miễn phí\", \"included\": true}], \"cancellationPolicy\": {\"type\": \"non_refundable\", \"penalty\": 100, \"description\": \"Hủy phòng mất toàn bộ tiền đặt (5.000đ cho 1 đêm)\"}}, \"roomId\": \"1\", \"optionId\": \"deluxe_single_basic\", \"quantity\": 2, \"totalPrice\": 10000, \"pricePerNight\": 5000}], \"preferences\": {\"bedPreference\": \"double\", \"breakfastOption\": \"none\", \"specialRequests\": \"\"}}', 10000.00, 'vietqr', 'confirmed', '2025-06-12 00:38:58', '2025-06-13', '2025-06-14', NULL, '2025-06-12 00:24:29', '2025-06-12 00:38:58'),
(18, 'LAVISH14450193', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_single_basic\": 2}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-13\", \"checkOut\": \"2025-06-14\", \"location\": \"\", \"dateRange\": [\"2025-06-12T17:00:00.000Z\", \"2025-06-13T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-12T06:40:05.463Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 3800000.00, 'vietqr', 'confirmed', '2025-06-12 00:49:42', '2025-06-13', '2025-06-14', NULL, '2025-06-12 00:47:30', '2025-06-12 00:49:42'),
(19, 'LAVISH14649295', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_single_basic\": 2}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-13\", \"checkOut\": \"2025-06-14\", \"location\": \"\", \"dateRange\": [\"2025-06-12T17:00:00.000Z\", \"2025-06-13T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-12T06:40:05.463Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 3800000.00, 'vietqr', 'confirmed', '2025-06-12 00:51:42', '2025-06-13', '2025-06-14', NULL, '2025-06-12 00:50:49', '2025-06-12 00:51:42'),
(20, 'LAVISH14758760', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_single_basic\": 2}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-13\", \"checkOut\": \"2025-06-14\", \"location\": \"\", \"dateRange\": [\"2025-06-12T17:00:00.000Z\", \"2025-06-13T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-12T06:40:05.463Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 3800000.00, 'vietqr', 'confirmed', '2025-06-12 01:03:19', '2025-06-13', '2025-06-14', NULL, '2025-06-12 00:52:39', '2025-06-12 01:03:19'),
(21, 'LAVISH15523453', 'Huỳnh Thị Bích Tuyền rr', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"7\": {\"presidential_suite_4guest\": 1}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-13\", \"checkOut\": \"2025-06-14\", \"location\": \"\", \"dateRange\": [\"2025-06-12T17:00:00.000Z\", \"2025-06-13T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-12T06:40:05.463Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 47000000.00, 'vietqr', 'confirmed', '2025-06-12 01:05:44', '2025-06-13', '2025-06-14', NULL, '2025-06-12 01:05:23', '2025-06-12 01:05:44'),
(22, 'LAVISH15676531', 'Huỳnh Thị Bích Tuyền IU', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"6\": {\"theLevel_premium_single_basic\": 2, \"theLevel_premium_single_premium\": 2}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-13\", \"checkOut\": \"2025-06-14\", \"location\": \"\", \"dateRange\": [\"2025-06-12T17:00:00.000Z\", \"2025-06-13T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-12T06:40:05.463Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 8600000.00, 'vietqr', 'confirmed', '2025-06-12 01:08:08', '2025-06-13', '2025-06-14', NULL, '2025-06-12 01:07:57', '2025-06-12 01:08:08'),
(23, 'LAVISH15846009', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"3\": {\"theLevel_premium_single_basic\": 2}, \"6\": {\"theLevel_premium_single_basic\": 2, \"theLevel_premium_single_premium\": 2}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-13\", \"checkOut\": \"2025-06-14\", \"location\": \"\", \"dateRange\": [\"2025-06-12T17:00:00.000Z\", \"2025-06-13T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-12T06:40:05.463Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 12900000.00, 'vietqr', 'confirmed', '2025-06-12 01:11:00', '2025-06-13', '2025-06-14', NULL, '2025-06-12 01:10:46', '2025-06-12 01:11:00'),
(24, 'LAVISH16328427', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_single_premium\": 1}, \"3\": {\"theLevel_premium_single_basic\": 2}, \"6\": {\"theLevel_premium_single_basic\": 2, \"theLevel_premium_single_premium\": 2}, \"7\": {\"presidential_suite_4guest\": 1}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-13\", \"checkOut\": \"2025-06-14\", \"location\": \"\", \"dateRange\": [\"2025-06-12T17:00:00.000Z\", \"2025-06-13T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-12T06:40:05.463Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 62700000.00, 'vietqr', 'confirmed', '2025-06-12 01:18:57', '2025-06-13', '2025-06-14', NULL, '2025-06-12 01:18:49', '2025-06-12 01:18:57'),
(25, 'LAVISH16803238', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_double_premium\": 1}, \"7\": {\"presidential_suite_4guest\": 1}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-15\", \"checkOut\": \"2025-06-17\", \"location\": \"\", \"dateRange\": [\"2025-06-14T17:00:00.000Z\", \"2025-06-16T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-12T08:20:13.069Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 114700000.00, 'vietqr', 'confirmed', '2025-06-12 01:26:54', '2025-06-15', '2025-06-17', NULL, '2025-06-12 01:26:43', '2025-06-12 01:26:54'),
(26, 'LAVISH17557817', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"6\": {\"theLevel_premium_single_basic\": 2}, \"7\": {\"presidential_suite_4guest\": 1}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-17\", \"checkOut\": \"2025-06-19\", \"location\": \"\", \"dateRange\": [\"2025-06-16T17:00:00.000Z\", \"2025-06-18T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-12T08:28:00.424Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 102600000.00, 'vietqr', 'confirmed', '2025-06-12 01:39:39', '2025-06-17', '2025-06-19', NULL, '2025-06-12 01:39:18', '2025-06-12 01:39:39'),
(27, 'LAVISH17603329', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"6\": {\"theLevel_premium_single_basic\": 2}, \"7\": {\"presidential_suite_4guest\": 1}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-17\", \"checkOut\": \"2025-06-19\", \"location\": \"\", \"dateRange\": [\"2025-06-16T17:00:00.000Z\", \"2025-06-18T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-12T08:28:00.424Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 102600000.00, 'vietqr', 'confirmed', '2025-06-12 01:40:10', '2025-06-17', '2025-06-19', NULL, '2025-06-12 01:40:03', '2025-06-12 01:40:10'),
(28, 'LAVISH22032745', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"7\": {\"presidential_suite_4guest\": 1}}, \"searchData\": {\"error\": null, \"guests\": 4, \"checkIn\": \"2025-06-17\", \"checkOut\": \"2025-06-21\", \"location\": \"\", \"dateRange\": [\"2025-06-16T17:00:00.000Z\", \"2025-06-20T17:00:00.000Z\"], \"guestType\": \"group\", \"isLoading\": false, \"searchDate\": \"2025-06-12T09:53:29.553Z\", \"guestDetails\": {\"adults\": 4, \"children\": 0}}}', 196000000.00, 'vietqr', 'confirmed', '2025-06-12 02:54:09', '2025-06-17', '2025-06-21', NULL, '2025-06-12 02:53:53', '2025-06-12 02:54:09'),
(29, 'LAVISH06686585', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_single_basic\": 1}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-13\", \"checkOut\": \"2025-06-14\", \"location\": \"\", \"dateRange\": [\"2025-06-12T17:00:00.000Z\", \"2025-06-13T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-12T09:58:44.012Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 5000.00, 'vietqr', 'confirmed', '2025-06-15 21:47:50', '2025-06-13', '2025-06-14', NULL, '2025-06-13 02:24:48', '2025-06-15 21:47:50'),
(30, 'LAVISH11305029', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_single_basic\": 1}, \"3\": {\"theLevel_premium_double_basic\": 1}}, \"searchData\": {\"error\": null, \"guests\": 4, \"checkIn\": \"2025-06-13\", \"checkOut\": \"2025-06-14\", \"location\": \"\", \"dateRange\": [\"2025-06-12T17:00:00.000Z\", \"2025-06-13T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-13T10:41:31.899Z\", \"guestDetails\": {\"adults\": 2, \"children\": 2}}}', 3205000.00, 'vietqr', 'confirmed', '2025-06-15 21:47:46', '2025-06-13', '2025-06-14', NULL, '2025-06-13 03:41:45', '2025-06-15 21:47:46'),
(31, 'LAVISH40964720', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_single_basic\": 1}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-16\", \"checkOut\": \"2025-06-17\", \"location\": \"\", \"dateRange\": [\"2025-06-15T17:00:00.000Z\", \"2025-06-16T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-15T09:28:13.032Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 5000.00, 'vietqr', 'confirmed', '2025-06-15 19:30:46', '2025-06-16', '2025-06-17', NULL, '2025-06-15 19:29:30', '2025-06-15 19:30:46'),
(32, 'LAVISH48045896', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_single_basic\": 1}, \"4\": {\"suite_double_standard\": 1}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-16\", \"checkOut\": \"2025-06-17\", \"location\": \"\", \"dateRange\": [\"2025-06-15T17:00:00.000Z\", \"2025-06-16T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-16T04:04:15.091Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 3625000.00, 'vietqr', 'confirmed', '2025-06-15 21:30:38', '2025-06-16', '2025-06-17', NULL, '2025-06-15 21:27:26', '2025-06-15 21:30:38'),
(33, 'LAVISH58227351', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_single_basic\": 1}, \"4\": {\"suite_double_standard\": 1}}, \"searchData\": {\"error\": null, \"guests\": 2, \"checkIn\": \"2025-06-16\", \"checkOut\": \"2025-06-17\", \"location\": \"\", \"dateRange\": [\"2025-06-15T17:00:00.000Z\", \"2025-06-16T17:00:00.000Z\"], \"guestType\": \"couple\", \"isLoading\": false, \"searchDate\": \"2025-06-16T05:05:57.040Z\", \"guestDetails\": {\"adults\": 2, \"children\": 0}}}', 3625000.00, 'vietqr', 'confirmed', '2025-06-16 00:17:24', '2025-06-16', '2025-06-17', NULL, '2025-06-16 00:17:08', '2025-06-16 00:17:24'),
(34, 'LAVISH29409401', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_single_basic\": 2}, \"4\": {\"suite_double_basic\": 3}}, \"searchData\": {\"error\": null, \"guests\": 5, \"checkIn\": \"2025-06-18\", \"checkOut\": \"2025-06-19\", \"location\": \"\", \"dateRange\": [\"2025-06-17T17:00:00.000Z\", \"2025-06-18T17:00:00.000Z\"], \"guestType\": \"group\", \"isLoading\": false, \"searchDate\": \"2025-06-18T06:49:35.439Z\", \"guestDetails\": {\"adults\": 4, \"children\": 1, \"childrenAges\": [{\"id\": \"child_1\", \"age\": 5}]}}}', 7960000.00, 'vietqr', 'pending', NULL, '2025-06-18', '2025-06-19', NULL, '2025-06-17 23:50:10', '2025-06-17 23:50:10'),
(35, 'LAVISH24738518', 'Test User', 'test@example.com', '0123456789', '{}', 1000000.00, 'vietqr', 'confirmed', '2025-06-18 00:00:33', '2025-06-19', '2025-06-21', NULL, '2025-06-17 23:59:20', '2025-06-18 00:00:33'),
(36, 'LAVISH31215674', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"1\": {\"deluxe_single_basic\": 2}, \"4\": {\"suite_double_basic\": 3}}, \"searchData\": {\"error\": null, \"guests\": 5, \"checkIn\": \"2025-06-18\", \"checkOut\": \"2025-06-19\", \"location\": \"\", \"dateRange\": [\"2025-06-17T17:00:00.000Z\", \"2025-06-18T17:00:00.000Z\"], \"guestType\": \"group\", \"isLoading\": false, \"searchDate\": \"2025-06-18T06:49:35.439Z\", \"guestDetails\": {\"adults\": 4, \"children\": 1, \"childrenAges\": [{\"id\": \"child_1\", \"age\": 5}]}}}', 9260000.00, 'vietqr', 'pending', NULL, '2025-06-18', '2025-06-19', NULL, '2025-06-18 00:20:16', '2025-06-18 00:20:16'),
(37, 'LAVISH32188871', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"4\": {\"suite_double_basic\": 3}}, \"searchData\": {\"error\": null, \"guests\": 5, \"checkIn\": \"2025-06-18\", \"checkOut\": \"2025-06-19\", \"location\": \"\", \"dateRange\": [\"2025-06-17T17:00:00.000Z\", \"2025-06-18T17:00:00.000Z\"], \"guestType\": \"group\", \"isLoading\": false, \"searchDate\": \"2025-06-18T07:35:59.726Z\", \"guestDetails\": {\"adults\": 4, \"children\": 1, \"childrenAges\": [{\"id\": \"child_1\", \"age\": 15}]}}}', 9250000.00, 'vietqr', 'pending', NULL, '2025-06-18', '2025-06-19', NULL, '2025-06-18 00:36:29', '2025-06-18 00:36:29'),
(38, 'PENDING96195', 'Khách Hàng Test', 'test@pending.com', '0987123456', '[]', 2087115.00, 'vietqr', 'pending', NULL, '2025-06-20', '2025-06-22', NULL, '2025-06-18 01:17:37', '2025-06-18 01:17:37'),
(39, 'LAVISH35864598', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '{\"rooms\": {\"4\": {\"suite_double_basic\": 3}}, \"searchData\": {\"error\": null, \"guests\": 5, \"checkIn\": \"2025-06-18\", \"checkOut\": \"2025-06-19\", \"location\": \"\", \"dateRange\": [\"2025-06-17T17:00:00.000Z\", \"2025-06-18T17:00:00.000Z\"], \"guestType\": \"group\", \"isLoading\": false, \"searchDate\": \"2025-06-18T07:35:59.726Z\", \"guestDetails\": {\"adults\": 4, \"children\": 1, \"childrenAges\": [{\"id\": \"child_1\", \"age\": 15}]}}}', 10450000.00, 'vietqr', 'confirmed', '2025-06-18 03:17:49', '2025-06-18', '2025-06-19', NULL, '2025-06-18 01:37:45', '2025-06-18 03:17:49'),
(40, 'LAVISH42330725', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '\"{\\\"rooms\\\":{\\\"4\\\":{\\\"suite_double_basic\\\":3}},\\\"searchData\\\":{\\\"location\\\":\\\"\\\",\\\"dateRange\\\":[\\\"2025-06-17T17:00:00.000Z\\\",\\\"2025-06-18T17:00:00.000Z\\\"],\\\"checkIn\\\":\\\"2025-06-18\\\",\\\"checkOut\\\":\\\"2025-06-19\\\",\\\"guests\\\":5,\\\"guestDetails\\\":{\\\"adults\\\":4,\\\"children\\\":1,\\\"childrenAges\\\":[{\\\"age\\\":15,\\\"id\\\":\\\"child_1\\\"}]},\\\"guestType\\\":\\\"group\\\",\\\"searchDate\\\":\\\"2025-06-18T07:35:59.726Z\\\",\\\"isLoading\\\":false,\\\"error\\\":null}}\"', 7950000.00, 'vietqr', 'pending', NULL, '2025-06-18', '2025-06-19', NULL, '2025-06-18 03:25:31', '2025-06-18 03:25:31'),
(41, 'LAVISH15877227', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '\"{\\\"rooms\\\":{\\\"1\\\":{\\\"deluxe_double_luxury\\\":3}},\\\"searchData\\\":{\\\"location\\\":\\\"\\\",\\\"dateRange\\\":[\\\"2025-06-18T17:00:00.000Z\\\",\\\"2025-06-19T17:00:00.000Z\\\"],\\\"checkIn\\\":\\\"2025-06-19\\\",\\\"checkOut\\\":\\\"2025-06-20\\\",\\\"guests\\\":5,\\\"guestDetails\\\":{\\\"adults\\\":4,\\\"children\\\":1,\\\"childrenAges\\\":[{\\\"age\\\":15,\\\"id\\\":\\\"child_1\\\"}]},\\\"guestType\\\":\\\"group\\\",\\\"searchDate\\\":\\\"2025-06-19T06:50:54.605Z\\\",\\\"isLoading\\\":false,\\\"error\\\":null}}\"', 7500000.00, 'vietqr', 'pending', NULL, '2025-06-19', '2025-06-20', NULL, '2025-06-18 23:51:18', '2025-06-18 23:51:18'),
(42, 'LAVISH17906674', 'Huỳnh Thị Bích Tuyền', 'reception@hotel.com', '0987654321', '\"{\\\"rooms\\\":{\\\"1\\\":{\\\"deluxe_double_luxury\\\":3}},\\\"searchData\\\":{\\\"location\\\":\\\"\\\",\\\"dateRange\\\":[\\\"2025-06-18T17:00:00.000Z\\\",\\\"2025-06-19T17:00:00.000Z\\\"],\\\"checkIn\\\":\\\"2025-06-19\\\",\\\"checkOut\\\":\\\"2025-06-20\\\",\\\"guests\\\":5,\\\"guestDetails\\\":{\\\"adults\\\":4,\\\"children\\\":1,\\\"childrenAges\\\":[{\\\"age\\\":15,\\\"id\\\":\\\"child_1\\\"}]},\\\"guestType\\\":\\\"group\\\",\\\"searchDate\\\":\\\"2025-06-19T06:50:54.605Z\\\",\\\"isLoading\\\":false,\\\"error\\\":null}}\"', 7500000.00, 'vietqr', 'pending', NULL, '2025-06-19', '2025-06-20', NULL, '2025-06-19 00:25:07', '2025-06-19 00:25:07');

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
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_eafa5d1dcf69169062ff414877a3ad1a', 'i:1;', 1750236893),
('laravel_cache_eafa5d1dcf69169062ff414877a3ad1a:timer', 'i:1750236893;', 1750236893);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Đang đổ dữ liệu cho bảng `check_out_requests`
--

INSERT INTO `check_out_requests` (`request_id`, `booking_id`, `type`, `requested_check_out_time`, `fee_vnd`, `status`, `created_at`, `updated_at`) VALUES
(1, 8, 'late', '2060-02-13 03:04:00', 0.00, 'rejected', '2025-06-17 02:26:23', '2025-06-17 02:26:23');

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
('e', 'e', 2.0000, '3', '3'),
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dynamic_pricing_rules`
--

INSERT INTO `dynamic_pricing_rules` (`rule_id`, `room_type_id`, `occupancy_threshold`, `price_adjustment`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 80.00, 10.00, 1, '2025-06-11 02:44:33', '2025-06-11 02:44:33'),
(2, 1, 90.00, 20.00, 1, '2025-06-11 02:44:33', '2025-06-11 02:44:33'),
(3, 2, 80.00, 20.00, 1, '2025-06-14 03:41:08', '2025-06-14 03:42:03'),
(4, 2, 90.00, 30.00, 1, '2025-06-14 04:17:32', '2025-06-14 04:17:32');

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
(1, 'Lễ hội pháo hoa Đà Nẵng', '2025-07-01', '2025-07-07', 'Sự kiện pháo hoa quốc tế', 1, '2025-06-11 02:20:27', '2025-06-11 02:20:27');

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
<<<<<<< HEAD
-- Cấu trúc bảng cho bảng `guests`
=======
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Quy tắc giá linh động cho cuối tuần, sự kiện, ngày lễ, mùa';

--
-- Dumping data for table `flexible_pricing_rules`
--

INSERT INTO `flexible_pricing_rules` (`rule_id`, `room_type_id`, `rule_type`, `days_of_week`, `event_id`, `holiday_id`, `season_name`, `start_date`, `end_date`, `price_adjustment`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'weekend', '[\"Saturday\", \"Sunday\"]', NULL, NULL, NULL, NULL, NULL, 15.00, 1, '2025-06-23 03:03:08', '2025-06-23 03:03:08'),
(2, 1, 'weekend', '[\"Saturday\"]', NULL, NULL, NULL, '2025-07-01', '2025-08-31', 20.00, 1, '2025-06-23 03:03:08', '2025-06-23 03:03:08'),
(3, NULL, 'event', NULL, 1, NULL, NULL, NULL, NULL, 25.00, 1, '2025-06-23 03:03:08', '2025-06-23 03:03:08'),
(4, NULL, 'holiday', NULL, NULL, 1, NULL, NULL, NULL, 30.00, 1, '2025-06-23 03:03:08', '2025-06-23 03:03:08'),
(5, NULL, 'season', NULL, NULL, NULL, 'Mùa cao điểm', '2025-06-01', '2025-08-31', 20.00, 1, '2025-06-23 03:03:08', '2025-06-23 03:03:08'),
(6, 1, 'season', NULL, NULL, NULL, 'Mùa thấp điểm', '2025-11-01', '2026-02-28', -10.00, 1, '2025-06-23 03:03:08', '2025-06-23 03:03:08');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
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
<<<<<<< HEAD
-- Cấu trúc bảng cho bảng `holidays`
=======
-- Table structure for table `holidays`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
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
('cn', 'tàu khựa'),
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
<<<<<<< HEAD
(9, '2025_06_16_094513_add_early_checkout_fields_to_check_out_policies_table', 3),
(10, '2025_06_18_014501_create_cache_table', 4);
=======
(9, '2025_06_16_094513_add_early_checkout_fields_to_check_out_policies_table', 3);
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508

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
<<<<<<< HEAD
  `payment_type` enum('deposit','full','qr_code','at_hotel') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại thanh toán',
=======
  `payment_type` enum('deposit','full','qr_code','at_hotel','pay_now_with_vietQR') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại thanh toán',
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
  `status` enum('pending','completed','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Trạng thái thanh toán',
  `transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mã giao dịch (từ cổng thanh toán)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin thanh toán';

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
-- Cấu trúc bảng cho bảng `room`
--

CREATE TABLE `room` (
  `room_id` int NOT NULL COMMENT 'Khóa chính, mã phòng',
  `hotel_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã khách sạn',
  `room_type_id` int NOT NULL COMMENT 'Khóa ngoại, mã loại phòng',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên phòng',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh chính',
  `floor` int DEFAULT NULL,
  `status` enum('available','occupied','maintenance','cleaning') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `base_price_vnd` decimal(15,2) NOT NULL COMMENT 'Giá cơ bản (VND)',
  `size` int NOT NULL COMMENT 'Diện tích phòng (m²)',
  `view` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tầm nhìn phòng',
  `rating` decimal(3,1) DEFAULT NULL COMMENT 'Điểm đánh giá (0-10)',
  `lavish_plus_discount` decimal(5,2) DEFAULT NULL COMMENT 'Giảm giá LavishPlus (%)',
  `max_guests` int NOT NULL COMMENT 'Số khách tối đa',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chi tiết phòng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin phòng';

--
-- Đang đổ dữ liệu cho bảng `room`
--

INSERT INTO `room` (`room_id`, `hotel_id`, `room_type_id`, `name`, `image`, `floor`, `status`, `base_price_vnd`, `size`, `view`, `rating`, `lavish_plus_discount`, `max_guests`, `description`) VALUES
<<<<<<< HEAD
(1706, NULL, 1, '0201', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1707, NULL, 1, '0202', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1708, NULL, 1, '0203', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1709, NULL, 1, '0204', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1710, NULL, 1, '0205', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1711, NULL, 1, '0206', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1712, NULL, 1, '0207', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1713, NULL, 1, '0208', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1714, NULL, 1, '0209', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1715, NULL, 1, '0210', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1716, NULL, 1, '0211', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1717, NULL, 1, '0212', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1718, NULL, 1, '0213', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1719, NULL, 1, '0214', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1720, NULL, 1, '0215', NULL, 2, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1721, NULL, 1, '0301', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1722, NULL, 1, '0302', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1723, NULL, 1, '0303', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1724, NULL, 1, '0304', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1725, NULL, 1, '0305', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1726, NULL, 1, '0306', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1727, NULL, 1, '0307', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1728, NULL, 1, '0308', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1729, NULL, 1, '0309', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1730, NULL, 1, '0310', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1731, NULL, 1, '0311', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1732, NULL, 1, '0312', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1733, NULL, 1, '0313', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1734, NULL, 1, '0314', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1735, NULL, 1, '0315', NULL, 3, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1736, NULL, 1, '0401', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1737, NULL, 1, '0402', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1738, NULL, 1, '0403', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1739, NULL, 1, '0404', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1740, NULL, 1, '0405', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1741, NULL, 1, '0406', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1742, NULL, 1, '0407', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1743, NULL, 1, '0408', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1744, NULL, 1, '0409', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1745, NULL, 1, '0410', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1746, NULL, 1, '0411', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1747, NULL, 1, '0412', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1748, NULL, 1, '0413', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1749, NULL, 1, '0414', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1750, NULL, 1, '0415', NULL, 4, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1751, NULL, 1, '0501', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1752, NULL, 1, '0502', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1753, NULL, 1, '0503', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1754, NULL, 1, '0504', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1755, NULL, 1, '0505', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1756, NULL, 1, '0506', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1757, NULL, 1, '0507', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1758, NULL, 1, '0508', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1759, NULL, 1, '0509', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1760, NULL, 1, '0510', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1761, NULL, 1, '0511', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1762, NULL, 1, '0512', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1763, NULL, 1, '0513', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1764, NULL, 1, '0514', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1765, NULL, 1, '0515', NULL, 5, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1766, NULL, 1, '0801', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1767, NULL, 1, '0802', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1768, NULL, 1, '0803', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1769, NULL, 1, '0804', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1770, NULL, 1, '0805', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1771, NULL, 1, '0806', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1772, NULL, 1, '0807', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1773, NULL, 1, '0808', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1774, NULL, 1, '0809', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1775, NULL, 1, '0810', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1776, NULL, 1, '0811', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1777, NULL, 1, '0812', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1778, NULL, 1, '0813', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1779, NULL, 1, '0814', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1780, NULL, 1, '0815', NULL, 8, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1781, NULL, 1, '0901', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1782, NULL, 1, '0902', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1783, NULL, 1, '0903', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1784, NULL, 1, '0904', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1785, NULL, 1, '0905', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1786, NULL, 1, '0906', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1787, NULL, 1, '0907', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1788, NULL, 1, '0908', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1789, NULL, 1, '0909', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1790, NULL, 1, '0910', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1791, NULL, 1, '0911', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1792, NULL, 1, '0912', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1793, NULL, 1, '0913', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1794, NULL, 1, '0914', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1795, NULL, 1, '0915', NULL, 9, 'available', 1200000.00, 33, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(1796, NULL, 2, '1001', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1797, NULL, 2, '1002', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1798, NULL, 2, '1003', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1799, NULL, 2, '1004', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1800, NULL, 2, '1005', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1801, NULL, 2, '1006', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1802, NULL, 2, '1007', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1803, NULL, 2, '1008', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1804, NULL, 2, '1009', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1805, NULL, 2, '1010', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1806, NULL, 2, '1011', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1807, NULL, 2, '1012', NULL, 10, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1808, NULL, 2, '1101', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1809, NULL, 2, '1102', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1810, NULL, 2, '1103', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1811, NULL, 2, '1104', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1812, NULL, 2, '1105', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1813, NULL, 2, '1106', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1814, NULL, 2, '1107', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1815, NULL, 2, '1108', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1816, NULL, 2, '1109', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1817, NULL, 2, '1110', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1818, NULL, 2, '1111', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1819, NULL, 2, '1112', NULL, 11, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1820, NULL, 2, '1201', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1821, NULL, 2, '1202', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1822, NULL, 2, '1203', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1823, NULL, 2, '1204', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1824, NULL, 2, '1205', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1825, NULL, 2, '1206', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1826, NULL, 2, '1207', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1827, NULL, 2, '1208', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1828, NULL, 2, '1209', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1829, NULL, 2, '1210', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1830, NULL, 2, '1211', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1831, NULL, 2, '1212', NULL, 12, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1832, NULL, 2, '1301', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1833, NULL, 2, '1302', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1834, NULL, 2, '1303', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1835, NULL, 2, '1304', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1836, NULL, 2, '1305', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1837, NULL, 2, '1306', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1838, NULL, 2, '1307', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1839, NULL, 2, '1308', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1840, NULL, 2, '1309', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1841, NULL, 2, '1310', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1842, NULL, 2, '1311', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1843, NULL, 2, '1312', NULL, 13, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1844, NULL, 2, '1401', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1845, NULL, 2, '1402', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1846, NULL, 2, '1403', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1847, NULL, 2, '1404', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1848, NULL, 2, '1405', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1849, NULL, 2, '1406', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1850, NULL, 2, '1407', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1851, NULL, 2, '1408', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1852, NULL, 2, '1409', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1853, NULL, 2, '1410', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1854, NULL, 2, '1411', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1855, NULL, 2, '1412', NULL, 14, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1856, NULL, 2, '1501', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1857, NULL, 2, '1502', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1858, NULL, 2, '1503', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1859, NULL, 2, '1504', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1860, NULL, 2, '1505', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1861, NULL, 2, '1506', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1862, NULL, 2, '1507', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1863, NULL, 2, '1508', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1864, NULL, 2, '1509', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1865, NULL, 2, '1510', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1866, NULL, 2, '1511', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1867, NULL, 2, '1512', NULL, 15, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1868, NULL, 2, '1601', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1869, NULL, 2, '1602', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1870, NULL, 2, '1603', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1871, NULL, 2, '1604', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1872, NULL, 2, '1605', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1873, NULL, 2, '1606', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1874, NULL, 2, '1607', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1875, NULL, 2, '1608', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1876, NULL, 2, '1609', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1877, NULL, 2, '1610', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1878, NULL, 2, '1611', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1879, NULL, 2, '1612', NULL, 16, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1880, NULL, 2, '1701', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1881, NULL, 2, '1702', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1882, NULL, 2, '1703', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1883, NULL, 2, '1704', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1884, NULL, 2, '1705', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1885, NULL, 2, '1706', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1886, NULL, 2, '1707', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1887, NULL, 2, '1708', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1888, NULL, 2, '1709', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1889, NULL, 2, '1710', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1890, NULL, 2, '1711', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1891, NULL, 2, '1712', NULL, 17, 'available', 1800000.00, 42, 'View góc, không gian rộng rãi', 10.0, NULL, 2, 'Đây là phòng thuộc loại Premium Corner'),
(1892, NULL, 3, '1801', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1893, NULL, 3, '1802', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1894, NULL, 3, '1803', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1895, NULL, 3, '1804', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1896, NULL, 3, '1805', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1897, NULL, 3, '1806', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1898, NULL, 3, '1807', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1899, NULL, 3, '1808', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1900, NULL, 3, '1809', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1901, NULL, 3, '1810', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1902, NULL, 3, '1811', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1903, NULL, 3, '1812', NULL, 18, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1904, NULL, 3, '1901', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1905, NULL, 3, '1902', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1906, NULL, 3, '1903', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1907, NULL, 3, '1904', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1908, NULL, 3, '1905', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1909, NULL, 3, '1906', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1910, NULL, 3, '1907', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1911, NULL, 3, '1908', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1912, NULL, 3, '1909', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1913, NULL, 3, '1910', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1914, NULL, 3, '1911', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1915, NULL, 3, '1912', NULL, 19, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1916, NULL, 3, '2001', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1917, NULL, 3, '2002', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1918, NULL, 3, '2003', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1919, NULL, 3, '2004', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1920, NULL, 3, '2005', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1921, NULL, 3, '2006', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1922, NULL, 3, '2007', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1923, NULL, 3, '2008', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1924, NULL, 3, '2009', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1925, NULL, 3, '2010', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1926, NULL, 3, '2011', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1927, NULL, 3, '2012', NULL, 20, 'available', 2200000.00, 33, 'Dịch vụ The Level, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium'),
(1928, NULL, 4, '2101', NULL, 21, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1929, NULL, 4, '2102', NULL, 21, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1930, NULL, 4, '2103', NULL, 21, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1931, NULL, 4, '2104', NULL, 21, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1932, NULL, 4, '2105', NULL, 21, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1933, NULL, 4, '2106', NULL, 21, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1934, NULL, 4, '2107', NULL, 21, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1935, NULL, 4, '2108', NULL, 21, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1936, NULL, 4, '2201', NULL, 22, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1937, NULL, 4, '2202', NULL, 22, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1938, NULL, 4, '2203', NULL, 22, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1939, NULL, 4, '2204', NULL, 22, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1940, NULL, 4, '2205', NULL, 22, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1941, NULL, 4, '2206', NULL, 22, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1942, NULL, 4, '2207', NULL, 22, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1943, NULL, 4, '2208', NULL, 22, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1944, NULL, 4, '2301', NULL, 23, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1945, NULL, 4, '2302', NULL, 23, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1946, NULL, 4, '2303', NULL, 23, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1947, NULL, 4, '2304', NULL, 23, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1948, NULL, 4, '2305', NULL, 23, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1949, NULL, 4, '2306', NULL, 23, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1950, NULL, 4, '2307', NULL, 23, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1951, NULL, 4, '2308', NULL, 23, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1952, NULL, 4, '2401', NULL, 24, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1953, NULL, 4, '2402', NULL, 24, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1954, NULL, 4, '2403', NULL, 24, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1955, NULL, 4, '2404', NULL, 24, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1956, NULL, 4, '2405', NULL, 24, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1957, NULL, 4, '2406', NULL, 24, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1958, NULL, 4, '2407', NULL, 24, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1959, NULL, 4, '2408', NULL, 24, 'available', 2500000.00, 45, 'Dịch vụ The Level, view góc', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Premium Corner'),
(1960, NULL, 5, '2501', NULL, 25, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1961, NULL, 5, '2502', NULL, 25, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1962, NULL, 5, '2503', NULL, 25, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1963, NULL, 5, '2504', NULL, 25, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1964, NULL, 5, '2505', NULL, 25, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1965, NULL, 5, '2506', NULL, 25, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1966, NULL, 5, '2507', NULL, 25, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1967, NULL, 5, '2601', NULL, 26, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1968, NULL, 5, '2602', NULL, 26, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1969, NULL, 5, '2603', NULL, 26, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1970, NULL, 5, '2604', NULL, 26, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1971, NULL, 5, '2605', NULL, 26, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1972, NULL, 5, '2606', NULL, 26, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1973, NULL, 5, '2607', NULL, 26, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1974, NULL, 5, '2701', NULL, 27, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1975, NULL, 5, '2702', NULL, 27, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1976, NULL, 5, '2703', NULL, 27, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1977, NULL, 5, '2704', NULL, 27, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1978, NULL, 5, '2705', NULL, 27, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1979, NULL, 5, '2706', NULL, 27, 'available', 3500000.00, 93, 'Suite sang trọng, dịch vụ The Level', 10.0, NULL, 2, 'Đây là phòng thuộc loại The Level Suite'),
(1980, NULL, 6, '2801', NULL, 28, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1981, NULL, 6, '2802', NULL, 28, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1982, NULL, 6, '2803', NULL, 28, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1983, NULL, 6, '2804', NULL, 28, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1984, NULL, 6, '2805', NULL, 28, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1985, NULL, 6, '2901', NULL, 29, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1986, NULL, 6, '2902', NULL, 29, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1987, NULL, 6, '2903', NULL, 29, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1988, NULL, 6, '2904', NULL, 29, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1989, NULL, 6, '2905', NULL, 29, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1990, NULL, 6, '3001', NULL, 30, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1991, NULL, 6, '3002', NULL, 30, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1992, NULL, 6, '3003', NULL, 30, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1993, NULL, 6, '3004', NULL, 30, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1994, NULL, 6, '3005', NULL, 30, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1995, NULL, 6, '3101', NULL, 31, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1996, NULL, 6, '3102', NULL, 31, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1997, NULL, 6, '3103', NULL, 31, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1998, NULL, 6, '3104', NULL, 31, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(1999, NULL, 6, '3105', NULL, 31, 'available', 3000000.00, 93, 'Suite sang trọng, view đẹp', 10.0, NULL, 2, 'Đây là phòng thuộc loại Suite'),
(2000, NULL, 7, '3201', NULL, 32, 'available', 10000000.00, 270, 'Suite tổng thống, sang trọng nhất, view panorama', 10.0, NULL, 2, 'Đây là phòng thuộc loại Presidential Suite');
=======
(6, NULL, 1, '0201', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(7, NULL, 1, '0202', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(8, NULL, 1, '0203', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(9, NULL, 1, '0204', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(10, NULL, 1, '0205', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(11, NULL, 1, '0206', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(12, NULL, 1, '0207', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(13, NULL, 1, '0208', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(14, NULL, 1, '0209', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(15, NULL, 1, '0210', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(16, NULL, 1, '0211', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(17, NULL, 1, '0212', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(18, NULL, 1, '0213', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(19, NULL, 1, '0214', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(20, NULL, 1, '0215', NULL, 2, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(21, NULL, 1, '0301', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(22, NULL, 1, '0302', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(23, NULL, 1, '0303', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(24, NULL, 1, '0304', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(25, NULL, 1, '0305', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(26, NULL, 1, '0306', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(27, NULL, 1, '0307', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(28, NULL, 1, '0308', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(29, NULL, 1, '0309', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(30, NULL, 1, '0310', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(31, NULL, 1, '0311', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(32, NULL, 1, '0312', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(33, NULL, 1, '0313', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(34, NULL, 1, '0314', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(35, NULL, 1, '0315', NULL, 3, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(36, NULL, 1, '0401', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(37, NULL, 1, '0402', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(38, NULL, 1, '0403', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(39, NULL, 1, '0404', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(40, NULL, 1, '0405', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(41, NULL, 1, '0406', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(42, NULL, 1, '0407', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(43, NULL, 1, '0408', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(44, NULL, 1, '0409', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(45, NULL, 1, '0410', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(46, NULL, 1, '0411', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(47, NULL, 1, '0412', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(48, NULL, 1, '0413', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(49, NULL, 1, '0414', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(50, NULL, 1, '0415', NULL, 4, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(51, NULL, 1, '0501', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(52, NULL, 1, '0502', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(53, NULL, 1, '0503', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(54, NULL, 1, '0504', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(55, NULL, 1, '0505', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(56, NULL, 1, '0506', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(57, NULL, 1, '0507', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(58, NULL, 1, '0508', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(59, NULL, 1, '0509', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(60, NULL, 1, '0510', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(61, NULL, 1, '0511', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(62, NULL, 1, '0512', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(63, NULL, 1, '0513', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(64, NULL, 1, '0514', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(65, NULL, 1, '0515', NULL, 5, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(66, NULL, 1, '0801', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(67, NULL, 1, '0802', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(68, NULL, 1, '0803', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(69, NULL, 1, '0804', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(70, NULL, 1, '0805', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(71, NULL, 1, '0806', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(72, NULL, 1, '0807', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(73, NULL, 1, '0808', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(74, NULL, 1, '0809', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(75, NULL, 1, '0810', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(76, NULL, 1, '0811', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(77, NULL, 1, '0812', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(78, NULL, 1, '0813', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(79, NULL, 1, '0814', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(80, NULL, 1, '0815', NULL, 8, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(81, NULL, 1, '0901', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(82, NULL, 1, '0902', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(83, NULL, 1, '0903', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(84, NULL, 1, '0904', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(85, NULL, 1, '0905', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(86, NULL, 1, '0906', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(87, NULL, 1, '0907', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(88, NULL, 1, '0908', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(89, NULL, 1, '0909', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(90, NULL, 1, '0910', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(91, NULL, 1, '0911', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(92, NULL, 1, '0912', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(93, NULL, 1, '0913', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(94, NULL, 1, '0914', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room'),
(95, NULL, 1, '0915', NULL, 9, NULL, 1200000.00, 32, 'View thành phố, bồn tắm, cửa kính lớn', 10.0, NULL, 2, 'Đây là phòng thuộc loại Deluxe Room');
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_availability`
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
  `deposit_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Phần trăm đặt cọc (%)',
  `deposit_fixed_amount_vnd` decimal(15,2) DEFAULT NULL COMMENT 'Số tiền đặt cọc cố định (VND)',
  `deposit_policy_id` int DEFAULT NULL,
  `cancellation_policy_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu tùy chọn giá và dịch vụ của phòng';

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
<<<<<<< HEAD
-- Cấu trúc bảng cho bảng `room_pricing`
--

CREATE TABLE `room_pricing` (
  `pricing_id` int NOT NULL COMMENT 'Khóa chính, mã giá',
  `room_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã phòng',
  `start_date` date NOT NULL COMMENT 'Ngày bắt đầu áp dụng giá',
  `end_date` date NOT NULL COMMENT 'Ngày kết thúc áp dụng giá',
  `price_vnd` decimal(15,2) NOT NULL COMMENT 'Giá phòng (VND)',
  `reason` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Lý do (mùa cao điểm, lễ hội, v.v.)',
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn phòng',
  `is_weekend` tinyint(1) DEFAULT '0' COMMENT 'Giá áp dụng cho cuối tuần',
  `event_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã sự kiện',
  `holiday_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã ngày lễ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu giá phòng theo thời gian';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_transfers`
=======
-- Table structure for table `room_transfers`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
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
  `room_type_id` int NOT NULL COMMENT 'Khóa chính, mã loại phòng',
  `room_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Code loại phòng',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên loại phòng',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả loại phòng',
  `total_room` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách loại phòng';

--
-- Đang đổ dữ liệu cho bảng `room_types`
--

INSERT INTO `room_types` (`room_type_id`, `room_code`, `name`, `description`, `total_room`) VALUES
(1, 'deluxe', 'Phòng Loại Sang (Deluxe Room)', 'Chưa có mô tả', 90),
(2, 'premium_corner', 'Phòng cao cấp trong góc (Premium Corner Room)', 'Chưa có mô tả', 96),
(3, 'suite', 'Phòng Suite (Suite Room)', 'Chưa có mô tả', 20),
(4, 'the_level_premium', 'Phòng The Level Cao cấp (The Level Premium Room)', 'Chưa luônnn', 30),
(5, 'the_level_suite', 'The Level Suite', 'Không', 20),
(6, 'the_level_premium_corner', 'The Level Premium Corner (Phòng cao cấp trong góc)', 'Không', 32),
(7, 'presidential_suite', 'Presidential Suite (Phòng Tổng thống)', 'Không', 1);

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
(1, 1, 0, '2025-06-13 21:03:56', '2025-06-13 21:03:56'),
(1, 2, 0, '2025-06-08 23:56:30', '2025-06-08 23:56:30'),
(1, 3, 0, '2025-06-15 09:34:39', '2025-06-15 09:34:39'),
(1, 4, 1, '2025-06-09 02:44:59', '2025-06-09 02:44:59'),
(1, 5, 1, '2025-06-09 00:07:12', '2025-06-09 00:07:12'),
(1, 6, 0, '2025-06-08 23:56:30', '2025-06-08 23:56:30'),
(1, 7, 0, '2025-06-15 09:34:39', '2025-06-15 09:34:39'),
(2, 1, 0, '2025-06-09 02:55:28', '2025-06-09 02:55:28'),
(2, 2, 0, '2025-06-09 02:55:28', '2025-06-09 02:55:28'),
(2, 3, 0, '2025-06-15 09:35:30', '2025-06-15 09:35:30'),
(2, 7, 0, '2025-06-09 03:04:52', '2025-06-09 03:04:52');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room_type_image`
--

CREATE TABLE `room_type_image` (
  `image_id` int NOT NULL COMMENT 'Khóa chính, mã ảnh',
  `room_type_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã phòng',
  `alt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Đường dẫn ảnh',
  `is_main` tinyint(1) DEFAULT '0' COMMENT 'Ảnh chính',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách ảnh của phòng';

--
-- Đang đổ dữ liệu cho bảng `room_type_image`
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
-- Cấu trúc bảng cho bảng `sessions`
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
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
<<<<<<< HEAD
('SH3OzhVi847T2peaGQVuwvh4qaKwSKzK6pEhMkTm', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUXpKc21ScktWalg0YXZMSHVYYmliZGtTUU9BYm5nVVBOaFVna3dXayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODg4OC9hZG1pbi9yb29tcy9zaG93LzE3MDciO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1750318289);
=======
('ORYn2FNStdEGJOnzvLfeGeZiFXQH3DYHaxBxG3Ye', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMU1xOXJrY1BYa29oRjhiZVB5OTRaU21NbGJpSzZNU0xNMnl4T0dNWCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjY1OiJodHRwOi8vMTI3LjAuMC4xOjg4ODgvYWRtaW4vZXZlbnQtZmVzdGl2YWwtbWFuYWdlbWVudC9kYXRhP3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1750647896);
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `table_translation`
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
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `address`, `role`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'NGUYỄN ANH ĐỨC', 'nguyenanhduc2909@gmail.com', NULL, '$2y$12$c2dNZ4nJgjNNQzaupkPYP.qIR6Ax7vkA65tXqK/n/uStI/bAr5haa', '0822153447', 'Thanh Hóa', 'admin', NULL, NULL, NULL, NULL, NULL, 'profile-photos/DjTR2i1uqXfUMCJURsg6kxS1HZfuCgqHyy5qERDx.jpg', '2025-05-21 01:07:42', '2025-06-11 07:11:24'),
(2, 'Nguyễn Anh Đức', 'nguyenandhduc2909@gmail.com', NULL, '$2y$12$ofny2jH99JRC2egJJaVzLOyRIuw2.5aL93twDg6Zw4hOq0KKWdxAu', '08221534422', 'Thanh Hóa', 'guest', NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-10 09:47:13', '2025-06-10 09:47:13');

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
<<<<<<< HEAD
-- Đang đổ dữ liệu cho bảng `weekend_days`
=======
-- Dumping data for table `weekend_days`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
--

INSERT INTO `weekend_days` (`id`, `day_of_week`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Monday', 0, '2025-06-14 02:38:18', '2025-06-16 09:03:15'),
(4, 'Tuesday', 0, '2025-06-14 02:38:18', '2025-06-16 09:03:15'),
(5, 'Wednesday', 0, '2025-06-14 02:38:18', '2025-06-16 09:03:15'),
(6, 'Thursday', 0, '2025-06-14 02:38:18', '2025-06-16 09:03:15'),
(7, 'Friday', 0, '2025-06-14 02:38:18', '2025-06-16 09:03:15'),
(8, 'Saturday', 1, '2025-06-14 02:38:18', '2025-06-16 09:03:15'),
(9, 'Sunday', 1, '2025-06-14 02:38:18', '2025-06-16 09:03:15');

--
<<<<<<< HEAD
-- Chỉ mục cho các bảng đã đổ
=======
-- Indexes for dumped tables
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
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
  ADD KEY `fk_booking_room` (`room_id`);

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  ADD KEY `bookings_payment_status_payment_method_index` (`payment_status`,`payment_method`),
  ADD KEY `bookings_booking_code_index` (`booking_code`),
  ADD KEY `bookings_created_at_index` (`created_at`);

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
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

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
  ADD KEY `room_type_id` (`room_type_id`);

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
<<<<<<< HEAD
-- Chỉ mục cho bảng `guests`
=======
-- Indexes for table `flexible_pricing_rules`
--
ALTER TABLE `flexible_pricing_rules`
  ADD PRIMARY KEY (`rule_id`),
  ADD KEY `flexible_pricing_room_type_id_index` (`room_type_id`),
  ADD KEY `flexible_pricing_event_id_index` (`event_id`),
  ADD KEY `flexible_pricing_holiday_id_index` (`holiday_id`);

--
-- Indexes for table `guests`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guest_id`),
  ADD KEY `fk_guests_user` (`user_id`);

--
<<<<<<< HEAD
-- Chỉ mục cho bảng `holidays`
=======
-- Indexes for table `holidays`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
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
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `idx_hotel_id` (`hotel_id`),
  ADD KEY `idx_room_type_id` (`room_type_id`);

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
-- Chỉ mục cho bảng `room_option`
--
ALTER TABLE `room_option`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `idx_room_id` (`room_id`),
  ADD KEY `bed_type` (`bed_type`),
  ADD KEY `meal_type` (`meal_type`),
  ADD KEY `fk_room_option_deposit_policy` (`deposit_policy_id`),
  ADD KEY `fk_room_option_cancellation_policy` (`cancellation_policy_id`);

--
-- Chỉ mục cho bảng `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  ADD PRIMARY KEY (`promotion_id`),
  ADD KEY `option_id` (`option_id`);

--
<<<<<<< HEAD
-- Chỉ mục cho bảng `room_pricing`
--
ALTER TABLE `room_pricing`
  ADD PRIMARY KEY (`pricing_id`),
  ADD KEY `idx_room_date` (`room_id`,`start_date`,`end_date`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `fk_room_pricing_event` (`event_id`),
  ADD KEY `fk_room_pricing_holiday` (`holiday_id`);

--
-- Chỉ mục cho bảng `room_transfers`
=======
-- Indexes for table `room_transfers`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
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
  MODIFY `amenity_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `bed_types`
--
ALTER TABLE `bed_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã đặt phòng', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

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
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `rule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
<<<<<<< HEAD
-- AUTO_INCREMENT cho bảng `guests`
=======
-- AUTO_INCREMENT for table `flexible_pricing_rules`
--
ALTER TABLE `flexible_pricing_rules`
  MODIFY `rule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `guests`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
--
ALTER TABLE `guests`
  MODIFY `guest_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
<<<<<<< HEAD
-- AUTO_INCREMENT cho bảng `holidays`
=======
-- AUTO_INCREMENT for table `holidays`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
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
<<<<<<< HEAD
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
=======
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã thanh toán';

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `room`
--
ALTER TABLE `room`
<<<<<<< HEAD
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã phòng', AUTO_INCREMENT=2001;
=======
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã phòng', AUTO_INCREMENT=96;
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508

--
-- AUTO_INCREMENT cho bảng `room_availability`
--
ALTER TABLE `room_availability`
  MODIFY `availability_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã lịch', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  MODIFY `promotion_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã khuyến mãi';

--
<<<<<<< HEAD
-- AUTO_INCREMENT cho bảng `room_pricing`
--
ALTER TABLE `room_pricing`
  MODIFY `pricing_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã giá', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `room_transfers`
=======
-- AUTO_INCREMENT for table `room_transfers`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
--
ALTER TABLE `room_transfers`
  MODIFY `transfer_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `room_types`
--
ALTER TABLE `room_types`
  MODIFY `room_type_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã loại phòng', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `room_type_image`
--
ALTER TABLE `room_type_image`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã ảnh', AUTO_INCREMENT=22;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
<<<<<<< HEAD
-- Ràng buộc cho bảng `guests`
=======
-- Constraints for table `flexible_pricing_rules`
--
ALTER TABLE `flexible_pricing_rules`
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `flexible_pricing_rules_ibfk_3` FOREIGN KEY (`holiday_id`) REFERENCES `holidays` (`holiday_id`) ON DELETE SET NULL;

--
-- Constraints for table `guests`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
--
ALTER TABLE `guests`
  ADD CONSTRAINT `fk_guests_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
<<<<<<< HEAD
-- Ràng buộc cho bảng `hotel_rating`
=======
-- Constraints for table `hotel_rating`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
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
-- Ràng buộc cho bảng `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`hotel_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
-- Ràng buộc cho bảng `room_option`
--
ALTER TABLE `room_option`
  ADD CONSTRAINT `fk_room_option_cancellation_policy` FOREIGN KEY (`cancellation_policy_id`) REFERENCES `cancellation_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_room_option_deposit_policy` FOREIGN KEY (`deposit_policy_id`) REFERENCES `deposit_policies` (`policy_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_option_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_option_ibfk_2` FOREIGN KEY (`bed_type`) REFERENCES `bed_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_option_ibfk_3` FOREIGN KEY (`meal_type`) REFERENCES `meal_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ràng buộc cho bảng `room_option_promotion`
--
ALTER TABLE `room_option_promotion`
  ADD CONSTRAINT `room_option_promotion_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE CASCADE;

--
<<<<<<< HEAD
-- Ràng buộc cho bảng `room_pricing`
--
ALTER TABLE `room_pricing`
  ADD CONSTRAINT `fk_room_pricing_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_room_pricing_holiday` FOREIGN KEY (`holiday_id`) REFERENCES `holidays` (`holiday_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_pricing_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_pricing_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `room_transfers`
=======
-- Constraints for table `room_transfers`
>>>>>>> 4c850614f986cc347ca4902350d21b5ae481d508
--
ALTER TABLE `room_transfers`
  ADD CONSTRAINT `room_transfers_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_transfers_ibfk_2` FOREIGN KEY (`old_room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `room_transfers_ibfk_3` FOREIGN KEY (`new_room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT;

--
-- Ràng buộc cho bảng `room_type_amenity`
--
ALTER TABLE `room_type_amenity`
  ADD CONSTRAINT `room_type_amenity_ibfk_1` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`amenity_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_type_amenity_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ràng buộc cho bảng `room_type_image`
--
ALTER TABLE `room_type_image`
  ADD CONSTRAINT `room_type_image_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ràng buộc cho bảng `translation`
--
ALTER TABLE `translation`
  ADD CONSTRAINT `translation_ibfk_1` FOREIGN KEY (`language_code`) REFERENCES `language` (`language_code`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
